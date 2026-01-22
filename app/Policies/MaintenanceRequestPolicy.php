<?php

namespace App\Policies;

use App\Models\User;
use App\Models\MaintenanceRequest;

class MaintenanceRequestPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function updateStatus(User $user, MaintenanceRequest $maintenanceRequest): bool
    {
        return $maintenanceRequest->technician_id === $user->id;
    }
}
