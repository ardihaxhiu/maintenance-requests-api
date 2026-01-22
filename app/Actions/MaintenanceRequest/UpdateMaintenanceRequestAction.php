<?php

namespace App\Actions\MaintenanceRequest;

use App\Models\MaintenanceRequest;
use App\Http\Resources\MaintenanceRequestResource;

class UpdateMaintenanceRequestAction
{
    public function handle(MaintenanceRequest $maintenanceRequest, array $data): MaintenanceRequestResource
    {
        $maintenanceRequest->update($data);
        
        return new MaintenanceRequestResource($maintenanceRequest);
    }
}