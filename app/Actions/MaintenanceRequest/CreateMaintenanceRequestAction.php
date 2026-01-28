<?php

namespace App\Actions\MaintenanceRequest;

use App\Events\MaintenanceRequest\MaintenanceRequestCreated;
use App\Http\Resources\MaintenanceRequestResource;
use App\Models\MaintenanceRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CreateMaintenanceRequestAction
{
    public function handle(array $data): MaintenanceRequestResource
    {
        $imagePath = null;

        try {
            return DB::transaction(function () use ($data, &$imagePath) {
                if (isset($data['image']) && $data['image']) {
                    $imagePath = $data['image']->store('maintenance-requests', 'public');
                    $data['image'] = $imagePath;
                }

                $maintenanceRequest = MaintenanceRequest::create($data + ['user_id' => Auth::id()]);

                MaintenanceRequestCreated::dispatch($maintenanceRequest);
                

                return new MaintenanceRequestResource($maintenanceRequest);
            });
        } catch (\Throwable $e) {
            // Clean up uploaded file if transaction fails
            if ($imagePath) {
                Storage::disk('public')->delete($imagePath);
            }
            throw $e;
        }
    }
}
