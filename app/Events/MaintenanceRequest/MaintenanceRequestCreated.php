<?php

namespace App\Events\MaintenanceRequest;

use App\Models\MaintenanceRequest;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MaintenanceRequestCreated
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public MaintenanceRequest $maintenanceRequest
    ) {}
}
