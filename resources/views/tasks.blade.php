@extends('layouts.app')
@section('title', 'Tasks')
@section('username', Auth::user()->name)

@php
$currentPage = $tasks->currentPage();
$lastPage = $tasks->lastPage();
$search = request()->query('search') ? '?search='. request()->query('search') . '&' : '?';
$filter = request()->query('filter');
$isAdmin = Auth::user()->role == 'Admin'
@endphp

@section('heading-bar')
<h1 class="main-heading"><a class='heading-link' href="/tasks">Tasks</a></h1>
@if($isAdmin || $isAdminInTeam)
<button class="btn btn--new">New task</button>
@endif
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
    <div class="tabs">
        <a class="tab @if($filter === 'Opened') tab-cta @endif" href="{{$search}}filter=Opened">Active</a>
        <a class="tab @if($filter === 'Pending') tab-cta @endif" href="{{$search}}filter=Pending">Pending</a>
        <a class="tab @if($filter === 'Closed') tab-cta @endif" href="{{$search}}filter=Closed">Closed</a>
    </div>
    <form class="search" method="GET" action="/tasks">
        @if(request()->query('search'))
        <input class="input-search" type="text" id="search" value='{{request()->query('search')}}'
            placeholder="Search for team" name="search" />
        @else
        <input class="input-search" type="text" id="search" placeholder="Search for task" name="search" />
        @endif
        <button class="btn-search">
            <ion-icon name="search-outline"></ion-icon>
        </button>
    </form>
</section>
<section class="page-items-section">
    <div class="page-items">
        @if(count($tasks) == 0)
        <div class="modal no-box-shadow margin-top-medium">
            <ion-icon class='danger-icon' name="alert-outline"></ion-icon>
            <h2 class="form-title">No Tasks Found</h2>
        </div>
        @else
        <ul class="page-data-list">
            @foreach($tasks as $task)
            <li class="page-data-item">
                <div class='left-part'>
                    <a href="/task?id={{$task['id']}}" class="page-data-title">{{$task['name']}}</a>
                    <p class="data-desc">{!! nl2br(wordwrap(Str::limit($task['description'], 100), 50, "\n", true)) !!}
                    </p>
                </div>
                <div class="right-part">
                    <div class="role">
                        <h3 class="data-role-title">Status</h3>
                        <p class="data-role-desc">{{$task['status']}}</p>
                    </div>
                    <div class="role">
                        <h3 class="data-role-title">Last edit</h3>
                        <p class="data-role-desc">{{\Carbon\Carbon::parse($task['updated_at'])->toDateString();}}</p>
                    </div>
                    <div class="role">
                        <h3 class="data-role-title">Deadline</h3>
                        <p class="data-role-desc">{{\Carbon\Carbon::parse($task['deadline'])->toDateString();}}</p>
                    </div>
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
        <h2 class="form-title">Add a new task</h2>
        <form class="sign-form" method='post' action='/add-task'>
            @csrf
            @method('POST')
            <div class="input-holder">
                <label class="input-label" for="title">Task title</label>
                <input class="input-box" id="title" type="text" name="title" required />
            </div>
            <div class="input-holder">
                <label class="input-label" for="description">Task description</label>
                <textarea class="input-box" id="description" name="description" rows="5" required></textarea>
            </div>
            <div class="input-holder">
                <label class="input-label" for="piority">Task piority</label>
                <select class="input-box" name="piority" id="piority">
                    <option value="low">Low</option>
                    <option value="normal">Normal</option>
                    <option value="severe">Severe</option>
                </select>
            </div>
            <div class="input-holder">
                <label class="input-label" for="deadline">Task deadline</label>
                <input class="input-box" id="deadline" type="datetime-local" name="deadline" required />
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