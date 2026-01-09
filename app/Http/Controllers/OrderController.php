<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\OrderItem;
use App\Http\Requests\OrderRequest;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Inertia\Inertia;

class OrderController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Admin sees all orders
        if ($user->hasRole('admin')) {
            $orders = Order::with([
                'user:id,name',
                'items.product:id,name'
            ])->latest()->paginate(10);
        } 
        // User sees only their own orders
        else {
            $orders = Order::with([
                'user:id,name',
                'items.product:id,name'
            ])->where('user_id', $user->id)
              ->latest()
              ->paginate(10);
        }

        return Inertia::render('Orders/Index', [
            'orders' => $orders
        ]);
    }

    public function create()
    {
        $authUser = auth()->user();

        // Determine which users to show based on role
        if ($authUser->hasRole('admin')) {
            // Admin sees all users
            $users = User::select('id', 'name')->get();
        } else {
            // Normal user sees only themselves
            $users = User::select('id', 'name')
                        ->where('id', $authUser->id)
                        ->get();
        }

        return Inertia::render('Orders/Create', [
            'users' => $users,
            'categories' => Category::select('id', 'name')->get(),
        ]);
    }


    public function productsByCategory(Category $category)
    {
        return $category->products()
            ->select('id', 'name', 'price')
            ->get();
    }

    public function store(OrderRequest $request)
    {
        DB::transaction(function () use ($request) {

            // 1️⃣ Calculate Sub Total (SERVER SIDE)
            $subTotal = 0;

            foreach ($request->items as $item) {
                $product = Product::findOrFail($item['product_id']);
                $subTotal += $product->price * $item['quantity'];
            }

            // 2️⃣ Calculate Discount & Final Amount
            $discount = $request->discount ?? 0;
            $finalAmount = max($subTotal - $discount, 0);
            

            // 3️⃣ Create Order (IMPORTANT: final_amount INCLUDED)
            $order = Order::create([
                'user_id'      => $request->user_id,
                'sub_total'    => $subTotal,
                'discount'     => $discount,
                'final_amount' => $finalAmount,
                // optional: keep in sync if still needed
                'total_amount' => $finalAmount,
            ]);

            // 4️⃣ Create Order Items
            foreach ($request->items as $item) {
                $product = Product::findOrFail($item['product_id']);

                OrderItem::create([
                    'order_id'   => $order->id,
                    'category_id'=> $item['category_id'],
                    'product_id' => $product->id,
                    'quantity'   => $item['quantity'],
                    'price'      => $product->price,
                    'total'      => $product->price * $item['quantity'],
                ]);
            }
        });

        return redirect()
            ->route('orders.index')
            ->with('success', 'Order placed successfully.');
    }

    public function edit(Order $order)
    {
        $order->load('items.product.category');

        $authUser = auth()->user();

        // Determine which users to show based on role
        if ($authUser->hasRole('admin')) {
            // Admin can see all users
            $users = User::select('id', 'name')->get();
        } else {
            // Normal user can only see themselves
            $users = User::select('id', 'name')
                        ->where('id', $authUser->id)
                        ->get();
        }

        return Inertia::render('Orders/Edit', [
            'order' => $order,
            'users' => $users,
            'categories' => Category::with('childrenRecursive')->get(),
        ]);
    }


    public function update(OrderRequest $request, Order $order)
    {
        DB::transaction(function () use ($request, $order) {
            // Update basic order info
            $order->update([
                'user_id'     => $request->user_id,
                'sub_total'   => array_sum(array_map(fn($i) => $i['price'] * $i['quantity'], $request->items)),
                'discount'    => $request->discount,
                'final_amount'=> max(array_sum(array_map(fn($i) => $i['price'] * $i['quantity'], $request->items)) - $request->discount, 0),
            ]);

            // Remove all old items and insert new
            $order->items()->delete();

            foreach ($request->items as $item) {
                $product = Product::findOrFail($item['product_id']);
                
                $order->items()->create([
                    'category_id' => $item['category_id'],
                    'product_id'  => $item['product_id'],
                    'quantity'    => $item['quantity'],
                    'price'       => $item['price'],
                    'total'      => $product->price * $item['quantity'],
                ]);
            }
        });

        return redirect()->route('orders.index')->with('success', 'Order updated successfully.');
    }

    public function invoice(Order $order)
    {
        $order->load([
            'user',
            'items.product.category'
        ]);

        $pdf = Pdf::loadView('orders.invoice', compact('order'))->setPaper('a4');
        return $pdf->download('invoice-order-' . $order->id . '.pdf');
    }
}
