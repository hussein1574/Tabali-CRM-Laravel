<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Task;
use App\Models\User;
use App\Models\UsersTask;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Http\RedirectResponse;

class TaskController extends Controller
{
    public function index(Request $request): View
    {
        $filterInput = $request->query('filter');
        $searchInput = $request->query('search');
        $recsPerPage = config('constants.RECS_PER_PAGE') - 1;
        $user = $request->user();

        //Get the teams where the user is Team Admin
        $teamsWhereUserIsAdmin = $user->userTeams()->where('team_role', 'Team Admin')->get();
        $isAdminInTeam = count($teamsWhereUserIsAdmin) != 0;

        $tasksQuery = '';
        if ($user->role === 'Admin') {
            $tasksQuery = Task::query();
        } else {
            $tasksIds = $user->userTasks()->pluck('task_id');
            $tasksQuery = Task::whereIn('id', $tasksIds);
        }
        if ($searchInput)
            $tasksQuery = $this->search($searchInput, $tasksQuery);
        if ($filterInput)
            $tasksQuery = $this->filter($filterInput, $tasksQuery);
        $tasks = $tasksQuery->paginate($recsPerPage);
        return view('tasks', compact('tasks', 'isAdminInTeam'));
    }
    function search($searchInput, $tasksQuery)
    {
        return $tasksQuery->where(
            function ($query) use ($searchInput) {
                $query->where('name', 'LIKE', '%' . $searchInput . '%')
                    ->orWhere('description', 'LIKE', '%' . $searchInput . '%');
            }
        );
    }
    function filter($filterInput, $tasksQuery)
    {
        return $tasksQuery->where('status', $filterInput);
    }
    public function add(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'title' => ['required', 'min:5', 'max:255'],
            'description' => ['required', 'min:50', 'max:5000'],
            'deadline' => [
                'required', 'date',
                'after_or_equal:' . Carbon::today()->format('Y-m-d'),
            ]
        ]);
        $task = Task::create([
            'name' => $credentials['title'],
            'description' => $credentials['description'],
            'priority' => $request->piority,
            'deadline' => $credentials['deadline'],
        ]);

        $taskUser = UsersTask::create([
            'task_id' => $task->id,
            'user_id' => $request->user()->id,
            'task_role' => 'Task Owner'
        ]);

        if ($taskUser) {
            return redirect()->route('tasks')->with('success', 'Task added successfully.');
        } else {
            return redirect()->route('tasks')->with('error', 'Failed to add task.');
        }
    }
    public function taskIndex(Request $request): View
    {
        $user = $request->user();
        $taskId = $request->query('id');

        //Get the teams where the user is Team Admin
        $teamsWhereUserIsAdmin = $user->userTeams()->where('team_role', 'Team Admin')->with('team')->get()->pluck('team.name', 'team.id');
        $isAdminInTeam = count($teamsWhereUserIsAdmin) != 0;


        $task = Task::where('id', $taskId)->first();
        if ($task) {
            $members = $task->taskUsers()->where('task_role', 'Member')->with('user')->get()->mapWithKeys(function ($taskUser) {
                return [$taskUser->user->id => [
                    $taskUser->user->name,
                    $taskUser->user->email,
                ]];
            })->toArray();
            $users = User::get()->map(function ($user) use ($members) {
                return !in_array($user->email, $members) ? $user : null;
            })->filter()->toArray();
            $comments = $task->comments()->with('user')->get();
            $IsTaskOwner = count(UsersTask::where('task_id', $taskId)->where('user_id', $user->id)->where('task_role', 'Task Owner')->get()) != 0;
            return view('task', compact('task', 'members', 'users', 'isAdminInTeam', 'teamsWhereUserIsAdmin', 'comments', 'IsTaskOwner'));
        } else {
            return $this->index($request);
        }
    }
    public function editTask(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'title' => ['required', 'min:5', 'max:255'],
            'description' => ['required', 'min:50', 'max:5000'],
            'deadline' => [
                'required', 'date',
                'after_or_equal:' . Carbon::today()->format('Y-m-d'),
            ]
        ]);
        $task = Task::where('id', $request->task_id)->first();
        $task->name = $credentials['title'];
        $task->description = $credentials['description'];
        $task->deadline = $credentials['deadline'];
        $task->priority = $request->priority;
        $task->status = $request->status;
        $task->save();

        return redirect()->back()->with('success', 'Task Updated successfully.');
    }
    public function deleteTask(Request $request): RedirectResponse
    {
        $task = Task::where('id', $request->task_id)->first();

        if ($task) {
            $task->delete();
            return redirect()->route('tasks')->with('success', 'Task deleted successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to delete task.');
        }
    }
    public function acceptTask(Request $request): RedirectResponse
    {
        $task = Task::where('id', $request->task_id)->first();
        $task->status = 'closed';
        $task->save();

        return redirect()->back()->with('success', 'The task is done 😊');
    }
    public function rejectTask(Request $request): RedirectResponse
    {
        $task = Task::where('id', $request->task_id)->first();
        $task->status = 'opened';
        $task->save();

        return redirect()->back()->with('success', 'The task is opened again 😒');
    }
    public function deleteTaskMember(Request $request)
    {
        $taskUser = UsersTask::where('task_id', $request->task_id)->where('user_id', $request->user_id)->first();

        if ($taskUser) {
            $taskUser->delete();
            return response()->json(['message' => 'Member removed successfully.']);
        } else {
            return response()->json(['message' => 'Failed to remove member.'], 400);
        }
    }
}
