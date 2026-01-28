<?php

namespace App\Actions\MaintenanceRequest;

use App\Enums\Maintenance\MaintenanceRequestStatus;
use App\Events\MaintenanceRequest\MaintenanceRequestStatusChanged;
use App\Http\Resources\MaintenanceRequestResource;
use App\Models\MaintenanceRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class UpdateStatusMaintenanceRequestAction
{
    public function handle(MaintenanceRequest $maintenanceRequest, array $data): MaintenanceRequestResource
    {
        $fromStatus = $maintenanceRequest->status;
        $toStatus = $data['status'];

        $this->validateTransition($fromStatus, $toStatus);

        return DB::transaction(function () use ($maintenanceRequest, $fromStatus, $toStatus) {
            $maintenanceRequest->update(['status' => $toStatus]);

            MaintenanceRequestStatusChanged::dispatch($maintenanceRequest->fresh(), $fromStatus, $toStatus);

            return new MaintenanceRequestResource($maintenanceRequest);
        });
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
