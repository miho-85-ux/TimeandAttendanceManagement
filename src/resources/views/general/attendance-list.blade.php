@extends('layout.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/attendance-list.css') }}">
@endsection

@section('content')
<div class="attendance-list__content">
    <h1 class="attendance-list__title">勤怠一覧</h1>
    <div class="attendance-list__date">
        <a class="attendance-list__date--month" href="{{ route('attendance.list', ['date' => $prevMonth]) }}">←前月</a>
        
        <span>{{ $date->translatedFormat('Y年m月') }}</span>

        <a class="attendance-list__date--month" href="{{ route('attendance.list', ['date' => $nextMonth]) }}">翌月→</a>
        
    </div>
    <table class="attendance-list__table">
        <tr class="table-title">
            <th>日付</th>
            <th>出勤</th>
            <th>退勤</th>
            <th>休憩</th>
            <th>合計</th>
            <th>詳細</th>
        </tr>
        @foreach($dates as $day)
        <tr class="table-detail">
            <td>{{ $day->translatedFormat('n/j (D)') }}</td>
            @php 
                $key = $day->format('Y-m-d');
                $attendance = $attendances[$key] ?? null;
            @endphp
            <td>{{ $attendance ? $attendance->check_in_time : '-' }}</td>
            <td>{{ $attendance ? $attendance->check_out_time : '-' }}</td>
            <td>{{ $attendance ? $attendance->break_hours : '-' }}</td>
            <td>{{ $attendance ? $attendance->actual_work_hours : '-' }}</td>
            <td>
                @if($attendance)
                    <a class="detail-button" href="/attendance/detail/{{ $attendance->id }}">詳細</a>
                @else
                    - 
                @endif
            </td>
        </tr>
        @endforeach
    </table>
</div>
@endsection

