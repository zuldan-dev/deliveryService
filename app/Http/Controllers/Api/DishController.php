<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\DishRestaurantRequest;
use App\Models\Restaurant;
use Exception;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class DishController extends Controller
{
    /**
     * @param DishRestaurantRequest $request
     * @return JsonResponse
     */
    public function list(DishRestaurantRequest $request): JsonResponse
    {
        try {
            $dishes = Restaurant::findOrFail($request->restaurant_id)->dishes()->get();

            return response()->json(['dishes' => $dishes], Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
