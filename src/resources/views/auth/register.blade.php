@extends('layout.auth')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/register.css') }}">
@endsection

@section('content')
<div class="Access-content">
    <h1 class="Access-title">会員登録</h1>
    <div class="Access-items">
        <form action="/register" method="POST" >
            @csrf 
            <div class="Access-item">
                <label class="label" for="name">名前</label>
                <input class="input" type="text" name="name" id="name" value="{{ old('name') }}">
            </div>
            @error ('name')
                <p class="error">{{ $message }}</p>
            @enderror
            <div class="Access-item">
                <label class="label" for="email">メールアドレス</label>
                <input class="input" type="email" name="email" id="email" value="{{ old('email') }}">
            </div>
            @error ('email')
                <p class="error">{{ $message }}</p>
            @enderror
            <div class="Access-item">
                <label class="label" for="password">パスワード</label>
                <input class="input" type="password" name="password" id="password">
            </div>
            @error ('password')
                <p class="error">{{ $message }}</p>
            @enderror
            <div class="Access-item">
                <label class="label" for="password_confirmation">パスワード確認</label>
                <input class="input" type="password" name="password_confirmation" id="password_confirmation">
            </div>
            <div class="Access-button">
                <button class="Access-button__submit" type="submit">登録する</button>
            </div>
        </form>
        <a class="login-button" href="/login">ログインはこちら</a>
    </div>
</div>

@endsection
