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
    public function index(Request $request)
    {
        $user = $request->user();

        $bookings = Booking::with(['user:id,name', 'destination:id,name,image_url'])
            ->when($user->role !== 'Admin', function ($query) use ($user) {
                return $query->where('user_id', $user->id);
            })
            ->latest()
            ->get();

        return new APIResource(true, 'List Data Booking', $bookings);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'booking_date' => 'required|date|after_or_equal:today',
            'destination_id' => 'required|integer|exists:destinations,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $booking = Booking::create(
            [
                'booking_date' => $request->booking_date,
                'user_id' =>  $request->user()->id,
                'destination_id' => $request->destination_id,
                'status' => 'Selesai'
            ]
        );
        return new APIResource(true, 'Data Booking Berhasil Ditambahkan!', $booking);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Booking $booking)
    {
        $validator = Validator::make($request->all(), [
            'booking_date' => 'required|date|after_or_equal:today',
            'destination_id' => 'required|integer|exists:destinations,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $booking->update([
            'booking_date' => $request->booking_date,
            'destination_id' => $request->destination_id,
        ]);

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
