<?php

namespace App\Http\Controllers;

use App\AccountHead;
use Illuminate\Http\Request;

class AccountHeadController extends Controller {

    public function index() {
        $heads = AccountHead::latest()->paginate(20);

        return view('backend.account_heads.index', compact('heads'));
    }

    public function create() {
        return view('backend.account_heads.create');
    }

    public function store(Request $request) {
        $request->validate([
            'name'   => 'required|string|max:255',
            'type'   => 'required|in:income,expenditure',
            'status' => 'nullable|boolean',
        ]);

        AccountHead::create([
            'name'   => $request->name,
            'type'   => $request->type,
            'status' => $request->status ?? 1,
        ]);

        return redirect()->route('account-heads.index')->with('success', 'Account head added successfully!');
    }

    public function edit($id) {
        $accountHead = AccountHead::findOrFail($id);

        return view('backend.account_heads.edit', compact('accountHead'));
    }

    public function update(Request $request, $id) {
        $request->validate([
            'name'   => 'required|string|max:255',
            'type'   => 'required|in:income,expenditure',
            'status' => 'nullable|boolean',
        ]);

        $head = AccountHead::findOrFail($id);
        $head->update([
            'name'   => $request->name,
            'type'   => $request->type,
            'status' => $request->status ?? 1,
        ]);

        return redirect()->route('account-heads.index')->with('success', 'Account head updated successfully!');
    }

    public function destroy($id) {
        $head = AccountHead::findOrFail($id);
        $head->delete();

        return redirect()->route('account-heads.index')->with('success', 'Account head deleted successfully!');
    }

    public function statusToggle($id) {
        $head         = AccountHead::findOrFail($id);
        $head->status = !$head->status;
        $head->save();

        return back()->with('success', 'Status updated successfully!');
    }
}
