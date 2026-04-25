<?php

namespace App\Http\Controllers;

use App\PageCategory;
use Illuminate\Http\Request;

class PageCategoryController extends Controller {

    public function index() {
        $pageCategories = PageCategory::latest()->get();

        return view('backend.page_categories.index', compact('pageCategories'));
    }

    public function create() {
        return view('backend.page_categories.create');
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required|unique:page_categories,name',
        ]);

        PageCategory::create([
            'name' => $request->name,
        ]);

        return redirect()->route('page-categories.index')->with('success', 'Category created successfully.');
    }

    public function edit(PageCategory $pageCategory) {
        return view('backend.page_categories.edit', compact('pageCategory'));
    }

    public function update(Request $request, PageCategory $pageCategory) {
        $request->validate([
            'name' => 'required|unique:page_categories,name,' . $pageCategory->id,
        ]);

        $pageCategory->update([
            'name' => $request->name,
        ]);

        return redirect()->route('page-categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(PageCategory $pageCategory) {
        $pageCategory->delete();

        return redirect()->route('page-categories.index')->with('success', 'Category deleted successfully.');
    }
}
