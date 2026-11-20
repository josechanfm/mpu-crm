<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\Department;

class RolePermissionController extends Controller
{
    // Load the single page with datasets including departments
    public function index()
    {
        return Inertia::render('Master/RolesPermissions', [
            'roles'       => Role::with(['permissions'])->get(),
            'permissions' => Permission::all(),
            'departments' => Department::all()
        ]);
    }

    // ==========================================
    // ROLE METHODS
    // ==========================================

    public function storeRole(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|unique:roles,name,NULL,id,guard_name,admin',
            'permissions'   => 'array',
            'permissions.*' => 'exists:permissions,id',
            // 'departments'   => 'array',
            // 'departments.*' => 'exists:departments,id'
        ]);

        $role = Role::create([
            'name'       => $request->name,
            'guard_name' => 'admin'
        ]);

        // Sync permissions matching the 'admin' guard
        if ($request->has('permissions')) {
            $permissions = Permission::whereIn('id', $request->permissions)
                ->where('guard_name', 'admin')
                ->get();
                
            $role->syncPermissions($permissions);
        }

        // Sync departments if your Role model has a departments() relationship
        // if ($request->has('departments')) {
        //     $role->departments()->sync($request->departments);
        // } else {
        //     $role->departments()->sync([]);
        // }

        return back()->with('success', 'Role created successfully.');
    }

    public function updateRole(Request $request, Role $role)
    {
        if ($role->name === 'Super Admin') {
            return back()->with('error', 'Cannot modify Super Admin role.');
        }

        $request->validate([
            'name'          => 'required|string|unique:roles,name,' . $role->id . ',id,guard_name,' . $role->guard_name,
            'permissions'   => 'array',
            'permissions.*' => 'exists:permissions,id',
            // 'departments'   => 'array',
            // 'departments.*' => 'exists:departments,id'
        ]);

        $role->update(['name' => $request->name]);

        // Sync permissions matching the role's guard
        if ($request->has('permissions')) {
            $permissions = Permission::whereIn('id', $request->permissions)
                ->where('guard_name', $role->guard_name)
                ->get();
                
            $role->syncPermissions($permissions);
        } else {
            $role->syncPermissions([]);
        }

        // Sync departments
        // if ($request->has('departments')) {
        //     $role->departments()->sync($request->departments);
        // } else {
        //     $role->departments()->sync([]);
        // }

        return back()->with('success', 'Role updated successfully.');
    }

    public function destroyRole(Role $role)
    {
        if ($role->name === 'Super Admin') {
            return back()->with('error', 'Cannot delete Super Admin role.');
        }

        $role->delete();
        return back()->with('success', 'Role deleted successfully.');
    }

    // ==========================================
    // PERMISSION METHODS
    // ==========================================

    public function storePermission(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:permissions,name',
        ]);

        Permission::create([
            'name'       => $request->name,
            'guard_name' => 'admin',
        ]);

        return back()->with('success', 'Permission created.');
    }

    public function updatePermission(Request $request, Permission $permission)
    {
        $request->validate([
            'name' => 'required|string|unique:permissions,name,' . $permission->id,
        ]);

        $permission->update([
            'name'       => $request->name,
            'guard_name' => 'admin',
        ]);

        return back()->with('success', 'Permission updated.');
    }

    public function destroyPermission(Permission $permission)
    {
        $permission->delete();
        return back()->with('success', 'Permission deleted.');
    }
}