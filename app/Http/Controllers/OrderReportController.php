<?php

namespace App\Http\Controllers;

use App\Customer;
use App\User;
use App\Vendor;
use App\Product;
use App\Order;
use App\OrderItem;
use App\Ordertype;
use App\ProductBrand;
use App\SellerWallet;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class OrderReportController extends Controller {

    public function allOrdersView() {
        $customers = Customer::select('id', 'name', 'phone')->orderBy('name')->get();

        return view('backend.orders.all', compact('customers'));
    }

    public function allOrders(Request $request) {
        $query = Order::with(['customer', 'ordertype']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

        if ($request->filled('customer_type')) {
            // Join the customers table to filter by customer type
            $query->whereHas('customer', function ($q) use ($request) {
                $q->where('type', $request->customer_type);
            });
        }

        if ($request->filled('from') && $request->filled('to')) {
            $query->whereBetween('created_at', [
                Carbon::parse($request->from)->startOfDay(),
                Carbon::parse($request->to)->endOfDay(),
            ]);
        }

        $data = $query->latest()->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('tracking_id', function ($row) {
                return $row->id;
            })
            ->addColumn('customer', function ($row) {
                return $row->customer->name ?? 'N/A';
            })
            ->addColumn('phone', function ($row) {
                return $row->customer->phone ?? 'N/A';
            })
            ->addColumn('status_text', function ($row) {
                return $row->ordertype->name ?? 'N/A';
            })
            ->addColumn('status_dropdown', function ($row) {
                $options = '';

                foreach (Ordertype::all() as $status) {
                    $selected = $row->status == $status->id ? 'selected' : '';
                    $options .= "<option value='{$status->id}' {$selected}>{$status->name}</option>";
                }

                return "<select class='form-control status-update' data-id='{$row->id}'>{$options}</select>";
            })
            ->addColumn('total_amount', function ($row) {
                return number_format($row->total_amount, 2);
            })
            ->addColumn('payment_method', function ($row) {
                return ucfirst($row->payment_method);
            })
            ->addColumn('date', function ($row) {
                return $row->created_at->format('d M Y');
            })
            ->addColumn('option', function ($row) {
                return '<a href="' . route('order.view', $row->id) . '" class="btn btn-sm btn-info">View</a>';
            })
            ->rawColumns(['status_dropdown', 'option'])
            ->make(true);

    }

    public function todaysOrders() {
        return view('backend.orders.todays_orders');
    }

    // Repeat similar logic for below if you use them:
    public function todaysOrdersData(Request $request) {
        $query = Order::with(['customer', 'ordertype'])
            ->whereDate('created_at', now());

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $data = $query->latest()->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('tracking_id', function ($row) {
                return $row->id;
            })
            ->addColumn('customer', function ($row) {
                return $row->customer->name ?? 'N/A';
            })
            ->addColumn('phone', function ($row) {
                return $row->customer->phone ?? 'N/A';
            })
            ->addColumn('status_text', function ($row) {
                return $row->ordertype->name ?? 'N/A';
            })
            ->addColumn('status_dropdown', function ($row) {
                $options = '';

                foreach (Ordertype::all() as $status) {
                    $selected = $row->status == $status->id ? 'selected' : '';
                    $options .= "<option value='{$status->id}' {$selected}>{$status->name}</option>";
                }

                return "<select class='form-control status-update' data-id='{$row->id}'>{$options}</select>";
            })
            ->addColumn('total_amount', function ($row) {
                return number_format($row->total_amount ?? 0, 2);
            })
            ->addColumn('payment_method', function ($row) {
                return ucfirst($row->payment_method ?? 'N/A');
            })
            ->addColumn('shipping_info', function ($row) {

                if ($row->shipping) {
                    return $row->shipping->note ?? $row->shipping->address ?? 'N/A';
                }

                return 'N/A';
            })
            ->addColumn('date', function ($row) {
                return $row->created_at ? $row->created_at->format('d M Y') : 'N/A';
            })
            ->addColumn('option', function ($row) {
                return '<a href="' . route('order.view', $row->id) . '" class="btn btn-sm btn-info">View</a>';
            })
            ->rawColumns(['status_dropdown', 'option'])
            ->make(true);

    }

    public function brandwiseOrder() {
        return view('backend.orders.brandwise_orders');
    }

    public function brandwiseOrderData(Request $request) {
        $query = ProductBrand::whereHas('products.orderItems');

        if ($request->filled('status') || ($request->filled('from') && $request->filled('to'))) {
            $query->whereHas('products.orderItems.order', function ($q) use ($request) {

                if ($request->filled('status')) {
                    $q->where('status', $request->status);
                }

                if ($request->filled('from') && $request->filled('to')) {
                    $q->whereBetween('created_at', [
                        Carbon::parse($request->from)->startOfDay(),
                        Carbon::parse($request->to)->endOfDay(),
                    ]);
                }

            });
        }

        $brands = $query->get();

        return DataTables::of($brands)
            ->addIndexColumn()
            ->addColumn('brand_name', function ($row) {
                return $row->name;
            })
            ->addColumn('total_orders', function ($row) {
                return OrderItem::whereHas('product', function ($q) use ($row) {
                    $q->where('brand_id', $row->id);
                })->distinct('order_id')->count('order_id');
            })
            ->addColumn('latest_order_id', function ($row) {
                $latestOrderItem = OrderItem::whereHas('product', function ($q) use ($row) {
                    $q->where('brand_id', $row->id);
                })->latest()->first();

                return $latestOrderItem ? $latestOrderItem->order_id : 'N/A';
            })
            ->addColumn('latest_order_date', function ($row) {
                $latestOrderItem = OrderItem::whereHas('product', function ($q) use ($row) {
                    $q->where('brand_id', $row->id);
                })->latest()->first();

                return $latestOrderItem && $latestOrderItem->created_at
                ? $latestOrderItem->created_at->format('d M Y h:i A')
                : 'N/A';
            })
            ->addColumn('status_text', function ($row) {
                $latestOrderItem = OrderItem::whereHas('product', function ($q) use ($row) {
                    $q->where('brand_id', $row->id);
                })->latest()->first();

                return $latestOrderItem && $latestOrderItem->order && $latestOrderItem->order->ordertype
                ? $latestOrderItem->order->ordertype->name
                : 'N/A';
            })
            ->addColumn('status_dropdown', function ($row) {
                $latestOrderItem = OrderItem::whereHas('product', function ($q) use ($row) {
                    $q->where('brand_id', $row->id);
                })->latest()->first();

                if (!$latestOrderItem || !$latestOrderItem->order) {
                    return 'N/A';
                }

                $options = '';

                foreach (Ordertype::all() as $status) {
                    $selected = $latestOrderItem->order->status == $status->id ? 'selected' : '';
                    $options .= "<option value='{$status->id}' {$selected}>{$status->name}</option>";
                }

                return "<select class='form-control status-update' data-id='{$latestOrderItem->order->id}'>{$options}</select>";
            })
            ->addColumn('option', function ($row) {
                return '<a href="' . route('order.view', $row->id) . '" class="btn btn-sm btn-primary">View Orders</a>';
            })
            ->rawColumns(['status_dropdown', 'option'])
            ->make(true);

    }

    public function customerwiseOrder() {
        $customers = Customer::select('id', 'name', 'phone')->orderBy('name')->get();

        return view('backend.orders.customerwise_orders', compact('customers'));
    }

    public function customerwiseOrderData(Request $request) {
        $query = Customer::withCount(['orders' => function ($q) use ($request) {

            if ($request->filled('status')) {
                $q->where('status', $request->status);
            }

            if ($request->filled('from') && $request->filled('to')) {
                $q->whereBetween('created_at', [
                    Carbon::parse($request->from)->startOfDay(),
                    Carbon::parse($request->to)->endOfDay(),
                ]);
            }

            $q->latest();
        },
        ])
            ->with(['orders' => function ($q) use ($request) {

                if ($request->filled('status')) {
                    $q->where('status', $request->status);
                }

                if ($request->filled('from') && $request->filled('to')) {
                    $q->whereBetween('created_at', [
                        Carbon::parse($request->from)->startOfDay(),
                        Carbon::parse($request->to)->endOfDay(),
                    ]);
                }

                $q->with('ordertype')
                    ->latest()
                    ->limit(1);
            },
            ]);

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('customer_id')) {
            $query->where('id', $request->customer_id);
        }

        $data = $query->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('name', function ($row) {
                return $row->name;
            })
            ->addColumn('phone', function ($row) {
                return $row->phone;
            })
            ->addColumn('type', function ($row) {
                return ucfirst($row->type);
            })
            ->addColumn('total_orders', function ($row) {
                return $row->orders_count;
            })
            ->addColumn('latest_order_id', function ($row) {
                $latestOrder = $row->orders->first();

                return $latestOrder ? $latestOrder->id : 'N/A';
            })
            ->addColumn('latest_order_status', function ($row) {
                $latestOrder = $row->orders->first();

                return $latestOrder && $latestOrder->ordertype
                ? $latestOrder->ordertype->name
                : 'N/A';
            })
            ->make(true);

    }

    public function byOrdertype($slug) {
        $customers = Customer::select('id', 'name', 'phone')->orderBy('name')->get();

        return view('backend.orders.order_by_status', compact('slug', 'customers'));
    }

    public function byOrdertypeData(Request $request, $slug) {
        $ordertype = Ordertype::where('slug', $slug)->firstOrFail();

        $query = Order::where('status', $ordertype->id)
            ->with(['customer', 'ordertype']);

        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

        if ($request->filled('customer_type')) {
            $query->whereHas('customer', function ($q) use ($request) {
                $q->where('type', $request->customer_type);
            });
        }

        if ($request->filled('from') && $request->filled('to')) {
            $query->whereBetween('created_at', [
                Carbon::parse($request->from)->startOfDay(),
                Carbon::parse($request->to)->endOfDay(),
            ]);
        }

        $query->latest();

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('id', function ($row) {
                return $row->id;
            })
            ->addColumn('customer', function ($row) {
                return $row->customer ? $row->customer->name : 'N/A';
            })
            ->addColumn('phone', function ($row) {
                return $row->customer ? $row->customer->phone : 'N/A';
            })
            ->addColumn('amount', function ($row) {
                return number_format($row->total_amount ?? 0, 2);
            })
            ->addColumn('status', function ($row) {
                return $row->ordertype ? $row->ordertype->name : 'N/A';
            })
            ->addColumn('status_text', function ($row) {
                return $row->ordertype->name ?? 'N/A';
            })
            ->addColumn('status_dropdown', function ($row) {
                $options = '';

                foreach (Ordertype::all() as $status) {
                    $selected = $row->status == $status->id ? 'selected' : '';
                    $options .= "<option value='{$status->id}' {$selected}>{$status->name}</option>";
                }

                return "<select class='form-control status-update' data-id='{$row->id}'>{$options}</select>";
            })
            ->addColumn('date', function ($row) {
                return $row->created_at ? $row->created_at->format('d M Y') : 'N/A';
            })
            ->addColumn('customer_id', function ($row) {
                return $row->customer_id;
            })
            ->addColumn('option', function ($row) {
                return '<a href="' . route('order.view', $row->id) . '" class="btn btn-sm btn-primary">Invoice</a>';
            })
            ->rawColumns(['option', 'status_dropdown'])
            ->make(true);
    }

    // public function updateStatus(Request $request) {
    //     $order         = Order::findOrFail($request->id);
        
    //     $order->status = $request->status;
    //     $order->save();

    //     return response()->json(['success' => true]);
    // }
    
    public function updateStatus(Request $request)
    {
        $order = Order::findOrFail($request->id);
    
        // Update order status
        $order->status = $request->status;
        $order->save();
    
        /** --------------------------------------
         * When order delivered (status = 6)
         * Add money to SellerWallet
         * --------------------------------------
         */
        if ($request->status == 6) {
    
            foreach ($order->items as $item) {
    
                $vendor = User::find($item->vendor_id);
                $product = Product::find($item->product_id);
    
                if (!$vendor) {
                    continue; 
                }
    
    
                $productTotal = $item->total_price;  
    
                /** ───────────── COMMISSION LOGIC ───────────── **/
                if ($product && $product->admin_commission) {
                    $adminCommission = ($productTotal * $product->admin_commission) / 100;
                } else {
                    $adminCommission = 0;
                }
    
                // Vendor earning
                $vendorEarn = $productTotal - $adminCommission;
    
                // Last balance
                $lastWallet = SellerWallet::where('vendor_id', $vendor->id)
                    ->orderBy('id', 'DESC')
                    ->first();
    
                $previousBalance = $lastWallet ? $lastWallet->current : 0;
                $newBalance      = $previousBalance + $vendorEarn;
    
                // Insert into sellerwallets table
                SellerWallet::create([
                    'title'       => 'Order Delivered #' . $order->id,
                    'vendor_id'   => $vendor->id,
                    'approved_by' => auth()->id(),
                    'amount'      => $vendorEarn,
                    'credit'      => 0,
                    'current'     => $newBalance,
                    'note'        => 'Earning from Order #' . $order->id,
                    'status'      => 0, 
                ]);
    
                // OPTIONAL: store inside OrderItem table also (if needed)
                $item->vendor_earning  = $vendorEarn;
                $item->admin_commission = $adminCommission;
                $item->save();
                
                /** UPDATE vendor total cash */
                $vendorUpdate = Vendor::where('user_id', $vendorId)->first();
                if ($vendorUpdate) {
                    $vendorUpdate->sellercash = ($vendorUpdate->sellercash ?? 0) + $vendorEarn;
                    $vendorUpdate->save();
                }
            }
        }
    
        return response()->json(['success' => true]);
    }



    public function orderView($id) {
        $order = Order::with(['customer', 'items'])->findOrFail($id);

        return view('backend.orders.view', compact('order'));
    }

    public function getCustomerOrders($customerId) {
        $orders = Order::with('ordertype')
            ->where('customer_id', $customerId)
            ->latest()
            ->get();
        // dd($orders);

        return response()->json($orders->map(function ($order) {
            return [
                'id'           => $order->id,
                'order_date'   => $order->created_at->format('d M Y h:i A'),
                'total_amount' => number_format($order->total_amount, 2),
                'status_text'  => $order->ordertype->name ?? 'N/A',
            ];
        }));
    }

}
