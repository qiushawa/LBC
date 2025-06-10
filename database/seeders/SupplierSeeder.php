<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        // 華碩
        Supplier::factory()->create([
            'supplier_name' => 'ASUS',
            'contact_phone' => '02-1234-5678',
            'contact_address' => '台北市內湖區瑞光路123號',
        ]);
        // 微星
        Supplier::factory()->create([
            'supplier_name' => 'MSI',
            'contact_phone' => '02-2345-6789',
            'contact_address' => '台北市南港區經貿二路456號',
        ]);
        // 技嘉
        Supplier::factory()->create([
            'supplier_name' => 'GIGABYTE',
            'contact_phone' => '02-3456-7890',
            'contact_address' => '台北市中山區民權東路789號',
        ]);
        // 英特爾
        Supplier::factory()->create([
            'supplier_name' => 'Intel',
            'contact_phone' => '02-4567-8901',
            'contact_address' => '台北市信義區松山路234號',
        ]);
        // 超微
        Supplier::factory()->create([
            'supplier_name' => 'AMD',
            'contact_phone' => '02-5678-9012',
            'contact_address' => '台北市大安區仁愛路345號',
        ]);
        // 美光
        Supplier::factory()->create([
            'supplier_name' => 'Micron',
            'contact_phone' => '02-6789-0123',
            'contact_address' => '台北市萬華區中正路456號',
        ]);
        // 金士頓
        Supplier::factory()->create([
            'supplier_name' => 'Kingston',
            'contact_phone' => '02-7890-1234',
            'contact_address' => '台北市士林區天母東路567號',
        ]);
        // 聯立
        Supplier::factory()->create([
            'supplier_name' => '聯立科技',
            'contact_phone' => '02-8901-2345',
            'contact_address' => '台北市北投區中央北路678號',
        ]);
        // 十銓
        Supplier::factory()->create([
            'supplier_name' => 'Team Group',
            'contact_phone' => '02-9012-3456',
            'contact_address' => '台北市文山區興隆路789號',
        ]);
        // 威剛
        Supplier::factory()->create([
            'supplier_name' => 'ADATA',
            'contact_phone' => '02-0123-4567',
            'contact_address' => '台北市中正區忠孝西路123號',
        ]);
        // 君主
        Supplier::factory()->create([
            'supplier_name' => 'Kingmax',
            'contact_phone' => '02-1234-5679',
            'contact_address' => '台北市大同區重慶北路234號',
        ]);

        // WD
        Supplier::factory()->create([
            'supplier_name' => 'Western Digital',
            'contact_phone' => '02-2345-6780',
            'contact_address' => '台北市松山區光復南路345號',
        ]);

        
    }
}
