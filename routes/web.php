<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\CounterController;
use App\Http\Controllers\CategoryController;

/*
|--------------------------------------------------------------------------
| Landing Page
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('index');
})->name('home');

/*
|--------------------------------------------------------------------------
| Admin Auth
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Customer Routes (Public)
|--------------------------------------------------------------------------
*/
Route::prefix('menu')->name('customer.')->group(function () {
    Route::get('/', [MenuController::class, 'customerMenu'])->name('menu');
    Route::get('/order-confirm', [OrderController::class, 'orderConfirm'])->name('order.confirm');
    Route::get('/order-status', [OrderController::class, 'orderStatus'])->name('order.status');
});

/*
|--------------------------------------------------------------------------
| Counter Routes
|--------------------------------------------------------------------------
*/
Route::prefix('counter')->name('counter.')->group(function () {
    Route::get('/login', [CounterController::class, 'showLogin'])->name('login');
    Route::post('/login', [CounterController::class, 'login'])->name('login.post');
    Route::get('/logout', [CounterController::class, 'logout'])->name('logout');

    Route::middleware('counter.auth')->group(function () {
        Route::get('/', [CounterController::class, 'index'])->name('index');
    });
});

/*
|--------------------------------------------------------------------------
| Admin Routes (Protected — blade views)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->middleware('admin.auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/menu', [MenuController::class, 'adminMenu'])->name('menu');
    Route::get('/tables', [TableController::class, 'index'])->name('tables');
    Route::get('/billing', [BillingController::class, 'index'])->name('billing');
    Route::get('/orders', [OrderController::class, 'adminOrders'])->name('orders');
    Route::get('/reports', [ReportController::class, 'index'])->name('reports');
});

/*
|--------------------------------------------------------------------------
| Public API Routes (no auth needed — customer facing)
|--------------------------------------------------------------------------
*/
Route::prefix('api')->group(function () {

    // Menu
    Route::get('/menu', [MenuController::class, 'getMenu']);

    // Orders (customer places order, checks status)
    Route::post('/orders/place', [OrderController::class, 'placeOrder']);
    Route::get('/orders/get', [OrderController::class, 'getOrder']);

    // Payments (Stripe intent + cash)
    Route::post('/payments/create-intent', [PaymentController::class, 'createOrder']);
    Route::post('/payments/create',        [PaymentController::class, 'createOrder']);
    Route::post('/payments/verify',        [PaymentController::class, 'verifyPayment']);
    Route::post('/payments/cash-paid',     [PaymentController::class, 'markCashPaid']);

    // Tables (customer menu needs active tables)
    Route::get('/tables/active', [TableController::class, 'getActiveTables']);

    // Unsplash image search (public)
    Route::get('/unsplash/search', function () {
        $query     = request('query', 'food');
        $accessKey = env('UNSPLASH_ACCESS_KEY');
        $url       = "https://api.unsplash.com/search/photos?query={$query}&per_page=12&orientation=squarish&client_id={$accessKey}";
        $response  = file_get_contents($url);
        $data      = json_decode($response, true);
        $images    = array_map(function ($photo) {
            return [
                'id'           => $photo['id'],
                'url'          => $photo['urls']['regular'],
                'thumb'        => $photo['urls']['thumb'],
                'small'        => $photo['urls']['small'],
                'alt'          => $photo['alt_description'] ?? '',
                'photographer' => $photo['user']['name']
            ];
        }, $data['results'] ?? []);
        return response()->json(['images' => $images]);
    });
});

/*
|--------------------------------------------------------------------------
| Admin API Routes (session auth required — uses web middleware + session)
|--------------------------------------------------------------------------
*/
Route::prefix('api')->middleware('admin.auth')->group(function () {

    // Dashboard
    Route::get('/dashboard/today',    [DashboardController::class, 'today']);
    Route::get('/dashboard/weekly',   [DashboardController::class, 'weekly']);
    Route::get('/dashboard/monthly',  [DashboardController::class, 'monthly']);
    Route::get('/dashboard/billing',  [BillingController::class, 'billingSummary']);
    Route::get('/dashboard/top-items',[DashboardController::class, 'topItems']);

    // Menu management
    Route::get('/menu/all',       [MenuController::class, 'getAllMenu']);
    Route::post('/menu/add',      [MenuController::class, 'addItem']);
    Route::post('/menu/update',   [MenuController::class, 'updateItem']);
    Route::post('/menu/delete',   [MenuController::class, 'deleteItem']);
    Route::post('/menu/toggle',   [MenuController::class, 'toggleItem']);

    // Categories
    Route::get('/categories',         [CategoryController::class, 'getCategories']);
    Route::post('/categories/add',    [CategoryController::class, 'addCategory']);
    Route::post('/categories/update', [CategoryController::class, 'updateCategory']);

    // Orders (admin)
    Route::get('/orders/today',         [OrderController::class, 'todayOrders']);
    Route::post('/orders/update-status',[OrderController::class, 'updateStatus']);
    Route::post('/orders/cancel',       [OrderController::class, 'cancelOrder']);

    // Tables (admin)
    Route::get('/tables',         [TableController::class, 'getTables']);
    Route::post('/tables/add',    [TableController::class, 'addTable']);
    Route::post('/tables/delete', [TableController::class, 'deleteTable']);
    Route::post('/tables/toggle', [TableController::class, 'toggleTable']);
    Route::get('/tables/qr',      [TableController::class, 'getQR']);

    // Reports
    Route::get('/reports/daily',     [ReportController::class, 'daily']);
    Route::get('/reports/yesterday', [ReportController::class, 'yesterday']);
    Route::get('/reports/monthly',   [ReportController::class, 'monthly']);
    Route::get('/reports/bill',      [ReportController::class, 'downloadBill']);
});

/*
|--------------------------------------------------------------------------
| Counter API Routes (counter auth required)
|--------------------------------------------------------------------------
*/
Route::prefix('api')->middleware('counter.auth')->group(function () {
    Route::get('/orders/live', [CounterController::class, 'liveOrders']);
});