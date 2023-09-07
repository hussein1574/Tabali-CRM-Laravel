<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Team;
use Illuminate\View\View;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        if (!$user) return redirect('/');
        $tasks = '';
        $teams = '';
        if ($user->role === 'Admin') {
            $tasks = Task::where('status', '!=', 'Closed')->get()->map(function ($task) {
                return $task->get()->toArray();
            })->flatten(1)->toArray();
            $teams = Team::get()->map(function ($team) {
                $teamArray = $team->toArray();
                $memberNames = $team->teamUsers->pluck('user.name', 'user.email')->toArray();
                $teamArray['members'] = $memberNames;
                return $teamArray;
            })->toArray();
        } else {
            $tasks = $user->userTasks()->get()->map(function ($task) {
                return $task->task()->get()->toArray();
            })->flatten(1)->toArray();
            $teams = $user->userTeams()->get()->map(function ($team) {
                $teamArray = $team->team()->first()->toArray();
                $memberNames = $team->team()->first()->teamUsers()->with('user')->get()->pluck('user.name', 'user.email')->toArray();
                $teamArray['members'] = $memberNames;
                return $teamArray;
            })->toArray();
        }
        $tasks = array_unique($tasks, SORT_REGULAR);
        return view('dashboard', compact('tasks', 'teams'));
    }
}
