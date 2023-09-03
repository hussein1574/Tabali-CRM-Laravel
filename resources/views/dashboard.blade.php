@extends('layouts.app')
@section('title', 'Dashboard')

@section('heading-bar')
<h1 class="main-heading">Dashboard</h1>
@endsection


@section('main')
<section class="stats-section">
    <div class="stats">
        <div class="stats-card">
            <p class="stat-desc">Tasks this month</p>
            <p class="stat-value">71</p>
        </div>
        <div class="stats-card">
            <p class="stat-desc">Team members</p>
            <p class="stat-value">3</p>
        </div>
        <div class="stats-card">
            <p class="stat-desc">Today deadlines</p>
            <p class="stat-value">2</p>
        </div>
    </div>
</section>
<section class="data-section">
    <div class="data">
        <div class="data-card scrollable">
            <header class="data-card-header">
                <ion-icon class="data-card-icon tasks-icon" name="list-circle-outline"></ion-icon>
                <h2 class="data-card-title">Tasks</h2>
            </header>
            <ul class="data-list">
                <li class="data-item">
                    <a href="#">
                        <h3 class="data-title">Task title</h3>
                        <p class="data-desc">Task description</p>
                    </a>
                    <p class="data-additional">Deadline: 9/1/2023</p>
                </li>
                <li class="data-item">
                    <a href="#">
                        <h3 class="data-title">Task title</h3>
                        <p class="data-desc">Task description</p>
                    </a>
                    <p class="data-additional">Deadline: 9/1/2023</p>
                </li>
                <li class="data-item">
                    <a href="#">
                        <h3 class="data-title">Task title</h3>
                        <p class="data-desc">Task description</p>
                    </a>
                    <p class="data-additional">Deadline: 9/1/2023</p>
                </li>
                <li class="data-item">
                    <a href="#">
                        <h3 class="data-title">Task title</h3>
                        <p class="data-desc">Task description</p>
                    </a>
                    <p class="data-additional">Deadline: 9/1/2023</p>
                </li>
                <li class="data-item">
                    <a href="#">
                        <h3 class="data-title">Task title</h3>
                        <p class="data-desc">Task description</p>
                    </a>
                    <p class="data-additional">Deadline: 9/1/2023</p>
                </li>
                <li class="data-item">
                    <a href="#">
                        <h3 class="data-title">Task title</h3>
                        <p class="data-desc">Task description</p>
                    </a>
                    <p class="data-additional">Deadline: 9/1/2023</p>
                </li>
            </ul>
        </div>
    </div>
    <div class="data">
        <div class="data-card team-card">
            <header class="data-card-header">
                <ion-icon class="data-card-icon team-icon" name="people-circle-outline"></ion-icon>
                <h2 class="data-card-title">Team</h2>
            </header>
            <ul class="data-list">
                <li class="data-item">
                    <div>
                        <h3 class="data-title">Joe Bloogs</h3>
                        <p class="data-desc">joe@tabali.com</p>
                    </div>
                    <a href="#" class="data-additional">Team name</a>
                </li>
                <li class="data-item">
                    <div>
                        <h3 class="data-title">Joe Bloogs</h3>
                        <p class="data-desc">joe@tabali.com</p>
                    </div>
                    <a href="#" class="data-additional">Team name</a>
                </li>
                <li class="data-item">
                    <div>
                        <h3 class="data-title">Joe Bloogs</h3>
                        <p class="data-desc">joe@tabali.com</p>
                    </div>
                    <a href="#" class="data-additional">Team name</a>
                </li>
            </ul>
        </div>
    </div>
</section>
@endsection