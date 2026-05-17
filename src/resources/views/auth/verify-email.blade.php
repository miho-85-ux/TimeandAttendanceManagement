@extends('layout.auth')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/verify-email.css') }}">
@endsection

@section('content')
<div class="verify-email__content">
    <div>
        <div class="verify-email__message">
            <p>登録していただいたメールアドレスに認証メールを送付しました。<br />メール認証を完了してください。</p>
        </div>
        <div class="verify-email__button">
            <a href="https://mailtrap.io/sandboxes" target="_blank" class="verify-button">認証はこちら</a>
        </div>
        <form action="/email/verification-notification" method="POST">
            @csrf 
            <button type="submit" class="verify-email__resend">認証メールを再送する</button>
        </form>
    </div>
</div>
@endsection