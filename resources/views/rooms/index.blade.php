@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-center">รายการห้องเรียน</h1>
    @if($rooms->isEmpty())
        <p class="text-center">ยังไม่มีห้องเรียนในระบบ</p>
    @else
        <ul>
            @foreach ($rooms as $room)
                <li>
                    <a href="{{ route('rooms.show', $room->id) }}">{{ $room->name }}</a>
                </li>
            @endforeach
        </ul>
    @endif
</div>
@endsection
