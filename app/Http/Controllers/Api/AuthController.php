<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Support\ApiMessages;
use Illuminate\Http\{JsonResponse,Request};
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    /**
     * @param UserRequest $request
     * @return JsonResponse
     */
    public function login(UserRequest $request): JsonResponse
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $token = Auth::user()->createToken('api-token')->accessToken;

            return response()->json(['token' => $token], Response::HTTP_OK);
        }

        return response()->json(['error' => ApiMessages::ERROR_UNAUTHENTICATED], Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->token()->revoke();

        return response()->json(['message' => ApiMessages::MESSAGE_LOGGED_OUT], Response::HTTP_OK);
    }
}
