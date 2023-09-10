@extends('layouts.app')
@section('title', __('messages.dashboard'))
@section('username', Auth::user()->name)

@section('heading-bar')
<h1 class="main-heading">{{__('messages.dashboard')}}</h1>
@endsection

@php
use Carbon\Carbon;

$today = Carbon::today();
$deadlinesCount = 0;
$openedTasksCount = 0;
foreach ($tasks as $task) {
if($task['status'] != 'Closed')
{
if (Carbon::parse($task['deadline'])->isSameDay($today)) {
$deadlinesCount++;
}
$openedTasksCount++;
}

}

$teamMembersCount = 0;
foreach($teams as $team){
$teamMembersCount += count($team['members']);
if(in_array(Auth::user()->name,$team['members'])) $teamMembersCount -= 1;
}

@endphp

@section('main')
<section class="stats-section">
    <div class="stats">
        <div class="stats-card">
            <p class="stat-desc">{{__('messages.curTasks')}}</p>
            <p class="stat-value">{{ $openedTasksCount}}</p>
        </div>
        <div class="stats-card">
            <p class="stat-desc">{{__('messages.members')}}</p>
            <p class="stat-value">{{$teamMembersCount}}</p>
        </div>
        <div class="stats-card">
            <p class="stat-desc">{{__('messages.deadlines')}}</p>
            <p class="stat-value">{{$deadlinesCount}}</p>
        </div>
    </div>
</section>
<section class="data-section">
    <div class="data">
        <div class="data-card  @if(count($tasks) > 4) scrollable @endif">
            <header class="data-card-header">
                <ion-icon class="data-card-icon tasks-icon" name="list-circle-outline"></ion-icon>
                <h2 class="data-card-title">{{__('messages.tasks')}}</h2>
            </header>
            @if($openedTasksCount == 0)
            <div class="modal no-box-shadow">
                <ion-icon class='orange-icon' name="alert-outline"></ion-icon>
                <h2 class="form-title">{{__('messages.noTasks')}}</h2>
            </div>
            @else
            <ul class="data-list" @if(count($tasks)>= 4) scrollable @endif>
                @foreach($tasks as $task)
                @if($task['status'] != 'Closed')
                <li class="data-item">
                    <a class='data-additional-right' href="/task?id={{$task['id']}}">
                        <h3 class="data-title">{{ $task['name'] }}</h3>
                        <p class="data-desc">{!! nl2br(Str::limit($task['description'], 100)) !!}</p>
                    </a>
                    <p class="data-additional">{{__('messages.deadline')}}:
                        {{\Carbon\Carbon::parse($task['deadline'])->toDateString();}}
                    </p>
                </li>
                @endif
                @endforeach
            </ul>
            @endif
        </div>
    </div>
    <div class="data">
        <div class="data-card team-card @if($teamMembersCount > 4) scrollable @endif">
            <header class="data-card-header">
                <ion-icon class="data-card-icon team-icon" name="people-circle-outline"></ion-icon>
                <h2 class="data-card-title">{{__('messages.teams')}}</h2>
            </header>
            @if(count($teams) == 0)
            <div class="modal no-box-shadow">
                <ion-icon class='green-icon ' name="alert-outline"></ion-icon>
                <h2 class="form-title">{{__('messages.noTeams')}}</h2>
            </div>
            @else
            <ul class="data-list">
                @foreach($teams as $team)
                @foreach($team['members'] as $email => $member)
                @continue($member === Auth::user()->name)
                <li class="data-item">
                    <div>
                        <h3 class="data-title">{{$member}}</h3>
                        <p class="data-desc">{{$email}}</p>
                    </div>
                    <a href="/team?id={{$team['id']}}" class="data-additional">{{$team['name']}}</a>
                </li>
                @endforeach
                @endforeach
            </ul>
            @endif
        </div>
    </div>
</section>
@endsection