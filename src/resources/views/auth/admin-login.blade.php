@extends('layout.auth')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/admin-login.css') }}">
@endsection

@section('content')
<div class="Access-content">
    <h1 class="Access-title">管理者ログイン</h1>
    <div class="Access-items">
        <form action="" method="POST" >
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
            <div class="Access-button">
                <button class="Access-button__submit" type="submit">管理者ログインする</button>
            </div>
        </form>
    </div>
</div>

@endsection
