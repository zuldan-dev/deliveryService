<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StatsRequest;
use App\Models\Order;
use Exception;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class StatsController extends Controller
{
    protected const STATS_DAYS_VALUE = 30;

    /**
     * @param StatsRequest $request
     * @return JsonResponse
     */
    public function costOfOrders(StatsRequest $request): JsonResponse
    {
        try {
            $result = Order::averageCostOfOrders(self::STATS_DAYS_VALUE);

            return response()->json(['result' => $result], Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param StatsRequest $request
     * @return JsonResponse
     */
    public function amountByDrivers(StatsRequest $request): JsonResponse
    {
        try {
            $result = Order::dailyAmountOfDrivers(self::STATS_DAYS_VALUE);

            return response()->json(['result' => $result], Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
