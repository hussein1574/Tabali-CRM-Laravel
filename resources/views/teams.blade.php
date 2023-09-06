@extends('layouts.app')
@section('title', 'Teams')
@section('username', Auth::user()->name)

@php
$currentPage = $teams->currentPage();
$lastPage = $teams->lastPage();
$isAdmin = Auth::user()->role == 'Admin'
@endphp

@section('heading-bar')
<h1 class="main-heading"><a class='heading-link' href="/teams">Teams</a></h1>
@if($isAdmin)
<button class="btn btn--new">New team</button>
@endif
@endsection



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
    <form class="search" method="GET" action="/teams">
        @if(request()->query('search'))
        <input class="input-search" type="text" id="search" value='{{request()->query('search')}}'
            placeholder="Search for team" name="search" />
        @else
        <input class="input-search" type="text" id="search" placeholder="Search for team" name="search" />
        @endif
        <button class="btn-search">
            <ion-icon name="search-outline"></ion-icon>
        </button>
    </form>
</section>
<section class="page-items-section">
    @if(count($teams) == 0)
    <div class="modal no-box-shadow margin-top-medium">
        <ion-icon class='danger-icon' name="alert-outline"></ion-icon>
        <h2 class="form-title">No Teams Found</h2>
    </div>
    @else
    <div class="page-items">
        <ul class="page-data-list">
            @foreach($teams->items() as $team)
            <li class="page-data-item">
                <div>
                    <a href="/team?id={{$team['id']}}" class="page-data-title">{{$team['name']}}</a>
                </div>
                <div class="right-part">
                    <p class="page-data-date">Created at:
                        {{\Carbon\Carbon::parse($team['created_at'])->toDateString()}}</p>
                    @if(Auth::user()->role == 'Admin')
                    <form method="POST" action="/delete-team">
                        @csrf
                        @method('DELETE')
                        <input hidden name='team_id' id='team_id' value={{$team['id']}} />
                        <button class="settings">
                            <ion-icon class="settings-icon" name="trash-outline"></ion-icon>
                        </button>
                    </form>
                    @endif
                </div>
            </li>
            @endforeach
        </ul>
    </div>
    @endif
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
        <h2 class="form-title">Create a team ?</h2>
        <form class="sign-form" method="POST" action="/add-team">
            @csrf
            @method('POST')
            <div class="input-holder">
                <label class="input-label" for="name">Team name</label>
                <input class="input-box" id="name" type="text" name="name" required />
            </div>
            <button class="btn btn-add">
                Add
                <ion-icon class="modal-icon" name="paper-plane-outline"></ion-icon>
            </button>
        </form>
        <button class="btn btn-close">
            <ion-icon class="modal-icon modal-icon-close" name="close-outline"></ion-icon>
        </button>
    </div>
</div>
@endsection