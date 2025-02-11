<?php

use App\Http\Controllers\RoomController;
use App\Http\Controllers\BookingController;

Route::get('/rooms', [RoomController::class, 'index'])->name('rooms.index');
Route::get('/rooms/{room}', [RoomController::class, 'show'])->name('rooms.show');

Route::get('/rooms/{room}/book/{day}/{slot}', [BookingController::class, 'create'])->name('rooms.book');
Route::post('/rooms/{room}/book', [BookingController::class, 'store'])->name('rooms.store');

Route::resource('bookings', BookingController::class);
Route::get('/bookings/create/{room}/{day}/{slot}', [BookingController::class, 'create'])->name('bookings.create');
Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
