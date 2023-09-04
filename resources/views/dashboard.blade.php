@extends('layouts.app')
@section('title', 'Dashboard')
@section('username', Auth::user()->name)

@section('heading-bar')
<h1 class="main-heading">Dashboard</h1>
@endsection

@php
use Carbon\Carbon;

$today = Carbon::today();
$deadlinesCount = 0;
foreach ($tasks as $task) {
if (Carbon::parse($task['deadline'])->isSameDay($today)) {
$deadlinesCount++;
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
            <p class="stat-desc">Tasks this month</p>
            <p class="stat-value">{{ count($tasks)}}</p>
        </div>
        <div class="stats-card">
            <p class="stat-desc">Team members</p>
            <p class="stat-value">{{$teamMembersCount}}</p>
        </div>
        <div class="stats-card">
            <p class="stat-desc">Today deadlines</p>
            <p class="stat-value">{{$deadlinesCount}}</p>
        </div>
    </div>
</section>
<section class="data-section">
    <div class="data">
        <div class="data-card  @if(count($tasks) > 4) scrollable @endif">
            <header class="data-card-header">
                <ion-icon class="data-card-icon tasks-icon" name="list-circle-outline"></ion-icon>
                <h2 class="data-card-title">Tasks</h2>
            </header>
            <ul class="data-list">
                @foreach($tasks as $task)
                <li class="data-item">
                    <a href="#">
                        <h3 class="data-title">{{ $task['name'] }}</h3>
                        <p class="data-desc">{{Str::limit($task['description'], 100); }}</p>
                    </a>
                    <p class="data-additional">Deadline: {{\Carbon\Carbon::parse($task['deadline'])->toDateString();}}
                    </p>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
    <div class="data">
        <div class="data-card team-card @if($teamMembersCount > 4) scrollable @endif">
            <header class="data-card-header">
                <ion-icon class="data-card-icon team-icon" name="people-circle-outline"></ion-icon>
                <h2 class="data-card-title">Team</h2>
            </header>
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
        </div>
    </div>
</section>
@endsection