<?php

namespace App\Http\Controllers;

use App\AccountEntry;
use App\AccountHead;
use Illuminate\Http\Request;

class AccountEntryController extends Controller {

    public function index(Request $request) {
        $query = AccountEntry::with('accountHead')->latest();

        if ($request->filled('start_date')) {
            $query->whereDate('entry_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('entry_date', '<=', $request->end_date);
        }

        $entries = $query->paginate(20)->appends($request->only('start_date', 'end_date'));

        return view('backend.accounts.index', compact('entries'));
    }

    public function create() {
        $heads = AccountHead::where('status', 1)->get();

        return view('backend.accounts.create', compact('heads'));
    }

    public function store(Request $request) {
        $request->validate([
            'account_head_id' => 'required|exists:account_heads,id',
            'amount'          => 'required|numeric|min:0.01',
            'entry_date'      => 'required|date',
            'note'            => 'nullable|string',
        ]);

        AccountEntry::create($request->all());

        return redirect()->route('account-entries.index')->with('success', 'Entry added successfully!');
    }

    public function edit(AccountEntry $accountEntry) {
        $heads = AccountHead::where('status', 1)->get();

        return view('backend.accounts.edit', compact('accountEntry', 'heads'));
    }

    public function update(Request $request, AccountEntry $accountEntry) {
        $request->validate([
            'account_head_id' => 'required|exists:account_heads,id',
            'amount'          => 'required|numeric|min:0.01',
            'entry_date'      => 'required|date',
            'note'            => 'nullable|string',
        ]);

        $accountEntry->update($request->all());

        return redirect()->route('account-entries.index')->with('success', 'Entry updated successfully!');
    }

    public function destroy(AccountEntry $accountEntry) {
        $accountEntry->delete();

        return redirect()->route('account-entries.index')->with('success', 'Entry deleted successfully!');
    }

}
