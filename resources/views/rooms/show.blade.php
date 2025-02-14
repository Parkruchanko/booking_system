@extends('layouts.app')

@php
    use Carbon\Carbon;

    $startOfWeek = Carbon::now()->startOfWeek()->addWeeks($weekOffset);
    $endOfWeek = $startOfWeek->copy()->endOfWeek();

    $daysOfWeek = [
        'จันทร์' => $startOfWeek->copy()->toDateString(),
        'อังคาร' => $startOfWeek->copy()->addDay()->toDateString(),
        'พุธ' => $startOfWeek->copy()->addDays(2)->toDateString(),
        'พฤหัสบดี' => $startOfWeek->copy()->addDays(3)->toDateString(),
        'ศุกร์' => $startOfWeek->copy()->addDays(4)->toDateString(),
        'เสาร์' => $startOfWeek->copy()->addDays(5)->toDateString(),
        'อาทิตย์' => $startOfWeek->copy()->addDays(6)->toDateString(),
    ];

    $timeSlots = [
        1 => "08:00-09:00", 
        2 => "09:00-10:00", 
        3 => "10:10-11:10", 
        4 => "11:10-12:10",
        5 => "12:10-13:00", 
        6 => "13:00-14:00", 
        7 => "14:00-15:00", 
        8 => "15:10-16:10",
        9 => "16:10-17:10", 
        10 => "17:30-18:30", 
        11 => "18:30-19:30", 
        12 => "19:30-20:30"
    ];

    $selectedSlots = request()->has('slots') ? json_decode(request()->input('slots'), true) : [];
@endphp

@section('content')
<div class="container">
    <h1 class="text-center mb-4">ตารางเรียนของ {{ $room->name }}</h1>

    <!-- เลื่อนสัปดาห์ -->
    <div class="text-center mb-3">
        <a href="{{ route('rooms.show', ['room' => $room->id, 'week' => $weekOffset - 1, 'slots' => json_encode($selectedSlots)]) }}" class="btn btn-outline-primary btn-lg">
            <i class="fa fa-arrow-left"></i> สัปดาห์ที่แล้ว
        </a>
        <span class="mx-4 text-bold" style="font-size: 1.25rem;">สัปดาห์ที่ {{ $weekOffset + 1 }}</span>
        <a href="{{ route('rooms.show', ['room' => $room->id, 'week' => $weekOffset + 1, 'slots' => json_encode($selectedSlots)]) }}" class="btn btn-outline-primary btn-lg">
            สัปดาห์ถัดไป <i class="fa fa-arrow-right"></i>
        </a>
    </div>

    <form action="{{ route('rooms.confirmBooking', ['room' => $room->id]) }}" method="GET">
        @csrf
        <input type="hidden" name="room_id" value="{{ $room->id }}">
        <input type="hidden" id="selectedSlots" name="slots" value="{{ json_encode($selectedSlots) }}">

        <button type="submit" class="btn btn-success mt-3 btn-lg w-100">ยืนยันการเลือก</button>
    </form>

    <div class="table-responsive mt-4">
        <table class="table table-bordered table-hover text-center">
            <thead class="thead-dark">
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
                        <td class="align-middle">
                            <strong>{{ $day }}</strong><br>
                            <small>{{ \Carbon\Carbon::parse($daysOfWeek[$day])->format('d/m/Y') }}</small>
                        </td>
                        @for ($i = 1; $i <= 12; $i++)
                            @php
                                $booking = $bookings->first(function ($b) use ($day, $i) {
                                    return $b->day == $day && $b->slot == $i;
                                });
                                $slotKey = "{$day}_{$i}";
                            @endphp
                            <td class="slot-cell {{ !$booking ? 'available' : 'booked' }} {{ in_array($slotKey, $selectedSlots) ? 'selected' : '' }}" 
                                data-day="{{ $day }}" 
                                data-slot="{{ $i }}">
                                @if ($booking)
                                    <span class="text-danger"><strong>{{ $booking->name }}</strong> ({{ $booking->reason }})</span>
                                @endif
                            </td>
                        @endfor
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<script>
    document.addEventListener("DOMContentLoaded", function () {
        let selectedSlots = {!! json_encode($selectedSlots) !!};

        document.querySelectorAll(".slot-cell.available").forEach(cell => {
            let day = cell.getAttribute("data-day");
            let slot = cell.getAttribute("data-slot");
            let slotKey = `${day}_${slot}`;

            if (selectedSlots.includes(slotKey)) {
                cell.classList.add("selected");
            }

            cell.addEventListener("click", function () {
                if (this.classList.contains("selected")) {
                    this.classList.remove("selected");
                    selectedSlots = selectedSlots.filter(s => s !== slotKey);
                } else {
                    this.classList.add("selected");
                    selectedSlots.push(slotKey);
                }

                document.getElementById("selectedSlots").value = JSON.stringify(selectedSlots);
            });
        });
    });
</script>

<style>
    .available {
        background-color: #d4edda;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .available:hover {
        background-color: #28a745;
        color: white;
    }

    .available.selected {
        background-color: #28a745 !important;
        color: white;
        font-weight: bold;
    }

    .booked {
        background-color: #f8d7da;
        color: #721c24;
    }

    th, td {
        vertical-align: middle;
        text-align: center;
    }

    th {
        background-color: #007bff;
        color: white;
    }

    .table-hover tbody tr:hover {
        background-color: #f1f1f1;
    }
</style>

@endsection
