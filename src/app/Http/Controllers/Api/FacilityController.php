<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\FacilityResource;
use App\Services\FacilityService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FacilityController extends Controller
{
    public function __construct(
        protected FacilityService $facilityService,
    ) {
    }

    public function index(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Facilities fetched successfully.',
            'data' => FacilityResource::collection($this->facilityService->listAll()),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $facility = $this->facilityService->create($request->validate([
            'name' => ['required', 'string', 'max:255'],
            'icon' => ['nullable', 'string', 'max:255'],
        ]), $request->user());

        return response()->json([
            'success' => true,
            'message' => 'Facility created successfully.',
            'data' => FacilityResource::make($facility),
        ], 201);
    }

    public function update(Request $request, int $facility): JsonResponse
    {
        $facility = $this->facilityService->update($facility, $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'icon' => ['nullable', 'string', 'max:255'],
        ]), $request->user());

        return response()->json([
            'success' => true,
            'message' => 'Facility updated successfully.',
            'data' => FacilityResource::make($facility),
        ]);
    }

    public function destroy(Request $request, int $facility): JsonResponse
    {
        $this->facilityService->delete($facility, $request->user());

        return response()->json([
            'success' => true,
            'message' => 'Facility deleted successfully.',
            'data' => null,
        ]);
    }
}
