<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search', ''));

        $users = User::with('roles')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhereHas('roles', function ($query) use ($search) {
                            $query->where('name', 'like', "%{$search}%");
                        });
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.users.index', compact('users', 'search'));
    }

    /**
     * Show the form for editing the user's role.
     */
    public function edit(User $user)
    {
        $roles = Role::latest()->get();
        $userRole = $user->roles()->first();
        $organizers = User::role('Event Organizer')->get();

        return view('admin.users.edit', compact('user', 'roles', 'userRole', 'organizers'));
    }

    /**
     * Update the user's role in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'role' => 'required|string|exists:roles,name',
            'organizer_id' => 'nullable|exists:users,id',
        ]);

        if ($validated['role'] === 'Gate Scanner' && !empty($validated['organizer_id'])) {
            $organizerUser = User::find($validated['organizer_id']);
            if (!$organizerUser || !$organizerUser->hasRole('Event Organizer')) {
                return redirect()->back()->with('danger', 'Pengguna yang dipilih bukan Event Organizer.');
            }
        }

        // Prevent assigning Super Admin
        if ($validated['role'] === 'Super Admin') {
            return redirect()->back()->with('danger', 'Anda tidak dapat memberikan peran Super Admin kepada pengguna melalui panel ini.');
        }

        // Prevent changing existing Super Admin role
        if ($user->hasRole('Super Admin')) {
            return redirect()->back()->with('danger', 'Peran Super Admin tidak dapat diubah.');
        }

        // Sync the user's role (replaces existing roles)
        $user->syncRoles($validated['role']);

        // Only save organizer_id if role is Gate Scanner
        if ($validated['role'] === 'Gate Scanner') {
            $user->update(['organizer_id' => $validated['organizer_id'] ?? null]);
        } else {
            $user->update(['organizer_id' => null]);
        }

        return redirect()->route('admin.users.index')
            ->with('success', "Peran pengguna diperbarui menjadi '{$validated['role']}'.");
    }
}
