<?php

namespace App\Services\Order;

use App\Models\DelayReport;

interface DelayQueue
{
    public function push($id): void;

    public function pop(): ?DelayReport;
}
