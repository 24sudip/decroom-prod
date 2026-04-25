<?php

namespace App\Http\Controllers;

use App\Generic;
use App\Product;
use App\ProductBrand;
use App\ProductCategory;
use App\PurchaseDetails;
use App\Supplier;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class InventoryController extends Controller {

    public function stockManage(Request $request) {
        $categories = ProductCategory::all();
        $brands     = ProductBrand::all();
        $generics   = Generic::all();

        $products = Product::with(['category', 'brand', 'unit', 'generic'])
            ->where('is_deleted', 0)
            ->orderByDesc('id');

        if ($request->ajax()) {

            if ($request->filled('category_id')) {
                $products->where('category_id', $request->category_id);
            }

            if ($request->filled('brand_id')) {
                $products->where('brand_id', $request->brand_id);
            }

            if ($request->filled('generic_id')) {
                $products->where('generic_id', $request->generic_id);
            }

            if ($request->filled('search')) {
                $searchTerm = $request->search;
                $products->where(function ($query) use ($searchTerm) {
                    $query->where('name', 'like', "%{$searchTerm}%")
                        ->orWhere('batch_no', 'like', "%{$searchTerm}%");
                });
            }

            return DataTables::of($products)
                ->addIndexColumn()
                ->addColumn('image', function ($row) {
                    $url = $row->image
                    ? asset('public/storage/products/' . $row->image)
                    : asset('public/storage/logo.png');

                    return '<img src="' . $url . '" width="60" height="60" class="img-thumbnail"/>';
                })
                ->addColumn('category', fn($row) => optional($row->category)->name)
                ->addColumn('brand', fn($row) => optional($row->brand)->name)
                ->addColumn('generic', fn($row) => optional($row->generic)->name)
                ->addColumn('unit', fn($row) => optional($row->unit)->name)
                ->rawColumns(['image'])
                ->make(true);
        }

        return view('backend.inventory.stock', compact('categories', 'brands', 'generics'));
    }

    public function stockWarning(Request $request) {
        $categories = ProductCategory::all();
        $brands     = ProductBrand::all();
        $generics   = Generic::all();

        if ($request->ajax()) {
            $products = Product::with(['category', 'brand', 'unit', 'generic'])
                ->where('is_deleted', 0)
                ->whereRaw('CAST(stock AS UNSIGNED) < CAST(exp_limit AS UNSIGNED)');

            if ($request->filled('category_id')) {
                $products->where('category_id', $request->category_id);
            }

            if ($request->filled('brand_id')) {
                $products->where('brand_id', $request->brand_id);
            }

            if ($request->filled('generic_id')) {
                $products->where('generic_id', $request->generic_id);
            }

            if ($request->filled('search')) {
                $searchTerm = $request->search;
                $products->where(function ($query) use ($searchTerm) {
                    $query->where('name', 'like', "%{$searchTerm}%")
                        ->orWhere('batch_no', 'like', "%{$searchTerm}%");
                });
            }

            $products->orderByDesc('id');

            return DataTables::of($products)
                ->addIndexColumn()
                ->addColumn('image', function ($row) {
                    $url = $row->image
                    ? asset('public/storage/products/' . $row->image)
                    : asset('public/storage/logo.png');

                    return '<img src="' . $url . '" width="60" height="60" class="img-thumbnail"/>';
                })
                ->addColumn('category', fn($row) => optional($row->category)->name)
                ->addColumn('brand', fn($row) => optional($row->brand)->name)
                ->addColumn('unit', fn($row) => optional($row->unit)->name)
                ->addColumn('generic', fn($row) => optional($row->generic)->name)
                ->rawColumns(['image'])
                ->make(true);
        }

        return view('backend.inventory.stock_warning', compact('categories', 'brands', 'generics'));
    }

    public function stockOut(Request $request) {
        $categories = ProductCategory::all();
        $brands     = ProductBrand::all();
        $generics   = Generic::all();

        if ($request->ajax()) {
            $products = Product::with(['category', 'brand', 'unit', 'generic'])
                ->where('is_deleted', 0)
                ->where('out_of_stock', 1);

            if ($request->filled('category_id')) {
                $products->where('category_id', $request->category_id);
            }

            if ($request->filled('brand_id')) {
                $products->where('brand_id', $request->brand_id);
            }

            if ($request->filled('generic_id')) {
                $products->where('generic_id', $request->generic_id);
            }

            if ($request->filled('search')) {
                $searchTerm = $request->search;
                $products->where(function ($query) use ($searchTerm) {
                    $query->where('name', 'like', "%{$searchTerm}%")
                        ->orWhere('batch_no', 'like', "%{$searchTerm}%");
                });
            }

            $products->orderByDesc('id');

            return DataTables::of($products)
                ->addIndexColumn()
                ->addColumn('image', function ($row) {
                    $url = $row->image
                    ? asset('public/storage/products/' . $row->image)
                    : asset('public/storage/logo.png');

                    return '<img src="' . $url . '" width="60" height="60" class="img-thumbnail"/>';
                })
                ->addColumn('category', fn($row) => optional($row->category)->name)
                ->addColumn('brand', fn($row) => optional($row->brand)->name)
                ->addColumn('unit', fn($row) => optional($row->unit)->name)
                ->addColumn('generic', fn($row) => optional($row->generic)->name)
                ->rawColumns(['image'])
                ->make(true);
        }

        return view('backend.inventory.stock_out', compact('categories', 'brands', 'generics'));
    }

    public function expiredList(Request $request) {
        $categories = ProductCategory::all();
        $brands     = ProductBrand::all();
        $generics   = Generic::all();
        $suppliers  = Supplier::all();

        $threeMonthsLater = Carbon::now()->addMonths(3)->endOfDay();

        $query = PurchaseDetails::with([
            'product.category',
            'product.brand',
            'product.unit',
            'product.generic',
            'purchase.supplier',
        ])
            ->whereNotNull('expire_date')
            ->where('expire_date', '<=', $threeMonthsLater);

        if ($request->ajax()) {

            if ($request->filled('category_id')) {
                $query->whereHas('product', function ($q) use ($request) {
                    $q->where('category_id', $request->category_id);
                });
            }

            if ($request->filled('brand_id')) {
                $query->whereHas('product', function ($q) use ($request) {
                    $q->where('brand_id', $request->brand_id);
                });
            }

            if ($request->filled('generic_id')) {
                $query->whereHas('product', function ($q) use ($request) {
                    $q->where('generic_id', $request->generic_id);
                });
            }

            if ($request->filled('supplier_id')) {
                $query->whereHas('purchase.supplier', function ($q) use ($request) {
                    $q->where('id', $request->supplier_id);
                });
            }

            if ($request->filled('search_term')) {
                $search = $request->search_term;
                $query->whereHas('product', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('batch_no', 'like', "%{$search}%");
                });
            }

            if ($request->filled('expire_date')) {
                $query->whereDate('expire_date', $request->expire_date);
            }

            if ($request->filled('manufacture_date')) {
                $query->whereDate('manufacturer_date', $request->manufacture_date);
            }

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('image', function ($row) {
                    $image = $row->product && $row->product->image
                    ? asset('public/storage/products/' . $row->product->image)
                    : asset('public/storage/logo.png');

                    return '<img src="' . $image . '" width="60" height="60" class="img-thumbnail"/>';
                })
                ->addColumn('product_name', fn($row) => optional($row->product)->name)
                ->addColumn('batch', fn($row) => optional($row->product)->batch_no)
                ->addColumn('category', fn($row) => optional($row->product->category)->name)
                ->addColumn('brand', fn($row) => optional($row->product->brand)->name)
                ->addColumn('generic', fn($row) => optional($row->product->generic)->name)
                ->addColumn('unit', fn($row) => optional($row->product->unit)->name)
                ->addColumn('supplier', fn($row) => optional($row->purchase->supplier)->name)
                ->addColumn('expire_date', fn($row) => \Carbon\Carbon::parse($row->expire_date)->format('d M, Y'))
                ->rawColumns(['image'])
                ->make(true);
        }

        return view('backend.inventory.expired_list', compact('categories', 'brands', 'generics', 'suppliers'));
    }

