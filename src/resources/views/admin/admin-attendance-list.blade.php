@extends('layout.admin')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/admin-list.css') }}">
@endsection

@section('content')
<div class="admin-list__content">
    <h1 class="admin-list__title">{{ $date->translatedFormat('Y年n月j日') }}の勤怠</h1>
    <div class="admin-list__date">
        <a class="admin-list__month" href="{{ route('admin.list', ['date' => $prevMonth]) }}">←前月</a>
        
        <span>{{ $date->translatedFormat('Y年m月') }}</span>

        <a class="admin-list__month"  href="{{ route('admin.list', ['date' => $nextMonth]) }}">翌月→</a>
    </div>
    <div>
        <table class="admin-list__table">
            <tr class="admin-list__row">
                <th>名前</th>
                <th>出勤</th>
                <th>退勤</th>
                <th>休憩</th>
                <th>合計</th>
                <th>詳細</th>
            </tr>
            @foreach ($attendances as $attendance)
            <tr class="admin-list__row">
                <td>{{ $attendance -> user -> name}}</td>
                <td>{{ $attendance ? $attendance->check_in_time : '-' }}</td>
                <td>{{ $attendance ? $attendance->check_out_time : '-' }}</td>
                <td>{{ $attendance ? $attendance->break_hours : '-' }}</td>
                <td>{{ $attendance ? $attendance->actual_work_hours : '-' }}</td>
                <td><a class="admin-list__detail" href="{{ route('admin.detail', $attendance->id) }}">詳細</a></td>
            </tr>
            @endforeach
        </table>
    </div>
</div>
@endsection