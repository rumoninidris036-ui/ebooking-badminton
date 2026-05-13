<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Court\StoreCourtRequest;
use App\Http\Requests\Court\UpdateCourtRequest;
use App\Http\Resources\CourtResource;
use App\Services\CourtService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CourtController extends Controller
{
    public function __construct(
        protected CourtService $courtService,
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        $courts = $this->courtService->listPublic((int) $request->integer('per_page', 10), [
            'search' => $request->string('search')->toString(),
            'location' => $request->string('location')->toString(),
            'min_price' => $request->input('min_price'),
            'max_price' => $request->input('max_price'),
            'facility_ids' => collect($request->input('facility_ids', []))->filter()->map(fn ($id) => (int) $id)->all(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Courts fetched successfully.',
            'data' => [
                'items' => CourtResource::collection($courts->getCollection()),
                'meta' => [
                    'current_page' => $courts->currentPage(),
                    'last_page' => $courts->lastPage(),
                    'per_page' => $courts->perPage(),
                    'total' => $courts->total(),
                ],
            ],
        ]);
    }

    public function store(StoreCourtRequest $request): JsonResponse
    {
        $court = $this->courtService->create($request->validated(), $request->user());

        return response()->json([
            'success' => true,
            'message' => 'Court created successfully.',
            'data' => CourtResource::make($court),
        ], 201);
    }

    public function show(int $court): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Court fetched successfully.',
            'data' => CourtResource::make($this->courtService->findPublicOrFail($court)),
        ]);
    }

    public function update(UpdateCourtRequest $request, int $court): JsonResponse
    {
        $court = $this->courtService->findOwnedOrFail($court, $request->user());
        $court = $this->courtService->update($court, $request->validated(), $request->user());

        return response()->json([
            'success' => true,
            'message' => 'Court updated successfully.',
            'data' => CourtResource::make($court),
        ]);
    }

    public function destroy(Request $request, int $court): JsonResponse
    {
        $court = $this->courtService->findOwnedOrFail($court, $request->user());
        $this->courtService->delete($court, $request->user());

        return response()->json([
            'success' => true,
            'message' => 'Court deleted successfully.',
            'data' => null,
        ]);
    }
}
