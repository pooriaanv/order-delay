<?php

namespace App\Services\Order;

use App\Models\DelayReport;
use App\Models\Order;
use App\Models\User;
use App\Repositories\Order\DelayReportRepositoryInterface;
use App\Repositories\Order\OrderRepositoryInterface;
use App\Services\CalculateDeliveryTime\CalculateDeliveryTimeService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpKernel\Exception\PreconditionFailedHttpException;

class OrderDelayService
{
    /**
     * @var OrderRepositoryInterface
     */
    private OrderRepositoryInterface $orderRepository;

    /**
     * @var DelayReportRepositoryInterface
     */
    private DelayReportRepositoryInterface $delayReportRepository;

    /**
     * @var CalculateDeliveryTimeService
     */
    private CalculateDeliveryTimeService $calculateDeliveryTimeService;

    /**
     * @var DelayQueue
     */
    private DelayQueue $delayQueue;

    /**
     * @param OrderRepositoryInterface       $orderRepository
     * @param DelayReportRepositoryInterface $delayReportRepository
     * @param CalculateDeliveryTimeService   $calculateDeliveryTimeService
     * @param DelayQueue                     $delayQueue
     */
    public function __construct(
        OrderRepositoryInterface       $orderRepository,
        DelayReportRepositoryInterface $delayReportRepository,
        CalculateDeliveryTimeService   $calculateDeliveryTimeService,
        DelayQueue                     $delayQueue
    )
    {
        $this->orderRepository              = $orderRepository;
        $this->delayReportRepository        = $delayReportRepository;
        $this->calculateDeliveryTimeService = $calculateDeliveryTimeService;
        $this->delayQueue                   = $delayQueue;
    }

    /**
     * @param int $orderId
     *
     * @return ?Carbon
     */
    public function reportDelay(int $orderId): ?Carbon
    {
        $order = $this->orderRepository->find($orderId);
        if (!$order) {
            throw new ModelNotFoundException(__('Order not found.'), 404);
        }
        assert($order instanceof Order);

        return Cache::lock("reportDelayOrder:" . $order->getId(), 5)->block(3, function () use ($order) {

            if (!$order->isDeliveryTimeExceeded() || $this->delayReportRepository->hasPendingReport($order->getId())) {
                throw new BadRequestException(__('Could not report delay for this order.'), 400);
            }

            $report = new DelayReport();
            $report->setOrderId($order->getId());

            $deliveryTime = null;
            if ($order->hasTrip() && !$order->isDelivered()) {
                $deliveryTime = $this->calculateDeliveryTimeService->calculate($order->id);
                $this->orderRepository->updateDeliveryTime($order, $deliveryTime);

                $report->checked();
            } else {
                $report->pending();
            }

            $this->delayReportRepository->save($report);

            if ($report->shouldQueue()) {
                try {
                    $this->delayQueue->push($report->getId());
                } catch (\Exception $exception) {
                    Log::error("Failed to push report to queue", [
                        'message' => $exception->getMessage()
                    ]);
                }
            }

            return $deliveryTime;

        });
    }

    /**
     * @param User $agent
     *
     * @return DelayReport
     */
    public function pickReport(User $agent): DelayReport
    {
        return Cache::lock("pickOrder", 5)->block(3, function () use ($agent) {

            if ($this->delayReportRepository->agentHasPendingReport($agent->getId())) {
                throw new PreconditionFailedHttpException(__("You already have a pending report."));
            }

            $report = $this->delayQueue->pop();

            if (!$report) {
                throw new PreconditionFailedHttpException(__("No available report."));
            }

            $this->delayReportRepository->assignToAgent($report, $agent->getId());

            return $report;
        });
    }
}
