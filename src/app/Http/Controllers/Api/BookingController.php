<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Booking\StoreBookingRequest;
use App\Http\Resources\BookingResource;
use App\Http\Resources\CourtResource;
use App\Services\BookingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function __construct(
        protected BookingService $bookingService,
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        $bookings = $this->bookingService->listForUser($request->user(), (int) $request->integer('per_page', 10));

        return response()->json([
            'success' => true,
            'message' => 'Bookings fetched successfully.',
            'data' => [
                'items' => BookingResource::collection($bookings->getCollection()),
                'meta' => [
                    'current_page' => $bookings->currentPage(),
                    'last_page' => $bookings->lastPage(),
                    'per_page' => $bookings->perPage(),
                    'total' => $bookings->total(),
                ],
            ],
        ]);
    }

    public function availability(Request $request): JsonResponse
    {
        $request->validate([
            'court_id' => ['required', 'integer', 'exists:courts,id'],
            'date' => ['required', 'date'],
        ]);

        $availability = $this->bookingService->availability((int) $request->integer('court_id'), (string) $request->string('date'));

        return response()->json([
            'success' => true,
            'message' => 'Availability fetched successfully.',
            'data' => [
                'court' => CourtResource::make($availability['court']),
                'date' => $availability['date'],
                'slots' => $availability['slots'],
            ],
        ]);
    }

    public function store(StoreBookingRequest $request): JsonResponse
    {
        $booking = $this->bookingService->create($request->validated(), $request->user());

        return response()->json([
            'success' => true,
            'message' => 'Booking created successfully.',
            'data' => BookingResource::make($booking),
        ], 201);
    }

    public function show(Request $request, int $booking): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Booking fetched successfully.',
            'data' => BookingResource::make($this->bookingService->findVisibleOrFail($booking, $request->user())),
        ]);
    }

    public function cancel(Request $request, int $booking): JsonResponse
    {
        $validated = $request->validate([
            'cancellation_reason' => ['required', 'string', 'max:1000'],
        ]);

        $booking = $this->bookingService->cancel($booking, $validated, $request->user());

        return response()->json([
            'success' => true,
            'message' => 'Booking canceled successfully.',
            'data' => BookingResource::make($booking),
        ]);
    }

    public function confirm(Request $request, int $booking): JsonResponse
    {
        $booking = $this->bookingService->confirm($booking, $request->user());

        return response()->json([
            'success' => true,
            'message' => 'Booking confirmed successfully.',
            'data' => BookingResource::make($booking),
        ]);
    }

    public function finish(Request $request, int $booking): JsonResponse
    {
        $booking = $this->bookingService->finish($booking, $request->user());

        return response()->json([
            'success' => true,
            'message' => 'Booking marked as finished successfully.',
            'data' => BookingResource::make($booking),
        ]);
    }
}
