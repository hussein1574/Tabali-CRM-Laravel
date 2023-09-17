<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\StoreprojectRequest;
use App\Http\Requests\UpdateprojectRequest;

class ProjectController extends Controller
{

    public function index(Request $request): View
    {
        $searchInput = $request->query('search');
        $recsPerPage = config('constants.RECS_PER_PAGE');
        $user = $request->user();
        $projectQuery = Project::query();
        if ($searchInput)
            $projectQuery = $this->search($searchInput, $projectQuery);
        $projects = $projectQuery->paginate($recsPerPage);
        return view('projects', compact('projects'));
    }
    function search($searchInput, $teamsQuery)
    {
        return $teamsQuery->where('name', 'LIKE', '%' . $searchInput . '%');
    }
    public function add(Request $request): RedirectResponse
    {

        $credentials = $request->validate([
            'name' => ['required', 'min:5', 'max:255'],
        ]);

        $project = Project::create([
            'name' => $credentials['name'],
        ]);

        if ($project) {
            if (App::isLocale('en')) {
                return redirect()->route('projects')->with('success', 'Project added successfully.');
            } else {
                return redirect()->route('projects')->with('نجاح', 'تم اضافة مشروع بنجاح');
            }
        } else {
            if (App::isLocale('en')) {
                return redirect()->route('projects')->with('error', 'Failed to add project.');
            } else {
                return redirect()->route('projects')->with('خطأ', 'حدث خطأ اثناء اضافة المشروع, برجاء اعادة المحاولة مجددا');
            }
        }
    }
    public function delete(Request $request): RedirectResponse
    {
        $project = Project::where('id', $request->project_id)->first();

        if ($project) {
            $project->delete();
            if (App::isLocale('en')) {
                return redirect()->route('projects')->with('success', 'Project deleted successfully.');
            } else {
                return redirect()->route('projects')->with('نجاح', 'تم حذف المشروع بنجاح');
            }
        } else {
            if (App::isLocale('en')) {
                return redirect()->route('teams')->with('error', 'Failed to delete project.');
            } else {
                return redirect()->route('teams')->with('خطأ', 'حدث خطأ اثناء حذف المشروع, برجاء اعادة المحاولة مجددا');
            }
        }
    }
}
