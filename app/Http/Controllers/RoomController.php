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

        // ✅ หาวันเริ่มต้นของสัปดาห์ที่เลือก
        $startOfWeek = Carbon::now()->startOfWeek()->addWeeks($weekOffset);

        // ✅ หาวันสุดท้ายของช่วงสัปดาห์ที่ต้องการ
        $endOfWeek = $startOfWeek->copy()->addWeeks($weeksToShow - 1)->endOfWeek();

        // ✅ ดึงรายการจองตามช่วงวันที่ของสัปดาห์ที่เลือก
        $bookings = $room->bookings()
            ->whereBetween('booking_date', [$startOfWeek->format('Y-m-d'), $endOfWeek->format('Y-m-d')])
            ->get();

        return view('rooms.show', compact(
            'room', 'startOfWeek', 'endOfWeek', 'weekOffset', 'weeksToShow', 'bookings'
        ));
    }
}
