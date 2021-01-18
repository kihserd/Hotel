<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use \App\Models\Room;
use \App\Models\Booking;

class HandlersTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;
    
    /** @test */
    public function room_can_be_created()
    {
        $response = $this->post('/rooms/create', [
            'description' => 'Best room in the hotel!',
            'price' => 99,
        ]);

        $response
            ->assertJson([
                'room_id' => 1,
            ]);
    }

    /** @test */
    public function existed_room_can_be_deleted()
    {
        $room=Room::factory()
            ->has(Booking::factory(2))
            ->create();

        $response = $this->delete('/rooms', ['id' => $room->id]);

        $response->assertStatus(200);
        $this->assertDeleted($room);
    }

    /** @test */
    public function rooms_list_can_be_received()
    {
        $rooms=Room::factory(5)
            ->create();

        $response = $this->get('/rooms/list?order=asc&type=price');

        $response
            ->assertStatus(200);
    }

    /** @test */
    public function new_booking_can_be_reserved()
    {
        $rooms=Room::factory()
            ->create([
                'id' => 125,
            ]);
        
        $response = $this->post('/bookings/create', [
            'room_id' => 125,
            'date_start' => '2021-10-15',
            'date_end' => '2021-11-25',
        ]);

        $response
            ->assertJson([
                'booking_id' => Booking::all()->last()->id,
            ]);
    }

    public function booking_can_be_deleted()
    {
        Room::factory(3)
            ->has(Booking::factory2())
            ->create();

        $booking=Booking::random();
            
        $response = $this->delete('/bookings', ['id' => $booking->id]);

        $response->assertStatus(200);
        $this->assertDeleted($booking);
    }

    /** @test */
    public function bookings_list_can_be_received()
    {
        Room::factory(3)
            ->has(Booking::factory(4))
            ->create();
        $dates = [
            'date_start' => '2019-10-15',
            'date_end' => '2019-11-25',
        ];

        $room=Room::all()->first();
        $room->addBooking($dates);
        $id=$room->id;
        
        $response = $this->get("/bookings/list?room_id=$id");
        $response
            ->assertStatus(200)
            ->assertJsonFragment($dates);
    }
}
