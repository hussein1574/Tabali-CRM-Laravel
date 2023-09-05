@extends('layouts.app')
@section('title', $task['name'])
@section('username', Auth::user()->name)

@php
$isAdmin = Auth::user()->role == 'Admin'
@endphp


@section('heading-bar')
<h1 class="main-heading"><a class='heading-link' href="/task?id={{$task['id']}}">{{$task['name']}}</a></h1>
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
<div class="task-body">
    <div class="task-communication">
        <p class="task-description">
            {{$task['description']}}
        </p>
        @if($task['status'] != 'Closed')
        <form class="comment-form" method='post' action='/add-comment'>
            @csrf
            @method('POST')
            <input hidden id='task_id' name='task_id' value='{{$task['id']}}' />
            <textarea class="input-box input-box--comment" id="description" name="description"
                placeholder="Write your comment!" rows="5" required></textarea>
            <button class="btn btn--add-comment">Add comment</button>
        </form>
        @endif
        <div class="comments">
            @foreach($comments->reverse() as $comment)
            <div class="comment">
                <div class="comment-info">
                    <h2 class="comment-author">{{$comment->user->name}}</h2>
                    <p class="comment-date">{{$comment['created_at']}}
                    </p>
                </div>
                <p class="comment-data">{{$comment['comment']}}</p>
            </div>
            @endforeach
        </div>
    </div>
    <div class="task-control">
        <div class="task-info-holder">
            <div class="task-info">
                <span class="task-info-title">Priority:</span>
                <span class="task-info-data"><span
                        class="task-priority task-priority--{{$task['priority']}}">{{$task['priority']}}</span></span>
                <span class="task-info-title">Status:</span>
                <span class="task-info-data"><span
                        class="task-priority task-priority--{{$task['status']}}">{{$task['status']}}</span></span>
                <span class="task-info-title">Date added:</span>
                <span class="task-info-data">{{\Carbon\Carbon::parse($task['created_at'])->toDateString()}}</span>
                <span class="task-info-title">Deadline:</span>
                <span class="task-info-data">{{\Carbon\Carbon::parse($task['deadline'])->toDateString()}}</span>
                <span class="task-info-title">Participants:</span>
                <span class="task-info-data">
                    @if(count($members) == 0)
                    No participants
                    @else
                    @foreach($members as $id => [$name , $email])
                    {{$name}}
                    @if(!$loop->last)
                    ,
                    @endif
                    @endforeach
                    @endif
                </span>
            </div>
        </div>
        @if($IsTaskOwner || $isAdmin)
        <button class="btn btn--task btn--parti">
            <ion-icon class="task-btn-icon" name="person-add-outline"></ion-icon>Add participant
        </button>
        <button class="btn btn--task btn--edit">
            <ion-icon class="task-btn-icon" name="create-outline"></ion-icon>Edit task
        </button>
        <button class="btn btn--task btn--delete">
            <ion-icon class="task-btn-icon" name="trash-outline"></ion-icon>Delete task
        </button>
        @if($task['status'] == 'Pending')
        <form class='button-form' method='post' action='/accept-task'>
            @csrf
            @method('POST')
            <input hidden id='task_id' name='task_id' value='{{$task['id']}}' />
            <button class="btn btn--task btn--accept">
                <ion-icon class="task-btn-icon" name="checkmark-outline"></ion-icon>Accept task
            </button>
        </form>
        <form class='button-form' method='post' action='/reject-task'>
            @csrf
            @method('POST')
            <input hidden id='task_id' name='task_id' value='{{$task['id']}}' />
            <button class="btn btn--task btn--reject">
                <ion-icon class="task-btn-icon" name="close-outline"></ion-icon>Reject task
            </button>
        </form>
        @endif
        @else
        @if($task['status'] != 'Pending')
        <button class="btn btn--task btn--accept">
            <ion-icon class="task-btn-icon" name="checkmark-outline"></ion-icon>Submit task
        </button>
        @endif
        @endif
    </div>
</div>
@endsection

