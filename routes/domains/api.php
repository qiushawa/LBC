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
});
