<?php

namespace Tests\Feature;

use App\Models\DelayReport;
use App\Models\Order;
use App\Models\OrderDeliveryTime;
use App\Models\Queue;
use App\Models\Trip;
use App\Models\TripLog;
use App\Models\User;
use App\Models\Vendor;
use App\Services\Order\OrderDelayService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Tests\TestCase;

class ReportDelayTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @var OrderDelayService|mixed
     */
    private OrderDelayService $sut;

    /**
     * test that checks if report delay fails when delivery time not exceeded
     */
    public function test_report_delay_fails_because_delivery_time_not_exceeded(): void
    {
        $vendor = Vendor::factory()->create();

        $order = Order::factory()->state([
            'delivery_time' => Carbon::now()->addMinutes(10),
            'vendor_id'     => $vendor->id
        ])->has(
            OrderDeliveryTime::factory()->state(['delivery_time' => Carbon::now()->addMinutes(10)]),
            'deliveryTimes'
        )->create();

        $this->expectException(BadRequestException::class);

        $this->sut->reportDelay($order->id);
    }

    /**
     * test that checks if report delay success and get new delivery time
     */
    public function test_report_delay_success_and_gets_new_delivery_time(): void
    {
        $vendor = Vendor::factory()->create();
        $rider  = User::factory()->create([
            'role' => User::ROLE_RIDER,
        ]);

        $order = Order::factory()->state([
            'delivery_time' => Carbon::now()->subMinutes(10),
            'vendor_id'     => $vendor->id
        ])->has(
            OrderDeliveryTime::factory()->state(
                ['delivery_time' => Carbon::now()->subMinutes(10)],
            )
            , 'deliveryTimes')->has(
            Trip::factory()->state([
                'rider_id' => $rider->id
            ])->has(
                TripLog::factory()
            )
        )->create();


        $deliveryTime = $this->sut->reportDelay($order->id);

        $this->assertNotNull($deliveryTime);
    }

    /**
     *
     */
    public function test_report_delay_success_and_push_to_queue(): void
    {
        $vendor = Vendor::factory()->create();
        $rider  = User::factory()->create([
            'role' => User::ROLE_RIDER,
        ]);

        $order = Order::factory()->state([
            'delivery_time' => Carbon::now()->subMinutes(10),
            'vendor_id'     => $vendor->id
        ])->has(
            OrderDeliveryTime::factory()->state(
                ['delivery_time' => Carbon::now()->subMinutes(10)],
            )
            , 'deliveryTimes')->has(
            Trip::factory()->state([
                'status'   => Trip::STATUS_DELIVERED,
                'rider_id' => $rider->id
            ])->has(
                TripLog::factory()->state([
                    'status' => Trip::STATUS_DELIVERED,
                ])
            )
        )->create();


        $deliveryTime = $this->sut->reportDelay($order->id);

        $this->assertNull($deliveryTime);
    }

    /**
     *
     */
    public function test_report_delay_fail_on_duplicate(): void
    {
        $vendor = Vendor::factory()->create();
        $rider  = User::factory()->create([
            'role' => User::ROLE_RIDER,
        ]);

        $order = Order::factory()->state([
            'delivery_time' => Carbon::now()->subMinutes(10),
            'vendor_id'     => $vendor->id
        ])->has(
            OrderDeliveryTime::factory()->state(
                ['delivery_time' => Carbon::now()->subMinutes(10)],
            )
            , 'deliveryTimes')->has(
            Trip::factory()->state([
                'status'   => Trip::STATUS_DELIVERED,
                'rider_id' => $rider->id
            ])->has(
                TripLog::factory()->state([
                    'status' => Trip::STATUS_DELIVERED,
                ])
            )
        )->create();

        DelayReport::factory()->state([
            'order_id' => $order->id,
            'status'   => DelayReport::STATUS_PENDING
        ])->create();


        $this->expectException(BadRequestException::class);
        $this->sut->reportDelay($order->id);
    }

    /**
     *
     */
    public function test_pick_report_successfully(): void
    {
        $vendor = Vendor::factory()->create();
        $agent  = User::factory()->state([
            'role' => User::ROLE_AGENT
        ])->create();

        $order = Order::factory()->state([
            'delivery_time' => Carbon::now()->subMinutes(10),
            'vendor_id'     => $vendor->id
        ])->create();

        $delayReport = DelayReport::factory()->state([
            'order_id' => $order->id,
            'status'   => DelayReport::STATUS_PENDING
        ])->create();

        Queue::factory()->state([
            'report_id' => $delayReport->id
        ])->create();

        $report = $this->sut->pickReport($agent);

        $this->assertInstanceOf(DelayReport::class, $report);
    }

    /**
     *
     */
    public function test_pick_report_failed_because_queue_is_empty(): void
    {
        $agent = User::factory()->state([
            'role' => User::ROLE_AGENT
        ])->create();
        Queue::query()->truncate();

        $this->expectExceptionMessage('No available report.');
        $this->sut->pickReport($agent);
    }

    /**
     *
     */
    public function test_pick_report_failed_because_agent_already_has_pending_report(): void
    {
        $vendor = Vendor::factory()->create();
        $agent  = User::factory()->state([
            'role' => User::ROLE_AGENT
        ])->create();

        $order = Order::factory()->state([
            'delivery_time' => Carbon::now()->subMinutes(10),
            'vendor_id'     => $vendor->id
        ])->create();

        DelayReport::factory()->state([
            'order_id' => $order->id,
            'agent_id' => $agent->id,
            'status'   => DelayReport::STATUS_PENDING
        ])->create();


        $this->expectExceptionMessage('You already have a pending report.');
        $this->sut->pickReport($agent);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->sut = $this->app->make(OrderDelayService::class);
    }
}
