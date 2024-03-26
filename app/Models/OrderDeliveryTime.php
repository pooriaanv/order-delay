<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int      $order_id
 * @property          $delivery_time
 * @property  boolean $is_expected
 */
class OrderDeliveryTime extends Model
{
    use HasFactory;

    protected $casts
        = [
            'delivery_time' => 'datetime'
        ];
}
