<?php

namespace App\Providers;

use App\Events\MaintenanceRequest\MaintenanceRequestAssigned;
use App\Events\MaintenanceRequest\MaintenanceRequestCreated;
use App\Events\MaintenanceRequest\MaintenanceRequestStatusChanged;
use App\Listeners\MaintenanceRequest\LogActivityAndNotifyOnAssigned;
use App\Listeners\MaintenanceRequest\LogActivityAndNotifyOnCreated;
use App\Listeners\MaintenanceRequest\LogActivityAndNotifyOnStatusChanged;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Event::listen(MaintenanceRequestCreated::class, LogActivityAndNotifyOnCreated::class);
        Event::listen(MaintenanceRequestAssigned::class, LogActivityAndNotifyOnAssigned::class);
        Event::listen(MaintenanceRequestStatusChanged::class, LogActivityAndNotifyOnStatusChanged::class);
    }
}
