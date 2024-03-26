<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int    id
 * @property int    order_id
 * @property int    agent_id
 * @property string status
 */
class DelayReport extends Model
{
    use HasFactory;

    protected $fillable
        = [
            'agent_id', 'status', 'order_id'
        ];

    const STATUS_CHECKED = 'CHECKED';
    const STATUS_PENDING = 'PENDING';

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
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * @return int|null
     */
    public function getOrderId(): ?int
    {
        return $this->order_id;
    }

    /**
     * @return int|null
     */
    public function getAgentId(): ?int
    {
        return $this->agent_id;
    }

    /**
     * @return bool
     */
    public function shouldQueue(): bool
    {
        return $this->status == self::STATUS_PENDING;
    }

    /**
     * Set status checked
     */
    public function checked(): void
    {
        $this->status = self::STATUS_CHECKED;
    }

    /**
     * Set status pending
     */
    public function pending(): void
    {
        $this->status = self::STATUS_PENDING;
    }

    /**
     * @param int $orderId
     */
    public function setOrderId(int $orderId)
    {
        $this->order_id = $orderId;
    }
}
