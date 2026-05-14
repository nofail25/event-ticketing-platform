<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class AdminRoleController extends Controller
{
    // Core roles that should not be modified
    protected array $coreRoles = [
        'Super Admin',
        'Event Organizer',
        'Customer',
        'Gate Scanner'
    ];

    /**
     * Display a listing of the roles.
     */
    public function index()
    {
        $roles = Role::latest()->paginate(15);
        return view('admin.roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new role.
     */
    public function create()
    {
        return view('admin.roles.create');
    }

    /**
     * Store a newly created role in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
        ], [
            'name.unique' => 'This role already exists.',
        ]);

        Role::create($validated);

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role created successfully.');
    }

    /**
     * Show the form for editing the role.
     */
    public function edit(Role $role)
    {
        // Prevent editing core roles
        if (in_array($role->name, $this->coreRoles)) {
            return redirect()->route('admin.roles.index')
                ->with('danger', 'Core roles cannot be edited.');
        }

        return view('admin.roles.edit', compact('role'));
    }

    /**
     * Update the role in storage.
     */
    public function update(Request $request, Role $role)
    {
        // Safety guard: prevent modifying core roles
        if (in_array($role->name, $this->coreRoles)) {
            return redirect()->route('admin.roles.index')
                ->with('danger', 'Core roles cannot be modified.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
        ], [
            'name.unique' => 'This role name is already in use.',
        ]);

        $role->update($validated);

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role updated successfully.');
    }

    /**
     * Remove the role from storage.
     */
    public function destroy(Role $role)
    {
        // Safety guard: prevent deleting core roles
        if (in_array($role->name, $this->coreRoles)) {
            return redirect()->route('admin.roles.index')
                ->with('danger', 'Core roles cannot be deleted.');
        }

        // Optional: Check if role is assigned to any users
        if ($role->users()->exists()) {
            return redirect()->route('admin.roles.index')
                ->with('warning', 'This role is assigned to users and cannot be deleted.');
        }

        $role->delete();

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role deleted successfully.');
    }
}
