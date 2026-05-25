@extends('layout.admin')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/admin-list.css') }}">
@endsection

@section('content')
<div class="admin-list__content">
    <h1 class="admin-list__title">{{ $date->translatedFormat('Y年n月j日') }}の勤怠</h1>
    <div class="admin-list__date">
        <a class="admin-list__month" href="{{ route('admin.list', ['date' => $prevDay]) }}">←前日</a>
        
        <span>{{ $date->translatedFormat('Y/m/j') }}</span>

        <a class="admin-list__month"  href="{{ route('admin.list', ['date' => $nextDay]) }}">翌日→</a>
    </div>
    <div>
        <table class="admin-list__table">
            <tr class="admin-list__row">
                <th class="table-title">名前</th>
                <th class="table-title">出勤</th>
                <th class="table-title">退勤</th>
                <th class="table-title">休憩</th>
                <th class="table-title">合計</th>
                <th class="table-title">詳細</th>
            </tr>
            @foreach ($attendances as $attendance)
            <tr class="admin-list__row">
                <td class="table-item">{{ $attendance -> user -> name}}</td>
                <td class="table-item">{{ $attendance ? $attendance->check_in_time : '-' }}</td>
                <td class="table-item">{{ $attendance ? $attendance->check_out_time : '-' }}</td>
                <td class="table-item">{{ $attendance ? $attendance->break_hours : '-' }}</td>
                <td class="table-item">{{ $attendance ? $attendance->actual_work_hours : '-' }}</td>
                <td class="table-item"><a class="admin-list__detail" href="{{ route('admin.detail', $attendance->id) }}">詳細</a></td>
            </tr>
            @endforeach
        </table>
    </div>
</div>
@endsection