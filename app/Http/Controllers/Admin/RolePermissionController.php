<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

class RolePermissionController extends Controller
{
    /**
     * Display a listing of roles
     */
    public function index()
    {
        $this->authorize('view-roles');
        $roles = Role::with('permissions')->where('name', '!=', 'ADMIN')->orderBy('name', 'asc')->paginate(15);
        return view('admin.roles.list', compact('roles'));
    }

    /**
     * Show the form for creating a new role
     */
    public function create()
    {
        $this->authorize('create-roles');
        $permissions = Permission::orderBy('name', 'asc')->get();
        $groupedPermissions = $this->groupPermissions($permissions);
        return view('admin.roles.create', compact('permissions', 'groupedPermissions'));
    }

    /**
     * Store a newly created role
     */
    public function store(Request $request)
    {
        $this->authorize('create-roles');
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        DB::beginTransaction();
        try {
            $role = Role::create(['name' => strtoupper($request->name)]);

            if ($request->has('permissions') && is_array($request->permissions)) {
                $permissions = array_map('intval', $request->permissions);
                $role->syncPermissions($permissions);
            }

            DB::commit();
            return redirect()->route('roles.index')
                ->with('message', 'Role created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to create role: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified role
     */
    public function show($id)
    {
        $this->authorize('view-roles');
        $role = Role::with('permissions')->findOrFail($id);
        $users = $role->users()->paginate(15);
        return view('admin.roles.show', compact('role', 'users'));
    }

    /**
     * Show the form for editing the specified role
     */
    public function edit($id)
    {
        $this->authorize('edit-roles');
        $role = Role::with('permissions')->findOrFail($id);
        $permissions = Permission::orderBy('name', 'asc')->get();
        $groupedPermissions = $this->groupPermissions($permissions);
        $rolePermissions = $role->permissions->pluck('id')->toArray();

        return view('admin.roles.edit', compact('role', 'permissions', 'groupedPermissions', 'rolePermissions'));
    }

    /**
     * Group permissions by their resource (suffix)
     */
    private function groupPermissions($permissions)
    {
        $grouped = [];
        foreach ($permissions as $permission) {
            $parts = explode('-', $permission->name);
            $group = count($parts) > 1 ? ucfirst(end($parts)) : 'General';
            $grouped[$group][] = $permission;
        }
        return $grouped;
    }

    /**
     * Update the specified role
     */
    public function update(Request $request, $id)
    {
        $this->authorize('edit-roles');
        $role = Role::findOrFail($id);

        $request->validate([
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        DB::beginTransaction();
        try {

            if ($request->has('permissions') && is_array($request->permissions)) {
                $permissions = array_map('intval', $request->permissions);
                $role->syncPermissions($permissions);
            } else {
                $role->syncPermissions([]);
            }

            DB::commit();
            return redirect()->route('roles.index')
                ->with('message', 'Role updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to update role: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified role
     */
    public function destroy($id)
    {
        $this->authorize('delete-roles');
        $role = Role::findOrFail($id);

        // Prevent deletion of default roles
        if (in_array($role->name, ['ADMIN', 'USER'])) {
            return redirect()->route('roles.index')
                ->with('error', 'Cannot delete default system roles.');
        }

        $role->delete();

        return redirect()->route('roles.index')
            ->with('error', 'Role has been deleted successfully.');
    }

    /**
     * Fetch data for AJAX pagination/search
     */
    public function fetchData(Request $request)
    {
        if ($request->ajax()) { // removed View check here to allow public/semi-public fetching if needed, OR add it back. User asked for specific permissions.
            // Actually, fetchData is for list view, so 'view-roles' should be required.
            $this->authorize('view-roles');

            $sortBy = $request->get('sortby', 'name');
            $sortType = $request->get('sorttype', 'asc');
            $search = $request->get('query');

            $rolesQuery = Role::with('permissions');

            // Apply search
            if (!empty($search)) {
                $search = str_replace(" ", "%", $search);
                $rolesQuery->where('name', 'like', "%{$search}%");
            }

            // Pagination
            $page = $request->get('page', 1);
            if (!empty($search)) {
                $page = 1;
            }

            $roles = $rolesQuery
                ->orderBy($sortBy, $sortType)
                ->paginate(15, ['*'], 'page', $page);

            return response()->json([
                'data' => view('admin.roles.table', compact('roles'))->render()
            ]);
        }
    }

    /**
     * Display permissions listing
     */
    public function permissionsIndex()
    {
        $this->authorize('view-permissions');
        $permissions = Permission::orderBy('name', 'asc')->paginate(15);
        return view('admin.permissions.list', compact('permissions'));
    }

    /**
     * Create new permission
     */
    public function permissionsCreate()
    {
        $this->authorize('create-permissions');
        return view('admin.permissions.create');
    }

    /**
     * Store new permission
     */
    public function permissionsStore(Request $request)
    {
        $this->authorize('create-permissions');
        $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name',
        ]);

        Permission::create(['name' => strtolower($request->name)]);

        return redirect()->route('permissions.index')
            ->with('message', 'Permission created successfully.');
    }

    /**
     * Edit permission
     */
    public function permissionsEdit($id)
    {
        $this->authorize('edit-permissions');
        $permission = Permission::findOrFail($id);
        return view('admin.permissions.edit', compact('permission'));
    }

    /**
     * Update permission
     */
    public function permissionsUpdate(Request $request, $id)
    {
        $this->authorize('edit-permissions');
        $permission = Permission::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name,' . $id,
        ]);

        $permission->update(['name' => strtolower($request->name)]);

        return redirect()->route('permissions.index')
            ->with('message', 'Permission updated successfully.');
    }

    /**
     * Delete permission
     */
    public function permissionsDestroy($id)
    {
        $this->authorize('delete-permissions');
        $permission = Permission::findOrFail($id);
        $permission->delete();

        return redirect()->route('permissions.index')
            ->with('error', 'Permission has been deleted successfully.');
    }

    /**
     * Fetch permissions data for AJAX
     */
    public function permissionsFetchData(Request $request)
    {
        $this->authorize('view-permissions');
        if ($request->ajax()) {
            $sortBy = $request->get('sortby', 'name');
            $sortType = $request->get('sorttype', 'asc');
            $search = $request->get('query');

            $permissionsQuery = Permission::query();

            // Apply search
            if (!empty($search)) {
                $search = str_replace(" ", "%", $search);
                $permissionsQuery->where('name', 'like', "%{$search}%");
            }

            // Pagination
            $page = $request->get('page', 1);
            if (!empty($search)) {
                $page = 1;
            }

            $permissions = $permissionsQuery
                ->orderBy($sortBy, $sortType)
                ->paginate(15, ['*'], 'page', $page);

            return response()->json([
                'data' => view('admin.permissions.table', compact('permissions'))->render()
            ]);
        }
    }
}
