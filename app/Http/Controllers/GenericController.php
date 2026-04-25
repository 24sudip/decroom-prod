<?php
namespace App\Http\Controllers;

use App\Generic;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Spatie\SimpleExcel\SimpleExcelReader;
use Yajra\DataTables\Facades\DataTables;

class GenericController extends Controller {
    private function user() {
        return Auth::user();
    }

    public function index(Request $request) {
        Gate::authorize('index-generic');

        $user = $this->user();
        $role = optional($user->role)->role_name;

        $generics = Generic::orderByDesc('id')->get();

        if ($request->ajax()) {
            return DataTables::of($generics)
                ->addIndexColumn()

            // Truncated Description Column
                ->addColumn('description', function ($row) {
                    return Str::limit(strip_tags($row->description), 150);
                })

            // Action Buttons Column
                ->addColumn('option', function ($row) use ($role) {

                    return '
                        <a href="' . route('generic.edit', $row->id) . '">
                            <button class="btn btn-primary btn-sm btn-rounded" title="Update generic">
                                <i class="mdi mdi-lead-pencil"></i>
                            </button>
                        </a>
                        <button class="btn btn-danger btn-sm btn-rounded" title="Deactivate generic" data-id="' . $row->id . '" id="delete-generic">
                            <i class="mdi mdi-trash-can"></i>
                        </button>';

                })

                ->rawColumns(['option'])
                ->make(true);
        }

        return view('backend.generic.generic', compact('user', 'role'));
    }

    public function create() {
        Gate::authorize('create-generic');

        $user    = $this->user();
        $role    = optional($user->role)->role_name;
        $generic = null;

        return view('backend.generic.generic-details', compact('user', 'role', 'generic'));
    }

    public function store(Request $request) {
        $user = $this->user();

        $validated = $request->validate([
            'name'        => 'required|string|max:250',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            DB::beginTransaction();

            $validated['slug'] = Str::slug($validated['name']);

            if ($request->hasFile('image')) {
                $validated['image'] = $request->file('image')->store('generics', 'public');
            }

            Generic::create($validated);

            DB::commit();

            return redirect()->route('generic.index')->with('success', 'Generic created successfully!');
        } catch (Exception $e) {
            DB::rollBack();

            return redirect()->route('generic.index')->with('error', 'Something went wrong! ' . $e->getMessage());
        }

    }

    public function show($id) {
        $generic = Generic::findOrFail($id);

        return view('backend.generic.show', compact('generic'));
    }

    public function edit(Generic $generic) {
        Gate::authorize('update-generic');

        $user = $this->user();
        $role = optional($user->role)->role_name;

        return view('backend.generic.generic-details', compact('user', 'role', 'generic'));
    }

    public function update(Request $request, Generic $generic) {
        $user = $this->user();

        $validated = $request->validate([
            'name'        => 'required|string|max:250',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            DB::beginTransaction();

            if ($request->hasFile('image')) {
                $validated['image'] = $request->file('image')->store('generics', 'public');
            }

            $generic->update($validated);

            DB::commit();

            return redirect()->route('generic.index')->with('success', 'Generic updated successfully!');
        } catch (Exception $e) {
            DB::rollBack();

            return redirect()->route('generic.index')->with('error', 'Something went wrong! ' . $e->getMessage());
        }

    }

    public function destroy(Request $request) {
        Gate::authorize('delete-generic');

        $user = $this->user();

        try {
            $generic = Generic::find($request->id);

            if (!$generic) {
                return response()->json([
                    'isSuccess' => false,
                    'message'   => 'Generic not found.',
                    'data'      => [],
                ], 404);
            }

            $generic->delete();

            return response()->json([
                'isSuccess' => true,
                'message'   => 'Generic deleted successfully.',
                'data'      => $generic,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'isSuccess' => false,
                'message'   => 'Something went wrong! ' . $e->getMessage(),
                'data'      => [],
            ], 500);
        }

    }
    
    

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,csv',
        ]);
    
        try {
            DB::beginTransaction();
    
            $path = $request->file('file')->store('temp');
            $rows = SimpleExcelReader::create(storage_path("app/{$path}"))->getRows();
    
            $now = now();
            $insertData = [];
    
            foreach ($rows as $row) {
                if (empty($row['name'])) {
                    continue;
                }
    
                $insertData[] = [
                    'name'       => trim($row['name']),
                    'slug'       => Str::slug($row['name']),
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
    
            if (!empty($insertData)) {
                // insert while avoiding duplicates
                foreach ($insertData as $data) {
                    Generic::updateOrCreate(
                        ['slug' => $data['slug']],
                        $data
                    );
                }
            }
    
            DB::commit();
            return redirect()->route('generic.index')->with('success', 'Generics imported successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('generic.index')->with('error', 'Import failed: ' . $e->getMessage());
        }
    }


}
