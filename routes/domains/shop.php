<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Shop\HomeController;


Route::domain('shop.qiushawa.studio')->group(function () {
    Route::controller(HomeController::class)
        ->name('shop.')
        ->group(function () {
            Route::get('/', 'index')->name('index');

            Route::get('/ad/{id}', 'showAd')->name('ad.show');
        });
});

