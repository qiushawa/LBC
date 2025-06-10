<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Employee; // Assuming you have an Employee model

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employees = [
            [
                'employee_name' => '邱聖傑',
                'employee_email' => 'qiushawa@gmail.com',
                'password' => bcrypt('qiushawa'),
                'permission_id' => 1, // 店長
            ],
            [
                'employee_name' => '劉博恩',
                'employee_email' => 'bon@gmail.com',
                'password' => bcrypt('bon'),
                'permission_id' => 2, // 主管
            ],
            [
                'employee_name' => '張鈞翔',
                'employee_email' => 'gc@gmail.com',
                'password' => bcrypt('gc'),
                'permission_id' => 3, // 員工
            ],
        ];

        Employee::insert($employees);

        // 100筆假資料
        Employee::factory()->count(47)->create([
            'permission_id' => 3, // 預設為員工
        ]);
    }
}
