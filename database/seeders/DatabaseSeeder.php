<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderDeliveryTime;
use App\Models\Trip;
use App\Models\TripLog;
use App\Models\User;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Vendor;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $rider = User::factory()->create([
            'name' => 'rider 1',
            'role' => User::ROLE_RIDER,
        ]);

        Vendor::factory()
            ->has(
                Order::factory()->state(['delivery_time' => Carbon::now()->addMinutes(10)])->has(
                    OrderDeliveryTime::factory()->state(
                        ['delivery_time' => Carbon::now()->addMinutes(10)],
                    )
                    , 'deliveryTimes')->has(
                    Trip::factory()->state([
                        'rider_id' => $rider->id
                    ])->has(
                        TripLog::factory()
                    )
                )
            )
            ->create();

        Vendor::factory()
            ->has(
                Order::factory()->state(['delivery_time' => Carbon::now()->subMinutes(10)])->has(
                    OrderDeliveryTime::factory()->state(
                        ['delivery_time' => Carbon::now()->subMinutes(10)],
                    )
                    , 'deliveryTimes')->has(
                    Trip::factory()->state([
                        'rider_id' => $rider->id
                    ])->has(
                        TripLog::factory()
                    )
                )
            )
            ->create();

        Vendor::factory()
            ->has(
                Order::factory()->state(['delivery_time' => Carbon::now()->subMinutes(10)])->has(
                    OrderDeliveryTime::factory()->state(
                        ['delivery_time' => Carbon::now()->subMinutes(10)],
                    )
                    , 'deliveryTimes')->has(
                    Trip::factory()->state([
                        'rider_id' => $rider->id,
                        'status'   => Trip::STATUS_DELIVERED
                    ])->has(
                        TripLog::factory()->state([
                            'status' => Trip::STATUS_DELIVERED
                        ])
                    )
                )
            )
            ->create();

        Vendor::factory()
            ->has(
                Order::factory()->state(['delivery_time' => Carbon::now()->subMinutes(10)])->has(
                    OrderDeliveryTime::factory()->state(
                        ['delivery_time' => Carbon::now()->subMinutes(10)],
                    )
                    , 'deliveryTimes')
            )
            ->create();
    }
}
