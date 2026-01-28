<?php

use App\Enums\UserRole;
use App\Models\MaintenanceRequest;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\patchJson;

it('admin can assign technician to request', function () {
    $admin = User::factory()->create(['role' => UserRole::ADMIN->value]);
    $technician = User::factory()->create(['role' => UserRole::TECHNICIAN->value]);
    $request = MaintenanceRequest::factory()->create();

    actingAs($admin)
        ->patchJson("/api/maintenance/requests/{$request->id}/assign", [
            'technician_id' => $technician->id,
        ])
        ->assertOk()
        ->assertJsonPath('data.technician.id', $technician->id);

    expect($request->fresh()->technician_id)->toBe($technician->id);
});

it('updates request status to assigned when technician is assigned', function () {
    $admin = User::factory()->create(['role' => UserRole::ADMIN->value]);
    $technician = User::factory()->create(['role' => UserRole::TECHNICIAN->value]);
    $request = MaintenanceRequest::factory()->create(['status' => 'open']);

    actingAs($admin)
        ->patchJson("/api/maintenance/requests/{$request->id}/assign", [
            'technician_id' => $technician->id,
        ])
        ->assertOk();

    expect($request->fresh()->status)->toBe('assigned');
});

it('non-admin cannot assign technician', function () {
    $user = User::factory()->create(['role' => UserRole::USER->value]);
    $technician = User::factory()->create(['role' => UserRole::TECHNICIAN->value]);
    $request = MaintenanceRequest::factory()->create();

    actingAs($user)
        ->patchJson("/api/maintenance/requests/{$request->id}/assign", [
            'technician_id' => $technician->id,
        ])
        ->assertForbidden();
});

it('technician cannot assign themselves', function () {
    $technician = User::factory()->create(['role' => UserRole::TECHNICIAN->value]);
    $request = MaintenanceRequest::factory()->create();

    actingAs($technician)
        ->patchJson("/api/maintenance/requests/{$request->id}/assign", [
            'technician_id' => $technician->id,
        ])
        ->assertForbidden();
});

it('requires authentication to assign technician', function () {
    $request = MaintenanceRequest::factory()->create();

    patchJson("/api/maintenance/requests/{$request->id}/assign", [
        'technician_id' => 1,
    ])->assertUnauthorized();
});

it('validates technician_id is required', function () {
    $admin = User::factory()->create(['role' => UserRole::ADMIN->value]);
    $request = MaintenanceRequest::factory()->create();

    actingAs($admin)
        ->patchJson("/api/maintenance/requests/{$request->id}/assign", [])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['technician_id']);
});
