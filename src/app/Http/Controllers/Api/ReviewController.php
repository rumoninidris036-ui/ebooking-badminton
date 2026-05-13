<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ReviewResource;
use App\Services\ReviewService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function __construct(
        protected ReviewService $reviewService,
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        $reviews = $this->reviewService->listPublic($request->integer('court_id') ?: null);

        return response()->json([
            'success' => true,
            'message' => 'Reviews fetched successfully.',
            'data' => ReviewResource::collection($reviews),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $review = $this->reviewService->create($request->validate([
            'court_id' => ['required', 'integer', 'exists:courts,id'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['required', 'string', 'max:2000'],
        ]), $request->user());

        return response()->json([
            'success' => true,
            'message' => 'Review created successfully.',
            'data' => ReviewResource::make($review),
        ], 201);
    }
}
