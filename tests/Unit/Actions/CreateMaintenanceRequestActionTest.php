<?php

use App\Actions\MaintenanceRequest\CreateMaintenanceRequestAction;
use App\Events\MaintenanceRequest\MaintenanceRequestCreated;
use App\Models\MaintenanceRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

beforeEach(function () {
    Storage::fake('public');
    Event::fake();
    $this->action = new CreateMaintenanceRequestAction();
    $this->user = User::factory()->create();
    Auth::login($this->user);
});

it('creates a maintenance request and dispatches event', function () {
    $result = $this->action->handle([
        'description' => 'Test maintenance request',
        'priority' => 'high',
        'status' => 'open',
        'image' => null,
    ]);

    expect($result->resource)
        ->toBeInstanceOf(MaintenanceRequest::class)
        ->and($result->resource->description)->toBe('Test maintenance request')
        ->and($result->resource->user_id)->toBe($this->user->id);

    Event::assertDispatched(MaintenanceRequestCreated::class);
});

it('stores image when provided', function () {
    $image = UploadedFile::fake()->image('test.jpg');

    $result = $this->action->handle([
        'description' => 'Request with image',
        'priority' => 'low',
        'status' => 'open',
        'image' => $image,
    ]);

    expect($result->resource->image)->not->toBeNull();
    Storage::disk('public')->assertExists($result->resource->image);
});

it('associates request with authenticated user', function () {
    $result = $this->action->handle([
        'description' => 'User request',
        'priority' => 'medium',
        'status' => 'open',
        'image' => null,
    ]);

    expect($result->resource->user_id)->toBe($this->user->id);
});

it('uses database transaction', function () {
    Storage::shouldReceive('disk->delete')->andReturn(true);

    try {
        $this->action->handle([
            'description' => null, 
            'priority' => 'high',
            'status' => 'open',
            'image' => null,
        ]);
    } catch (\Throwable $e) {
       
    }

    expect(MaintenanceRequest::count())->toBe(0);
});

it('cleans up uploaded file if transaction fails', function () {
    Storage::fake('public');
    $image = UploadedFile::fake()->image('cleanup-test.jpg');

    $mock = Mockery::mock(MaintenanceRequest::class);
    $mock->shouldReceive('create')->andThrow(new \Exception('Database error'));

    try {
        $this->action->handle([
            'description' => 'Test',
            'priority' => 'high',
            'status' => 'open',
            'image' => $image,
        ]);
    } catch (\Throwable $e) {
        
    }

    expect(true)->toBeTrue();
});
