@extends('layouts.auth')
@section('title', 'Sign in')


@section('main')
<div class="sign-form">
    <h2 class="form-title">Sign in</h2>
    <form class="sign-form" method="POST" action="/login">
        @csrf
        @method('POST')
        @if (session('status'))
        <div class="alert alert-success">{{session('status')}} ✔</div>
        @endif
        @if($errors->any())
        {{-- {!! implode('', $errors->all('<div class="alert alert-danger">:message</div>')) !!} --}}
        {!! $errors->all('<div class="alert alert-danger">:message</div>')[0] !!}
        @endif
        <div class="input-holder">
            <label class="input-label" for="email">Your email address</label>
            <input class="input-box" id="email" value="{{ old('email') }}" type="email" name="email" required />
        </div>
        <div class="input-holder">
            <label class="input-label" for="password">Your password</label>
            <input class="input-box" id="password" type="password" name="password" required />
        </div>
        <div class="input-holder-check-box">
            <input class="input-checkbox" id="remember" type="checkbox" name="remember" />
            <label class="input-label remember" for="remember">Remember me</label>
        </div>
        <button class="btn btn-sign-in">Sign in</button>

    </form>
    <div class="flex-container">
        <p class="sign-text">Dont have an account ?</p>
        <a class="sign-link" href="/register">Sign up</a>
    </div>
    <div class="flex-container">
        <p class="sign-text">Forgotten your password ?</p>
        <a class="sign-link" href="/reset-password">Reset</a>
    </div>
</div>
@endsection