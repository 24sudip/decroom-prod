<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Role;
use App\User;
use App\Vendor;
use App\SellerWallet;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller {
    public function index() {
        Gate::authorize('index-user');

        $users = User::where('is_active', true)->get();

        return view('backend.user.index', compact('users'));

    }

    public function create() {
        // Gate::authorize('create-user');
        $roles = Role::all();

        return view('backend.user.create', compact('roles'));
    }

    public function store(Request $request) {
        Gate::authorize('create-module');
    
        $request->validate([
            'role_id'  => 'required|integer',
            'name'     => 'required|string|max:200',
            'phone'    => 'nullable|string|max:15',
            'email'    => 'nullable|email|unique:users,email',
            'image'    => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'password' => 'required|string|min:6|confirmed',
        ]);
    
        $image_path = null;
    
        if ($request->hasFile('image')) {
            $file      = $request->file('image');
            $imageName = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('storage/users/'), $imageName);
            $image_path = $imageName;
        }
    
        // Create the user
        $user = User::create([
            'role_id'  => $request->role_id,
            'name'     => $request->name,
            'phone'    => $request->phone,
            'email'    => $request->email,
            'image'    => $image_path,
            'password' => Hash::make($request->password),
        ]);
    
        /**
         * Auto-create SellerWallet for vendors
         * role_id = 2 is vendor
         */
        if ($user->role_id == 2) {
             // Create vendor record
            Vendor::create([
                'user_id' => $user->id,
                'shop_name' => 'Your Shop Name',
                'status' => 1,
            ]);
            
            SellerWallet::create([
                'title'     => 'Initial Wallet',
                'vendor_id' => $user->id,
                'amount'    => 0,
                'current'   => 0,
                'credit'    => 0,
                'status'    => 1,
            ]);
        }
    
        Toastr::success('User Created Successfully!');
    
        return redirect()->route('user.index');
    }


    public function edit(string $id) {
        Gate::authorize('update-user');

        $roles = Role::all();
        $user  = User::find($id);

        return view('backend.user.edit', compact('user', 'roles'));
    }

    public function update(Request $request, $id) {
        Gate::authorize('edit-module');

        $user = User::findOrFail($id);

        $request->validate([
            'role_id' => 'required|integer',
            'name'    => 'required|string|max:255',
            'phone'   => 'nullable|string|max:15',
            'email'   => 'nullable|email|unique:users,email,' . $user->id,
            'image'   => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $image_path = $user->image;

        if ($request->hasFile('image')) {
            $file      = $request->file('image');
            $imageName = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('storage/users/'), $imageName);
            $image_path = $imageName;
        }

        $user->update([
            'role_id' => $request->role_id,
            'name'    => $request->name,
            'phone'   => $request->phone,
            'email'   => $request->email,
            'image'   => $image_path,
        ]);

        Toastr::success('User Updated Successfully!');

        return redirect()->route('user.index');
    }

    public function destroy(string $id) {
        Gate::authorize('delete-user');

        $user = User::find($id);
        $user->delete();

        Toastr::success('User Deleted Successfully!');

        return redirect()->route('user.index');
    }

}
