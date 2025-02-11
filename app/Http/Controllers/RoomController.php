<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;
use Carbon\Carbon;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::all();
        return view('rooms.index', compact('rooms'));
    }

    public function show(Room $room, Request $request)
{
    $weekOffset = (int) $request->query('week', 0);
    $weeksToShow = (int) $request->query('weeks', 1);

    $startOfWeek = \Carbon\Carbon::now()->startOfWeek()->addWeeks($weekOffset);
    $endOfWeek = $startOfWeek->copy()->addWeeks($weeksToShow - 1)->endOfWeek(); // ✅ ดึงหลายสัปดาห์

    $bookings = $room->bookings()
                    ->whereBetween('booking_date', [$startOfWeek, $endOfWeek]) 
                    ->get();


    return view('rooms.show', compact(
        'room', 'startOfWeek', 'endOfWeek', 'weekOffset', 'weeksToShow', 'bookings'
    ));
}

    
}

