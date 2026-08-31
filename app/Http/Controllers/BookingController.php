<?php

namespace App\Http\Controllers;

use App\Http\Resources\APIResource;
use App\Models\Booking;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $booking = Booking::join("users", "users.id", "=", "bookings.user_id")
            ->join("destinations", "destinations.id", "=", "bookings.destination_id")
            ->select("users.name as name", "destinations.name as destination", "bookings.booking_date", "bookings.status")
            ->get();

        return new APIResource(true, 'List Data Booking', $booking);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'booking_date' => 'required|date',
            'user_id' => 'required|integer|exists:users,id',
            'destination_id' => 'required|integer|exists:destinations,id',
            'status' => 'required|max:50'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $booking = Booking::create($request->all());
        return new APIResource(true, 'Data Booking Berhasil Ditambahkan!', $booking);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Booking $booking)
    {
        $validator = Validator::make($request->all(), [
            'booking_date' => 'required|date',
            'user_id' => 'required|integer|exists:users,id',
            'destination_id' => 'required|integer|exists:destinations,id',
            'status' => 'required|max:50'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $booking->update($request->all());
        return new APIResource(true, 'Data Booking Berhasil Diubah!', $booking);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Booking $booking)
    {
        $booking->delete();
        return new APIResource(true, 'Data Booking Berhasil Dihapus!', null);
    }
}
