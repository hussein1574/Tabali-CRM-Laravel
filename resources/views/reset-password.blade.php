@extends('layouts.auth')
@section('title', 'Reset password')


@section('main')
<div class="sign-form">
    @if (session('status'))
    <h2 class="form-title alert-success top-gap">{{session('status')}} ✔</h2>
    <div class='flex-container'>
        <p class="sign-text bigger-font">Return to login ?</p>
        <a class="sign-link bigger-font" href="/">Sign in</a>
    </div>
    @else
    <h2 class="form-title">Forgot Your password ?</h2>
    <form class="sign-form" method="POST" action="/reset-password">
        @csrf
        @method('POST')
        @if($errors->any())
        {!! implode('', $errors->all('<div class="alert alert-danger">:message</div>')) !!}
        @endif
        <div class="input-holder">
            <label class="input-label" for="email">Your email address</label>
            <input class="input-box" id="email" type="email" name="email" required />
        </div>
        <button class="btn btn-sign-in">Reset password</button>
    </form>
    <div class="flex-container">
        <p class="sign-text">Login ?</p>
        <a class="sign-link" href="/">Sign in</a>
    </div>
    @endif
</div>
@endsection