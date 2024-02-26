<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StatsRequest;
use App\Interfaces\Repositories\OrderRepositoryInterface;
use Exception;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class StatsController extends Controller
{
    protected const STATS_DAYS_VALUE = 30;

    protected OrderRepositoryInterface $orderRepository;

    public function __construct(OrderRepositoryInterface $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    /**
     * @param StatsRequest $request
     * @return JsonResponse
     */
    public function costOfOrders(StatsRequest $request): JsonResponse
    {
        try {
            $result = $this->orderRepository->averageCostOfOrders(self::STATS_DAYS_VALUE);

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
            $result = $this->orderRepository->dailyAmountOfDrivers(self::STATS_DAYS_VALUE);

            return response()->json(['result' => $result], Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
