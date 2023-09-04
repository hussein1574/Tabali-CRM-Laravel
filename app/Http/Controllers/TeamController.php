<?php

namespace App\Http\Controllers;


use id;
use App\Models\Team;
use App\Models\User;
use App\Models\UsersTeam;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class TeamController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        if (!$user) return redirect('/');
        $teams = '';
        if ($user->role === 'Admin') {
            $teams = Team::paginate(9);
        } else {
            $teamIds = $user->userTeams()->pluck('team_id');
            $teams = Team::whereIn('id', $teamIds)->paginate(9);
        }
        return view('teams', compact('teams'));
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
            return redirect()->route('teams')->with('success', 'Team added successfully.');
        } else {
            return redirect()->route('teams')->with('error', 'Failed to add team.');
        }
    }
    public function delete(Request $request): RedirectResponse
    {
        $team = Team::where('id', $request->team_id)->first();

        if ($team) {
            $team->delete();
            return redirect()->route('teams')->with('success', 'Team deleted successfully.');
        } else {
            return redirect()->route('teams')->with('error', 'Failed to delete team.');
        }
    }
    public function search(Request $request): View
    {
        $user = $request->user();
        if (!$user) return redirect('/');
        $searchMessage = $request->search_text;
        $teams = '';
        if ($user->role === 'Admin') {
            $teams = Team::where('name', 'LIKE', '%' . $searchMessage . '%')->paginate(9);
        } else {
            $teamIds = $user->userTeams()->pluck('team_id');
            $teams = Team::whereIn('id', $teamIds)->where('name', 'LIKE', '%' . $searchMessage . '%')->paginate(9);
        }
        return view('teams', compact('teams', 'searchMessage'));
    }
    public function teamIndex(Request $request): View
    {
        $team = Team::where('id', $request->query('id'))->first();
        if ($team) {
            $teamArray = $team->toArray();
            $members = $team->teamUsers()->paginate(9);
            $membersArray = $team->teamUsers()->with('user')->get()->pluck('user.email', 'user.id')->toArray();
            $team = $teamArray;
            $users = User::get()->map(function ($user) use ($membersArray) {
                return !in_array($user->email, $membersArray) ? $user : null;
            })->filter()->toArray();
            return view('team', compact('team', 'members', 'users'));
        } else {
            return $this->index($request);
        }
    }
    public function toogleTeamAdmin(Request $request): RedirectResponse
    {
        $userTeamRecord = UsersTeam::where('team_id', $request->team_id)->where('user_id', $request->user_id)->first();
        if ($userTeamRecord->team_role == 'Member') {
            $userTeamRecord->team_role = 'Team Admin';
        } else {
            $userTeamRecord->team_role = 'Member';
        }
        $userTeamRecord->save();
        return redirect()->back()->with('success', "User role changed successfully");
    }
    public function removeTeamMember(Request $request): RedirectResponse
    {
        $userTeamRecord = UsersTeam::where('team_id', $request->team_id)->where('user_id', $request->user_id)->first();
        $userTeamRecord->delete();
        return redirect()->back()->with('success', "User removed from the team successfully");
    }
    public function addTeamMember(Request $request): RedirectResponse
    {
        UsersTeam::create([
            'user_id' => $request->user_id,
            'team_id' => $request->team_id
        ]);

        return redirect()->back()->with('success', "User added successfully");
    }
    public function searchMembers(Request $request): View
    {
        $searchMessage = $request->search_text;
        $team = Team::where('id', $request->team_id)->first();
        if ($team) {
            $teamArray = $team->toArray();
            $members = $team->teamUsers()
                ->whereHas('user', function ($query) use ($searchMessage) {
                    $query->where('name', 'LIKE', '%' . $searchMessage . '%')
                        ->orWhere('email', 'LIKE', '%' . $searchMessage . '%');
                })
                ->paginate(9);
            $membersArray = $team->teamUsers()->with('user')->get()->pluck('user.email', 'user.id')->toArray();
            $team = $teamArray;
            $users = User::get()->map(function ($user) use ($membersArray) {
                return !in_array($user->email, $membersArray) ? $user : null;
            })->filter()->toArray();
            return view('team', compact('team', 'members', 'users', 'searchMessage'));
        } else {
            return $this->index($request);
        }
    }
}
