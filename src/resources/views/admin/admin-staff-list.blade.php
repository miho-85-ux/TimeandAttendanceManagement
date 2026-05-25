@extends('layout.admin')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/admin-staff-list.css') }}">
@endsection

@section('content')
<div class="staff-list__content">
    <h1 class="staff-list__title">スタッフ一覧</h1>
    <div>
        <table class="staff-list__table">
            <tr class="table__row">
                <th class="table-title">名前</th>
                <th class="table-title">メールアドレス</th>
                <th class="table-title">月次勤怠</th>
            </tr>
            @foreach($users as $user)
            <tr class="table__row">
                <td class="table-item">{{ $user-> name }}</td>
                <td class="table-item">{{ $user-> email }}</td>
                <td class="table-item"><a class="staff-detail" href="{{ route('attendance.staff', $user->id ) }}">詳細</a></td>
            </tr>
            @endforeach
        </table>
    </div>
</div>
@endsection