@section('modals')
<div class="modal-holder modal--add">
    <div class="modal">
        <h2 class="form-title">Current participants</h2>
        @if(count($members) == 0)
        <div class="modal no-box-shadow">
            <ion-icon class='orange-icon' name="alert-outline"></ion-icon>
            <h3 class="form-title lighter-font">No participants Yet</h3>
        </div>
        @else
        <ul class="user-lines scrollable">
            @foreach($members as $id => [$name , $email])
            <li class="user-line">
                <span class="user-line-name">{{$name}}</span>
                <form method='post' action='{{ route('delete-task-member')}}'>
                    @csrf
                    @method('DELETE')
                    <input hidden id='task_id' name='task_id' value='{{$task['id']}}' />
                    <input hidden id='user_id' name="user_id" value="{{$id}}" />
                    <button type="submit" class="btn btn-user-delete">
                        <ion-icon class="task-btn-icon" name="trash-outline"></ion-icon>
                    </button>
                </form>
            </li>
            @endforeach
        </ul>
        @endif

        <h2 class="form-title">Add a new participants</h2>
        <form class="sign-form">
            <div class="input-holder">
                <label class="input-label" for="type">Who do you want to add ?</label>
                <select class="input-box type-select" name="type" id="type">
                    <option selected="true" disabled="disabled" value="">
                        Please choose
                    </option>
                    <option value="user">User</option>
                    <option value="team">Team</option>
                </select>
            </div>
            <div class="input-holder input-users hidden">
                <label class="input-label" for="title">User Emails</label>
                <select class="input-box" name="type" id="type">
                    <option selected="true" disabled="disabled" value="">
                        Please choose
                    </option>
                    <option value="user1">User1@gmail.com</option>
                    <option value="user2">User1@gmail.com</option>
                </select>
            </div>
            <div class="input-holder input-teams hidden">
                <label class="input-label" for="title">Team Names</label>
                <select class="input-box" name="type" id="type">
                    <option selected="true" disabled="disabled" value="">
                        Please choose
                    </option>
                    <option value="user1">Team 1</option>
                    <option value="user2">Team 2</option>
                </select>
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
<div class="modal-holder modal--delete">
    <div class="modal">
        <h2 class="form-title">
            <ion-icon class="danger-icon" name="alert-circle-outline"></ion-icon>
            Are you sure you want to delete this task ? <br />
            <br />
        </h2>
        <form class="sign-form" method='post' action='/delete-task'>
            @csrf
            @method('DELETE')

            <input hidden id='task_id' name='task_id' value='{{$task['id']}}' />
            <button class="btn btn-add">I'm Sure</button>
        </form>
        <button class="btn btn-close">
            <ion-icon class="modal-icon modal-icon-close" name="close-outline"></ion-icon>
        </button>
    </div>
</div>
<div class="modal-holder modal--edit">
    <div class="modal">
        <h2 class="form-title">Edit task</h2>
        <form class="sign-form" method='post' action='/edit-task'>
            @csrf
            @method('PUT')
            <input hidden id='task_id' name='task_id' value='{{$task['id']}}' />
            <div class="input-holder">
                <label class="input-label" for="title">Task Title</label>
                <input class="input-box" id="title" value="{{$task['name']}}" type="text" name="title" required />
            </div>
            <div class="input-holder">
                <label class="input-label" for="description">Task description</label>
                <textarea class="input-box" id="description" name="description" rows="5"
                    required>{{$task['description']}}</textarea>>
            </div>
            <div class="input-holder">
                <label class="input-label" for="priority">Task piority</label>
                <select class="input-box" name="priority" id="priority">
                    <option @if($task['priority']=='Low' ) selected @endif value="low">Low</option>
                    <option @if($task['priority']=='Normal' ) selected @endif value="normal">Normal</option>
                    <option @if($task['priority']=='Severe' ) selected @endif value="severe">Severe</option>
                </select>
            </div>
            <div class="input-holder">
                <label class="input-label" for="status">Task status</label>
                <select class="input-box" name="status" id="status">
                    <option @if($task['status']=='Open' ) selected @endif value="opened">Open</option>
                    <option @if($task['status']=='Pending' ) selected @endif value="pending">Pending</option>
                    <option @if($task['status']=='Closed' ) selected @endif value="closed">Closed</option>
                </select>
            </div>
            <div class="input-holder">
                <label class="input-label" for="deadline">Task deadline</label>
                <input class="input-box" id="deadline" value="{{$task['deadline']}}" type="datetime-local"
                    name="deadline" required />
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