<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TimeandAttendanceManagement</title>
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    @yield('css')
</head>
<body>
    <div class="content">
        <div class="header">
            <div class="header-logo">
                <img src="{{ asset('images/COACHTECHヘッダーロゴ (1).png') }}" alt="COACHTECHロゴ">
            </div>
            <ul class="header-items">
                <li><a class="item" href="/admin/attendance/list">勤怠一覧</a></li>
                <li><a class="item" href="/admin/staff/list">スタッフ一覧</a></li>
                <li><a class="item" href="/admin/stamp_correction_request/list">申請一覧</a></li>
                <form action="/logout" method="POST">
                    @csrf 
                    <button class="logout-submit" type="submit">ログアウト</button>
                </form>
            </ul>
        </div>
        <div>
            @yield('content')
        </div>
    </div>
</body>
</html>