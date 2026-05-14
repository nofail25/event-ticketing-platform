<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class AdminUserController extends Controller
{
    public function index()
    {
        $users = User::with('roles')
            ->latest()
            ->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for editing the user's role.
     */
    public function edit(User $user)
    {
        $roles = Role::latest()->get();
        $userRole = $user->roles()->first();

        return view('admin.users.edit', compact('user', 'roles', 'userRole'));
    }

    /**
     * Update the user's role in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'role' => 'required|string|exists:roles,name',
        ]);

        // Sync the user's role (replaces existing roles)
        $user->syncRoles($validated['role']);

        return redirect()->route('admin.users.index')
            ->with('success', "User role updated to '{$validated['role']}'.");
    }
}
