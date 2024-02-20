<?php

namespace App\Http\Controllers\Api;

use App\Enums\OrderStatusEnum;
use App\Events\OrderCreated;
use App\Http\Controllers\Controller;
use App\Http\Requests\OrderCreateRequest;
use App\Http\Requests\OrderUpdateStatusRequest;
use App\Http\Requests\OrderViewStatusRequest;
use App\Models\Driver;
use App\Models\Order;
use App\Support\ApiMessages;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class OrderController extends Controller
{
    /**
     * @param OrderCreateRequest $request
     * @return JsonResponse
     */
    public function create(OrderCreateRequest $request): JsonResponse
    {
        try {
            $order = new Order();
            $order->user_id = Auth::id();
            $order->status = OrderStatusEnum::pending;
            $order->save();

            if ($request->has('dishes')) {
                $order->dishes()->attach($request->dishes);
            }

            event(new OrderCreated($order));

            return response()->json(['message' => ApiMessages::MESSAGE_ORDER_CREATED], Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param OrderViewStatusRequest $request
     * @return JsonResponse
     */
    public function statusView(OrderViewStatusRequest $request): JsonResponse
    {
        try {
            $order = Order::findOrFail($request->id);

            if ($order->status === OrderStatusEnum::processed->value) {
                $driver = Driver::findOrFail($order->driver_id);

                return response()->json([
                    'status' => $order->status,
                    'driver' => $driver,
                ], Response::HTTP_OK);
            }

            return response()->json(['status' => $order->status], Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param OrderUpdateStatusRequest $request
     * @return JsonResponse
     */
    public function statusUpdate(OrderUpdateStatusRequest $request): JsonResponse
    {
        try {
            $order = Order::findOrFail($request->id);
            $order->status = $request->status;
            $order->save();

            return response()->json(['message' => ApiMessages::MESSAGE_ORDER_UPDATED], Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
