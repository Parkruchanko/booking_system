<?php

use App\Http\Controllers\RoomController;
use App\Http\Controllers\BookingController;

Route::get('/rooms', [RoomController::class, 'index'])->name('rooms.index');
Route::get('/rooms/{room}', [RoomController::class, 'show'])->name('rooms.show');

// การยืนยันการจอง
Route::get('/rooms/{room}/confirm-booking', [BookingController::class, 'confirmBooking'])->name('rooms.confirmBooking');
Route::post('/rooms/{room}/confirm-booking', [BookingController::class, 'storeBooking'])->name('rooms.storeBooking');
