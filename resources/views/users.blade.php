@extends('layouts.app')
@section('title', 'Users')
@section('username', Auth::user()->name)

@section('heading-bar')
<h1 class="main-heading"><a class='heading-link' href="/users">Users</a></h1>
@endsection

@php
$currentPage = $users->currentPage();
$lastPage = $users->lastPage();
@endphp

@section('main')
@if (session('success'))
<div class="modal-holder-message appear">
    <div class="modal">
        <ion-icon class='danger-icon' name="checkmark-done-circle-outline"></ion-icon>
        <h2 class="form-title">Success</h2>
        <p class="alert alert-success bigger-font">{{ session('success') }}</p>
        <button class="btn btn-close-message">
            <ion-icon class="modal-icon modal-icon-close" name="close-outline"></ion-icon>
        </button>
    </div>
</div>
@endif
@if($errors->any())
<div class="modal-holder-message appear">
    <div class="modal">
        <ion-icon class='danger-icon' name="alert-circle-outline"></ion-icon>
        <h2 class="form-title">Error</h2>
        {!! $errors->all('<div class="alert alert-danger bigger-font">:message</div>')[0] !!}
        <button class="btn btn-close-message">
            <ion-icon class="modal-icon modal-icon-close" name="close-outline"></ion-icon>
        </button>
    </div>
</div>
@endif

<section class="search-section">
    <form class="search" method="GET" action="/users">
        @if(request()->query('search'))
        <input class="input-search" type="text" id="search" value='{{request()->query('search')}}'
            placeholder="Search for team" name="search" />
        @else
        <input class="input-search" type="text" id="search" placeholder="Search for a user" name="search" />
        @endif
        <button class="btn-search">
            <ion-icon name="search-outline"></ion-icon>
        </button>
    </form>
</section>
<section class="page-items-section">
    <div class="page-items">
        @if(count($users) == 0)
        <div class="modal no-box-shadow margin-top-medium">
            <ion-icon class='danger-icon' name="alert-outline"></ion-icon>
            <h2 class="form-title">No Users Found</h2>
        </div>
        @else
        <ul class="page-data-list">
            @foreach($users as $user)
            <li class="page-data-item">
                <div class="left-part">
                    <h3 class="data-title">{{$user['name']}}</h3>
                    <p class="data-desc">{{$user['email']}}</p>
                </div>
                <div class="right-part">
                    <div class="role user-active">
                        <h3 class="data-role-title">State</h3>
                        <p class="data-role-desc">{{$user['is_activated'] ? 'Activated' : 'Not Activated'}}</p>
                    </div>
                    <div class="role user-role">
                        <h3 class="data-role-title">Role</h3>
                        <p class="data-role-desc">{{$user['role']}}
                        </p>
                    </div>
                    <form>
                        <input hidden id='user_id' name='user_id' value={{$user['id']}} />
                        <button class="settings user-settings">
                            <ion-icon class="settings-icon" name="create-outline"></ion-icon>
                        </button>
                    </form>
                </div>
            </li>
            @endforeach
        </ul>
        @endif
    </div>
</section>
@if($lastPage != 1)
<section class="pagination-section">
    <div class="pagination">
        @if($currentPage != 1)
        <a href="?page={{$currentPage-1}}" class="btn-pag btn--left">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="btn-img">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
            </svg>
        </a>
        @endif
        @if($lastPage > 7)
        @if($currentPage != 1)
        <a class="page-btn" href="?page=1">1</a>
        @endif
        @if($currentPage != 1 && $currentPage != 2)
        <span class="dots">...</span>
        @endif
        @if($currentPage + 5 < $lastPage) @for($i=$currentPage; $i < $currentPage + 5; $i++) <a
            class="page-btn @if($currentPage == $i) page--current @endif" href="?page={{$i}}">{{$i}}</a>
            @endfor
            @else
            @for($i = $lastPage-5; $i < $lastPage; $i++) <a
                class="page-btn @if($currentPage == $i) page--current @endif" href="?page={{$i}}">{{$i}}</a>
                @endfor
                @endif
                @if($currentPage + 5 < $lastPage) <span class="dots">...</span>
                    @endif
                    <a class="page-btn @if($currentPage == $lastPage) page--current @endif"
                        href="?page={{$lastPage}}">{{$lastPage}}</a>
                    @else
                    @for($i = 1; $i <= $lastPage; $i++) <a class="page-btn @if($currentPage == $i) page--current @endif"
                        href="?page={{$i}}">{{$i}}</a>
                        @endfor
                        @endif
                        @if($currentPage != $lastPage)
                        <a href="?page={{$currentPage+1}}" class="btn-pag btn--right">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="btn-img">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                            </svg>
                        </a>
                        @endif
    </div>
</section>
@endif
@endsection
@section('modals')
<div class="modal-holder">
    <div class="modal">
        <h2 class="form-title">Edit User</h2>
        <form class="sign-form" method="POST" action="/edit-user">
            @csrf
            @method('put')
            <input hidden id='user_id' name='user_id' value="" />
            <div class="input-holder">
                <label class="input-label" for="name">Name</label>
                <input class="input-box" id="name" type="text" value="Joe Bloogs" name="name" required />
            </div>
            <div class="input-holder">
                <label class="input-label" for="email">Email address</label>
                <input class="input-box" id="email" type="email" value="joe@tabali.com" name="email" required />
            </div>
            <div class="input-holder">
                <label class="input-label" for="role">Role</label>
                <select class="input-box" name="role" id="role">
                    <option value="Admin">Adminstartor</option>
                    <option value="User">User</option>
                </select>
            </div>
            <div class="input-holder">
                <label class="input-label" for="active">Activation</label>
                <select class="input-box" name="active" id="active">
                    <option value="active">Active</option>
                    <option value="not-active">Not Active</option>
                </select>
            </div>
            <button class="btn btn-add">
                Edit
                <ion-icon class="modal-icon" name="paper-plane-outline"></ion-icon>
            </button>
        </form>
        <button class="btn btn-close">
            <ion-icon class="modal-icon modal-icon-close" name="close-outline"></ion-icon>
        </button>
    </div>
</div>
@endsection