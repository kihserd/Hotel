<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RoomController extends Controller
{
    public function create(Request $request)
    {
        $validator = Validator::make(
            $request->only('description','price'), 
            [
                'description' => 'required|min:5',
                'price' => 'required|integer|min:10',
            ]
        );
        if($validator->fails()) {
            return $validator->errors();
        };

        $room = Room::create($validator->valid());
        $json = $room->makeVisible('id')->toJson();
        return Room::correctId($json);
    }

    public function destroy(Request $request)
    {
        $validator = Validator::make(
            $request->only('id'), ['id' => 'required|integer|exists:rooms']
        );

        if($validator->fails()) {
            return $validator->errors();
        };

        Room::findOrFail($request->input('id'))->remove();
    }

    public function list(Request $request)
    {
        $validator = Validator::make(
            $request->only('order','type'), [
                'type' => [
                'required',
                Rule::in(['price', 'created_at']),
                ],
                'order' => [
                    'required',
                    Rule::in(['asc', 'desc']),
                ],
            ]
        );

        if($validator->fails()) {
            return $validator->errors();
        };

        if($request->input('order')=='asc') {
            $rooms = Room::all()->sortBy($request->input('type'));
        } else {
            $rooms = Room::all()->sortByDesc($request->input('type'));
        }

        return $rooms->makeVisible([
            'id',
            'description',
            'price',
            'created_at',
        ])
        ->values();
    }
}
