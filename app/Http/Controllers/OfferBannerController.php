<?php

namespace App\Http\Controllers;

use App\OfferBanner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;

class OfferBannerController extends Controller
{
    public function index(Request $request)
    {
        $offerBanners = OfferBanner::orderBy('id', 'desc')->get();

        if ($request->ajax()) {
            return DataTables::of($offerBanners)
                ->addIndexColumn()
                ->addColumn('image', function ($row) {
                    $imageUrl = $row->image
                        ? asset('storage/banners/' . $row->image)
                        : asset('assets/no-image.png');

                    return '<img src="' . $imageUrl . '" width="60" height="60" class="img-thumbnail"/>';
                })
                ->addColumn('link_url', function ($row) {
                    return $row->link_url ?? '-';
                })
                ->addColumn('status', function ($row) {
                    $btnClass = $row->status ? 'btn-success' : 'btn-secondary';
                    $btnText  = $row->status ? 'Active' : 'Inactive';

                    return '<button class="btn btn-sm toggle-home-btn ' . $btnClass . '" data-id="' . $row->id . '">' . $btnText . '</button>';
                })
                ->addColumn('option', function ($row) {

                    $editBtn = '
                        <a href="' . route('offerbanner.edit', $row->id) . '">
                            <button class="btn btn-primary btn-sm btn-rounded" title="Update Banner">
                                <i class="mdi mdi-lead-pencil"></i>
                            </button>
                        </a>';

                    $deleteBtn = '
                        <button class="btn btn-danger btn-sm btn-rounded delete-banner" data-id="' . $row->id . '" title="Delete Banner">
                            <i class="mdi mdi-delete"></i>
                        </button>';

                    return $editBtn . ' ' . $deleteBtn;
                })
                ->rawColumns(['image', 'status', 'option'])
                ->make(true);
        }

        return view('backend.offerbanner.banner');
    }

    public function create()
    {
        $banner = null;
        return view('backend.offerbanner.banner-details', compact('banner'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'link_url' => 'nullable|string',
            'status'   => 'nullable',
            'image'    => 'nullable|image|mimes:jpeg,png,jpg,gif,webp',
        ]);

        $banner = new OfferBanner();
        $banner->link_url = $request->link_url;
        $banner->status   = $request->has('status') ? 1 : 0;

        // Image Upload
        if ($request->hasFile('image')) {
            $imageName = 'banner-' . time() . '-' . uniqid() . '.' . $request->image->extension();
            $request->image->move(public_path('storage/banners/'), $imageName);
            $banner->image = $imageName;
        }

        $banner->save();

        return redirect()->route('offerbanner.index')->with('success', 'Banner created successfully');
    }

    public function edit($id)
    {
        $banner = OfferBanner::findOrFail($id);
        return view('backend.offerbanner.banner-details', compact('banner'));
    }

    public function update(Request $request, $id)
    {
        $banner = OfferBanner::findOrFail($id);

        $request->validate([
            'link_url' => 'nullable|string',
            'status'   => 'nullable',
            'image'    => 'nullable|image|mimes:jpeg,png,jpg,gif,webp',
        ]);

        $banner->link_url = $request->link_url;
        $banner->status   = $request->has('status') ? 1 : 0;

        if ($request->hasFile('image')) {

            // Delete old image
            if ($banner->image && File::exists(public_path('storage/banners/' . $banner->image))) {
                File::delete(public_path('storage/banners/' . $banner->image));
            }

            $imageName = 'banner-' . time() . '-' . uniqid() . '.' . $request->image->extension();
            $request->image->move(public_path('storage/banners/'), $imageName);
            $banner->image = $imageName;
        }

        $banner->save();

        return redirect()->route('offerbanner.index')->with('success', 'Banner updated successfully');
    }

    public function destroy($id)
    {
        $banner = OfferBanner::findOrFail($id);

        if ($banner->image) {
            Storage::disk('public')->delete('banners/' . $banner->image);
        }

        $banner->delete();

        return response()->json([
            'success' => true,
            'message' => 'Banner deleted successfully!',
        ]);
    }
    
    public function toggleStatus(Request $request) {
        $banner          = OfferBanner::findOrFail($request->id);
        $banner->status = !$banner->status;
        $banner->save();

        return response()->json([
            'success' => true,
            'message' => 'Status updated.',
            'status'  => $banner->status ? 'Active' : 'Inactive',
        ]);
    }
}
