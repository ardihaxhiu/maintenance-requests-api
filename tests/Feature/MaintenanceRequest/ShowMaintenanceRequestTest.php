<?php

use App\Models\MaintenanceRequest;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\getJson;

it('returns a single maintenance request by id', function () {
    $user = User::factory()->create();
    $request = MaintenanceRequest::factory()->create();

    actingAs($user)
        ->getJson("/api/maintenance/requests/{$request->id}")
        ->assertOk()
        ->assertJsonStructure([
            'data' => ['id', 'description', 'status', 'priority'],
        ])
        ->assertJsonPath('data.id', $request->id);
});

it('includes user and technician relationships', function () {
    $user = User::factory()->create();
    $request = MaintenanceRequest::factory()->assigned()->create();

    actingAs($user)
        ->getJson("/api/maintenance/requests/{$request->id}")
        ->assertOk()
        ->assertJsonStructure([
            'data' => [
                'id',
                'description',
                'user' => ['id', 'name', 'email'],
                'technician' => ['id', 'name', 'email'],
            ],
        ]);
});

it('returns 404 for non-existent request', function () {
    $user = User::factory()->create();

    actingAs($user)
        ->getJson('/api/maintenance/requests/99999')
        ->assertNotFound();
});

it('requires authentication to view request', function () {
    $request = MaintenanceRequest::factory()->create();

    getJson("/api/maintenance/requests/{$request->id}")
        ->assertUnauthorized();
});
