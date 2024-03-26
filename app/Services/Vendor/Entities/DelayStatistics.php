<?php

namespace App\Services\Vendor\Entities;

class DelayStatistics
{
    /**
     * @var string
     */
    private string $vendorName;

    /**
     * @var int
     */
    private int $delay;

    /**
     * @param string $vendorName
     * @param int    $delay
     */
    public function __construct(string $vendorName, int $delay)
    {
        $this->vendorName = $vendorName;
        $this->delay      = $delay;
    }

    /**
     * @return string
     */
    public function getVendorName(): string
    {
        return $this->vendorName;
    }

    /**
     * @return int
     */
    public function getDelay(): int
    {
        return $this->delay;
    }
}
