<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserSession;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserManagementController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:user.view', only: ['index']),
            new Middleware('permission:user.edit', only: ['edit', 'update']),
            new Middleware('permission:user.create', only: ['create', 'store']),
            new Middleware('permission:user.delete', only: ['destroy']),
            new Middleware('permission:user.view', only: ['sessions']),
            new Middleware('permission:user.edit', only: ['destroySession', 'destroyOtherSessions']),
        ];
    }
    public function index()
    {
        $users = User::with('roles')->latest()->paginate(10);
        return view('backend.pages.users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('backend.pages.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|exists:roles,name',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Role assign করা
        $user->assignRole($request->role);

        return redirect()->route('system.users.index')->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        return view('backend.pages.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed', // Password is optional
            'role' => 'required|exists:roles,name',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        $user->syncRoles($request->role);

        return redirect()->route('system.users.index')->with('success', 'User updated successfully.');
    }

    public function sessions(Request $request, User $user)
    {
        $sessions = UserSession::query()
            ->where('user_id', $user->id)
            ->latest('last_activity')
            ->paginate(15);

        return view('backend.pages.users.sessions', [
            'user' => $user,
            'sessions' => $sessions,
            'currentSessionId' => $request->session()->getId(),
        ]);
    }

    public function destroySession(Request $request, User $user, UserSession $session)
    {
        abort_unless((int) $session->user_id === (int) $user->id, 404);

        $session->delete();

        if ($session->getKey() === $request->session()->getId()) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->with('success', 'The current session has been logged out.');
        }

        return back()->with('success', 'Session logged out successfully.');
    }

    public function destroyOtherSessions(Request $request, User $user)
    {
        UserSession::query()
            ->where('user_id', $user->id)
            ->where('id', '!=', $request->session()->getId())
            ->delete();

        return back()->with('success', 'All other sessions have been logged out successfully.');
    }

    public function destroy(User $user)
    {
        if (Auth::id() == $user->id) {
            return back()->with('error', 'You cannot delete your own account.');
        }
        $user->delete();
        return redirect()->route('system.users.index')->with('success', 'User deleted successfully.');
    }
}
