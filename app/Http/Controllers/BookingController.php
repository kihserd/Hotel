<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Room;
use App\Models\Booking;

class BookingController extends Controller
{
    public function create(Request $request)
    {
        $validator = Validator::make(
            $request->only('room_id', 'date_start', 'date_end'), 
            [
                'room_id' => 'required|exists:rooms,id',
                'date_start' => 'required|date',
                'date_end' => 'required|date',
            ]
        );

        if($validator->fails()) {
            return $validator->errors();
        };
        
        $room = Room::findOrFail($request->input('room_id'));
        return $room->addBooking($validator->valid());
    }

    public function destroy(Request $request)
    {
        $validator = Validator::make(
            $request->only('id'), ['id' => 'required|integer|exists:bookings']
        );

        if($validator->fails()) {
            return $validator->errors();
        };

        Booking::findOrFail($request->input('id'))->delete();
    }

    public function list(Request $request)
    {
        $validator = Validator::make(
            $request->only('room_id'), ['room_id' => 'required|integer|exists:bookings']
        );

        if($validator->fails()) {
            return $validator->errors();
        };        

        $room=Room::findOrFail($request->input('room_id'));

        return $room->bookings
            ->sortBy('date_start')
            ->makeVisible([
                'id',
                'date_start',
                'date_end',
            ])
            ->values();
    }
}
