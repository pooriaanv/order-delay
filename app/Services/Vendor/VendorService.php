<?php

namespace App\Services\Vendor;

use App\Repositories\Vendor\VendorRepositoryInterface;
use App\Services\Vendor\Entities\DelayStatistics;

class VendorService
{
    /**
     * @var VendorRepositoryInterface
     */
    private VendorRepositoryInterface $vendorRepository;

    /**
     * @param VendorRepositoryInterface $vendorRepository
     */
    public function __construct(VendorRepositoryInterface $vendorRepository)
    {
        $this->vendorRepository = $vendorRepository;
    }

    /**
     * @return DelayStatistics[]
     */
    public function calculateDelays(): array
    {
        return $this->vendorRepository->getAverageDelay();
    }
}
