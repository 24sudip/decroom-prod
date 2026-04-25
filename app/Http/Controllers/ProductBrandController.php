<?php
namespace App\Http\Controllers;

use App\ProductBrand;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Spatie\SimpleExcel\SimpleExcelReader;
use Yajra\DataTables\Facades\DataTables;

class ProductBrandController extends Controller {
    private function user() {
        return Auth::guard('admin')->user();
    }

    public function index(Request $request) {
        // Gate::authorize('index-brand');

        $user = $this->user();
        $role = optional($user->role)->slug;

        $brands = ProductBrand::orderByDesc('id')->get();

        if ($request->ajax()) {
            return DataTables::of($brands)
                ->addIndexColumn()
                ->addColumn('image', function ($row) {
                    $imageUrl = $row->image ? asset('public/storage/brands/' . $row->image) : asset('assets/no-image.png');

                    return '<img src="' . $imageUrl . '" alt="brand-image" width="60" height="60" class="img-thumbnail"/>';
                })
                ->addColumn('description', function ($row) {
                    return Str::limit(strip_tags($row->description), 150);
                })
                ->addColumn('option', function ($row) use ($role) {

                    return '
                            <a href="' . route('brand.edit', $row->id) . '">
                                <button class="btn btn-primary btn-sm btn-rounded" title="Update brand">
                                    <i class="mdi mdi-lead-pencil"></i>
                                </button>
                            </a>
                            <button class="btn btn-danger btn-sm btn-rounded" title="Deactivate brand" data-id="' . $row->id . '" id="delete-brand">
                                <i class="mdi mdi-trash-can"></i>
                            </button>';

                })
                ->rawColumns(['image', 'option'])
                ->make(true);
        }

        return view('backend.brand.brand', compact('user', 'role'));
    }

    public function create() {
        // Gate::authorize('create-brand');

        $user  = $this->user();
        $role  = optional($user->role)->slug;
        $brand = null;

        return view('backend.brand.brand-details', compact('user', 'role', 'brand'));
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'name'        => 'required|string|max:250',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        try {
            DB::beginTransaction();

            $validated['slug'] = Str::slug($validated['name']);

            if ($request->hasFile('image')) {
                $file      = $request->file('image');
                $ext       = $file->getClientOriginalExtension();
                $imageName = time() . '.' . $ext;
                $file->move(public_path('storage/brands/'), $imageName);
                $validated['image'] = $imageName;
            }

            ProductBrand::create($validated);

            DB::commit();

            return redirect()->route('brand.index')->with('success', 'Brand created successfully!');
        } catch (Exception $e) {
            DB::rollBack();

            return redirect()->route('brand.index')->with('error', 'Something went wrong! ' . $e->getMessage());
        }

    }

    public function show($id) {
        $brand = ProductBrand::findOrFail($id);

        return view('backend.brand.show', compact('brand'));
    }

    public function edit(ProductBrand $brand) {
        // Gate::authorize('update-brand');

        $user = $this->user();
        $role = optional($user->role)->slug;

        return view('backend.brand.brand-details', compact('user', 'role', 'brand'));
    }

    public function update(Request $request, ProductBrand $brand) {
        $validated = $request->validate([
            'name'        => 'required|string|max:250',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        try {
            DB::beginTransaction();

            if ($request->hasFile('image')) {
                $file      = $request->file('image');
                $ext       = $file->getClientOriginalExtension();
                $imageName = time() . '.' . $ext;
                $file->move(public_path('storage/brands/'), $imageName);
                $validated['image'] = $imageName;
            }

            $brand->update($validated);

            DB::commit();

            return redirect()->route('brand.index')->with('success', 'Brand updated successfully!');
        } catch (Exception $e) {
            DB::rollBack();

            return redirect()->route('brand.index')->with('error', 'Something went wrong! ' . $e->getMessage());
        }

    }

    public function destroy(Request $request) {
        // Gate::authorize('delete-brand');

        $user = $this->user();
        $role = optional($user->role)->slug;

        if ($role !== 'admin') {
            return response()->json([
                'isSuccess' => false,
                'message'   => 'You have no permission to delete brand',
                'data'      => [],
            ], 403);
        }

        try {
            $brand = ProductBrand::find($request->id);

            if (!$brand) {
                return response()->json([
                    'isSuccess' => false,
                    'message'   => 'Brand not found.',
                    'data'      => [],
                ], 404);
            }

            $brand->delete();

            return response()->json([
                'isSuccess' => true,
                'message'   => 'Brand deleted successfully.',
                'data'      => $brand,
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
                    ProductBrand::updateOrCreate(
                        ['slug' => $data['slug']],
                        $data
                    );
                }
            }
    
            DB::commit();
            return redirect()->route('brand.index')->with('success', 'ProductBrand imported successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('brand.index')->with('error', 'Import failed: ' . $e->getMessage());
        }
    }

}
