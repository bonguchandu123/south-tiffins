<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\CounterController;

Route::prefix('menu')->group(function () {
    Route::get('/', [MenuController::class, 'getMenu']);
    Route::get('/all', [MenuController::class, 'getAllMenu']);
    Route::post('/add', [MenuController::class, 'addItem']);
    Route::post('/update', [MenuController::class, 'updateItem']);
    Route::post('/delete', [MenuController::class, 'deleteItem']);
    Route::post('/toggle', [MenuController::class, 'toggleItem']);
});

Route::prefix('categories')->group(function () {
    Route::get('/', [CategoryController::class, 'getCategories']);
    Route::post('/add', [CategoryController::class, 'addCategory']);
    Route::post('/update', [CategoryController::class, 'updateCategory']);
});

Route::prefix('orders')->group(function () {
    Route::post('/place', [OrderController::class, 'placeOrder']);
    Route::get('/live', [CounterController::class, 'liveOrders']);
    Route::get('/today', [OrderController::class, 'todayOrders']);
    Route::get('/get', [OrderController::class, 'getOrder']);
    Route::post('/update-status', [OrderController::class, 'updateStatus']);
    Route::post('/cancel', [OrderController::class, 'cancelOrder']);
});

Route::prefix('payments')->group(function () {
    Route::post('/create',        [PaymentController::class, 'createOrder']);
    Route::post('/create-intent', [PaymentController::class, 'createOrder']);
    Route::post('/verify',        [PaymentController::class, 'verifyPayment']);
    Route::post('/cash-paid',     [PaymentController::class, 'markCashPaid']);
});

Route::prefix('tables')->group(function () {
    Route::get('/', [TableController::class, 'getTables']);
    Route::get('/active', [TableController::class, 'getActiveTables']);
    Route::post('/add', [TableController::class, 'addTable']);
    Route::post('/delete', [TableController::class, 'deleteTable']);
    Route::post('/toggle', [TableController::class, 'toggleTable']);
    Route::get('/qr', [TableController::class, 'getQR']);
});

Route::prefix('dashboard')->group(function () {
    Route::get('/today', [DashboardController::class, 'today']);
    Route::get('/weekly', [DashboardController::class, 'weekly']);
    Route::get('/monthly', [DashboardController::class, 'monthly']);
    Route::get('/billing', [BillingController::class, 'billingSummary']);
    Route::get('/top-items', [DashboardController::class, 'topItems']);
});

Route::prefix('reports')->group(function () {
    Route::get('/daily', [ReportController::class, 'daily']);
    Route::get('/yesterday', [ReportController::class, 'yesterday']);
    Route::get('/monthly', [ReportController::class, 'monthly']);
    Route::get('/bill', [ReportController::class, 'downloadBill']);
});

Route::get('/unsplash/search', function () {
    $query     = request('query', 'food');
    $accessKey = env('UNSPLASH_ACCESS_KEY');
    $url       = "https://api.unsplash.com/search/photos?query={$query}&per_page=12&orientation=squarish&client_id={$accessKey}";
    $response  = file_get_contents($url);
    $data      = json_decode($response, true);

    $images = array_map(function ($photo) {
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