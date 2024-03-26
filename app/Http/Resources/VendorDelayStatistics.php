<?php

namespace App\Http\Resources;

use App\Services\Vendor\Entities\DelayStatistics;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VendorDelayStatistics extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return $this->transformData($this->resource);
    }

    /**
     * @param DelayStatistics[] $items
     *
     * @return array
     */
    public function transformData(array $items): array
    {
        $result = [];
        foreach ($items as $item) {
            $result[] = [
                'vendor_name'   => $item->getVendorName(),
                'average_delay' => $item->getDelay()
            ];
        }

        return $result;
    }
}
