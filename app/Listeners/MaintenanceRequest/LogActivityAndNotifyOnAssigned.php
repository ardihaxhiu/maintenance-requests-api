<?php

namespace App\Listeners\MaintenanceRequest;

use App\Events\MaintenanceRequest\MaintenanceRequestAssigned;
use App\Jobs\SendMaintenanceNotificationJob;
use App\Models\MaintenanceActivity;

class LogActivityAndNotifyOnAssigned
{
    public function handle(MaintenanceRequestAssigned $event): void
    {
        MaintenanceActivity::create([
            'maintenance_request_id' => $event->maintenanceRequest->id,
            'action' => 'assigned',
            'metadata' => ['technician_id' => $event->technicianId],
        ]);

        SendMaintenanceNotificationJob::dispatch(
            $event->maintenanceRequest->id,
            'assigned',
            ['technician_id' => $event->technicianId]
        );
    }
}
