<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\DishRestaurantRequest;
use App\Http\Resources\DishResource;
use App\Models\Restaurant;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response;

class DishController extends Controller
{
    /**
     * @param DishRestaurantRequest $request
     * @return JsonResponse|AnonymousResourceCollection
     */
    public function list(DishRestaurantRequest $request): JsonResponse|AnonymousResourceCollection
    {
        try {
            $dishes = Restaurant::findOrFail($request->restaurant_id)->dishes()->get();

            return DishResource::collection($dishes);
        } catch (Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
