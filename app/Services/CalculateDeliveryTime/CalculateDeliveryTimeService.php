<?php

namespace App\Services\CalculateDeliveryTime;

use Carbon\Carbon;

interface CalculateDeliveryTimeService
{
    public function calculate($orderId): Carbon;
}
