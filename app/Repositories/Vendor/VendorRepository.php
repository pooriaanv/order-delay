<?php

namespace App\Repositories\Vendor;

use App\Models\Trip;
use App\Models\Vendor;
use App\Repositories\BaseEloquentRepository;
use App\Services\Vendor\Entities\DelayStatistics;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class VendorRepository extends BaseEloquentRepository implements VendorRepositoryInterface
{
    /**
     * @return Model
     */
    function getModel(): Model
    {
        return new Vendor();
    }

    /**
     * @return DelayStatistics[]
     */
    public function getAverageDelay(int $days = 7): array
    {
        $results = DB::table('orders')
            ->select('vendors.name', DB::raw('floor(avg(EXTRACT(EPOCH FROM (trip_logs.created_at - order_delivery_times.delivery_time)) / 60)) AS difference'))
            ->whereDate('orders.created_at', '>=', Carbon::now()->subDays($days))
            ->join('vendors', 'vendors.id', '=', 'orders.vendor_id')
            ->join('trips', 'orders.id', '=', 'trips.order_id')
            ->join('trip_logs', function (JoinClause $join) {
                $join->on('trip_logs.trip_id', '=', 'trips.id')
                    ->where('trip_logs.status', '=', Trip::STATUS_DELIVERED);
            })
            ->join('order_delivery_times', function (JoinClause $join) {
                $join->on('orders.id', '=', 'order_delivery_times.order_id')
                    ->where('order_delivery_times.is_expected', '=', true);
            })
            ->groupBy('vendors.name')
            ->orderBy('difference', 'desc')
            ->get();

        $items = [];
        foreach ($results as $result) {
            $items[] = new DelayStatistics($result->name, $result->difference < 0 ? 0 : $result->difference);
        }

        return $items;
    }
}
