<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\AdController;
use Illuminate\Support\Facades\Route;

$domain = config('app.domains.admin', 'admin.qiushawa.studio');

// 管理員後台路由
Route::domain($domain)->group(function () {
    // 公開路由
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // 需要身份驗證的路由
    Route::middleware('auth:employee')->group(function () {
        // 控制台
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

        Route::get('/employee-images/{filename}', [EmployeeController::class, 'serveImage'])->name('employee.image');
        Route::post('/employee/upload-image', [EmployeeController::class, 'uploadImage'])->name('employee.upload-image');

        Route::middleware('can:manage-employees')->group(function () {
            Route::get('/dashboard/employees', [EmployeeController::class, 'index'])->name('admin.employees');
            Route::get('/dashboard/employees/create', [EmployeeController::class, 'create'])->name('admin.employees.create');
            Route::post('/dashboard/employees', [EmployeeController::class, 'store'])->name('admin.employees.store');
            Route::get('/dashboard/employees/edit/{employee}', [EmployeeController::class, 'edit'])->name('admin.employees.edit');
            Route::put('/dashboard/employees/{employee}', [EmployeeController::class, 'update'])->name('admin.employees.update');
            Route::delete('/dashboard/employees/{employee}', [EmployeeController::class, 'destroy'])->name('admin.employees.delete');
        });

        Route::middleware('can:manage-level-2')->group(function () {
            // Ad management
            Route::get('/dashboard/ads', [AdController::class, 'index'])->name('admin.ads');
            Route::get('/dashboard/ads/create', [AdController::class, 'create'])->name('admin.ads.create');
            Route::post('/dashboard/ads', [AdController::class, 'store'])->name('admin.ads.store');
            Route::get('/dashboard/ads/{ad}', [AdController::class, 'show'])->name('admin.ads.show');
            Route::get('/dashboard/ads/{ad}/edit', [AdController::class, 'edit'])->name('admin.ads.edit');
            Route::put('/dashboard/ads/{ad}', [AdController::class, 'update'])->name('admin.ads.update');
            Route::delete('/dashboard/ads/{ad}', [AdController::class, 'destroy'])->name('admin.ads.destroy');
            Route::post('/dashboard/ads/upload-banner', [AdController::class, 'uploadBanner'])->name('admin.ads.upload-banner');

            Route::get('/dashboard/products', [DashboardController::class, 'products'])->name('admin.products');
            Route::get('/dashboard/products/create', [DashboardController::class, 'createProduct'])->name('admin.products.create');
            Route::post('/dashboard/products', [DashboardController::class, 'storeProduct'])->name('admin.products.store');
            Route::get('/dashboard/products/{product}/edit', [DashboardController::class, 'editProduct'])->name('admin.products.edit');
            Route::put('/dashboard/products/{product}', [DashboardController::class, 'updateProduct'])->name('admin.products.update');
            Route::delete('/dashboard/products/{product}', [DashboardController::class, 'destroyProduct'])->name('admin.products.delete');

            Route::model('inventory', App\Models\Inventory::class);
            Route::get('/dashboard/inventory', [DashboardController::class, 'inventory'])->name('admin.inventory');
            Route::patch('/dashboard/inventory/{inventory}', [DashboardController::class, 'updateInventory'])->name('admin.inventory.update');

            // 庫存查詢
            Route::get('/dashboard/inventorySearch', [DashboardController::class, 'inventorySearch'])->name('admin.inventory.search');
        
        });


    });
});
