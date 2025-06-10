<?php

namespace Database\Seeders;

use App\Models\ProductCategorie;
use Illuminate\Database\Seeder;

class ProductCategorySeeder extends Seeder
{
    public function run(): void
    {
        ProductCategorie::factory()->create([
            'category_name' => '處理器 CPU',
            'category_icon' => 'cpu1234567',
        ]);
        ProductCategorie::factory()->create([
            'category_name' => '主機板 MB',
            'category_icon' => 'mb12345678',
        ]);
        ProductCategorie::factory()->create([
            'category_name' => '記憶體 RAM',
            'category_icon' => '1012345678',
        ]);
        ProductCategorie::factory()->create([
            'category_name' => '固態硬碟 SSD',
            'category_icon' => '1212345678',
        ]);
        ProductCategorie::factory()->create([
            'category_name' => '傳統硬碟 HDD',
            'category_icon' => '5123456789',
        ]);
        ProductCategorie::factory()->create([
            'category_name' => '顯示卡 VGA',
            'category_icon' => '9123456789',
        ]);
        ProductCategorie::factory()->create([
            'category_name' => '電源供應器 PSU',
            'category_icon' => 'case123456',
        ]);
        ProductCategorie::factory()->create([
            'category_name' => '機殼 CASE',
            'category_icon' => 'hdd1234567',
        ]);
        ProductCategorie::factory()->create([
            'category_name' => '散熱器 COOLER',
            'category_icon' => 'ssd1234567',
        ]);
        ProductCategorie::factory()->create([
            'category_name' => '螢幕 MONITOR',
            'category_icon' => 'vga1234567',
        ]);
    }
}
