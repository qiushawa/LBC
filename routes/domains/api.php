<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;


Route::domain('api.qiushawa.studio')->group(function () {
    Route::controller(ApiController::class)
        ->name('api.')
        ->group(function () {
            Route::get('/status', 'status')->name('status');
            Route::get('/products/category', 'productsByCategory')
                ->name('productsByCategory');
        });
    // 獲取上線人數
    Route::get('/status/online', [ApiController::class, 'getOnlineUsers'])
        ->name('status.online');

    // 需要登入的路由
    Route::middleware(['auth:sanctum'])->group(function () {
        // 送出CustomConfiguration

    });

});
