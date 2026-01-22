<?php

namespace App\Actions\MaintenanceRequest;

use App\Models\MaintenanceRequest;
use App\Http\Resources\MaintenanceRequestResource;
use Illuminate\Support\Facades\Auth;

class CreateMaintenanceRequestAction
{
    public function handle(array $data): MaintenanceRequestResource
    {
        $maintenanceRequest = MaintenanceRequest::create($data + ['user_id' => Auth::id()]);

        return new MaintenanceRequestResource($maintenanceRequest);
    }
}
