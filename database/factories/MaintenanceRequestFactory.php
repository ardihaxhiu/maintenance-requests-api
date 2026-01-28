<?php

namespace Database\Factories;

use App\Enums\Maintenance\MaintenanceRequestPriority;
use App\Enums\Maintenance\MaintenanceRequestStatus;
use App\Models\MaintenanceRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MaintenanceRequest>
 */
class MaintenanceRequestFactory extends Factory
{
    protected $model = MaintenanceRequest::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'technician_id' => null,
            'description' => fake()->paragraph(),
            'priority' => fake()->randomElement([
                MaintenanceRequestPriority::LOW->value,
                MaintenanceRequestPriority::MEDIUM->value,
                MaintenanceRequestPriority::HIGH->value,
            ]),
            'status' => MaintenanceRequestStatus::OPEN->value,
            'image' => null,
        ];
    }

    /**
     * Indicate that the request is assigned to a technician.
     */
    public function assigned(): static
    {
        return $this->state(fn (array $attributes) => [
            'technician_id' => User::factory(),
            'status' => MaintenanceRequestStatus::ASSIGNED->value,
        ]);
    }

    /**
     * Indicate that the request is in progress.
     */
    public function inProgress(): static
    {
        return $this->state(fn (array $attributes) => [
            'technician_id' => User::factory(),
            'status' => MaintenanceRequestStatus::IN_PROGRESS->value,
        ]);
    }

    /**
     * Indicate that the request is completed.
     */
    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'technician_id' => User::factory(),
            'status' => MaintenanceRequestStatus::COMPLETED->value,
        ]);
    }
}
