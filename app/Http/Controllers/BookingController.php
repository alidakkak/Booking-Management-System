<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookingRequest;
use App\Http\Resources\BookingResource;
use App\Models\Booking;
use Illuminate\Http\JsonResponse;

class BookingController extends Controller
{
    public function store(StoreBookingRequest $request): JsonResponse
    {
        $data = $request->validated();

        $booking = Booking::create($data);

        return response()->json([
            'message' => 'Booking created successfully.',
            'data'    => BookingResource::make($booking),
        ], 201);
    }
}
