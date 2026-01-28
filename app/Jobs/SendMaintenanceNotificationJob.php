<?php

namespace App\Jobs;

use App\Models\MaintenanceRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use App\Models\MaintenanceActivity;

class SendMaintenanceNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public int $maintenanceRequestId,
        public string $eventType,
        public array $metadata = []
    ) {}

    public function handle(): void
    {
        $request = MaintenanceRequest::find($this->maintenanceRequestId);
        
        if (!$request) {
            return;
        }

        // Simulate sending notification 
        Log::info('Maintenance notification (simulated)', [
            'maintenance_request_id' => $this->maintenanceRequestId,
            'event_type' => $this->eventType,
            'metadata' => $this->metadata,
        ]);
    }
}
