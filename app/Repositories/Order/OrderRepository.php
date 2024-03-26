<?php

namespace App\Repositories\Order;

use App\Models\DelayReport;
use App\Models\Order;
use App\Models\OrderDeliveryTime;
use App\Repositories\BaseEloquentRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class OrderRepository extends BaseEloquentRepository implements OrderRepositoryInterface
{
    function getModel(): Model
    {
        return new Order();
    }

    public function findWithTrip($identifier): ?Model
    {
        return $this->model->query()->with('trip')->find($identifier);
    }

    /**
     * @param Order  $order
     * @param Carbon $deliveryTime
     */
    public function updateDeliveryTime(Order $order, Carbon $deliveryTime): void
    {
        $order->fill(['delivery_time' => $deliveryTime]);
        $order->save();

        $deliveryTimeModel                = new OrderDeliveryTime();
        $deliveryTimeModel->delivery_time = $deliveryTime;
        $deliveryTimeModel->order_id      = $order->getId();
        $deliveryTimeModel->is_expected   = false;

        $deliveryTimeModel->save();
    }
}
