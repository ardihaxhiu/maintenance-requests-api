<?php

use App\Enums\UserRole;
use App\Models\MaintenanceRequest;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\getJson;

it('returns all maintenance requests for authenticated user', function () {
    $user = User::factory()->create();
    $requests = MaintenanceRequest::factory()->count(3)->create();

    actingAs($user)
        ->getJson('/api/maintenance/requests')
        ->assertOk()
        ->assertJsonCount(3, 'data')
        ->assertJsonStructure([
            'data' => [
                '*' => ['id', 'description', 'status', 'priority'],
            ],
        ]);
});

it('returns empty array when no requests exist', function () {
    $user = User::factory()->create();

    actingAs($user)
        ->getJson('/api/maintenance/requests')
        ->assertOk()
        ->assertJsonCount(0, 'data');
});

it('requires authentication to list requests', function () {
    getJson('/api/maintenance/requests')
        ->assertUnauthorized();
});

it('includes user and technician relationships', function () {
    $user = User::factory()->create();
    $technician = User::factory()->create(['role' => UserRole::TECHNICIAN->value]);
    MaintenanceRequest::factory()->create([
        'user_id' => $user->id,
        'technician_id' => $technician->id,
    ]);

    actingAs($user)
        ->getJson('/api/maintenance/requests')
        ->assertOk()
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'description',
                    'user' => ['id', 'name', 'email'],
                    'technician' => ['id', 'name', 'email'],
                ],
            ],
        ]);
});
