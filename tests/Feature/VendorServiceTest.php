<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\OrderDeliveryTime;
use App\Models\Trip;
use App\Models\TripLog;
use App\Models\User;
use App\Models\Vendor;
use App\Services\Vendor\VendorService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class VendorServiceTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @var VendorService|mixed
     */
    private VendorService $sut;

    /**
     * test for success result for vendor statistics
     */
    public function test_get_result_for_vendor_statistics(): void
    {
        $vendor = Vendor::factory()->create();
        $rider  = User::factory()->create([
            'role' => User::ROLE_RIDER,
        ]);

        $now = Carbon::now();
        Order::factory()->state([
            'delivery_time' => Carbon::now()->subMinutes(10),
            'vendor_id'     => $vendor->id
        ])->has(
            OrderDeliveryTime::factory()->state(
                [
                    'delivery_time' => $now->subMinutes(10)
                ],
            )
            , 'deliveryTimes')->has(
            Trip::factory()->state([
                'status'   => Trip::STATUS_DELIVERED,
                'rider_id' => $rider->id
            ])->has(
                TripLog::factory()->state([
                    'status'     => Trip::STATUS_DELIVERED,
                    'created_at' => $now
                ])
            )
        )->create();

        $result = $this->sut->calculateDelays();

        $this->assertNotCount(0, $result);
    }


    protected function setUp(): void
    {
        parent::setUp();

        $this->sut = $this->app->make(VendorService::class);
    }
}
