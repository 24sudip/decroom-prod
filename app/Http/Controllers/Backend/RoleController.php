<?php
namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoleStoreRequest;
use App\Http\Requests\RoleUpdateRequest;
use App\Module;
use App\Role;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class RoleController extends Controller {
    public function index() {
        Gate::authorize('index-role');

        $roles = Role::with('permissions:id,permission_name,permission_slug')->select(['id', 'role_name', 'role_slug', 'is_deletable', 'role_note', 'updated_at'])->latest()->get();

        return view('backend.role.index', compact('roles'));
    }

    public function create() {
        Gate::authorize('create-role');

        $modules = Module::with(['permissions:id,module_id,permission_name,permission_slug'])->select('id', 'module_name')->get();

        return view('backend.role.create', compact('modules'));
    }

    public function store(RoleStoreRequest $request) {
        Gate::authorize('create-role');

        Role::updateOrCreate([
            'role_name' => $request->role_name,
            'role_slug' => Str::slug($request->role_name),
            'role_note' => $request->role_note,
        ])->permissions()->sync($request->input('permissions', []));

        Toastr::success('Role Created Successfully!');

        return redirect()->route('role.index');
    }

    public function show(string $id) {
        //
    }

    public function edit(string $id) {
        Gate::authorize('update-role');

        $role    = Role::find($id);
        $modules = Module::with(['permissions:id,module_id,permission_name,permission_slug'])->select('id', 'module_name')->get();

        return view('backend.role.edit', compact('role', 'modules'));
    }

    public function update(RoleUpdateRequest $request, string $id) {
        Gate::authorize('update-role');

        $role = Role::find($id);
        $role->update([
            'role_name' => $request->role_name,
            'role_slug' => Str::slug($request->role_name),
            'role_note' => $request->role_note,
        ]);

        $role->permissions()->sync($request->input('permissions', []));

        Toastr::success('Role Updated Successfully!');

        return redirect()->route('role.index');
    }

    public function destroy(string $id) {
        Gate::authorize('delete-role');

        $role = Role::find($id);

        if ($role->is_deletable) {
            $role->delete();

            Toastr::success('Role Deleted Successfully!');

            return redirect()->route('role.index');
        } else {

            return redirect()->route('role.index');
        }

    }

}
