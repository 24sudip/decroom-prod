<?php

namespace App\Http\Controllers;

use App\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class SliderController extends Controller {
    public function index() {
        $sliders = Slider::latest()->get();

        return view('backend.slider.index', compact('sliders'));
    }

    public function create() {
        return view('backend.slider.create');
    }

    public function store(Request $request) {
        $request->validate([
            'title'       => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image'       => 'required|image|mimes:jpeg,png,jpg,gif,webp',
            'link'        => 'nullable|url',
        ]);

        $slider              = new Slider();
        $slider->title       = $request->title;
        $slider->description = $request->description;
        $slider->link        = $request->link;
        $slider->status      = 1;

        if ($request->hasFile('image')) {
            $image    = $request->file('image');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('storage/sliders'), $filename);
            $slider->image = $filename;
        }

        $slider->save();

        return redirect()->route('slider.index')->with('success', 'Slider created successfully!');
    }

    // Show the form for editing the specified slider
    public function edit($id) {
        $slider = Slider::findOrFail($id);

        return view('backend.slider.edit', compact('slider'));
    }

    public function update(Request $request, $id) {
        $slider = Slider::findOrFail($id);

        // Checkbox fallback: if not present in request, set to 0
        $request->merge([
            'status' => $request->has('status') ? 1 : 0,
        ]);

        $validated = $request->validate([
            'title'       => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'link'        => 'nullable|url',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif,webp',
            'status'      => 'required|boolean',
        ]);

        if ($request->hasFile('image')) {
            $file      = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $imageName = time() . '.' . $extension;
            $file->move(public_path('storage/sliders/'), $imageName);

// Delete old image
            if ($slider->image && file_exists(public_path('storage/sliders/' . $slider->image))) {
                unlink(public_path('storage/sliders/' . $slider->image));
            }

            $validated['image'] = $imageName;
        }

        $slider->update($validated);

        return redirect()->route('slider.index')->with('success', 'Slider updated successfully!');
    }

    public function destroy(Slider $slider) {
        $imagePath = public_path('storage/sliders/' . $slider->image);

        if (File::exists($imagePath)) {
            File::delete($imagePath);
        }

        $slider->delete();

        return redirect()->back()->with('success', 'Slider deleted successfully!');
    }

    public function toggleStatus(Slider $slider) {
        try {
            $slider->status = !$slider->status; // Toggle between 1 (active) and 0 (inactive)
            $slider->save();

            $message = $slider->status ? 'Slider activated successfully!' : 'Slider deactivated successfully!';

            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update slider status: ' . $e->getMessage());
        }

    }

}
