<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\BookingController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::post('/rooms/create', [RoomController::class, 'create']);
Route::post('/bookings/create', [BookingController::class, 'create']);
Route::delete('/rooms', [RoomController::class, 'destroy']);
Route::delete('/bookings', [BookingController::class, 'destroy']);
Route::get('/rooms/list', [RoomController::class, 'list']);
Route::get('/bookings/list', [BookingController::class, 'list']);

Route::get('/', function () {
    return "Welcome to booking.app!";
});


