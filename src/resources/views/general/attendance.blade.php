@extends('layout.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/attendance.css') }}">
@endsection

@section('content')
<div class="attendance-content">
    @if(session('error'))
        <p class="error">{{ session('error') }}</p>
    @endif
    <form class="attendance-content__inner" action="/attendance" method="POST">
        @csrf
        <input type="hidden" name="user_id" value="{{ Auth::id() }}">
        @if(!$attendance)
        
            <div class="attendance-status">勤務外</div>
            <div class="attendance-date">
                {{ $date->translatedFormat('Y年m月d日 (D)') }}
                <input type="hidden" name="date" value="{{ $date->translatedFormat('Y年m月d日 (D)') }}" >
            </div>
            <div class="attendance-time">
                {{ $date->translatedFormat('H:i') }}
                <input type="hidden" name="check_in" value="{{ $date->translatedFormat('H:i') }}" >
            </div>
            @if(!$attendance)
                <button class="attendance-button" type="submit" name="type" value="check_in">出勤</button>
            
            @endif

        @elseif(!$attendance->check_out)

            @if($break && !$break->break_end)
                <div class="attendance-status">休憩中</div>
                <div class="attendance-date">
                    {{ $date->translatedFormat('Y年m月d日 (D)') }}
                    <input type="hidden" name="date" value="{{ $date->translatedFormat('Y年m月d日 (D)') }}" >
                </div>
                <div class="attendance-time">
                    {{ $date->translatedFormat('H:i') }}
                    <input type="hidden" name="check_in" value="{{ $date->translatedFormat('H:i') }}" >
                </div>
                <button class="attendance-button__break"type="submit" name="type" value="break_end">休憩戻</button>
            @else
                <div class="attendance-status">出勤中</div>
                <div class="attendance-date">
                    {{ $date->translatedFormat('Y年m月d日 (D)') }}
                </div>
                <div class="attendance-time">
                    {{ $date->translatedFormat("H:i") }}
                </div>
                <div class="attendance__submit">
                    <button class="attendance-button" type="submit" name="type" value="check_out">退勤</button>
                    <button class="attendance-button__break"type="submit" name="type" value="break_start">休憩入</button>
                </div>
            @endif

        @else
        
            <div class="attendance-status">退勤済</div>
            <div class="attendance-date">
                {{ $date->translatedFormat('Y年m月d日 (D)') }}
            </div>
            <div class="attendance-time">
                {{ $date->translatedFormat("H:i") }}
                <input type="hidden" name="check_out" value="{{ $date->translatedFormat('H:i') }}" >
            </div>
            <div class="attendance-end">お疲れさまでした。</div>
        @endif
    </form>
</div>
@endsection