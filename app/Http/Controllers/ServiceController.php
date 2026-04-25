<?php

namespace App\Http\Controllers;

use App\Service;
use App\ServiceCategory;
use App\Customer;
use App\Vendor;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Validator, Session};
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class ServiceController extends Controller
{
    /**
     * Display a listing of services.
     */
    public function index(Request $request)
    {
        // Start the query
        $query = Service::with(['category', 'vendor']);

        // Filter by status if provided
        if ($request->has('status') && $request->status && $request->status != 'rejected') {
            $query->where('status', $request->status);
        }
        // rejected
        if ($request->has('status') && $request->status == 'rejected') {
            $query->where('admin_reject', 1);
        }

        // Show only vendor's services if vendor is logged in
        if (Auth::guard('vendor')->check()) {
            $query->where('vendor_id', Auth::guard('vendor')->id());
        }

        $services = $query->latest()->paginate(30);

        return view('frontend.seller.services.index', compact('services'));
    }


    public function RejectService(Request $request, $id) {
        $service = Service::find($id);
        $service->update([
            'admin_reject' => 1,
            'status' => 'cancelled',
            'admin_approval' => 0,
        ]);
        return back()->with('success', 'Service rejected successfully.');
    }

    public function DeleteStatusChange(Request $request, $id) {
        $service = Service::find($id);
        $service->update([
            'delete_access' => $request->delete_access
        ]);
        return back()->with('success', 'Permission changed successfully.');
    }

    public function getServices(Request $request)
    {
        $categories = ServiceCategory::all();
        $vendors = User::where('role_id', 2)->get();

        // Start the query with filtering
        $query = Service::with(['category', 'vendor'])
        // ->where('admin_reject', 0)
        ;

        // Apply filters
        $query->when($request->category_id, function($q) use ($request) {
            return $q->where('category_id', $request->category_id);
        })
        ->when($request->vendor_id, function($q) use ($request) {
            return $q->where('vendor_id', $request->vendor_id);
        })
        ->when($request->search, function($q) use ($request) {
            return $q->where('title', 'like', '%' . $request->search . '%');
        })
        ->when($request->has('status') && $request->status, function($q) use ($request) {
            return $q->where('status', $request->status);
        });

        $services = $query->latest()->paginate(30);

        return view('backend.service.services', compact('services', 'categories', 'vendors'));
    }

    public function viewDetails($id)
    {
        $service = Service::with(['category', 'client', 'vendor.vendorDetails'])->findOrFail($id);
        // dd($service);
        return view('backend.service.view', compact('service'));
    }

    public function updateServiceStatus(Request $request)
    {
        \Log::info('Service Status Update Request:', $request->all());

        // Validate the request
        $request->validate([
            'id' => 'required|exists:services,id',
            'type' => 'required|in:status,admin_approval',
            'value' => 'required'
        ]);

        try {
            $service = Service::findOrFail($request->id);

            if ($request->type === 'status') {
                $validStatuses = ['in_process', 'response', 'on_hold', 'cancelled', 'complete', 'draft', 'records'];
                if (!in_array($request->value, $validStatuses)) {
                    throw new \Exception('Invalid status value');
                }
                $service->status = $request->value;
                $message = "Service status updated to " . ucfirst(str_replace('_', ' ', $request->value));
            } elseif ($request->type === 'admin_approval') {
                $service->admin_approval = $request->value;
                $message = $request->value ? "Service approved by admin" : "Service approval revoked";
            }

            $service->save();

            \Log::info('Service Status Update Successful:', [
                'service_id' => $service->id,
                'type' => $request->type,
                'value' => $request->value
            ]);

            return response()->json([
                'success' => true,
                'message' => $message
            ]);

        } catch (\Exception $e) {
            \Log::error('Service Status Update Failed:', [
                'error' => $e->getMessage(),
                'request' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Update failed: ' . $e->getMessage()
            ], 500);
        }
    }


    /**
     * Show the form for creating a new service.
     */
    public function create()
    {
        $vendor = Auth::guard('vendor')->user();
        if($vendor->is_active != 1) {
            $real_vendor = Vendor::where('user_id', $vendor->id)->first();
            if( !empty($real_vendor->email) ) {
                return redirect()->route('vendor.dashboard')->with('error', "Waiting for Admin Approval");
            }
            Session::put('user_id', $vendor->id);
            return redirect()->route('vendor.kyc')->with('error', "Please fill up this kyc form first");
        }
        $serviceCategories = ServiceCategory::where('status', 1)->get();
        $vendors = Vendor::where('status', 1)->get();

        return view('frontend.seller.services.create', compact('serviceCategories', 'vendors'));
    }

    /**
     * Store a newly created service.
     */
    public function store(Request $request)
    {
        \Log::info('Service Store Request:', $request->all());

        $rules = [
            'title' => 'required|string|max:255',
            'category_id' => 'nullable|exists:service_categories,id',
            'client_id' => 'nullable',
            'vendor_id' => 'nullable|exists:users,id',
            'delivery_duration' => 'nullable|string|max:255',
            'time_line' => 'nullable|string|max:255',
            'total_cost' => 'nullable|numeric|min:0',
            'material_cost' => 'nullable|numeric|min:0',
            'service_charge' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'installment' => 'nullable|in:0,1,2,3',
            'advance' => 'nullable|numeric|min:0',
            'mid' => 'nullable|numeric|min:0',
            'final' => 'nullable|numeric|min:0',
            'catalog' => 'nullable|file|mimes:pdf,jpg,png,docx|max:2048',
            'attachment' => 'nullable|file|mimes:pdf,jpg,png,docx|max:2048',
            'note' => 'nullable|string',
            'expire_duration' => 'required|integer|min:1|max:255',
            'status' => 'nullable|in:draft,in_progress,response,on_hold,cancelled,complete,record',
            'service_video' => 'nullable|file|mimetypes:video/mp4,video/webm,video/quicktime',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            \Log::error('Validation Failed:', $validator->errors()->toArray());
            return redirect()->back()
            ->withErrors($validator)
            ->withInput()
            ->with('error', 'Please fix the errors below.');
        }

        try {
            $data = $request->except(['catalog', 'attachment', '_token']);

            // Auto-set vendor_id if vendor is logged in
            if (Auth::guard('vendor')->check()) {
                $data['vendor_id'] = Auth::guard('vendor')->id();
                \Log::info('Vendor ID set to: ' . $data['vendor_id']);
            }

            // Set default values for nullable fields
            $data['organization'] = $data['organization'] ?? null;
            $data['client_id'] = $data['client_id'] ?? null;
            $data['total_cost'] = $data['total_cost'] ?? 0;
            $data['material_cost'] = $data['material_cost'] ?? 0;
            $data['service_charge'] = $data['service_charge'] ?? 0;
            $data['discount'] = $data['discount'] ?? 0;
            $data['installment'] = $data['installment'] ?? 0;
            $data['advance'] = $data['advance'] ?? 0;
            $data['mid'] = $data['mid'] ?? 0;
            $data['final'] = $data['final'] ?? 0;

            // Set status based on the button clicked
            $data['status'] = $request->has('save_draft') ? 'draft' : 'in_progress';

            \Log::info('Processed Data:', $data);

            // File upload handling
            if ($request->hasFile('catalog')) {
                $file = $request->file('catalog');
                $filename = time() . '_catalog_' . Str::slug($request->title) . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/service/catalogs'), $filename);
                $data['catalog'] = 'uploads/service/catalogs/' . $filename;
                \Log::info('Catalog uploaded: ' . $data['catalog']);
            }

            if ($request->hasFile('attachment')) {
                $file = $request->file('attachment');
                $filename = time() . '_attachment_' . Str::slug($request->title) . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/service/attachments'), $filename);
                $data['attachment'] = 'uploads/service/attachments/' . $filename;
                \Log::info('Attachment uploaded: ' . $data['attachment']);
            }

            if ($request->hasFile('service_video')) {
                $file = $request->file('service_video');
                $filename = time() . '_service_video_' . Str::slug($request->title) . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/service/service-video'), $filename);
                $data['service_video'] = 'uploads/service/service-video/' . $filename;
                \Log::info('Video uploaded: ' . $data['service_video']);
            }

            // Create the service
            $service = Service::create($data);
            \Log::info('Service created successfully with ID: ' . $service->id);

            $message = $request->has('save_draft')
                ? 'Service saved as draft successfully.'
                : 'Service created successfully.';

            return redirect()->route('services.index')->with('success', $message);

        } catch (\Exception $e) {
            \Log::error('Service Creation Error: ' . $e->getMessage());
            \Log::error('Stack Trace: ' . $e->getTraceAsString());

            return redirect()->back()
            ->withInput()
            ->with('error', 'Error creating service: ' . $e->getMessage());
        }
    }

    /**
     * Display a specific service.
     */
    public function show($id)
    {
        $service = Service::with(['category', 'client', 'vendor'])->findOrFail($id);
        return view('frontend.seller.services.show', compact('service'));
    }

    /**
     * Show the form for editing a service.
     */
    public function edit($id)
    {
        $service = Service::findOrFail($id);
        $serviceCategories = ServiceCategory::where('status', 1)->get();
        $vendors = Vendor::where('status', 1)->get();

        return view('frontend.seller.services.edit', compact('service', 'serviceCategories', 'vendors'));
    }

    /**
     * Update an existing service.
     */
    public function update(Request $request, $id)
    {
        $service = Service::findOrFail($id);

        $rules = [
            'title' => 'required|string|max:255',
            'category_id' => 'nullable|exists:service_categories,id',
            'client_id' => 'nullable',
            'vendor_id' => 'nullable|exists:users,id',
            'total_cost' => 'nullable|numeric|min:0',
            'material_cost' => 'nullable|numeric|min:0',
            'service_charge' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'catalog' => 'nullable|file|mimes:pdf,jpg,gif,webp,png,docx',
            'attachment' => 'nullable|file|mimes:pdf,jpg,png,gif,webp,docx',
            'expire_duration' => 'required|integer|min:1|max:255',
            // 'status' => 'nullable|in:draft,in_progress,response,on_hold,cancelled,complete,record',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $data = $request->except(['catalog', 'attachment']);

        // Replace old files if new uploaded
        if ($request->hasFile('catalog')) {
            // Delete old file if exists
            if ($service->catalog && file_exists(public_path($service->catalog))) {
                unlink(public_path($service->catalog));
            }

            $file = $request->file('catalog');
            $filename = time() . '_catalog_' . Str::slug($request->title) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/service/catalogs'), $filename);
            $data['catalog'] = 'uploads/service/catalogs/' . $filename;
        }

        if ($request->hasFile('attachment')) {
            // Delete old file if exists
            if ($service->attachment && file_exists(public_path($service->attachment))) {
                unlink(public_path($service->attachment));
            }

            $file = $request->file('attachment');
            $filename = time() . '_attachment_' . Str::slug($request->title) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/service/attachments'), $filename);
            $data['attachment'] = 'uploads/service/attachments/' . $filename;
        }

        if ($request->hasFile('service_video')) {
            // Delete old file if exists
            if ($service->service_video && file_exists(public_path($service->service_video))) {
                unlink(public_path($service->service_video));
            }

            $file = $request->file('service_video');
            $filename = time() . '_service_video_' . Str::slug($request->title) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/service/service-video'), $filename);
            $data['service_video'] = 'uploads/service/service-video/' . $filename;
        }
        $data['status'] = 'in_process';
        $data['admin_approval'] = 0;
        $service->update($data);

        return redirect()->route('services.index')->with('success', 'Service updated successfully.');
    }

    /**
     * Delete a service.
     */
    public function destroy($id)
    {
        $service = Service::findOrFail($id);
        if($service->delete_access != 1) {
            return redirect()->route('services.index')->with('error', 'You do not have Permission to delete Service.');
        }
        // Delete associated files
        if ($service->catalog && file_exists(public_path($service->catalog))) {
            unlink(public_path($service->catalog));
        }
        if ($service->attachment && file_exists(public_path($service->attachment))) {
            unlink(public_path($service->attachment));
        }
        if ($service->service_video && file_exists(public_path($service->service_video))) {
            unlink(public_path($service->service_video));
        }

        $service->delete();

        return redirect()->route('services.index')->with('success', 'Service deleted successfully.');
    }
}
