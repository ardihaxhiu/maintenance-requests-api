<?php

namespace App\Listeners\MaintenanceRequest;

use App\Events\MaintenanceRequest\MaintenanceRequestStatusChanged;
use App\Jobs\SendMaintenanceNotificationJob;
use App\Models\MaintenanceActivity;

class LogActivityAndNotifyOnStatusChanged
{
    public function handle(MaintenanceRequestStatusChanged $event): void
    {
        MaintenanceActivity::create([
            'maintenance_request_id' => $event->maintenanceRequest->id,
            'action' => 'status_changed',
            'metadata' => [
                'from' => $event->fromStatus,
                'to' => $event->toStatus,
            ],
        ]);

        SendMaintenanceNotificationJob::dispatch(
            $event->maintenanceRequest->id,
            'status_changed',
            ['from' => $event->fromStatus, 'to' => $event->toStatus]
        );
    }
}
