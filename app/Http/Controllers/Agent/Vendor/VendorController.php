<?php

namespace App\Http\Controllers\Agent\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Resources\VendorDelayStatistics;
use App\Services\Vendor\VendorService;
use Illuminate\Http\JsonResponse;

class VendorController extends Controller
{
    /**
     * @var VendorService
     */
    private VendorService $vendorService;

    /**
     * @param VendorService $vendorService
     */
    public function __construct(VendorService $vendorService)
    {
        $this->vendorService = $vendorService;
    }

    /**
     * @return JsonResponse
     */
    public function delayStatistics(): JsonResponse
    {
        try {
            $result = $this->vendorService->calculateDelays();

            return success(new VendorDelayStatistics($result), __('Success'));

        } catch (\Exception $exception) {
            report($exception);
            return fail('failed', __("Something wrong happened, please try later."));
        }
    }
}
