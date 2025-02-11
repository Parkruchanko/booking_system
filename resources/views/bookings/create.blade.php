@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-center">จองห้อง {{ $room->name }}</h1>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('bookings.store') }}" method="POST">
        @csrf
        <input type="hidden" name="room_id" value="{{ $room->id }}">
        <input type="hidden" name="day" value="{{ $day }}">
        <input type="hidden" name="slot" value="{{ $slot }}">

        <div class="mb-3">
            <label for="name" class="form-label">ชื่อผู้จอง</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="reason" class="form-label">ชื่อวิชา</label>
            <textarea name="reason" id="reason" class="form-control" required></textarea>
        </div>

        <div class="mb-3">
            <label for="weeks" class="form-label">จำนวนสัปดาห์ที่จอง</label>
            <select name="weeks" id="weeks" class="form-control">
                <option value="1">1 สัปดาห์</option>
                <option value="2">2 สัปดาห์</option>
                <option value="3">3 สัปดาห์</option>
                <option value="4">4 สัปดาห์</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">ยืนยันการจอง</button>
    </form>

    <a href="{{ route('rooms.show', $room->id) }}" class="btn btn-secondary mt-3">กลับไปหน้าห้องเรียน</a>
</div>
@endsection
