@extends('layouts.auth')
@section('title', 'Change password')


@section('main')
<div class="sign-form">
    <h2 class="form-title">Change your password ?</h2>
    <form class="sign-form" method="POST" action="/change-password">
        @csrf
        @method('POST')
        @if($errors->any())
        {!! implode('', $errors->all('<div class="alert alert-danger">:message</div>')) !!}
        @endif
        <input hidden id='token' name='token' value='{{ $token }}' />
        <div class="input-holder hidden">
            <label class="input-label" for="email">Your email address</label>
            <input class="input-box" id="email" type="email" name="email" value={{ request('email') }} />
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
        <button class="btn btn-sign-in">Change password</button>
    </form>
</div>
@endsection