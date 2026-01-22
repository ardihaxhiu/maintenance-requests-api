<?php 

namespace App\Actions\MaintenanceRequest;

use App\Models\MaintenanceRequest;
use App\Http\Resources\MaintenanceRequestResource;

class AssignMaintenanceRequestAction
{
    public function handle(MaintenanceRequest $maintenanceRequest, array $data): MaintenanceRequestResource
    {
        $maintenanceRequest->update(['technician_id' => $data['technician_id']]);
        return new MaintenanceRequestResource($maintenanceRequest->load('technician'));
    }
}