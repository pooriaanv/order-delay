<?php

namespace App\Services\Order;

use App\Models\DelayReport;
use App\Models\Queue;
use App\Repositories\Queue\QueueRepositoryInterface;
use Illuminate\Database\UniqueConstraintViolationException;

class DbDelayQueue implements DelayQueue
{
    /**
     * @var QueueRepositoryInterface
     */
    private QueueRepositoryInterface $repository;

    /**
     * @param QueueRepositoryInterface $repository
     */
    public function __construct(QueueRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param $id
     *
     * @return void
     */
    public function push($id): void
    {
        $queue = new Queue(['report_id' => $id]);
        $this->repository->save($queue);
    }

    /**
     * @return mixed|null
     */
    public function pop(): ?DelayReport
    {
        $queue = $this->repository->getFirstAvailable();
        if (!$queue)
            return null;

        $report = $queue->delayReport;

        $this->repository->delete($queue);

        return $report;
    }
}
