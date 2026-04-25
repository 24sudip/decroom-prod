<?php

namespace App\Http\Controllers;

use App\Socialmedia;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

class SocialController extends Controller
{
    public function index()
    {
        return view('backend.socialmedia.add');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'   => 'required|string|max:255',
            'icon'   => 'required|string|max:255',
            'link'   => 'required|url',
            'status' => 'required|in:0,1',
        ]);

        Socialmedia::create([
            'name'   => $request->name,
            'icon'   => $request->icon,
            'link'   => $request->link,
            'status' => $request->status,
        ]);

        Toastr::success('Social media added successfully!');
        return redirect('/editor/social-media/manage');
    }

    public function manage()
    {
        $show_datas = Socialmedia::all();

        return view('backend.socialmedia.manage', compact('show_datas'));
    }

    public function edit($id)
    {
        $edit_data = Socialmedia::findOrFail($id);

        return view('backend.socialmedia.edit', compact('edit_data'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'hidden_id' => 'required|exists:socialmedia,id',
            'name'      => 'required|string|max:255',
            'icon'      => 'required|string|max:255',
            'link'      => 'required|url',
            'status'    => 'required|in:0,1',
        ]);
    
        $social = Socialmedia::findOrFail($request->hidden_id);
    
        $social->update([
            'name'   => $request->name,
            'icon'   => $request->icon,
            'link'   => $request->link,
            'status' => $request->status,
        ]);
    
        Toastr::success('Social media updated successfully!');
        return redirect()->route('social.manage');
    }


    public function destroy(Request $request)
    {
        $deleteId = Socialmedia::findOrFail($request->hidden_id);
        $deleteId->delete();

        Toastr::success('Social media deleted successfully!');
        return redirect('/editor/social-media/manage');
    }

    public function unpublished(Request $request)
    {
        $unpublish_data = Socialmedia::findOrFail($request->hidden_id);
        $unpublish_data->update(['status' => 0]);

        Toastr::success('Social media unpublished successfully!');
        return redirect('/editor/social-media/manage');
    }

    public function published(Request $request)
    {
        $publishId = Socialmedia::findOrFail($request->hidden_id);
        $publishId->update(['status' => 1]);

        Toastr::success('Social media published successfully!');
        return redirect('/editor/social-media/manage');
    }
}
