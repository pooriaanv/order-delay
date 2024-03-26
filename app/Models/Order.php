<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int    $id
 * @property Trip   $trip
 * @property string $content
 * @property int    $vendor_id
 * @property Carbon $delivery_time
 */
class Order extends Model
{
    use HasFactory;

    protected $fillable
        = [
            'delivery_time'
        ];

    /**
     * @var string[]
     */
    protected $casts
        = [
            'delivery_time' => 'datetime'
        ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function trip()
    {
        return $this->hasOne(Trip::class, 'order_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function deliveryTimes()
    {
        return $this->hasMany(OrderDeliveryTime::class, 'order_id');
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * @return int|null
     */
    public function getVendorId(): ?int
    {
        return $this->vendor_id;
    }

    /**
     * @return Carbon|null
     */
    public function getDeliveryTime(): ?Carbon
    {
        return $this->delivery_time;
    }

    public function isDeliveryTimeExceeded(): bool
    {
        return !$this->delivery_time->gt(now());
    }

    /**
     * @return bool
     */
    public function hasTrip(): bool
    {
        return (bool)$this->trip;
    }

    /**
     * @return bool
     */
    public function isDelivered(): bool
    {
        return $this->trip->status == Trip::STATUS_DELIVERED;
    }
}
