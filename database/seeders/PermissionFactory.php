<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Permission;
class PermissionFactory extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // permission_level 權限等級
        // 3 = 最高權限，可以管理所有功能
        // 2 = 中等權限，可以管理大部分功能，但不能管理員工
        // 1 = 最低權限，只能查看產品和廣告
        $permissions = [
            [
                'permission_level' => 3,
                'job_title' => '店長',
            ],
            [
                'permission_level' => 2,
                'job_title' => '主管',
            ],
            [
                'permission_level' => 1,
                'job_title' => '員工',
            ],
        ];

        Permission::insert($permissions);
    }
}
