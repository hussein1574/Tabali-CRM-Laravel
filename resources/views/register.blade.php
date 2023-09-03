@extends('layouts.auth')
@section('title', 'Sign up')


@section('main')
<div class="sign-form">
    <h2 class="form-title">Sign up</h2>
    <form class="sign-form" method="POST" action="/register">
        @csrf
        @method('POST')
        @if($errors->any())
        {!! implode('', $errors->all('<div class="alert alert-danger">:message</div>')) !!}
        @endif
        <div class="input-holder">
            <label class="input-label @error('name') alert-danger @enderror" for="name">Your full name</label>
            <input class="input-box @error('name') input-danger @enderror" value='{{ old('name')}}' id="name"
                type="text" name="name" required />
        </div>
        <div class="input-holder">
            <label class="input-label @error('email') alert-danger @enderror" for="email">Your email address</label>
            <input class="input-box @error('email') input-danger @enderror" value='{{ old('email')}}' id="email"
                type="email" name="email" required />
        </div>
        <div class="input-holder">
            <label class="input-label @error('password') alert @enderror" for="password">Your password</label>
            <input class="input-box @error('password') input-danger @enderror" id="password" type="password"
                name="password" required />
        </div>
        <div class="input-holder">
            <label class="input-label @error('password') alert-danger @enderror" for="password_confirmation">Confirm
                your password</label>
            <input class="input-box @error('password') input-danger @enderror" id="password_confirmation"
                type="password" name="password_confirmation" required />
        </div>
        <button class="btn btn-sign-in">Register</button>
    </form>
    <div class="flex-container">
        <p class="sign-text">Already have an account ?</p>
        <a class="sign-link" href="/">Sign in</a>
    </div>
</div>
@endsection