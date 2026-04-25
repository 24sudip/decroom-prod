<?php

namespace App\Http\Controllers;

use App\About;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class AboutController extends Controller {

    public function index() {
        $aboutInfo = About::latest()->paginate(10);

        return view('backend.abouts.index', compact('aboutInfo'));
    }

    public function create() {
        return view('backend.abouts.create');
    }

    public function store(Request $request) {
        $request->validate([
            'name'            => 'required|string|max:255',
            'description_top' => 'nullable|string',
            'description'     => 'nullable|string',
            'image'           => 'nullable|image|max:2048',
        ]);

        $data = $request->only(['name', 'phone', 'hot_line', 'whats_app', 'address', 'description_top', 'description']);

        if ($request->hasfile('image')) {
            $des = 'build/images/abouts' . $request->image;

            if (File::exists($des)) {
                File::delete($des);
            }

            $image    = $request->file('image');
            $imageall = 'logo-light1.' . $image->getClientOriginalExtension();
            $image->move(public_path('build/images/abouts'), $imageall);
            $data['image'] = $imageall;
        } else {
            $data['image'] = "";
        }

        About::create($data);

        return redirect()->route('abouts.index')->with('success', 'About created successfully.');
    }

    public function edit(About $about) {
        return view('backend.abouts.edit', compact('about'));
    }

    public function update(Request $request, About $about) {
        $request->validate([
            'name'            => 'required|string|max:255',
            'description_top' => 'nullable|string',
            'description'     => 'nullable|string',
            'image'           => 'nullable|image|max:2048',
        ]);

        $data = $request->only(['name', 'phone', 'hot_line', 'whats_app', 'address', 'description_top', 'description']);

        if ($request->hasfile('image')) {
            $des = 'build/images/abouts' . $request->image;

            if (File::exists($des)) {
                File::delete($des);
            }

            $image    = $request->file('image');
            $imageall = 'logo-light1.' . $image->getClientOriginalExtension();
            $image->move(public_path('build/images/abouts'), $imageall);
            $data['image'] = $imageall;
        } else {
            $data['image'] = $about->image;
        }

        $about->update($data);

        return redirect()->route('abouts.index')->with('success', 'About updated successfully.');
    }

    public function destroy(About $about) {

        if ($about->image) {
            Storage::disk('public')->delete($about->image);
        }

        $about->delete();

        return redirect()->route('abouts.index')->with('success', 'About deleted successfully.');
    }

}
