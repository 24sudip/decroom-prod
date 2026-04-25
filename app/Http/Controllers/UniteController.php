<?php
namespace App\Http\Controllers;

use App\Unite;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class UniteController extends Controller {
    private function user() {
        return Auth::user();
    }

    public function index(Request $request) {
        Gate::authorize('index-unit');

        $user  = $this->user();
        $role  = optional($user->role)->role_name;
        $units = Unite::orderByDesc('id')->get();

        if ($request->ajax()) {
            return DataTables::of($units)
                ->addIndexColumn()
                ->addColumn('image', function ($row) {
                    $imageUrl = $row->image ? asset('storage/' . $row->image) : asset('assets/no-image.png');

                    return '<img src="' . $imageUrl . '" alt="unit-image" width="60" height="60" class="img-thumbnail"/>';
                })
                ->addColumn('description', function ($row) {
                    return Str::limit(strip_tags($row->description), 150);
                })
                ->addColumn('option', function ($row) use ($role) {

                    return '
                            <a href="' . route('unit.edit', $row->id) . '">
                                <button class="btn btn-primary btn-sm btn-rounded" title="Update unit">
                                    <i class="mdi mdi-lead-pencil"></i>
                                </button>
                            </a>
                            <button class="btn btn-danger btn-sm btn-rounded" title="Delete unit" data-id="' . $row->id . '" id="delete-unit">
                                <i class="mdi mdi-trash-can"></i>
                            </button>';

                })
                ->rawColumns(['image', 'option'])
                ->make(true);
        }

        return view('backend.unit.unit', compact('user', 'role'));
    }

    public function create() {
        Gate::authorize('create-unit');

        $user = $this->user();
        $role = optional($user->role)->role_name;
        $unit = null;

        return view('backend.unit.unit-details', compact('user', 'role', 'unit'));
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'name'        => 'required|string|max:250',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            DB::beginTransaction();

            $validated['slug'] = Str::slug($validated['name']);

            if ($request->hasFile('image')) {
                $validated['image'] = $request->file('image')->store('units', 'public');
            }

            Unite::create($validated);

            DB::commit();

            return redirect()->route('unit.index')->with('success', 'Unit created successfully!');
        } catch (Exception $e) {
            DB::rollBack();

            return redirect()->route('unit.index')->with('error', 'Something went wrong! ' . $e->getMessage());
        }

    }

    public function show($id) {
        $unit = Unite::findOrFail($id);

        return view('backend.unit.show', compact('unit'));
    }

    public function edit(Unite $unit) {
        Gate::authorize('update-unit');

        $user = $this->user();
        $role = optional($user->role)->role_name;

        return view('backend.unit.unit-details', compact('user', 'role', 'unit'));
    }

    public function update(Request $request, Unite $unit) {
        $validated = $request->validate([
            'name'        => 'required|string|max:250',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            DB::beginTransaction();

            if ($request->hasFile('image')) {
                $validated['image'] = $request->file('image')->store('units', 'public');
            }

            $validated['slug'] = Str::slug($validated['name']);

            $unit->update($validated);

            DB::commit();

            return redirect()->route('unit.index')->with('success', 'Unit updated successfully!');
        } catch (Exception $e) {
            DB::rollBack();

            return redirect()->route('unit.index')->with('error', 'Something went wrong! ' . $e->getMessage());
        }

    }

    public function destroy(Request $request) {
        Gate::authorize('delete-unit');

        $user = $this->user();

        if (optional($user->role)->role_name !== 'admin') {
            return response()->json([
                'isSuccess' => false,
                'message'   => 'You have no permission to delete unit',
                'data'      => [],
            ], 403);
        }

        try {
            $unit = Unite::find($request->id);

            if (!$unit) {
                return response()->json([
                    'isSuccess' => false,
                    'message'   => 'Unit not found.',
                    'data'      => [],
                ], 404);
            }

            $unit->delete();

            return response()->json([
                'isSuccess' => true,
                'message'   => 'Unit deleted successfully.',
                'data'      => $unit,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'isSuccess' => false,
                'message'   => 'Something went wrong! ' . $e->getMessage(),
                'data'      => [],
            ], 500);
        }

    }

}
