<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Validator};
use App\{ServiceDraft, Service};
use Illuminate\Support\Str;

class ServiceDraftController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('frontend.seller.service-draft.index', [
            'service_drafts' => ServiceDraft::where('vendor_user_id', Auth::guard('vendor')->id())->latest()->paginate(10)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('frontend.seller.service-draft.create', [
            'services' => Service::latest()->get(['id','title'])
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'service_id' => 'required|unique:service_drafts,service_id',
            'title' => 'required|string|max:255',
            'delivery_duration' => 'required',
            'project_cost' => 'required|numeric|min:0',
            'material_cost' => 'required|numeric|min:0',
            'service_charge' => 'required|numeric|min:0',
            'discount' => 'required|numeric|min:0',
            'service_video' => 'required|file|mimetypes:video/mp4,video/webm,video/quicktime',
        ];
        // |max:25600
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()
            ->withErrors($validator)
            ->withInput()
            ->with('error', 'Please fix the errors below.');
        }

        try {
            $data = $request->except(['service_video', '_token']);
            $data['vendor_user_id'] = Auth::guard('vendor')->id();
            if ($request->hasFile('service_video')) {
                $file = $request->file('service_video');
                $filename = time() . '_service_video_' . Str::slug($request->title) . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/service/draft-video'), $filename);
                $data['service_video'] = 'uploads/service/draft-video/' . $filename;
            }
            ServiceDraft::create($data);

            $message = 'Post Service created successfully.';

            return redirect()->route('service-draft.index')->with('success', $message);
        } catch (\Exception $e) {
            // \Log::error('Stack Trace: ' . $e->getTraceAsString());

            return redirect()->back()
            ->withInput()
            ->with('error', 'Error creating post service: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('frontend.seller.service-draft.edit', [
            'service_draft' => ServiceDraft::findOrFail($id),
            'services' => Service::latest()->get(['id','title'])
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $service_draft = ServiceDraft::findOrFail($id);
        $rules = [
            'service_id' => 'required|unique:service_drafts,service_id,' . $service_draft->id,
            'title' => 'required|string|max:255',
            'delivery_duration' => 'required',
            'project_cost' => 'required|numeric|min:0',
            'material_cost' => 'required|numeric|min:0',
            'service_charge' => 'required|numeric|min:0',
            'discount' => 'required|numeric|min:0',
            'service_video' => 'nullable|file|mimetypes:video/mp4,video/webm,video/quicktime',
        ];
        // |max:25600
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()
            ->withErrors($validator)
            ->withInput()
            ->with('error', 'Please fix the errors below.');
        }
        $data = $request->except(['service_video']);
        if ($request->hasFile('service_video')) {
            // Delete old file if exists
            if ($service_draft->service_video && file_exists(public_path($service_draft->service_video))) {
                unlink(public_path($service_draft->service_video));
            }

            $file = $request->file('service_video');
            $filename = time() . '_service_video_' . Str::slug($request->title) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/service/draft-video'), $filename);
            $data['service_video'] = 'uploads/service/draft-video/' . $filename;
        }

        $service_draft->update($data);

        return redirect()->route('service-draft.index')->with('success', 'Post Service updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $service_draft = ServiceDraft::findOrFail($id);
        if ($service_draft->service_video && file_exists(public_path($service_draft->service_video))) {
            unlink(public_path($service_draft->service_video));
        }
        $service_draft->delete();

        return redirect()->route('service-draft.index')->with('success', 'Post Service deleted successfully.');
    }
}
