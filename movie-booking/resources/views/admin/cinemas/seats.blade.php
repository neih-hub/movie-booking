@extends('layouts.admin')

@section('title', 'Danh sách ghế: ' . $room->name)
@section('page-title', 'Danh sách ghế phòng ' . $room->name)

@section('content')
<div class="content-card">
    <div class="card-header">
        <h2 class="card-title">
            <i class="fas fa-chair"></i> Ghế trong phòng {{ $room->name }}
        </h2>
        <a href="{{ url()->previous() }}" class="btn btn-primary">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
    </div>

    <div class="grid" style="display:grid;grid-template-columns:repeat(5,1fr);gap:1rem;margin-top:20px;">
        @foreach($room->seats as $seat)
        <div style="
            padding:15px;
            background:#f1f5f9;
            border-radius:10px;
            text-align:center;
            font-weight:600;
            font-size:18px;
            border:1px solid #cbd5e1;
        ">
            {{ $seat->label }}
        </div>
        @endforeach
    </div>
</div>
@endsection
