@extends('layouts.app')

@php use Carbon\Carbon; @endphp

@section('content')
<div class="container">
    <h1 class="text-center">ตารางเรียนของ {{ $room->name }}</h1>

    <!-- ปุ่มเลื่อนสัปดาห์ -->
    <div class="text-center my-3">
        <a href="{{ route('rooms.show', ['room' => $room->id, 'week' => $weekOffset - 1, 'weeks' => $weeksToShow]) }}" class="btn btn-primary">⬅ ก่อนหน้า</a>
        <strong class="mx-3">สัปดาห์ที่ {{ $startOfWeek->format('d/m/Y') }} - {{ $endOfWeek->format('d/m/Y') }}</strong>
        <a href="{{ route('rooms.show', ['room' => $room->id, 'week' => $weekOffset + 1, 'weeks' => $weeksToShow]) }}" class="btn btn-primary">ถัดไป ➡</a>
    </div>

    <!-- แสดงตารางการจองห้องเรียน -->
    @for ($week = 0; $week < $weeksToShow; $week++)
        @php
            $currentStartOfWeek = \Carbon\Carbon::parse($startOfWeek)->copy()->addWeeks($week);
            $currentEndOfWeek = $currentStartOfWeek->copy()->endOfWeek();
        @endphp



        <h3 class="my-3">สัปดาห์ที่ {{ $currentStartOfWeek->format('d/m/Y') }} - {{ $currentEndOfWeek->format('d/m/Y') }}</h3>

        <table class="table table-bordered">
            @php
                $timeSlots = [
                    1 => "08:00-09:00", 2 => "09:00-10:00", 3 => "10:10-11:10", 4 => "11:10-12:10",
                    5 => "12:10-13:00", 6 => "13:00-14:00", 7 => "14:00-15:00", 8 => "15:10-16:10",
                    9 => "16:10-17:10", 10 => "17:30-18:30", 11 => "18:30-19:30", 12 => "19:30-20:30"
                ];
            @endphp

            <thead>
                <tr>
                    <th>วัน/เวลา</th>
                    @for ($i = 1; $i <= 12; $i++)
                        <th>คาบ {{ $i }} <br> <small>{{ $timeSlots[$i] }}</small></th>
                    @endfor
                </tr>
            </thead>

            <tbody>
                @foreach (['จันทร์', 'อังคาร', 'พุธ', 'พฤหัสบดี', 'ศุกร์', 'เสาร์', 'อาทิตย์'] as $day)
                    <tr>
                        <td>{{ $day }}</td>
                        @for ($i = 1; $i <= 12; $i++)
                            @php
                                // ดึงข้อมูลการจองในวันและคาบที่ตรงกับสัปดาห์นี้
                                $booking = $bookings->first(function ($b) use ($day, $i, $currentStartOfWeek, $currentEndOfWeek) {
                                                return $b->day == $day 
                                                    && $b->slot == $i 
                                                    && Carbon::parse($b->booking_date)->between($currentStartOfWeek, $currentEndOfWeek);
                                            });


                            @endphp
                            <td 
                                @if (!$booking) 
                                    onclick="window.location='{{ route('rooms.book', ['room' => $room->id, 'day' => $day, 'slot' => $i, 'week' => $weekOffset]) }}'"
                                    style="cursor: pointer; background-color: #d4edda;" 
                                @else 
                                    style="background-color: #f8d7da; color: #721c24;" 
                                @endif>
                                {{ $booking ? $booking->name . ' (' . $booking->reason . ')' : '' }}
                            </td>
                        @endfor
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endfor
</div>
@endsection
