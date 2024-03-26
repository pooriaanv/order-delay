<?php

namespace App\Repositories\Order;

use App\Models\Order;
use App\Repositories\BaseRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

interface OrderRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * @param $identifier
     *
     * @return Model|null
     */
    public function findWithTrip($identifier): ?Model;

    /**
     * @param Order  $order
     * @param Carbon $deliveryTime
     *
     * @return void
     */
    public function updateDeliveryTime(Order $order, Carbon $deliveryTime): void;
}
