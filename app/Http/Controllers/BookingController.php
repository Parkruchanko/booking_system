<?php



namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\Booking;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function store(Request $request)
{
    $request->validate([
        'room_id' => 'required|exists:rooms,id',
        'name' => 'required|string',
        'reason' => 'required|string',
        'day' => 'required|string',
        'slot' => 'required|integer',
        'weeks' => 'required|integer|min:1|max:4',
    ]);

    $startOfWeek = \Carbon\Carbon::now()->startOfWeek();

    for ($i = 0; $i < $request->weeks; $i++) {
        $bookingDate = $startOfWeek->copy()->addWeeks($i)->toDateString();

        // ตรวจสอบว่ามีการจองแล้วหรือยัง
        $existingBooking = Booking::where('room_id', $request->room_id)
            ->where('day', $request->day)
            ->where('slot', $request->slot)
            ->where('booking_date', $bookingDate)
            ->exists();

        if (!$existingBooking) {
            Booking::create([
                'room_id' => $request->room_id,
                'name' => $request->name,
                'reason' => $request->reason,
                'day' => $request->day,
                'slot' => $request->slot,
                'booking_date' => $bookingDate, // ✅ จองตามสัปดาห์ที่เลือก
            ]);
        }
    }

    return redirect()->route('rooms.show', $request->room_id)->with('success', 'จองสำเร็จ');
}

    public function create(Room $room, $day, $slot)
{
    return view('bookings.create', compact('room', 'day', 'slot'));
}

}

