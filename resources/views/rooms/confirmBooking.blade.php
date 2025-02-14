@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-center">ยืนยันการจองห้อง {{ $room->name }}</h1>

    <div class="my-3">
        <h3>ช่วงเวลาที่เลือก:</h3>
        <ul>
        @foreach ($selectedSlots as $slotData)
        @php
    // แยกวันและคาบออกจากค่าที่ได้รับมา เช่น "อาทิตย์_1" => "อาทิตย์" และ "1"
    list($day, $slot) = explode('_', $slotData);

    // แปลงวันเป็นภาษาอังกฤษ
    $daysMap = [
        'อาทิตย์' => 'Sunday', 'จันทร์' => 'Monday', 'อังคาร' => 'Tuesday',
        'พุธ' => 'Wednesday', 'พฤหัสบดี' => 'Thursday', 'ศุกร์' => 'Friday', 'เสาร์' => 'Saturday'
    ];
    $dayInEnglish = $daysMap[$day];

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

    // คำนวณวันที่ที่ตรงกับวันในสัปดาห์
    $date = \Carbon\Carbon::now()->startOfWeek()->addDays(array_search($dayInEnglish, array_keys($daysMap)));

    // ถ้าเป็นวันอาทิตย์ (Sunday) ต้องเพิ่มวันเพื่อให้ตรงกับวันแรกของสัปดาห์
    if ($dayInEnglish == 'Sunday') {
        $date = $date->addDays(7);
    }

    // ดึงเวลาเริ่มต้นและสิ้นสุดจาก timeSlots ตามคาบ
    $startTime = $timeSlots[$slot][0];
    $endTime = $timeSlots[$slot][1];

    // แปลงวันที่เป็นรูปแบบ วัน/เดือน/ปี
    $dateFormatted = $date->locale('th')->isoFormat('dddd ที่ D MMMM YYYY');
@endphp

            <li>
                <strong>{{ $day }}:</strong>
                <p>{{ $dateFormatted }} เวลา {{ $startTime }} - {{ $endTime }}</p>
            </li>
        @endforeach
        </ul>
    </div>

    <form action="{{ route('rooms.storeBooking', ['room' => $room->id]) }}" method="POST">
        @csrf
        <input type="hidden" name="room_id" value="{{ $room->id }}">
        <input type="hidden" name="selected_slots" value="{{ json_encode($selectedSlots) }}">

        <div class="mb-3">
            <label for="name" class="form-label">ชื่อผู้จอง</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="reason" class="form-label">ชื่อวิชา</label>
            <textarea name="reason" id="reason" class="form-control" required></textarea>
        </div>

        <!-- เพิ่มตัวเลือกจำนวนสัปดาห์ -->
        <div class="mb-3">
            <label for="weeks" class="form-label">จำนวนสัปดาห์ที่จอง</label>
            <select name="weeks" id="weeks" class="form-control">
                <option value="1">1 สัปดาห์</option>
                <option value="2">2 สัปดาห์</option>
                <option value="3">3 สัปดาห์</option>
                <option value="4">4 สัปดาห์</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">ยืนยันการจอง</button>
        <a href="{{ route('rooms.show', $room->id) }}" class="btn btn-secondary">ย้อนกลับ</a>
    </form>

</div>
@endsection
