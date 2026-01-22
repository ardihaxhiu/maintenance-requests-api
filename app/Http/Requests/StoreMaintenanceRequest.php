<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Enums\Maintenance\MaintenanceRequestStatus;
use App\Enums\Maintenance\MaintenanceRequestPriority;

class StoreMaintenanceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'description' => 'required|string|max:255',
            'status' => [
                'required',
                'string',
                Rule::in([
                    MaintenanceRequestStatus::OPEN->value, 
                    MaintenanceRequestStatus::ASSIGNED->value, 
                    MaintenanceRequestStatus::IN_PROGRESS->value, 
                    MaintenanceRequestStatus::COMPLETED->value, 
                    MaintenanceRequestStatus::CANCELLED->value,
                ]),
            ],
            'priority' => [
                'required',
                'string',
                Rule::in([
                    MaintenanceRequestPriority::LOW->value, 
                    MaintenanceRequestPriority::MEDIUM->value, 
                    MaintenanceRequestPriority::HIGH->value,
                ]),
            ],
        ];
    }
}
