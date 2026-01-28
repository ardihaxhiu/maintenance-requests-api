<?php

namespace App\Actions\MaintenanceRequest;

use App\Models\MaintenanceRequest;
use App\Http\Resources\MaintenanceRequestResource;

class GetMaintenanceRequestAction
{
    public function handle(MaintenanceRequest $maintenanceRequest): MaintenanceRequestResource
    {
        return new MaintenanceRequestResource($maintenanceRequest->load(['user', 'technician']));
    }
}
