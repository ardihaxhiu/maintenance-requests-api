<?php

namespace App\Actions\MaintenanceRequest;

use App\Models\MaintenanceRequest;
use App\Http\Resources\MaintenanceRequestResource;
use Illuminate\Support\Facades\Storage;

class UpdateMaintenanceRequestAction
{
    public function handle(MaintenanceRequest $maintenanceRequest, array $data): MaintenanceRequestResource
    {
        if (isset($data['image'])) {
            if ($maintenanceRequest->image) {
                Storage::disk('public')->delete($maintenanceRequest->image);
            }
            
            $data['image'] = $data['image']->store('maintenance-requests', 'public');
        }
        
        $maintenanceRequest->update($data);
        
        return new MaintenanceRequestResource($maintenanceRequest);
    }
}