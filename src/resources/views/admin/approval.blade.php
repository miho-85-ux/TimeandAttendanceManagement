@extends('layout.admin')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/approval.css') }}">
@endsection

@section('content')
<div class="approval__content">
    <h1 class="approval__title">勤怠詳細</h1>
    <form action="/admin/stamp_correction_request/approval/{{ $request->id }}" method="POST">
        @method('PATCH')
        @csrf 
        <table class="approval__table">
            <tr class="approval__row">
                <th class="table-title">名前</th>
                <td class="table-item">{{ $request->user->name }}</td>
            </tr>
            <tr class="approval__row">
                <th class="table-title">日付</th>
                <td class="table-item">{{ $request->attendance->detail_date  }}</td>
            </tr>
            <tr class="approval__row">
                <th class="table-title">出勤・退勤</th>
                <td class="table-item">{{ \Carbon\Carbon::parse($request->requested_check_in)->format('H:i') }}
                    <span>~</span>
                    {{ \Carbon\Carbon::parse($request->requested_check_out)->format('H:i') }}
                </td>
            </tr>
            @foreach($request->attendanceRequestBreakTimes as $breaktime)
                <tr class="approval__row">
                    <th class="table-title">
                        @if($loop->first)
                            休憩
                        @else
                            休憩{{ $loop->iteration }}
                        @endif
                    </th>
                    <td class="table-item">
                        {{ $breaktime->break_start_time  }}
                        <span>~</span>
                        {{  $breaktime->break_end_time  }}
                    </td>
                </tr>
            @endforeach
            <tr class="approval__row">
                <th class="table-title">備考</th>
                <td class="table-item">{{ $request->reason }}</td>
            </tr>
        </table>
        <div class="button">
            @if($request -> status === 'pending')
                <button class="button-submit" type="submit">承認</button>
            @else
                <p class="button-submit__approved">承認済み</p>
            @endif
        </div>
    </form>
</div>
@endsection