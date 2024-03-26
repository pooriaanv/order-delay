<?php

namespace App\Services\Order;

use App\Models\DelayReport;
use Illuminate\Support\Facades\Cache;

class DelayQueueWithLock implements DelayQueue
{
    /**
     * @var DelayQueue
     */
    private DelayQueue $delayQueue;

    /**
     * @param DelayQueue $delayQueue
     */
    public function __construct(DelayQueue $delayQueue)
    {
        $this->delayQueue = $delayQueue;
    }

    /**
     * @param $id
     *
     * @return void
     */
    public function push($id): void
    {
        $this->delayQueue->push($id);
    }

    /**
     * @return mixed|null
     */
    public function pop(): ?DelayReport
    {
        return Cache::lock("popQueue", 5)->block(3, function () {
            return $this->delayQueue->pop();
        });
    }
}
