<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Models\{Order, Product, Category};
use App\Http\Controllers\Inventory\ProductController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Inventory\PurchaseOrderController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Sales\SalesController;
use App\Http\Controllers\Sales\OrderController;
use App\Http\Controllers\Sales\CustomerController;

// Route model binding
Route::model('order', Order::class);
Route::model('product', Product::class);
Route::model('category', Category::class);

// Authentication Routes
Auth::routes();

// Public Website Routes
Route::get('/', [App\Http\Controllers\Website\HomeController::class, 'index'])->name('home');
Route::get('/products', [App\Http\Controllers\Website\ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [App\Http\Controllers\Website\ProductController::class, 'show'])->name('products.show');
Route::get('/categories/{category}', [App\Http\Controllers\Website\ProductController::class, 'category'])->name('products.category');

// Cart Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/cart', [App\Http\Controllers\Website\CartController::class, 'index'])->name('cart.index');
    Route::post('/cart', [App\Http\Controllers\Website\CartController::class, 'store'])->name('cart.store');
    Route::post('/cart/update/{cartItem}', [App\Http\Controllers\Website\CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove/{cartItem}', [App\Http\Controllers\Website\CartController::class, 'destroy'])->name('cart.destroy');
    Route::resource('checkout', App\Http\Controllers\Website\CheckoutController::class)->only(['index', 'store']);
});

// Admin Routes
Route::prefix('admin')->middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])->name('admin.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\AdminController::class, 'dashboard'])->name('dashboard');
    Route::resource('users', UserController::class);
    Route::resource('departments', DepartmentController::class);
    
    // Inventory Management
    Route::get('inventory', [App\Http\Controllers\Admin\AdminController::class, 'inventory'])->name('inventory.index');
    
    // Purchase Orders
    Route::get('purchase-orders', [App\Http\Controllers\Admin\AdminController::class, 'purchaseOrders'])->name('purchase-orders.index');
    Route::get('purchase-orders/create', [App\Http\Controllers\Admin\AdminController::class, 'createPurchaseOrder'])->name('purchase-orders.create');
    Route::post('purchase-orders', [App\Http\Controllers\Admin\AdminController::class, 'storePurchaseOrder'])->name('purchase-orders.store');
    Route::get('purchase-orders/{purchaseOrder}', [App\Http\Controllers\Admin\AdminController::class, 'showPurchaseOrder'])->name('purchase-orders.show');
    Route::post('purchase-orders/{purchaseOrder}/approve', [App\Http\Controllers\Admin\AdminController::class, 'approvePurchaseOrder'])
        ->name('purchase-orders.approve');
    
    // Department Items Assignment
    Route::get('department-items', [App\Http\Controllers\Admin\AdminController::class, 'departmentItems'])->name('department-items.index');
    Route::post('department-items/assign', [App\Http\Controllers\Admin\AdminController::class, 'assignItemsToDepartment'])->name('department-items.assign');
    
    // Stock Alerts
    Route::get('stock/alerts', [App\Http\Controllers\Admin\AdminController::class, 'stockAlerts'])->name('stock.alerts');
    
    // Admin reports
    Route::get('reports', [App\Http\Controllers\Admin\AdminController::class, 'reports'])->name('reports.index');
    Route::get('reports/inventory', [App\Http\Controllers\Admin\ReportController::class, 'inventory'])->name('reports.inventory');
    Route::get('reports/sales', [App\Http\Controllers\Admin\ReportController::class, 'sales'])->name('reports.sales');
});

// Sales Routes
Route::prefix('sales')->middleware(['auth', \App\Http\Middleware\SalesMiddleware::class])->name('sales.')->group(function () {
    Route::get('/dashboard', [SalesController::class, 'dashboard'])->name('dashboard');
    Route::resource('orders', OrderController::class);
    Route::resource('customers', CustomerController::class);
});

// Inventory Routes
Route::prefix('inventory')->middleware(['auth', \App\Http\Middleware\InventoryMiddleware::class])->name('inventory.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Inventory\InventoryController::class, 'dashboard'])->name('dashboard');
    
    // Product Management
    Route::resource('products', ProductController::class);
    
    // Stock Management
    Route::get('stock', [App\Http\Controllers\Inventory\StockController::class, 'index'])->name('stock.index');
    Route::get('stock/low', [App\Http\Controllers\Inventory\StockController::class, 'lowStock'])->name('stock.low');
    Route::get('stock/alerts', [App\Http\Controllers\Inventory\StockController::class, 'alerts'])->name('stock.alerts');
    Route::post('stock/reorder/{product}', [App\Http\Controllers\Inventory\StockController::class, 'reorder'])->name('stock.reorder');
    Route::post('stock/adjust/{product}', [App\Http\Controllers\Inventory\StockController::class, 'adjust'])->name('stock.adjust');
    
    // Purchase Orders
    Route::resource('purchase-orders', PurchaseOrderController::class);
    Route::get('purchase-orders/{purchaseOrder}/receive', [PurchaseOrderController::class, 'showReceive'])->name('purchase-orders.show-receive');
    Route::post('purchase-orders/{purchaseOrder}/receive', [PurchaseOrderController::class, 'receive'])->name('purchase-orders.receive');
    
    // Supplier Management
    Route::resource('suppliers', App\Http\Controllers\Inventory\SupplierController::class);

    // Department Assignment
    Route::get('assignments', [App\Http\Controllers\Inventory\AssignmentController::class, 'index'])->name('assignments.index');
    Route::post('assignments', [App\Http\Controllers\Inventory\AssignmentController::class, 'store'])->name('assignments.store');
    
    // Reports
    Route::get('reports/stock-movement', [App\Http\Controllers\Inventory\ReportController::class, 'stockMovement'])->name('reports.stock-movement');
    Route::get('reports/low-stock', [App\Http\Controllers\Inventory\ReportController::class, 'lowStock'])->name('reports.low-stock');
});

// Account Management Routes
Route::middleware(['auth'])->group(function () {
    Route::prefix('account')->name('account.')->group(function () {
        Route::get('/', [App\Http\Controllers\Website\AccountController::class, 'index'])->name('index');
        Route::get('/edit', [App\Http\Controllers\Website\AccountController::class, 'edit'])->name('edit');
        Route::put('/', [App\Http\Controllers\Website\AccountController::class, 'update'])->name('update');
        Route::get('/orders', [App\Http\Controllers\Website\AccountController::class, 'orders'])->name('orders');
        Route::get('/orders/{order}', [App\Http\Controllers\Website\AccountController::class, 'orderShow'])->name('orders.show');
    });
});

