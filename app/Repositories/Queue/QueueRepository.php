<?php

namespace App\Repositories\Queue;

use App\Models\Queue;
use App\Repositories\BaseEloquentRepository;
use Illuminate\Database\Eloquent\Model;

class QueueRepository extends BaseEloquentRepository implements QueueRepositoryInterface
{
    function getModel(): Model
    {
        return new Queue();
    }

    /**
     * @return Queue|null
     */
    public function getFirstAvailable(): ?Queue
    {
        return $this->model->newQuery()
            ->with('delayReport')
            ->oldest()
            ->first();
    }
}
