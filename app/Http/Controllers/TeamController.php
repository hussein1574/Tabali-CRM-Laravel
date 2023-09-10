<?php

namespace App\Http\Controllers;


use id;
use App\Models\Team;
use App\Models\User;
use App\Models\UsersTeam;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Http\RedirectResponse;

class TeamController extends Controller
{
    public function index(Request $request): View
    {
        $searchInput = $request->query('search');
        $recsPerPage = config('constants.RECS_PER_PAGE');
        $user = $request->user();
        $teamsQuery = '';
        if ($user->role === 'Admin') {
            $teamsQuery = Team::query();
        } else {
            $teamIds = $user->userTeams()->pluck('team_id');
            $teamsQuery = Team::whereIn('id', $teamIds);
        }
        if ($searchInput)
            $teamsQuery = $this->search($searchInput, $teamsQuery);
        $teams = $teamsQuery->paginate($recsPerPage);
        return view('teams', compact('teams'));
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

        $team = Team::create([
            'name' => $credentials['name'],
        ]);

        if ($team) {
            if (App::isLocale('en')) {
                return redirect()->route('teams')->with('success', 'Team added successfully.');
            } else {
                return redirect()->route('teams')->with('نجاح', 'تم اضافة فريق بنجاح');
            }
        } else {
            if (App::isLocale('en')) {
                return redirect()->route('teams')->with('error', 'Failed to add team.');
            } else {
                return redirect()->route('teams')->with('خطأ', 'حدث خطأ اثناء اضافة الفريق, برجاء اعادة المحاولة مجددا');
            }
        }
    }
    public function delete(Request $request): RedirectResponse
    {
        $team = Team::where('id', $request->team_id)->first();

        if ($team) {
            $team->delete();
            if (App::isLocale('en')) {
                return redirect()->route('teams')->with('success', 'Team deleted successfully.');
            } else {
                return redirect()->route('teams')->with('نجاح', 'تم حذف الفريق بنجاح');
            }
        } else {
            if (App::isLocale('en')) {
                return redirect()->route('teams')->with('error', 'Failed to delete team.');
            } else {
                return redirect()->route('teams')->with('خطأ', 'حدث خطأ اثناء حذف الفريق, برجاء اعادة المحاولة مجددا');
            }
        }
    }
    public function teamIndex(Request $request): View
    {
        $searchInput = $request->query('search');
        $recsPerPage = config('constants.RECS_PER_PAGE');
        $team = Team::where('id', $request->query('id'))->first();
        if ($team) {
            $teamArray = $team->toArray();
            $membersQuery = $team->teamUsers();
            $membersArray = $team->teamUsers()->with('user')->get()->pluck('user.email', 'user.id')->toArray();
            $team = $teamArray;
            $users = User::get()->map(function ($user) use ($membersArray) {
                return !in_array($user->email, $membersArray) ? $user : null;
            })->filter()->toArray();
            if ($searchInput)
                $membersQuery = $this->searchForMember($searchInput, $membersQuery);
            $members = $membersQuery->paginate($recsPerPage);
            return view('team', compact('team', 'members', 'users'));
        } else {
            return $this->index($request);
        }
    }
    function searchForMember($searchInput, $membersQuery)
    {
        return $membersQuery->whereHas('user', function ($query) use ($searchInput) {
            $query->where('name', 'LIKE', '%' . $searchInput . '%')
                ->orWhere('email', 'LIKE', '%' . $searchInput . '%');
        });
    }
    public function toggleTeamAdmin(Request $request): RedirectResponse
    {
        $userTeamRecord = UsersTeam::where('team_id', $request->team_id)->where('user_id', $request->user_id)->first();
        if ($userTeamRecord->team_role == 'Member') {
            $userTeamRecord->team_role = 'Team Admin';
        } else {
            $userTeamRecord->team_role = 'Member';
        }
        $userTeamRecord->save();
        if (App::isLocale('en')) {
            return redirect()->back()->with('success', "User role changed successfully");
        } else {
            return redirect()->back()->with('نجاح', "تم تغير وظيفة العضو بالفريق بنجاح");
        }
    }
    public function removeTeamMember(Request $request): RedirectResponse
    {
        $userTeamRecord = UsersTeam::where('team_id', $request->team_id)->where('user_id', $request->user_id)->first();
        $userTeamRecord->delete();
        if (App::isLocale('en')) {
            return redirect()->back()->with('success', "User removed from the team successfully");
        } else {
            return redirect()->back()->with('نجاح', "تم حذف العضو من الفريق بنجاح");
        }
    }
    public function addTeamMember(Request $request): RedirectResponse
    {
        UsersTeam::create([
            'user_id' => $request->user_id,
            'team_id' => $request->team_id
        ]);
        if (App::isLocale('en')) {
            return redirect()->back()->with('success', "User added successfully");
        } else {
            return redirect()->back()->with('نجاح', "تم اضافة عضو الي الفريق بنجاح");
        }
    }
}
