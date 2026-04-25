<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\{ServiceDraft, User};

class SettingController extends Controller
{
    public function changeServiceDraftStatus(Request $request, $id) {
        ServiceDraft::find($id)->update([
            'status' => $request->status
        ]);
        return back()->with('success','Draft Status Changed Successfully');
    }

    public function ServiceDraftAll(Request $request) {
        $vendors = User::where('role_id', 2)->get();

        // Start the query with filtering
        $query = ServiceDraft::with(['vendor']);

        // Apply filters
        $query->when($request->search, function($q) use ($request) {
            return $q->where('title', 'like', '%' . $request->search . '%');
        })
        ->when($request->vendor_id, function($q) use ($request) {
            return $q->where('vendor_user_id', $request->vendor_id);
        })
        ->when($request->has('status') && $request->status, function($q) use ($request) {
            return $q->where('status', $request->status);
        });

        $service_drafts = $query->latest()->paginate(10);
        return view('backend.service-draft.all', compact('service_drafts','vendors'));
    }

    public function updateServiceDraftStatus(Request $request)
    {
        // Validate the request
        $request->validate([
            'id' => 'required|exists:service_drafts,id',
            'type' => 'required|in:status',
            'value' => 'required'
        ]);

        try {
            $service_draft = ServiceDraft::findOrFail($request->id);

            if ($request->type === 'status') {
                $validStatuses = [0, '0', 1, '1'];
                if (!in_array($request->value, $validStatuses)) {
                    throw new \Exception('Invalid status value');
                }
                $service_draft->status = $request->value;
                $message = "Post Service status updated successfully";
            }
            // elseif ($request->type === 'admin_approval') {
            //     $service_draft->admin_approval = $request->value;
            //     $message = $request->value ? "Service approved by admin" : "Service approval revoked";
            // }

            $service_draft->save();

            return response()->json([
                'success' => true,
                'message' => $message
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Update failed: ' . $e->getMessage()
            ], 500);
        }
    }
}
