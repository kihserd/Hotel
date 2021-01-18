<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use \App\Models\Room;
use \App\Models\Booking;

class RoomsTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;

    /** @test */
    public function it_can_be_created()
    {
        Room::create([
            'description' => 'lakjsd',
            'price' => 33
        ]);
        $this->assertDatabaseCount('rooms', 1);
    }

    /** @test */
    public function it_can_add_booking()
    {
        $room=Room::factory()->create();
        $room->addBooking([
            'date_start' => '2020-10-15',
            'date_end' => '2020-11-25',
        ]);

        $this->assertDatabaseHas('bookings', [
            'room_id' => $room->id
        ]);
    }
    
    /** @test */
    public function it_can_be_deleted()
    {
        $room=Room::factory()->create();
        $room->remove();
        $this->assertDeleted($room);
    }

    /** @test */
    public function it_can_be_deleted_with_its_own_bookings()
    {
        $room=Room::factory()
            ->has(Booking::factory()->count(5))
            ->create();
        Room::factory()
            ->has(Booking::factory()->count(2))
            ->create();

        $room->remove();

        $this->assertDatabaseMissing('bookings', [
            'room_id' => $room->id,
        ]);
    }
}
