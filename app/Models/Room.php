<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;


class Room extends Model
{
    use HasFactory;

    protected $fillable=[
        'description',
        'price'
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d',
    ];

    protected $visible = [''];

    public function addBooking($attr)
    {
        $booking = $this->bookings()->create($attr);
        return json_encode(['booking_id' => $booking->id]);
    }

    public function Bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function remove()
    {
        if($this->bookings) {
            Booking::where('room_id',$this->id)->delete();
        }
        $this->delete();
    }

    // I'm really sorry about this part :(
    public static function correctId($str)
    {
        return Str::replaceFirst('id', 'room_id', $str);
    }
}
