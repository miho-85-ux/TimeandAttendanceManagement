@extends('layout.admin')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/admin-staff.css') }}">
@endsection

@section('content')
<div class="attendance-staff__content">
    <h1 class="attendance-staff__title">{{ $user->name }}さんの勤怠</h1>
    <div class="attendance-staff__date">
        <a class="month" href="{{ route('attendance.staff', ['id'=> $user->id ,'date' => $prevMonth]) }}">←前月</a>
        <span>{{ $date->translatedFormat('Y年m月') }}</span>
        <a class="month" href="{{ route('attendance.staff', ['id'=> $user->id ,'date' => $nextMonth]) }}">翌月→</a>
    </div>
    <div>
        <table class="admin-staff__table">
            <tr class="admin-staff__row">
                <th class="table-title">日付</th>
                <th class="table-title">出勤</th>
                <th class="table-title">退勤</th>
                <th class="table-title">休憩</th>
                <th class="table-title">合計</th>
                <th class="table-title">詳細</th>
            </tr>
            @foreach ($dates as $day)
            <tr class="admin-staff__row">
                <td class="table-item">{{ $day->translatedFormat('n/j (D)') }}</td>
                @php 
                    $key = $day->format('Y-m-d');
                    $attendance = $attendances[$key] ?? null;
                @endphp
                <td class="table-item">{{ $attendance ? $attendance->check_in_time : '-' }}</td>
                <td class="table-item">{{ $attendance ? $attendance->check_out_time : '-' }}</td>
                <td class="table-item">{{ $attendance ? $attendance->break_hours : '-' }}</td>
                <td class="table-item">{{ $attendance ? $attendance->actual_work_hours : '-' }}</td>
                <td class="table-item">
                    @if($attendance)
                        <a class="detail-button" href="{{ route('admin.detail', $attendance->id) }}">詳細</a>
                    @else
                        - 
                    @endif
                </td>
            </tr>
            @endforeach
        </table>
        <div class="button">
            <a class="button-submit" href="{{ route('csv', ['id' => $user->id, 'month' => now()->format('Y-m')]) }}">CSV出力</a>
        </div>
    </div>
</div>
@endsection