<?php
namespace App\Http\Controllers;

use App\PaymentMethod;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Spatie\SimpleExcel\SimpleExcelReader;
use Yajra\DataTables\Facades\DataTables;

class PaymentMethodController extends Controller {
    
    private function user() {
        return Auth::guard('admin')->user();
    }

    public function index(Request $request) {

        $user = $this->user();
        $role = optional($user->role)->slug;

        $methods = PaymentMethod::orderByDesc('id')->get();

        if ($request->ajax()) {
            return DataTables::of($methods)
                ->addIndexColumn()
                ->addColumn('logo', function ($row) {
                    $imageUrl = $row->logo ? asset('uploads/paymentmethod/' . $row->logo) : asset('assets/no-image.png');

                    return '<img src="' . $imageUrl . '" alt="method-logo" width="60" height="60" class="img-thumbnail"/>';
                })
                ->addColumn('description', function ($row) {
                    return Str::limit(strip_tags($row->description), 150);
                })
                ->addColumn('option', function ($row) use ($role) {

                    return '
                            <a href="' . route('paymentmethod.edit', $row->id) . '">
                                <button class="btn btn-primary btn-sm btn-rounded" title="Update method">
                                    <i class="mdi mdi-lead-pencil"></i>
                                </button>
                            </a>
                            <button class="btn btn-danger btn-sm btn-rounded" title="Deactivate method" data-id="' . $row->id . '" id="delete-method">
                                <i class="mdi mdi-trash-can"></i>
                            </button>';

                })
                ->rawColumns(['logo', 'option'])
                ->make(true);
        }

        return view('backend.paymentmethod.method', compact('user', 'role'));
    }

    public function create()
    {
        $user = $this->user();
        $role = optional($user->role)->slug;
        $method = null; // ensures view has variable
    
        return view('backend.paymentmethod.method-details', compact('user', 'role', 'method'));
    }


    public function store(Request $request) {
        $validated = $request->validate([
            'title'        => 'required|string|max:250',
            'description' => 'nullable|string',
            'logo'       => 'nullable|image|mimes:jpeg,png,jpg,gif,webp',
        ]);

        try {
            DB::beginTransaction();

            $validated['slug'] = Str::slug($validated['title']);

            if ($request->hasFile('logo')) {
                $file      = $request->file('logo');
                $ext       = $file->getClientOriginalExtension();
                $imageName = time() . '.' . $ext;
                $file->move(public_path('uploads/paymentmethod/'), $imageName);
                $validated['logo'] = $imageName;
            }

            PaymentMethod::create($validated);

            DB::commit();

            return redirect()->route('paymentmethod.index')->with('success', 'Payment method created successfully!');
        } catch (Exception $e) {
            DB::rollBack();

            return redirect()->route('paymentmethod.index')->with('error', 'Something went wrong! ' . $e->getMessage());
        }

    }

    public function show($id) {
        $method = PaymentMethod::findOrFail($id);

        return view('backend.paymentmethod.show', compact('method'));
    }

    public function edit($id)
    {
        $user = $this->user();
        $role = optional($user->role)->slug;
        
        $method = PaymentMethod::findOrFail($id);
    
        return view('backend.paymentmethod.method-details', compact('user', 'role', 'method'));
    }


    public function update(Request $request, $id) {
        $validated = $request->validate([
            'title'        => 'required|string|max:250',
            'description' => 'nullable|string',
            'logo'       => 'nullable|image|mimes:jpeg,png,jpg,gif,webp',
        ]);

        try {
            DB::beginTransaction();

            $method = PaymentMethod::findOrFail($id);
            
            if ($request->hasFile('logo')) {
                $file      = $request->file('logo');
                $ext       = $file->getClientOriginalExtension();
                $imageName = time() . '.' . $ext;
                $file->move(public_path('uploads/paymentmethod/'), $imageName);
                $validated['logo'] = $imageName;
            }

            $method->update($validated);

            DB::commit();

            return redirect()->route('paymentmethod.index')->with('success', 'Payment Method updated successfully!');
        } catch (Exception $e) {
            DB::rollBack();

            return redirect()->route('paymentmethod.index')->with('error', 'Something went wrong! ' . $e->getMessage());
        }

    }

    public function destroy(Request $request) {

        try {
            $method = PaymentMethod::find($request->id);

            if (!$method) {
                return response()->json([
                    'isSuccess' => false,
                    'message'   => 'Method not found.',
                    'data'      => [],
                ], 404);
            }

            $method->delete();

            return response()->json([
                'isSuccess' => true,
                'message'   => 'Method deleted successfully.',
                'data'      => $method,
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
