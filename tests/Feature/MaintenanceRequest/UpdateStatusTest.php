<?php

use App\Enums\Maintenance\MaintenanceRequestStatus;
use App\Enums\UserRole;
use App\Models\MaintenanceRequest;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\patchJson;

it('technician can update request status to in_progress', function () {
    $technician = User::factory()->create(['role' => UserRole::TECHNICIAN->value]);
    $request = MaintenanceRequest::factory()->create([
        'technician_id' => $technician->id,
        'status' => MaintenanceRequestStatus::ASSIGNED->value,
    ]);

    actingAs($technician)
        ->patchJson("/api/maintenance/requests/{$request->id}/status", [
            'status' => MaintenanceRequestStatus::IN_PROGRESS->value,
            'notes' => 'Starting work on the AC',
        ])
        ->assertOk();

    expect($request->fresh()->status)->toBe(MaintenanceRequestStatus::IN_PROGRESS->value);
});

it('technician can update request status to completed', function () {
    $technician = User::factory()->create(['role' => UserRole::TECHNICIAN->value]);
    $request = MaintenanceRequest::factory()->create([
        'technician_id' => $technician->id,
        'status' => MaintenanceRequestStatus::IN_PROGRESS->value,
    ]);

    actingAs($technician)
        ->patchJson("/api/maintenance/requests/{$request->id}/status", [
            'status' => MaintenanceRequestStatus::COMPLETED->value,
            'notes' => 'Fixed the AC unit',
        ])
        ->assertOk();

    expect($request->fresh()->status)->toBe(MaintenanceRequestStatus::COMPLETED->value);
});

it('technician can cancel a request', function () {
    $technician = User::factory()->create(['role' => UserRole::TECHNICIAN->value]);
    $request = MaintenanceRequest::factory()->create([
        'technician_id' => $technician->id,
        'status' => MaintenanceRequestStatus::ASSIGNED->value,
    ]);

    actingAs($technician)
        ->patchJson("/api/maintenance/requests/{$request->id}/status", [
            'status' => MaintenanceRequestStatus::CANCELLED->value,
            'notes' => 'Duplicate request',
        ])
        ->assertOk();

    expect($request->fresh()->status)->toBe(MaintenanceRequestStatus::CANCELLED->value);
});

it('non-technician cannot update status', function () {
    $user = User::factory()->create(['role' => UserRole::USER->value]);
    $request = MaintenanceRequest::factory()->create([
        'status' => MaintenanceRequestStatus::ASSIGNED->value,
    ]);

    actingAs($user)
        ->patchJson("/api/maintenance/requests/{$request->id}/status", [
            'status' => MaintenanceRequestStatus::COMPLETED->value,
        ])
        ->assertForbidden();
});

it('admin cannot update status', function () {
    $admin = User::factory()->create(['role' => UserRole::ADMIN->value]);
    $request = MaintenanceRequest::factory()->create([
        'status' => MaintenanceRequestStatus::ASSIGNED->value,
    ]);

    actingAs($admin)
        ->patchJson("/api/maintenance/requests/{$request->id}/status", [
            'status' => MaintenanceRequestStatus::COMPLETED->value,
        ])
        ->assertForbidden();
});

it('requires authentication to update status', function () {
    $request = MaintenanceRequest::factory()->create();

    patchJson("/api/maintenance/requests/{$request->id}/status", [
        'status' => MaintenanceRequestStatus::COMPLETED->value,
    ])->assertUnauthorized();
});

it('validates status is required', function () {
    $technician = User::factory()->create(['role' => UserRole::TECHNICIAN->value]);
    $request = MaintenanceRequest::factory()->create([
        'technician_id' => $technician->id,
    ]);

    actingAs($technician)
        ->patchJson("/api/maintenance/requests/{$request->id}/status", [])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['status']);
});

it('validates status must be a valid enum value', function () {
    $technician = User::factory()->create(['role' => UserRole::TECHNICIAN->value]);
    $request = MaintenanceRequest::factory()->create([
        'technician_id' => $technician->id,
    ]);

    actingAs($technician)
        ->patchJson("/api/maintenance/requests/{$request->id}/status", [
            'status' => 'invalid_status',
        ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['status']);
});
