<?php

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\postJson;

beforeEach(function () {
    Storage::fake('public');
    $this->user = User::factory()->create(['role' => UserRole::USER->value]);
});

it('creates a maintenance request with image', function () {
    $image = UploadedFile::fake()->image('issue.jpg');

    $response = actingAs($this->user)
        ->postJson('/api/maintenance/requests', [
            'description' => 'AC not working in room 101',
            'priority' => 'high',
            'status' => 'open',
            'image' => $image,
        ])
        ->assertCreated()
        ->assertJsonStructure([
            'data' => [
                'id',
                'description',
                'status',
                'priority',
            ],
        ]);

    assertDatabaseHas('maintenance_requests', [
        'description' => 'AC not working in room 101',
        'user_id' => $this->user->id,
        'priority' => 'high',
    ]);

    expect($response->json('data.image'))->not->toBeNull();
});

it('creates a maintenance request without image', function () {
    actingAs($this->user)
        ->postJson('/api/maintenance/requests', [
            'description' => 'Leaking faucet',
            'priority' => 'medium',
            'status' => 'open',
        ])
        ->assertCreated()
        ->assertJsonStructure([
            'data' => [
                'id',
                'description',
                'status',
                'priority',
            ],
        ]);

    assertDatabaseHas('maintenance_requests', [
        'description' => 'Leaking faucet',
        'user_id' => $this->user->id,
    ]);
});

it('requires authentication to create request', function () {
    postJson('/api/maintenance/requests', [
        'description' => 'Test',
        'priority' => 'low',
        'status' => 'open',
    ])->assertUnauthorized();
});

it('validates required fields', function () {
    actingAs($this->user)
        ->postJson('/api/maintenance/requests', [])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['description', 'priority', 'status']);
});

it('validates priority enum values', function () {
    actingAs($this->user)
        ->postJson('/api/maintenance/requests', [
            'description' => 'Test',
            'priority' => 'invalid',
            'status' => 'open',
        ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['priority']);
});

it('validates status enum values', function () {
    actingAs($this->user)
        ->postJson('/api/maintenance/requests', [
            'description' => 'Test',
            'priority' => 'low',
            'status' => 'invalid',
        ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['status']);
});

it('validates image must be an image file', function () {
    actingAs($this->user)
        ->postJson('/api/maintenance/requests', [
            'description' => 'Test',
            'priority' => 'low',
            'status' => 'open',
            'image' => UploadedFile::fake()->create('document.pdf', 100),
        ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['image']);
});
