<?php

namespace App\Repositories\Vendor;

use App\Repositories\BaseRepositoryInterface;
use App\Services\Vendor\Entities\DelayStatistics;

interface VendorRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * @return DelayStatistics[]
     */
    public function getAverageDelay(): array;
}
