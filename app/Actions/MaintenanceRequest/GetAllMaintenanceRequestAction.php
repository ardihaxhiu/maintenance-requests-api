<?php

namespace App\Actions\MaintenanceRequest;

use App\Models\MaintenanceRequest;
use App\Http\Resources\MaintenanceRequestResource;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class GetAllMaintenanceRequestAction
{
    public function handle(int $perPage = 15)
    {
        $maintenanceRequests = MaintenanceRequest::with(['user', 'technician'])->paginate($perPage);
        
        return MaintenanceRequestResource::collection($maintenanceRequests);
    }
}
