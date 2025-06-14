<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class, // 使用者 Seeder
            // AdSeeder::class, // 廣告 Seeder
            ProductCategorySeeder::class, // 產品類別 Seeder
            SupplierSeeder::class, // 供應商 Seeder
            // ProductSeeder::class,
            PermissionFactory::class, // 權限 Seeder
            EmployeeSeeder::class, // 員工
        ]);
    }
}
