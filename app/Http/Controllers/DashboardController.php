<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use Inertia\Inertia;
class DashboardController extends Controller
{
    public function index()
    {
        // Fetch counts dynamically
        $categoryCount = Category::count();
        $productCount  = Product::count();
        $user = auth()->user();
        // Admin sees all orders, user sees only own orders
        $orderCount = $user->hasPermissionTo('view orders') && $user->hasRole('admin')
        ? Order::count()
        : Order::where('user_id', $user->id)->count();

        return Inertia::render('Dashboard', [
            'counts' => [
                'categories' => $categoryCount,
                'products'   => $productCount,
                'orders'     => $orderCount,
            ],
        ]);
    }
}
