<?php
namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\ModuleStoreRequest;
use App\Module;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class ModuleController extends Controller {
    public function index() {
        Gate::authorize('index-module');

        $modules = Module::select(['id', 'module_name', 'module_slug', 'updated_at'])->latest()->get();

        return view('backend.module.index', compact('modules'));
    }

    public function create() {
        Gate::authorize('create-module');

        return view('backend.module.create');
    }

    public function store(ModuleStoreRequest $request) {
        Gate::authorize('create-module');

        Module::updateOrCreate([
            'module_name' => $request->module_name,
            'module_slug' => Str::slug($request->module_name),
        ]);

        Toastr::success('Module Created Successfully!');

        return redirect()->route('module.index');
    }

    public function show(string $id) {
        //
    }

    public function edit(string $id) {
        Gate::authorize('edit-module');

        $module = Module::find($id);

        return view('backend.module.edit', compact('module'));
    }

    public function update(ModuleStoreRequest $request, string $id) {
        Gate::authorize('edit-module');

        $module = Module::find($id);
        $module->update([
            'module_name' => $request->module_name,
            'module_slug' => Str::slug($request->module_name),
        ]);

        Toastr::success('Module Updated Successfully!');

        return redirect()->route('module.index');
    }

    public function destroy(string $id) {
        Gate::authorize('delete-module');

        $module = Module::find($id);
        $module->delete();

        Toastr::success('Module Deleted Successfully!');

        return redirect()->route('module.index');
    }
}
