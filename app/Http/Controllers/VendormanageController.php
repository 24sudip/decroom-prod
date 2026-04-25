<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, File};
use App\{User, Product, ServiceOrder};
use App\{Vendor, Service};
use App\{Order, Chat};
use App\{SellerWallet, CustomerPayment};
use Toastr;

class VendormanageController extends Controller
{
    public function vendorKyc() {
        $users = User::with('vendorDetails')
            ->where('role_id', 2)
            ->get();

        return view('backend.vendor.kyc', compact('users'));
    }

    public function vendorStatusChange(Request $request, $id) {
        $user = User::find($id);
        $user->update([
            'is_active' => $request->is_active
        ]);
        return back()->with('success', 'Status updated successfully.');
    }

    public function vendorDestroy(Request $request, $id) {
        $user = User::find($id);
        $products = Product::with(['images', 'variants','orderItems'])->where('vendor_id', $id)->get();
        foreach ($products as $product) {
            $promotionImageName = $product->promotion_image;
            if ($promotionImageName && file_exists(public_path($promotionImageName))) {
                unlink(public_path($promotionImageName));
            }
            $videoName = $product->video_path;
            if ($videoName && file_exists(public_path($videoName))) {
                unlink(public_path($videoName));
            }
            $imagesToDelete = $product->images()->get();
            foreach ($imagesToDelete as $image) {
                if ($image->image_path && file_exists(public_path($image->image_path))) {
                    unlink(public_path($image->image_path));
                }
                $image->delete();
            }
            $product->variants()->delete();
            $product->orderItems()->delete();
            $product->delete();
        }
        $services = Service::where('vendor_id', $id)->get();
        foreach ($services as $service) {
            if ($service->catalog && file_exists(public_path($service->catalog))) {
                unlink(public_path($service->catalog));
            }
            if ($service->attachment && file_exists(public_path($service->attachment))) {
                unlink(public_path($service->attachment));
            }
            if ($service->service_video && file_exists(public_path($service->service_video))) {
                unlink(public_path($service->service_video));
            }
            $service->delete();
        }
        Order::where('vendor_id', $id)->delete();
        Chat::where('sellerId', $id)->delete();
        $vendor = Vendor::where('user_id', $id)->first();
        if (File::exists(public_path($vendor->nid_front))) {
            File::delete(public_path($vendor->nid_front));
        }
        if (File::exists(public_path($vendor->nid_back))) {
            File::delete(public_path($vendor->nid_back));
        }
        if (File::exists(public_path($vendor->banner_image))) {
            File::delete(public_path($vendor->banner_image));
        }
        if (File::exists(public_path($vendor->logo))) {
            File::delete(public_path($vendor->logo));
        }
        $vendor->delete();
        SellerWallet::where('vendor_id', $id)->delete();
        if (File::exists(public_path($user->image))) {
            File::delete(public_path($user->image));
        }
        $user->delete();
        return back()->with('success', 'Vendor deleted successfully.');
    }

    public function vendorList(Request $request)
    {
        $vendors = User::with('vendorDetails')
            ->where('role_id', 2)
            ->get();

        return view('backend.vendor.index', compact('vendors'));
    }

    public function orderCommission(Request $request)
    {
        $orders = Order::with([
            'items.product',
            'items.vendor',
            'customer',
            'shipping',
            'ordertype',
            'user'
        ])
        ->orderBy('created_at', 'desc')
        ->get();

        return view('backend.vendor.ordercommission', compact('orders'));
    }

    public function updateCommission(Request $request)
    {

        $request->validate([
            'vendor_id' => 'required|exists:users,id',
            'commission_type' => 'required|in:1,2',
            'commission' => 'required|numeric|min:0',
        ]);

        $vendor = Vendor::where('user_id', $request->vendor_id)->first();

        $vendor->update([
            'commission_type' => $request->commission_type,
            'commission' => $request->commission,
        ]);

        return back()->with('success', 'Commission updated successfully.');
    }


    public function withdrawRequest() {
        $show_datas = SellerWallet::with('vendor')->where('title', 'Withdraw')->where('status', 0)->orderBy('id', 'DESC')->get();

        return view('backend.vendor.withdrawrequest', ['show_datas' => $show_datas]);
    }

    public function withdrawRequestApproved(Request $request) {
        $show_data              = SellerWallet::find($request->hidden_id);
        $show_data->status      = 1;
        $show_data->approved_by = Auth::guard('admin')->user()->id;
        $show_data->save();
        Toastr::success('message', 'Withdraw Request Approved');

        return redirect()->back();
    }

    public function withdraws() {
        $show_datas = SellerWallet::with('vendor.vendorDetails')->where(['status' => 1, 'credit' => 0])->orderBy('id', 'DESC')->get();

        return view('backend.vendor.withdraws', ['show_datas' => $show_datas]);
    }

    public function receiptUpload(Request $request) {
        $customerPayment = CustomerPayment::where('sellerwallet_id', $request->hidden_id)->first();
        if ($customerPayment) {
            $service_order = ServiceOrder::where('id', $customerPayment->service_order_id)->first();
            if ($service_order) {
                $service_order->installment_status = 1;
                $service_order->save();
            }
        }
        $file       = $request->file('receipt');
        $name       = $file->getClientOriginalName();
        $uploadPath = 'uploads/payments/';
        $file->move($uploadPath, $name);
        $fileUrl = $uploadPath . $name;

        $wallet              = SellerWallet::find($request->hidden_id);
        $wallet->status      = 1;
        $wallet->credit      = 1;
        $wallet->approved_by = Auth::guard('admin')->user()->id;
        $wallet->receipt     = $fileUrl;
        $wallet->save();

        Toastr::success('Payment Receipt Uploaded');

        return redirect()->back();
    }

    public function ledger(Request $request) {
        $sellerid = $request->vendor_id;

        $sellers = User::with('vendorDetails')->where('role_id', 2)->where('is_active', 1)->get();

        $show_datas = SellerWallet::with('vendor.vendorDetails')->orderBy('id', 'DESC');

        if ($sellerid) {
            $show_datas = $show_datas->where('vendor_id', $sellerid);
        }

        $show_datas = $show_datas->get();

        return view('backend.vendor.transactions', ['show_datas' => $show_datas, 'sellers' => $sellers, 'vendor_id' => $sellerid]);
    }
}
