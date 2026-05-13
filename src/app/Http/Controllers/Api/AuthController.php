<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\AuthResource;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(
        protected AuthService $authService,
    ) {
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        $payload = $this->authService->register($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Registration completed successfully.',
            'data' => AuthResource::make($payload),
        ], 201);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $payload = $this->authService->login($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Login completed successfully.',
            'data' => AuthResource::make($payload),
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $this->authService->logoutFromToken($request);

        return response()->json([
            'success' => true,
            'message' => 'Logout completed successfully.',
            'data' => null,
        ]);
    }

    public function me(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Authenticated user fetched successfully.',
            'data' => UserResource::make($this->authService->currentUser($request)),
        ]);
    }
}
