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
        </div>
        <div>
            @yield('content')
        </div>
    </div>
</body>
</html>