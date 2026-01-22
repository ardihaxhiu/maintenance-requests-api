<?php

namespace App\Actions\MaintenanceRequest;

use App\Models\MaintenanceRequest;
use App\Enums\Maintenance\MaintenanceRequestStatus;
use Illuminate\Validation\ValidationException;
use App\Http\Resources\MaintenanceRequestResource;

class UpdateStatusMaintenanceRequestAction
{
    public function handle(MaintenanceRequest $maintenanceRequest, array $data): MaintenanceRequestResource
    {
        $this->validateTransition($maintenanceRequest->status, $data['status']);
        
        $maintenanceRequest->update(['status' => $data['status']]);
        
        return new MaintenanceRequestResource($maintenanceRequest);
    }

    private function validateTransition(string $from, string $to): void
    {
        $validTransitions = [
            MaintenanceRequestStatus::OPEN->value => [
                MaintenanceRequestStatus::ASSIGNED->value,
                MaintenanceRequestStatus::CANCELLED->value,
            ],
            MaintenanceRequestStatus::ASSIGNED->value => [
                MaintenanceRequestStatus::IN_PROGRESS->value,
                MaintenanceRequestStatus::CANCELLED->value,
            ],
            MaintenanceRequestStatus::IN_PROGRESS->value => [
                MaintenanceRequestStatus::COMPLETED->value,
                MaintenanceRequestStatus::CANCELLED->value,
            ],
        ];
        
        $allowedStatuses = $validTransitions[$from] ?? [];
        
        if (!in_array($to, $allowedStatuses)) {
            throw ValidationException::withMessages([
                'status' => ["Cannot transition from {$from} to {$to}"]
            ]);
        }
    }
}
