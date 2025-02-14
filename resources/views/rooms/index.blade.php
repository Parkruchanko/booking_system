@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-center">รายการห้องเรียน</h1>

    @if($rooms->isEmpty())
        <p class="text-center">ยังไม่มีห้องเรียนในระบบ</p>
    @else
        <div class="row row-cols-1 row-cols-md-3 g-4">
            @foreach ($rooms as $room)
                <div class="col">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">{{ $room->name }}</h5>
                            <p class="card-text">รายละเอียดของห้องเรียน</p>
                            <a href="{{ route('rooms.show', $room->id) }}" class="btn btn-primary">ดูรายละเอียด</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
