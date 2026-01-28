<?php

namespace App\Listeners\MaintenanceRequest;

use App\Events\MaintenanceRequest\MaintenanceRequestCreated;
use App\Jobs\SendMaintenanceNotificationJob;
use App\Models\MaintenanceActivity;

class LogActivityAndNotifyOnCreated
{
    public function handle(MaintenanceRequestCreated $event): void
    {
        MaintenanceActivity::create([
            'maintenance_request_id' => $event->maintenanceRequest->id,
            'action' => 'created',
            'metadata' => ['user_id' => $event->maintenanceRequest->user_id],
        ]);

        SendMaintenanceNotificationJob::dispatch(
            $event->maintenanceRequest->id,
            'created',
            ['user_id' => $event->maintenanceRequest->user_id]
        );
    }
}
