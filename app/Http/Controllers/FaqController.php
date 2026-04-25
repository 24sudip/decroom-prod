<?php

namespace App\Http\Controllers;

use App\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller {

    public function index() {
        $faqs = Faq::latest()->paginate(10);

        return view('backend.faqs.index', compact('faqs'));
    }

    public function create() {
        return view('backend.faqs.create');
    }

    public function store(Request $request) {
        $request->validate([
            'question' => 'required|string|max:255',
            'answer'   => 'required|string',
            'status'   => 'nullable|boolean',
        ]);

        Faq::create($request->all());

        return redirect()->route('faqs.index')->with('success', 'FAQ created successfully.');
    }

    public function show(Faq $faq) {
        return view('backend.faqs.show', compact('faq'));
    }

    public function edit(Faq $faq) {
        return view('backend.faqs.edit', compact('faq'));
    }

    public function update(Request $request, Faq $faq) {
        $request->validate([
            'question' => 'required|string|max:255',
            'answer'   => 'required|string',
            'status'   => 'nullable|boolean',
        ]);

        $faq->update($request->all());

        return redirect()->route('faqs.index')->with('success', 'FAQ updated successfully.');
    }

    public function destroy(Faq $faq) {
        $faq->delete();

        return redirect()->route('faqs.index')->with('success', 'FAQ deleted successfully.');
    }
}
