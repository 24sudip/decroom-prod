<?php

namespace App\Http\Controllers;

use App\Page;
use App\PageCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PageController extends Controller {
    public function index() {
        $pages = Page::with('category')->latest()->get();

        return view('backend.pages.index', compact('pages'));
    }

    public function create() {
        $pageCategories = PageCategory::all();

        return view('backend.pages.create', compact('pageCategories'));
    }

    public function store(Request $request) {
        $request->validate([
            'title'       => 'required|unique:pages,title',
            'category_id' => 'nullable|exists:page_categories,id',
            'content'     => 'nullable',
        ]);

        Page::create([
            'title'       => $request->title,
            'slug'        => Str::slug($request->title),
            'category_id' => $request->category_id,
            'content'     => $request->content,
            'status'      => $request->has('status') ? 1 : 0,
        ]);

        return redirect()->route('pages.index')->with('success', 'Page created successfully.');
    }

    public function edit(Page $page) {
        $pageCategories = PageCategory::all();

        return view('backend.pages.edit', compact('page', 'pageCategories'));
    }

    public function update(Request $request, Page $page) {
        $request->validate([
            'title'       => 'required|unique:pages,title,' . $page->id,
            'category_id' => 'nullable|exists:page_categories,id',
            'content'     => 'nullable',
        ]);

        $page->update([
            'title'       => $request->title,
            'slug'        => Str::slug($request->title),
            'category_id' => $request->category_id,
            'content'     => $request->content,
            'status'      => $request->has('status') ? 1 : 0,
        ]);

        return redirect()->route('pages.index')->with('success', 'Page updated successfully.');
    }

    public function destroy(Page $page) {
        $page->delete();

        return redirect()->route('pages.index')->with('success', 'Page deleted successfully.');
    }
}
