<?php

namespace App\Events\MaintenanceRequest;

use App\Models\MaintenanceRequest;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MaintenanceRequestStatusChanged
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public MaintenanceRequest $maintenanceRequest,
        public string $fromStatus,
        public string $toStatus
    ) {}
}
