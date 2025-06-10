<?php

namespace App\Providers;

use App\Models\Employee;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Permission;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // 權限等級3
        Gate::define('manage-employees', function (Employee $employee) {
            $permission = Permission::find($employee->permission_id);
            return $permission && $permission->permission_level === 3;
        });

        // 權限等級2
        Gate::define('manage-level-2', function (Employee $employee) {
            $permission = Permission::find($employee->permission_id);
            return $permission && $permission->permission_level >= 2;
        });

        // 權限等級1 (基礎權限，不需要特別定義，因為所有員工都擁有)
    }
}
