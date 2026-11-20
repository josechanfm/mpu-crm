<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\AdminUser;
use App\Models\Department;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    public function index()
    {
        return Inertia::render('Master/AdminUsers', [
            'adminUsers'  => AdminUser::with(['departments', 'roles', 'permissions'])->get(),
            'roles'       => Role::where('guard_name', 'admin')->get(),
            'permissions' => Permission::where('guard_name', 'admin')->get(), 
            'departments' => Department::all()
        ]);
    }

    public function store(Request $request)
    {
        // 1. Validate required fields
        $request->validate([
            'username'           => 'required|string|unique:admin_users,username',
            'email'              => 'required|email|unique:admin_users,email',
            'name'               => 'required|string',
            'password'           => 'required|string|min:6',
            'belong_departments' => 'array',
            'belong_departments.*' => 'exists:departments,name', // or 'id' depending on your form values
        ]);

        // 2. Safely extract only user table columns
        $data = $request->only(['username', 'name', 'email']);
        
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        // 3. Create the user
        $adminUser = AdminUser::create($data);

        // 4. Sync Departments
        if ($request->has('belong_departments')) {
            $adminUser->departments()->sync(
                Department::whereIn('name', $request->belong_departments)->pluck('id')
            );
        } else {
            $adminUser->departments()->sync([]);
        }

        // 5. Sync Roles (Filtered by 'admin' guard)
        $manageDepartments = $request->input('manage_departments', []);
        if (!empty($manageDepartments)) {
            $roles = Role::whereIn('name', $manageDepartments)
                ->where('guard_name', 'admin')
                ->get();
            $adminUser->syncRoles($roles);
        } else {
            $adminUser->syncRoles([]);
        }

        // 6. Sync Direct Permissions (Filtered by 'admin' guard)
        $directPermissions = $request->input('direct_permissions', []);
        if (!empty($directPermissions)) {
            $permissions = Permission::whereIn('name', $directPermissions)
                ->where('guard_name', 'admin')
                ->get();
            $adminUser->syncPermissions($permissions);
        } else {
            $adminUser->syncPermissions([]);
        }

        return redirect()->back()->with('success', 'User created successfully.');
    }

    public function update(Request $request, AdminUser $adminUser)
    {
        // 1. Validate the incoming request fields
        $request->validate([
            'username'           => 'required|string|unique:admin_users,username,' . $adminUser->id,
            'email'              => 'required|email|unique:admin_users,email,' . $adminUser->id,
            'name'               => 'required|string',
            'belong_departments' => 'array',
        ]);

        // 2. Extract only the allowed user table columns to update
        $data = $request->only(['username', 'name', 'email']);
        
        // Only update the password if a new one was provided and not empty
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        // 3. Update core user details
        $adminUser->update($data);

        // 4. Sync Departments
        if ($request->has('belong_departments')) {
            $adminUser->departments()->sync(
                $request->belong_departments
            );
        } else {
            $adminUser->departments()->sync([]);
        }

        // 5. Sync Roles (Filtered by 'admin' guard)
        $manageDepartments = $request->input('manage_departments', []);
        if (!empty($manageDepartments)) {
            $roles = Role::whereIn('name', $manageDepartments)
                ->where('guard_name', 'admin')
                ->get();
            $adminUser->syncRoles($roles);
        } else {
            $adminUser->syncRoles([]);
        }

        // 6. Sync Direct Permissions (Filtered by 'admin' guard)
        $directPermissions = $request->input('direct_permissions', []);
        if (!empty($directPermissions)) {
            $permissions = Permission::whereIn('name', $directPermissions)
                ->where('guard_name', 'admin')
                ->get();
            $adminUser->syncPermissions($permissions);
        } else {
            $adminUser->syncPermissions([]);
        }

        return redirect()->back()->with('success', 'User updated successfully.');
    }
}