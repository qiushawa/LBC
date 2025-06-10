<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\AdController;
use Illuminate\Support\Facades\Route;

$domin = config('app.domains.admin', 'admin.qiushawa.studio');
Route::domain($domin)->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::middleware('auth:employee')->group(function () {
        Route::get('/employee-images/{filename}', [EmployeeController::class, 'serveImage'])->name('employee.image');
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
        Route::post('/employee/upload-image', [EmployeeController::class, 'uploadImage'])->name('employee.upload-image');
        // 員工管理路由
        Route::middleware('can:manage-employees')->group(function () {
            Route::get('/dashboard/employees', [EmployeeController::class, 'index'])->name('admin.employees');
            Route::get('/dashboard/employees/create', [EmployeeController::class, 'create'])->name('admin.employees.create');
            Route::post('/dashboard/employees', [EmployeeController::class, 'store'])->name('admin.employees.store');
            Route::get('/dashboard/employees/edit/{employee}', [EmployeeController::class, 'edit'])->name('admin.employees.edit');
            Route::put('/dashboard/employees/{employee}', [EmployeeController::class, 'update'])->name('admin.employees.update');
            Route::delete('/dashboard/employees/{employee}', [EmployeeController::class, 'destroy'])->name('admin.employees.delete');
        });
        // 廣告管理路由
        Route::middleware('can:manage-level-2')->group(function () {
            $adRoute = '/dashboard/ads/{ad}';
            Route::get('/dashboard/ads', [AdController::class, 'index'])->name('admin.ads');
            Route::get('/dashboard/ads/create', [AdController::class, 'create'])->name('admin.ads.create');
            Route::post('/dashboard/ads', [AdController::class, 'store'])->name('admin.ads.store');
            Route::get($adRoute, [AdController::class, 'show'])->name('admin.ads.show');
            Route::get("$adRoute/edit", [AdController::class, 'edit'])->name('admin.ads.edit');
            Route::put($adRoute, [AdController::class, 'update'])->name('admin.ads.update');
            Route::delete($adRoute, [AdController::class, 'destroy'])->name('admin.ads.destroy');
            Route::post('dashboard/ads/upload-banner', [AdController::class, 'uploadBanner'])->name('admin.ads.upload-banner');
            // 其他路由
            Route::get('/dashboard/products', [DashboardController::class, 'products'])->name('admin.products');
            Route::get('/dashboard/inventory', [DashboardController::class, 'inventory'])->name('admin.inventory');
        });
        // 庫存查詢路由（無權限限制）
        Route::get('/dashboard/inventory/search', [DashboardController::class, 'inventorySearch'])->name('admin.inventory.search');
    });
});
