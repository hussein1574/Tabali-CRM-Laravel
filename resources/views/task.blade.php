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
<div class="task-body">
    <div class="task-communication">
        <p class="task-description">
            {!! nl2br($task['description']) !!}
        </p>
        @if($task['status'] != 'Closed')
        <form class="comment-form" method='post' action='/add-comment'>
            @csrf
            @method('POST')
            <input hidden id='task_id' name='task_id' value='{{$task['id']}}' />
            <textarea class="input-box input-box--comment" id="description" name="description"
                placeholder="{{__('messages.writeComment')}}" rows="5" required></textarea>
            <button class="btn btn--add-comment">{{__('messages.addComment')}}</button>
        </form>
        @endif
        <div class="comments @if(count($comments)>= 2) scrollable @endif">
            @foreach($comments->reverse() as $comment)
            <div class="comment">
                <div class="comment-info">
                    <h2 class="comment-author">{{$comment->user->name}}</h2>
                    <p class="comment-date">{{$comment['created_at']}}
                    </p>
                </div>
                <p class="comment-data">{{$comment['comment']}}</p>
                @if($comment->user->id == Auth::user()->id)
                <div class='comment-delete'>
                    <form method="POST" action="/delete-comment">
                        @csrf
                        @method('DELETE')
                        <input hidden name='comment_id' id='comment_id' value={{$comment['id']}} />
                        <button class="settings">
                            <ion-icon class="settings-icon" name="trash-outline"></ion-icon>
                        </button>
                    </form>
                </div>
                @endif
            </div>
            @endforeach
        </div>
    </div>
    <div class="task-control">
        <div class="task-info-holder">
            <div class="task-info">
                <span class="task-info-title">{{__('messages.priority')}}:</span>
                <span class="task-info-data"><span
                        class="task-priority task-priority--{{$task['priority']}}">@if($task['priority'] === 'Low')
                        {{__('messages.low')}}
                        @elseif($task['priority'] === 'Normal') {{__('messages.normal')}} @else
                        {{__('messages.severe')}} @endif</span></span>
                <span class="task-info-title">{{__('messages.status')}}:</span>
                <span class="task-info-data"><span
                        class="task-priority task-priority--{{$task['status']}}">@if($task['status'] === 'Opened')
                        {{__('messages.activeFilter')}}
                        @elseif($task['status'] === 'Pending') {{__('messages.pendingFilter')}} @else
                        {{__('messages.closedFilter')}} @endif</span></span>
                <span class="task-info-title">{{__('messages.createdAt')}}:</span>
                <span class="task-info-data">{{\Carbon\Carbon::parse($task['created_at'])->toDateString()}}</span>
                <span class="task-info-title">{{__('messages.deadline')}}:</span>
                <span class="task-info-data">{{\Carbon\Carbon::parse($task['deadline'])->toDateString()}}</span>
                <span class="task-info-title">{{__('messages.participants')}}:</span>
                <span class="task-info-data participants">
                    @if(count($members) == 0)
                    {{__('messages.noParticipants')}}
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
            <ion-icon class="task-btn-icon" name="person-add-outline"></ion-icon>{{__('messages.addParticipants')}}
        </button>
        <button class="btn btn--task btn--edit">
            <ion-icon class="task-btn-icon" name="create-outline"></ion-icon>{{__('messages.editTask')}}
        </button>
        <button class="btn btn--task btn--delete">
            <ion-icon class="task-btn-icon" name="trash-outline"></ion-icon>{{__('messages.deleteTask')}}
        </button>
        @if($task['status'] == 'Pending')
        <form class='button-form' method='post' action='/accept-task'>
            @csrf
            @method('POST')
            <input hidden id='task_id' name='task_id' value='{{$task['id']}}' />
            <button class="btn btn--task btn--accept">
                <ion-icon class="task-btn-icon" name="checkmark-outline"></ion-icon>{{__('messages.acceptTask')}}
            </button>
        </form>
        <form class='button-form' method='post' action='/reject-task'>
            @csrf
            @method('POST')
            <input hidden id='task_id' name='task_id' value='{{$task['id']}}' />
            <button class="btn btn--task btn--reject">
                <ion-icon class="task-btn-icon" name="close-outline"></ion-icon>{{__('messages.rejectTask')}}
        </form>
        @endif
        @else
        @if($task['status'] == 'Opened')
        <form class='button-form' method='post' action='/submit-task'>
            @csrf
            @method('POST')
            <input hidden id='task_id' name='task_id' value='{{$task['id']}}' />
            <button class="btn btn--task btn--accept">
                <ion-icon class="task-btn-icon" name="checkmark-outline"></ion-icon>{{__('messages.submitTask')}}
            </button>
        </form>
        @endif
        @endif
    </div>
</div>
@endsection

