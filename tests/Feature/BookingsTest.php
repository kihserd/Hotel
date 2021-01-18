<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use \App\Models\Booking;

class BookingsTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;
    
    protected $booking;

    public function setUp(): void
    {
        Parent::setUp();
        $this->booking=new Booking([
                'room_id' => 12,
                'date_start' => '2020-10-15',
                'date_end' => '2020-11-25',
            ]);
    }

    /** @test */
    public function it_has_room_id()
    {
        $this->assertEquals($this->booking->room_id, 12);
    }

    /** @test */
    public function it_has_date_start()
    {
        $this->assertEquals($this->booking->date_start, '2020-10-15');
    }

    /** @test */
    public function it_has_date_end()
    {
        $this->assertEquals($this->booking->date_end, '2020-11-25');
    }

    /** @test */
    public function it_can_be_deleted()
    {
        Booking::destroy($this->booking->id);

        $this->assertDeleted($this->booking);
    }
}