// public function expiredList(Request $request) {

//     $categories = ProductCategory::all();

//     $brands     = ProductBrand::all();

//     $generics   = Generic::all();

//     $suppliers  = Supplier::all();

//     if ($request->ajax()) {

//         $threeMonthsLater = Carbon::now()->addMonths(3)->endOfDay();

//         $query = PurchaseDetails::with([

//             'product.category',

//             'product.brand',

//             'product.unit',

//             'product.generic',

//             'purchase.supplier',

//         ])

//             ->whereNotNull('expire_date')

//             ->where('expire_date', '<=', $threeMonthsLater);

//         if ($request->filled('category_id')) {

//             $query->whereHas('product', fn($q) =>

//                 $q->where('category_id', $request->category_id));

//         }

//         if ($request->filled('brand_id')) {

//             $query->whereHas('product', fn($q) =>

//                 $q->where('brand_id', $request->brand_id));

//         }

//         if ($request->filled('supplier_id')) {

//             $query->whereHas('purchase.supplier', fn($q) =>

//                 $q->where('id', $request->supplier_id));

//         }

//         if ($request->filled('search_term')) {

//             $search = $request->search_term;

//             $query->whereHas('product', function ($q) use ($search) {

//                 $q->where('name', 'like', "%{$search}%")

//                     ->orWhere('batch_no', 'like', "%{$search}%");

//             });

//         }

//         $query->orderByDesc('expire_date');

//         return DataTables::of($query)

//             ->addIndexColumn()

//             ->addColumn('product_name', fn($row) => optional($row->product)->name)

//             ->addColumn('batch_no', fn($row) => optional($row->product)->batch_no)

//             ->addColumn('category', fn($row) => optional($row->product->category)->name)

//             ->addColumn('brand', fn($row) => optional($row->product->brand)->name)

//             ->addColumn('unit', fn($row) => optional($row->product->unit)->name)

//             ->addColumn('generic', fn($row) => optional($row->product->generic)->name)

//             ->addColumn('supplier', fn($row) => optional($row->purchase->supplier)->name)

//             ->addColumn('expire_date', fn($row) => Carbon::parse($row->expire_date)->format('d M, Y'))

//             ->addColumn('image', function ($row) {

//                 $url = $row->product && $row->product->image

//                 ? asset('public/storage/products/' . $row->product->image)

//                 : asset('public/storage/logo.png');

//                 return '<img src="' . $url . '" width="60" height="60" class="img-thumbnail"/>';

//             })

//             ->rawColumns(['image'])

//             ->make(true);

//     }

//     return view('backend.inventory.expired_list', compact('categories', 'brands', 'generics', 'suppliers'));
    // }

}
