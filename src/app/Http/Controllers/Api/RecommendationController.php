<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\RecommendationResource;
use App\Models\User;
use App\Services\RecommendationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RecommendationController extends Controller
{
    public function __construct(
        protected RecommendationService $recommendationService,
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        $request->validate([
            'user_id' => ['nullable', 'integer', 'exists:users,id'],
        ]);

        $user = $request->user() ?? User::query()->findOrFail((int) $request->integer('user_id'));
        $items = $this->recommendationService->listForUser($user, (int) $request->integer('limit', 10));

        return response()->json([
            'success' => true,
            'message' => 'Recommendations fetched successfully.',
            'data' => RecommendationResource::collection($items),
        ]);
    }
}
