<?php

namespace App\Http\Controllers\User\Order;

use App\Http\Controllers\Controller;
use App\Http\Requests\Order\ReportDelayRequest;
use App\Services\Order\OrderDelayService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

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
     * @param ReportDelayRequest $request
     *
     * @return JsonResponse
     */
    public function reportDelay(ReportDelayRequest $request): JsonResponse
    {
        try {
            $deliveryTime = $this->orderDelayService->reportDelay($request->get('id'));

            if ($deliveryTime) {
                return success([
                    'delivery_time' => $deliveryTime
                ], __('Success'));
            }

            return success(null, __('Success'), 201);

        } catch (BadRequestException | ModelNotFoundException $exception) {
            return fail('failed', $exception->getMessage(), $exception->getCode());
        } catch (\Exception $exception) {
            report($exception);
            return fail('failed', __("Something wrong happened, please try later."));
        }
    }
}
