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
                <td class="table-item">{{ $request->attendance->date }}</td>
            </tr>
            <tr class="approval__row">
                <th class="table-title">出勤・退勤</th>
                <td class="table-item">{{ $request->requested_check_in }}
                    <span>~</span>
                    {{ $request->requested_check_out }}
                </td>
            </tr>
            <tr class="approval__row">
                <th class="table-title">休憩</th>
                <td class="table-item">休憩時間</td>
            </tr>
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