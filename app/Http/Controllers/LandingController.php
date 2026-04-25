<?php
namespace App\Http\Controllers;

use App\LandingSections;
use Illuminate\Support\Facades\Auth;

class LandingController extends Controller {
    private function user() {
        return Auth::user();
    }

    public function index() {
        $sections = LandingSections::all();
        $data     = [];

        foreach ($sections as $key => $section) {
            $data[$section->title] = $section->is_enable;
        }

        return view('backend.landing.index', compact('data'));
    }

    public function manage() {
        $user = $this->user();
        $role = $user->role->role_name;

        $sections = LandingSections::all();

        return view('backend.landing.manage', compact('user', 'role', 'sections'));

    }

    public function section_enable($id) {

        $section            = LandingSections::find($id);
        $section->is_enable = 0;
        $section->save();

        return redirect(route('landing.manage'))->with('success', 'Section Enabled Successfully.');

    }

    public function section_disable($id) {

        $section            = LandingSections::find($id);
        $section->is_enable = 1;
        $section->save();

        return redirect(route('landing.manage'))->with('success', 'Section Disabled Successfully.');

    }

}
