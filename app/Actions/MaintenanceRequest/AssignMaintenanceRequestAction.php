<?php

namespace App\Actions\MaintenanceRequest;

use App\Events\MaintenanceRequest\MaintenanceRequestAssigned;
use App\Http\Resources\MaintenanceRequestResource;
use App\Models\MaintenanceRequest;
use Illuminate\Support\Facades\DB;

class AssignMaintenanceRequestAction
{
    public function handle(MaintenanceRequest $maintenanceRequest, array $data): MaintenanceRequestResource
    {
        return DB::transaction(function () use ($maintenanceRequest, $data) {
            $technicianId = (int) $data['technician_id'];
            $maintenanceRequest->update(['technician_id' => $technicianId]);

            MaintenanceRequestAssigned::dispatch($maintenanceRequest->fresh(), $technicianId);

            return new MaintenanceRequestResource($maintenanceRequest->load('technician'));
        });
    }
}