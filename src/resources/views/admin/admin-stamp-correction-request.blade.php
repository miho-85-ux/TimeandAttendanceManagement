@extends('layout.admin')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/stamp-correction-request.css') }}">
@endsection

@section('content')
<div class="request-content">
    <h1 class="request-content__title">申請一覧</h1>
    <div class="request-content__select">
        <a class="wait-approved {{ request('status') == 'pending' ? 'active' : '' }}" href="?status=pending" >承認待ち</a>
        <a class="approved {{ request('status') == 'approved' ? 'active' : '' }}" href="?status=approved">承認済み</a>
    </div>
    <div>
        <table class="request-table">
            <tr class="request-table__row">
                <th>状態</th>
                <th>名前</th>
                <th>対象日時</th>
                <th>申請理由</th>
                <th>申請日時</th>
                <th>詳細</th>
            </tr>
            @foreach($requests as $request)
            <tr class="request-table__row">
                <td>
                    @if($request->status === 'pending')
                        承認待ち
                    @elseif($request->status === 'approved')
                        承認済み
                    @endif
                </td>
                <td>{{ $request->user->name }}</td>
                <td>{{ $request->requested_check_in  }}</td>
                <td>{{ $request->reason  }}</td>
                <td>{{ $request->created_at  }}</td>
                <td>
                    <a class="request-table__button" href="{{ route('admin.approval_show', $request->id) }}">詳細</a>
                </td>
            </tr>
            @endforeach
        </table>
    </div>
</div>
@endsection