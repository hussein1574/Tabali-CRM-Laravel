<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rules\Password;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $dashboardController = new DashboardController();
        $recsPerPage = config('constants.RECS_PER_PAGE');
        $userIsAdmin = $request->user()->role == 'Admin';
        if (!$userIsAdmin) return $dashboardController->index($request);
        $users = User::paginate($recsPerPage);;
        return view('users', compact('users'));
    }
    public function edit(Request $request): RedirectResponse
    {
        $user = User::where('id', $request->user_id)->first();
        if ($user) {
            $credentials = $request->validate([
                'name' => ['required', 'min:5', 'max:255'],
                'email' => ['required', 'email'],
            ]);
            if (!$user->name == $credentials['name'])
                $user->name = $credentials['name'];
            if (!$user->email == $credentials['email'])
                $user->email = $credentials['email'];
            $user->role = $request->role;
            $user->is_activated = $request->active == 'active' ? true : false;
            $user->save();
            return redirect()->route('users')->with('success', 'User updated successfully.');
        } else {
            return redirect()->route('users')->with('error', 'Failed to update user.');
        }
    }
    public function delete(Request $request): RedirectResponse
    {
        $user = User::where('id', $request->user_id)->first();

        if ($user) {
            $user->delete();
            return redirect()->route('users')->with('success', 'User deleted successfully.');
        } else {
            return redirect()->route('users')->with('error', 'Failed to delete user.');
        }
    }
    public function search(Request $request): View
    {
        $recsPerPage = config('constants.RECS_PER_PAGE');
        $userIsAdmin = $request->user()->role == 'Admin';
        if (!$userIsAdmin) return redirect('/');
        $searchMessage = $request->search_text;
        $users = User::where(function ($query) use ($searchMessage) {
            $query->where('name', 'LIKE', '%' . $searchMessage . '%')
                ->orWhere('email', 'LIKE', '%' . $searchMessage . '%');
        })->paginate($recsPerPage);
        return view('users', compact('users', 'searchMessage'));
    }
}
