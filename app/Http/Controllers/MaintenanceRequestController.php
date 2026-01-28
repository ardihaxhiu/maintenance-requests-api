<?php

namespace App\Http\Controllers;

use App\Actions\MaintenanceRequest\GetAllMaintenanceRequestAction;
use App\Actions\MaintenanceRequest\CreateMaintenanceRequestAction;
use App\Http\Requests\StoreMaintenanceRequest;
use App\Models\MaintenanceRequest;
use App\Actions\MaintenanceRequest\GetMaintenanceRequestAction;
use App\Actions\MaintenanceRequest\UpdateMaintenanceRequestAction;
use App\Http\Requests\UpdateMaintenanceRequest;
use App\Http\Requests\AssignMaintenanceRequest;
use App\Actions\MaintenanceRequest\AssignMaintenanceRequestAction;
use App\Http\Requests\UpdateStatusMaintenanceRequest;
use App\Actions\MaintenanceRequest\UpdateStatusMaintenanceRequestAction;

class MaintenanceRequestController extends Controller
{
    public function index(GetAllMaintenanceRequestAction $action)
    {
        return $action->handle();
    }

    public function store(StoreMaintenanceRequest $request, CreateMaintenanceRequestAction $action)
    {
        $maintenanceRequest = $action->handle($request->validated());

        return $maintenanceRequest->response();
    }

    public function update(MaintenanceRequest $maintenanceRequest, UpdateMaintenanceRequest $request, UpdateMaintenanceRequestAction $action)
    {
        return $action->handle($maintenanceRequest, $request->validated());
    }

    public function destroy(MaintenanceRequest $maintenanceRequest)
    {
        $maintenanceRequest->delete();
        return response()->json(['message' => 'Maintenance request deleted successfully']);
    }

    public function show(MaintenanceRequest $maintenanceRequest, GetMaintenanceRequestAction $action)
    {
        return $action->handle($maintenanceRequest);
    }

    public function assign(MaintenanceRequest $maintenanceRequest, AssignMaintenanceRequest $request, AssignMaintenanceRequestAction $action)
    {
        return $action->handle($maintenanceRequest, $request->validated());
    }

    public function status(MaintenanceRequest $maintenanceRequest, UpdateStatusMaintenanceRequest $request, UpdateStatusMaintenanceRequestAction $action)
    {
        $this->authorize('updateStatus', $maintenanceRequest);

        return $action->handle($maintenanceRequest, $request->validated());
    }
}
