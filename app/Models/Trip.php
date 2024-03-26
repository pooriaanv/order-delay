<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $order_id
 * @property int $rider_id
 * @property int $status
 */
class Trip extends Model
{
    use HasFactory;

    const STATUS_ASSIGNED = 'ASSIGNED';
    const STATUS_AT_VENDOR = 'AT_VENDOR';
    const STATUS_PICKED = 'PICKED';
    const STATUS_DELIVERED = 'DELIVERED';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tripLog()
    {
        return $this->hasMany(TripLog::class, 'trip_id');
    }
}
