<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Guest Routes
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->get('/', function () {
    return Inertia::render('Auth/Login', [
        'canLogin'       => Route::has('login'),
        'canRegister'    => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion'     => PHP_VERSION,
    ]);
});

require __DIR__.'/auth.php';

/*
|--------------------------------------------------------------------------
| Authenticated Routes (Both Admin & User)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    /* Dashboard */
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    /* Profile */
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
    });

    // Orders - Both roles can access, controller filters data
    Route::resource('orders', OrderController::class)
        ->middleware('permission:view orders', 'permission:create orders');

    // Invoice (accessible to both)
    Route::get('orders/{order}/invoice', [OrderController::class, 'invoice'])
        ->name('orders.invoice')
        ->middleware('permission:view orders');

    // Products by category (both can see)
    Route::get('categories/{category}/products', 
        [OrderController::class, 'productsByCategory']
    )->name('categories.products');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->group(function () {

    /* Users Management */
    Route::resource('users', UserController::class)
        ->except(['show'])
        ->middleware('permission:view users|create users|edit users|delete users');

    /* Categories */
    Route::resource('categories', CategoryController::class)
        ->middleware('permission:view categories|create categories|edit categories|delete categories');

    /* Products */
    Route::resource('products', ProductController::class)
        ->middleware('permission:view products|create products|edit products|delete products');
});