@section('modals')
<div class="modal-holder modal--add">
    <div class="modal">
        <h2 class="form-title">{{__('messages.currentParti')}}</h2>
        @if(count($members) == 0)
        <div class="modal no-box-shadow">
            <ion-icon class='orange-icon' name="alert-outline"></ion-icon>
            <h3 class="form-title lighter-font">{{__('messages.noParticipants')}}</h3>
        </div>
        @else
        <ul class="user-lines @if(count($members) >= 4) scrollable @endif">
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

        <h2 class="form-title">{{__('messages.addNewParti')}}</h2>
        <form class="sign-form" method='post' action='{{route('add-task-members')}}'>
            @csrf
            @method('POST')
            <input hidden id='task_id' name='task_id' value='{{$task['id']}}' />
            @if(!$isAdmin)
            <div class="input-holder input-teams">
                <label class="input-label" for="team_id">{{__('messages.teamNames')}}</label>
                <select class="input-box" name="team_id" id="team_id">
                    <option selected="true" disabled="disabled" value="">
                        {{__('messages.pleaseChoose')}}
                    </option>
                    @foreach($teamsWhereUserIsAdmin as $id => $name )
                    <option value="{{$id}}">{{$name}}</option>
                    @endforeach
                </select>
            </div>
            @else
            <div class="input-holder">
                <label class="input-label" for="type">{{__('messages.addNewMessage')}}</label>
                <select class="input-box type-select" name="type" id="type">
                    <option selected="true" disabled="disabled" value="">
                        {{__('messages.pleaseChoose')}}
                    </option>
                    <option value="user">{{__('messages.user')}}</option>
                    <option value="team">{{__('messages.team')}}</option>
                </select>
            </div>
            <div class="input-holder input-users hidden">
                <label class="input-label" for="user_id">{{__('messages.users')}}</label>
                <select class="input-box" name="user_id" id="user_id">
                    <option selected="true" disabled="disabled" value="">
                        {{__('messages.pleaseChoose')}}
                    </option>
                    @foreach($users as $user)
                    <option value="{{$user['id']}}">{{$user['name']}}</option>
                    @endforeach
                </select>
            </div>
            <div class="input-holder input-teams hidden">
                <label class="input-label" for="team_id">{{__('messages.teamNames')}}</label>
                <select class="input-box" name="team_id" id="team_id">
                    <option selected="true" disabled="disabled" value="">
                        {{__('messages.pleaseChoose')}}
                    </option>
                    @foreach($teams as $team)
                    <option value="{{$team['id']}}">{{$team['name']}}</option>
                    @endforeach
                </select>
            </div>
            @endif

            <button class="btn btn-add btn-add-parti">
                {{__('messages.add')}}
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
            {{__('messages.deleteMsg')}} <br />
            <br />
        </h2>
        <form class="sign-form" method='post' action='/delete-task'>
            @csrf
            @method('DELETE')

            <input hidden id='task_id' name='task_id' value='{{$task['id']}}' />
            <button class="btn btn-add">{{__('messages.deleteConfirm')}}</button>
        </form>
        <button class="btn btn-close">
            <ion-icon class="modal-icon modal-icon-close" name="close-outline"></ion-icon>
        </button>
    </div>
</div>
<div class="modal-holder modal--edit">
    <div class="modal">
        <h2 class="form-title">{{__('messages.editTask')}}</h2>
        <form class="sign-form" method='post' action='/edit-task'>
            @csrf
            @method('PUT')
            <input hidden id='task_id' name='task_id' value='{{$task['id']}}' />
            <div class="input-holder">
                <label class="input-label" for="title">{{__('messages.taskTitle')}}</label>
                <input class="input-box" id="title" value="{{$task['name']}}" type="text" name="title" required />
            </div>
            <div class="input-holder">
                <label class="input-label" for="description">{{__('messages.taskDesc')}}</label>
                <textarea class="input-box" id="description" name="description" rows="5"
                    required>{{$task['description']}}</textarea>>
            </div>
            <div class="input-holder">
                <label class="input-label" for="priority">{{__('messages.taskPriority')}}</label>
                <select class="input-box" name="priority" id="priority">
                    <option @if($task['priority']=='Low' ) selected @endif value="low">{{__('messages.low')}}</option>
                    <option @if($task['priority']=='Normal' ) selected @endif value="normal">{{__('messages.normal')}}
                    </option>
                    <option @if($task['priority']=='Severe' ) selected @endif value="severe">{{__('messages.severe')}}
                    </option>
                </select>
            </div>
            <div class="input-holder">
                <label class="input-label" for="status">{{__('messages.status')}}</label>
                <select class="input-box" name="status" id="status">
                    <option @if($task['status']=='Open' ) selected @endif value="opened">{{__('messages.activeFilter')}}
                    </option>
                    <option @if($task['status']=='Pending' ) selected @endif value="pending">
                        {{__('messages.pendingFilter')}}</option>
                    <option @if($task['status']=='Closed' ) selected @endif value="closed">
                        {{__('messages.closedFilter')}}</option>
                </select>
            </div>
            <div class="input-holder">
                <label class="input-label" for="deadline">{{__('messages.taskDeadline')}}</label>
                <input class="input-box" id="deadline" value="{{$task['deadline']}}" type="datetime-local"
                    name="deadline" required />
            </div>
            <button class="btn btn-add">
                {{__('messages.edit')}}
                <ion-icon class="modal-icon" name="paper-plane-outline"></ion-icon>
            </button>
        </form>
        <button class="btn btn-close">
            <ion-icon class="modal-icon modal-icon-close" name="close-outline"></ion-icon>
        </button>
    </div>
</div>
@endsection