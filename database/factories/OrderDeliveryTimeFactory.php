<?php

namespace Database\Factories;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderDeliveryTime>
 */
class OrderDeliveryTimeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_id'      => Order::factory(),
            'is_expected'   => true,
            'delivery_time' => Carbon::now()
        ];
    }
}
