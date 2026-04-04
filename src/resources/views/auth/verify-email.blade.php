@extends('layout.auth')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/register.css') }}">
@endsection

@section('content')
<div>
    <div>
        登録していただいたメールアドレスに認証メールを送付しました。<br />メール認証を完了してください。
    </div>
    <div>
        <a href="">認証はこちら</a>
    </div>
    <form action="" method="POST">
        <button type="submit">認証メールを再送する</button>
    </form>
</div>
@endsection