<?php

namespace App\Enums\Maintenance;

enum MaintenanceRequestPriority: string
{
    case LOW = 'low';
    case MEDIUM = 'medium';
    case HIGH = 'high';
}
