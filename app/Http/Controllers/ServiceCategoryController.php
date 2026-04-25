<?php

namespace App\Http\Controllers;

use App\ServiceCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;

class ServiceCategoryController extends Controller {

    private function user() {
        return Auth::guard('admin')->user();
    }

    public function index(Request $request) {
        // Gate::authorize('index-service-category');

        $user = $this->user();
        $role = $user ? optional($user->role)->role_name : 'Guest';

        $serviceCategories = ServiceCategory::orderBy('name', 'asc')->get();

        if ($request->ajax()) {
            return DataTables::of($serviceCategories)
                ->addIndexColumn()
                ->addColumn('image', function ($row) {
                    $imageUrl = $row->image ? asset('storage/service/' . $row->image) : asset('assets/no-image.png');
                    return '<img src="' . $imageUrl . '" alt="service-category-image" width="60" height="60" class="img-thumbnail"/>';
                })
                ->addColumn('status', function ($row) {
                    $status = $row->status ? 'Active' : 'Inactive';
                    $badgeClass = $row->status ? 'badge bg-success' : 'badge bg-danger';
                    return '<span class="' . $badgeClass . '">' . $status . '</span>';
                })
                ->addColumn('option', function ($row) use ($role) {
                    $editUrl = route('servicecategory.edit', $row->id);
                    $deleteBtn = '<button class="btn btn-danger btn-sm btn-rounded" title="Delete service category" data-id="' . $row->id . '" id="delete-service-category">
                                    <i class="mdi mdi-trash-can"></i>
                                </button>';

                    return '<a href="' . $editUrl . '">
                                <button class="btn btn-primary btn-sm btn-rounded" title="Edit service category">
                                    <i class="mdi mdi-lead-pencil"></i>
                                </button>
                            </a>
                            ' . $deleteBtn;
                })
                ->rawColumns(['image', 'status', 'option'])
                ->make(true);
        }

        return view('backend.servicecategory.manage', compact('user', 'role'));
    }
    public function create() {
        // Gate::authorize('create-service-category');

        return view('backend.servicecategory.create');
    }

    public function store(Request $request) {
        // Gate::authorize('create-service-category');

        $request->validate([
            'name'        => 'required|string|max:255|unique:service_categories,name',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif,webp',
        ]);

        $serviceCategory = new ServiceCategory();
        $serviceCategory->name = $request->name;
        $serviceCategory->status = $request->has('status') ? 1 : 0;

        // Slug will be auto-generated in model boot method

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $name = time() . '.' . $file->getClientOriginalExtension();
            $request->image->move(public_path('storage/service/'), $name);
            $serviceCategory->image = $name;
        }

        $serviceCategory->save();

        return redirect()->route('servicecategory.index')->with('success', 'Service category created successfully');
    }

    public function edit($id) {
        // Gate::authorize('edit-service-category');

        $serviceCategory = ServiceCategory::findOrFail($id);

        return view('backend.servicecategory.create', compact('serviceCategory'));
    }

    public function update(Request $request, $id) {
        // Gate::authorize('edit-service-category');

        $serviceCategory = ServiceCategory::findOrFail($id);

        $request->validate([
            'name'        => 'required|string|max:255|unique:service_categories,name,' . $id,
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif,webp',
        ]);

        $serviceCategory->name = $request->name;
        $serviceCategory->status = $request->has('status') ? 1 : 0;

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($serviceCategory->image && File::exists(public_path('storage/service/' . $serviceCategory->image))) {
                File::delete(public_path('storage/service/' . $serviceCategory->image));
            }

            $imageName = Str::slug($request->name) . '-' . time() . '.' . $request->image->extension();
            $request->image->move(public_path('storage/service/'), $imageName);
            $serviceCategory->image = $imageName;
        }

        $serviceCategory->save();

        return redirect()->route('servicecategory.index')->with('success', 'Service category updated successfully');
    }

    public function destroy($id) {
        // Gate::authorize('delete-service-category');

        $serviceCategory = ServiceCategory::findOrFail($id);

        // Delete category image
        if ($serviceCategory->image && Storage::disk('public')->exists('service/' . $serviceCategory->image)) {
            Storage::disk('public')->delete('service/' . $serviceCategory->image);
        }

        // Finally delete category
        $serviceCategory->delete();

        return response()->json([
            'success' => true,
            'message' => 'Service category deleted successfully!'
        ]);
    }

    public function toggleStatus(Request $request) {
        // Gate::authorize('edit-service-category');

        $serviceCategory = ServiceCategory::findOrFail($request->id);
        $serviceCategory->status = !$serviceCategory->status;
        $serviceCategory->save();

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully.',
            'status'  => $serviceCategory->status ? 1 : 0,
        ]);
    }
}
