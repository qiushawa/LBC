<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\AdController;
use App\Http\Controllers\Admin\DiscountController;
use Illuminate\Support\Facades\Route;

$domain = config('app.domains.admin', 'admin.qiushawa.studio');

Route::domain($domain)->group(function () {
    // 公開路由
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // 已登入員工身份
    Route::middleware('auth:employee')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

        // 員工圖片與上傳
        Route::prefix('/employee')->group(function () {
            Route::get('/images/{filename}', [EmployeeController::class, 'serveImage'])->name('employee.image');
            Route::post('/upload-image', [EmployeeController::class, 'uploadImage'])->name('employee.upload-image');
        });

        // 庫存查詢
        Route::get('/dashboard/inventorySearch', [DashboardController::class, 'inventorySearch'])->name('admin.inventory.search');

        // 員工管理 (Level 1 權限)
        Route::middleware('can:manage-employees')->prefix('/dashboard/employees')->group(function () {
            Route::get('/', [EmployeeController::class, 'index'])->name('admin.employees');
            Route::get('/create', [EmployeeController::class, 'create'])->name('admin.employees.create');
            Route::post('/', [EmployeeController::class, 'store'])->name('admin.employees.store');
            Route::get('/edit/{employee}', [EmployeeController::class, 'edit'])->name('admin.employees.edit');
            Route::put('/{employee}', [EmployeeController::class, 'update'])->name('admin.employees.update');
            Route::delete('/{employee}', [EmployeeController::class, 'destroy'])->name('admin.employees.delete');
        });

        // Level 2 管理權限群組
        Route::middleware('can:manage-level-2')->prefix('/dashboard')->group(function () {
            // 廣告管理
            Route::prefix('/ads')->group(function () {
                Route::get('/', [AdController::class, 'index'])->name('admin.ads');
                Route::get('/create', [AdController::class, 'create'])->name('admin.ads.create');
                Route::post('/', [AdController::class, 'store'])->name('admin.ads.store');
                Route::get('/{ad}', [AdController::class, 'show'])->name('admin.ads.show');
                Route::get('/{ad}/edit', [AdController::class, 'edit'])->name('admin.ads.edit');
                Route::put('/{ad}', [AdController::class, 'update'])->name('admin.ads.update');
                Route::delete('/{ad}', [AdController::class, 'destroy'])->name('admin.ads.destroy');
                Route::post('/upload-banner', [AdController::class, 'uploadBanner'])->name('admin.ads.upload-banner');
            });

            // 產品管理
            Route::prefix('/products')->group(function () {
                Route::get('/', [DashboardController::class, 'products'])->name('admin.products');
                Route::get('/create', [DashboardController::class, 'createProduct'])->name('admin.products.create');
                Route::post('/', [DashboardController::class, 'storeProduct'])->name('admin.products.store');
                Route::get('/{product}/edit', [DashboardController::class, 'editProduct'])->name('admin.products.edit');
                Route::put('/{product}', [DashboardController::class, 'updateProduct'])->name('admin.products.update');
                Route::delete('/{product}', [DashboardController::class, 'destroyProduct'])->name('admin.products.delete');
            });

            // 庫存管理
            Route::model('inventory', App\Models\Inventory::class);
            Route::get('/inventory', [DashboardController::class, 'inventory'])->name('admin.inventory');
            Route::patch('/inventory/{inventory}', [DashboardController::class, 'updateInventory'])->name('admin.inventory.update');

            // 折扣管理
            Route::prefix('/discounts')->group(function () {
                Route::get('/', [DiscountController::class, 'index'])->name('admin.discounts');
                Route::get('/create', [DiscountController::class, 'create'])->name('admin.discounts.create');
                Route::post('/', [DiscountController::class, 'store'])->name('admin.discounts.store');
                Route::get('/{id}/edit', [DiscountController::class, 'edit'])->name('admin.discounts.edit');
                Route::put('/{id}', [DiscountController::class, 'update'])->name('admin.discounts.update');
                Route::delete('/{id}', [DiscountController::class, 'destroy'])->name('admin.discounts.destroy');
            });
        });
    });
});
