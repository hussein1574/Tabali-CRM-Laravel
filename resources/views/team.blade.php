@extends('layouts.app')


@php
use App\Models\UsersTeam;
$currentPage = $members->currentPage();
$lastPage = $members->lastPage();
$isAdmin = Auth::user()->role == 'Admin' || UsersTeam::where('user_id', Auth::user()->id)->where('team_id',
$team['id'])->first()->team_role == 'Team Admin';
@endphp
@section('title', $team['name'])
@section('username', Auth::user()->name)

@section('heading-bar')
<div class="heading-bar">
    <h1 class="main-heading"><a class='heading-link' href="/team?id={{$team['id']}}">{{$team['name']}}</a></h1>
    @if($isAdmin)
    <button class="btn btn--new">{{__('messages.addMember')}}</button>
    @endif
</div>
@endsection



@section('main')
@if (session(__('messages.success'))))
<div class="modal-holder-message appear">
    <div class="modal">
        <ion-icon class='danger-icon' name="checkmark-done-circle-outline"></ion-icon>
        <h2 class="form-title">{{__('messages.success')}}</h2>
        <p class="alert alert-success bigger-font">{{ session(__('messages.success')) }}</p>
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
        <h2 class="form-title">{{__('messages.error')}}</h2>
        {!! $errors->all('<div class="alert alert-danger bigger-font">:message</div>')[0] !!}
        <button class="btn btn-close-message">
            <ion-icon class="modal-icon modal-icon-close" name="close-outline"></ion-icon>
        </button>
    </div>
</div>
@endif

<section class="search-section">
    <form class="search" method="GET" action="/team">
        <input hidden name='id' value='{{$team['id']}}' id='id' />
        @if(request()->query('search'))
        <input class="input-search" type="text" id="search" value='{{request()->query('search')}}'
            placeholder="{{__('messages.searchForMember')}}" name="search" required />
        @else
        <input class="input-search" type="text" id="search" placeholder="{{__('messages.searchForMember')}}"
            name="search" />
        @endif
        <button class="btn-search">
            <ion-icon name="search-outline"></ion-icon>
        </button>
    </form>
</section>
<section class="page-items-section">
    @if(count($members) == 0)
    <div class="modal no-box-shadow margin-top-medium">
        <ion-icon class='danger-icon' name="alert-outline"></ion-icon>
        <h2 class="form-title">{{__('messages.noMembersFound')}}</h2>
    </div>
    @else
    <div class="page-items">
        <ul class="page-data-list">
            @foreach($members->items() as $member)
            <li class="page-data-item">
                <div class="left-part">
                    <h3 class="data-title">{{$member->user()->first()['name']}}</h3>
                    <p class="data-desc">{{$member->user()->first()['email']}}</p>
                </div>
                <div class="right-part">
                    <div class="role">
                        <h3 class="data-role-title">{{__('messages.role')}}</h3>
                        <p class="data-role-desc">
                            {{$member['team_role'] == 'Member' ? __('messages.member') : __('messages.teamAdmin') }}</p>
                    </div>
                    @if($isAdmin)
                    <button class="settings settings-open-list">
                        <ion-icon class="settings-icon" name="settings-outline"></ion-icon>
                    </button>
                    @endif
                    <div class="settings-nav">
                        <ul class="settings-list">
                            <li>
                                <form method='POST' action='/toggle-team-admin'>
                                    @csrf
                                    @method('put')
                                    <input hidden id='team_id' name='team_id' value='{{$team['id']}}' />
                                    <input hidden id='user_id' name='user_id'
                                        value='{{$member->user()->first()['id']}}' />
                                    <button class='settings-option'>@if($member['team_role'] === 'Team Admin')
                                        {{__('messages.removeAdmin')}} @else {{__('messages.assignAdmin')}}
                                        @endif</button>
                                </form>
                            </li>
                            <li>
                                <form method='POST' action='/remove-member'>
                                    @csrf
                                    @method('put')
                                    <input hidden id='team_id' name='team_id' value='{{$team['id']}}' />
                                    <input hidden id=' user_id' name='user_id'
                                        value='{{$member->user()->first()['id']}}' />
                                    <button class='settings-option'>{{__('messages.removeFromTeam')}}</button>
                                </form>
                            </li>
                        </ul>
                    </div>
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
        <h2 class="form-title">{{__('messages.addMember')}}</h2>
        <form class="sign-form" method='post' action='/add-member'>
            @csrf
            @method('put')
            <div class="input-holder">
                <input hidden id='team_id' name='team_id' value='{{$team['id']}}' />
                <label class="input-label" for="user_id">{{__('messages.usersNames')}}</label>
                <select class="input-box" name="user_id" id="user_id">
                    <option value='' disabled="disabled">{{__('messages.chooseUser')}}</option>
                    @foreach($users as $user)
                    <option value="{{$user['id']}}">{{$user['name']}}</option>
                    @endforeach
                </select>
            </div>
            <button class="btn btn-add">
                {{__('messages.add')}}
                <ion-icon class="modal-icon" name="paper-plane-outline"></ion-icon>
            </button>
        </form>
        <button class="btn btn-close">
            <ion-icon class="modal-icon modal-icon-close" name="close-outline">
            </ion-icon>
        </button>
    </div>
</div>
@endsection