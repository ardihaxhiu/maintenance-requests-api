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

        return response()->json($maintenanceRequest);
    }

    public function update(MaintenanceRequest $maintenanceRequest, UpdateMaintenanceRequest $request, UpdateMaintenanceRequestAction $action)
    {
        $maintenanceRequest = $action->handle($maintenanceRequest, $request->validated());
        return response()->json($maintenanceRequest);
    }

    public function destroy(MaintenanceRequest $maintenanceRequest)
    {
        $maintenanceRequest->delete();
        return response()->json(['message' => 'Maintenance request deleted successfully']);
    }

    public function show(MaintenanceRequest $maintenanceRequest, GetMaintenanceRequestAction $action)
    {
        return response()->json($action->handle($maintenanceRequest));
    }

    public function assign(MaintenanceRequest $maintenanceRequest, AssignMaintenanceRequest $request, AssignMaintenanceRequestAction $action)
    {
        $maintenanceRequest = $action->handle($maintenanceRequest, $request->validated());
        return response()->json($maintenanceRequest);
    }

    public function status(MaintenanceRequest $maintenanceRequest, UpdateStatusMaintenanceRequest $request, UpdateStatusMaintenanceRequestAction $action)
    {
        $this->authorize('updateStatus', $maintenanceRequest);

        $maintenanceRequest = $action->handle($maintenanceRequest, $request->validated());
        
        return response()->json($maintenanceRequest);
    }
}
