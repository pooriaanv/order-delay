<?php

namespace App\Repositories\Queue;

use App\Models\Queue;
use App\Repositories\BaseRepositoryInterface;

interface QueueRepositoryInterface extends BaseRepositoryInterface
{
    public function getFirstAvailable(): ?Queue;
}
