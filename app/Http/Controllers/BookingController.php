<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\Booking;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function storeBooking(Request $request, Room $room)
    {
        // ดึงข้อมูล selected_slots จากฟอร์ม
        $selectedSlots = json_decode($request->input('selected_slots'));
    
        // บันทึกการจอง
        foreach ($selectedSlots as $slotData) {
            // แยกวันและคาบออกจากค่าที่ได้รับมา เช่น "จันทร์_1" => "จันทร์" และ "1"
            list($day, $slot) = explode('_', $slotData);
    
            // แปลงวันเป็นภาษาอังกฤษ
            $daysMap = [
                'อาทิตย์' => 'Sunday', 'จันทร์' => 'Monday', 'อังคาร' => 'Tuesday',
                'พุธ' => 'Wednesday', 'พฤหัสบดี' => 'Thursday', 'ศุกร์' => 'Friday', 'เสาร์' => 'Saturday'
            ];
            $dayInEnglish = $daysMap[$day];
    
            // คำนวณวันที่ที่ตรงกับวันในสัปดาห์
            $date = \Carbon\Carbon::now()->startOfWeek()->addDays(array_search($dayInEnglish, array_keys($daysMap)));
    
            // กำหนดเวลาเริ่มต้นและสิ้นสุดของแต่ละคาบ
            $timeSlots = [
                1 => ['08:00', '09:00'],
                2 => ['09:00', '10:00'],
                3 => ['10:00', '11:00'],
                4 => ['11:00', '12:00'],
                5 => ['12:00', '13:00'],
                6 => ['13:00', '14:00'],
                7 => ['14:00', '15:00'],
                8 => ['15:00', '16:00'],
                9 => ['16:00', '17:00'],
                10 => ['17:00', '18:00'],
                11 => ['18:00', '19:00'],
                12 => ['19:00', '20:00']
            ];
    
            // คำนวณเวลาเริ่มต้นและสิ้นสุดจาก timeSlots ตามคาบ
            $startTime = $timeSlots[$slot][0];
            $endTime = $timeSlots[$slot][1];
    
            // สร้างการจองในฐานข้อมูล
            Booking::create([
                'room_id' => $room->id,
                'booking_date' => $date->toDateString(), // วันที่ที่ตรงกับวันในสัปดาห์
                'slot' => $slot, // คาบ
                'day' => $day, // เพิ่มข้อมูลวัน
                'start_time' => $startTime,
                'end_time' => $endTime,
                'name' => $request->name,
                'reason' => $request->reason,
                'weeks' => $request->weeks,
            ]);
        }
    
        return redirect()->route('rooms.show', $room->id)->with('success', 'การจองห้องสำเร็จ');
    }
    


    



    
    

    



    // ✅ ยืนยันข้อมูลก่อนจอง
    public function confirmBooking(Request $request, Room $room)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'slots' => 'required|string', // JSON string ของคาบที่เลือก
        ]);

        // แปลงข้อมูลจาก JSON เป็นอาร์เรย์
        $selectedSlots = json_decode($request->slots, true);
        
        // รับค่า weekOffset จาก URL ถ้าไม่มีให้เป็น 0
        $weekOffset = (int) $request->query('week', 0);

        return view('rooms.confirmBooking', compact('selectedSlots', 'room', 'weekOffset'));
    }

    




    
    
    


    
    
    

    // ✅ ฟังก์ชันช่วยบันทึกการจองหลายสัปดาห์
    private function saveBookings($roomId, $name, $reason, $selectedSlots, $weeks)
    {
        $daysMap = [
            'อาทิตย์' => 'Sunday', 'จันทร์' => 'Monday', 'อังคาร' => 'Tuesday',
            'พุธ' => 'Wednesday', 'พฤหัสบดี' => 'Thursday', 'ศุกร์' => 'Friday', 'เสาร์' => 'Saturday'
        ];

        $today = Carbon::now();
        $startOfWeek = $today->startOfWeek(); // เริ่มที่วันจันทร์ของสัปดาห์ปัจจุบัน

        foreach ($selectedSlots as $slotData) {
            list($day, $slot) = $slotData;

            if (!isset($daysMap[$day])) {
                continue;
            }

            $dayInEnglish = $daysMap[$day];

            for ($i = 0; $i < $weeks; $i++) {
                $bookingDate = Carbon::parse($startOfWeek)->addWeeks($i)->next($dayInEnglish)->toDateString();

                // ตรวจสอบว่ามีการจองแล้วหรือยัง
                $existingBooking = Booking::where('room_id', $roomId)
                    ->where('day', $day)
                    ->where('slot', $slot)
                    ->where('booking_date', $bookingDate)
                    ->exists();

                if (!$existingBooking) {
                    Booking::create([
                        'room_id' => $roomId,
                        'name' => $name,
                        'reason' => $reason,
                        'day' => $day,
                        'slot' => $slot,
                        'booking_date' => $bookingDate,
                    ]);
                }
            }
        }
    }
}
