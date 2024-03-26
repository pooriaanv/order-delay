<?php

namespace App\Http\Controllers\Agent\Order;

use App\Http\Controllers\Controller;
use App\Http\Requests\Order\ReportDelayRequest;
use App\Services\Order\OrderDelayService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpKernel\Exception\PreconditionFailedHttpException;

class OrderDelayController extends Controller
{
    /**
     * @var OrderDelayService
     */
    private OrderDelayService $orderDelayService;

    /**
     * @param OrderDelayService $orderDelayService
     */
    public function __construct(OrderDelayService $orderDelayService)
    {
        $this->orderDelayService = $orderDelayService;
    }

    /**
     * @return JsonResponse
     */
    public function pickReport(): JsonResponse
    {
        try {
            $agent  = Auth::user();
            $report = $this->orderDelayService->pickReport($agent);

            return success([
                'report_id' => $report->getId()
            ], __('Success'));

        } catch (PreconditionFailedHttpException $exception) {
            return fail('failed', $exception->getMessage(), 412);
        } catch (\Exception $exception) {
            report($exception);
            return fail('failed', __("Something wrong happened, please try later."));
        }
    }
}
