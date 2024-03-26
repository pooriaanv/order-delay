<?php

namespace Database\Factories;

use App\Models\DelayReport;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DelayReport>
 */
class DelayReportFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_id' => Order::factory(),
            'agent_id' => User::factory()->state(['role' => User::ROLE_AGENT]),
            'status'   => DelayReport::STATUS_PENDING
        ];
    }
}
