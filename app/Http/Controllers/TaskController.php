<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\View\View;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request): View
    {
        $filterInput = $request->query('filter');
        $searchInput = $request->query('search');
        $recsPerPage = config('constants.RECS_PER_PAGE') - 1;
        $user = $request->user();
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
        return view('tasks', compact('tasks'));
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
}
