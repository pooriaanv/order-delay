<?php

namespace App\Services\CalculateDeliveryTime;

use Illuminate\Http\Client\HttpClientException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;

class RemoteCalculateDeliveryTimeService implements CalculateDeliveryTimeService
{
    /**
     * @param $orderId
     *
     * @return Carbon
     * @throws HttpClientException
     */
    public function calculate($orderId): Carbon
    {
        /*
        * for better testing I didn't use delivery time from response, most of the time mocky.io had issues
        */
        return Carbon::now()->addMinutes(2);


//        $url      = config('services.deliveryTime.url');
//        $response = Http::get($url);


//        if (!$response->successful()) {
                //throw new HttpClientException("CalculateDeliveryTimeService failed to get time.");
//        }

//        return Carbon::make($response->json(['data'])['delivery_time'])->setTimezone(new \DateTimeZone(config('app.timezone')));

    }
}
