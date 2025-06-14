<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // 1. Users Table
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // 使用者編號，BIGINT UNSIGNED AUTO_INCREMENT
            $table->string('name'); // 使用者名稱
            $table->string('email')->unique(); // 使用者電子郵件，唯一
            $table->timestamp('email_verified_at')->nullable(); // 電子郵件驗證時間，可為空
            $table->string('password'); // 密碼雜湊值
            $table->rememberToken(); // 記住我令牌
            $table->timestamps(); // 建立時間、更新時間
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        // 2. Ads Table
        Schema::create('ads', function (Blueprint $table) {
            $table->increments('ad_id'); // 自動遞增整數主鍵
            $table->string('ad_title', 50); // 廣告標題
            $table->string('ad_content', 4096); // 廣告內文，支援 Markdown
            $table->char('ad_banner', 10); // 廣告橫幅雜湊（如用於圖檔名）
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        // 3. Product Categories Table
        Schema::create('product_categories', function (Blueprint $table) {
            $table->unsignedInteger('category_id')->autoIncrement()->primary(); // 類別編號
            $table->string('category_name', 50); // 類別名稱
            $table->char('category_icon', 10); // 類別圖示，儲存雜湊用於定位檔名
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        // 4. Discounts Table
        Schema::create('discounts', function (Blueprint $table) {
            $table->unsignedInteger('discount_id')->autoIncrement()->primary(); // 折價編號
            $table->string('discount_name', 50); // 折價名稱
            $table->enum('discount_type', ['percentage', 'fixed_amount']); // 折價類型
            $table->integer('discount_value'); // 折價數值，例如 20（代表 20% 或 20 元）
            $table->date('start_date'); // 開始日期
            $table->date('end_date'); // 結束日期
            $table->string('discount_description', 4096); // 折價說明，支援 Markdown
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });
        Schema::create('permissions', function (Blueprint $table) {
            $table->id('permission_id')->autoIncrement()->primary();
            $table->integer('permission_level'); // 權限層級
            $table->string('job_title', 255); // 職務名稱

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });
        // 5. Employees Table
        Schema::create('employees', function (Blueprint $table) {
            $table->unsignedInteger('employee_id')->autoIncrement()->primary(); // 員工編號
            $table->string('employee_name', 255); // 員工名稱
            $table->string('employee_email', 255)->unique(); // 員工電子郵件，唯一
            $table->string('password', 255); // 密碼雜湊值
            $table->unsignedBigInteger('permission_id'); // 外鍵，關聯到權限表

            // 外鍵約束
            $table->foreign('permission_id')
                ->references('permission_id')
                ->on('permissions')
                ->onDelete('restrict'); // 限制刪除，防止權限被誤刪

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });



        // 6. Suppliers Table
        Schema::create('suppliers', function (Blueprint $table) {
            $table->unsignedInteger('supplier_id')->autoIncrement()->primary(); // 供應商編號
            $table->string('supplier_name', 255); // 供應商名稱
            $table->string('contact_phone', 20); // 聯絡電話
            $table->string('contact_address', 255); // 聯絡地址
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        // 7. Assembly Services Table
        Schema::create('assembly_services', function (Blueprint $table) {
            $table->unsignedInteger('service_id')->autoIncrement()->primary(); // 服務編號
            $table->string('service_name', 50); // 服務名稱
            $table->integer('service_fee'); // 服務費用，單位：元
            $table->string('service_description', 4096); // 服務說明，支援 Markdown
            $table->enum('availability_status', ['available', 'unavailable']); // 可用狀態
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        // 8. Products Table
        Schema::create('products', function (Blueprint $table) {
            $table->unsignedInteger('product_id')->autoIncrement()->primary(); // 商品編號
            $table->unsignedInteger('category_id'); // 類別編號
            $table->unsignedInteger('supplier_id')->nullable(); // 供應商編號，可為空
            $table->string('product_name', 50); // 商品名稱
            $table->string('product_description', 4096); // 商品說明，支援 Markdown
            $table->char('product_image', 10); // 商品圖片，儲存雜湊用於定位檔名
            $table->integer('product_price'); // 商品定價，單位：元
            $table->date('launch_date'); // 上架日期
            $table->enum('launch_status', ['active', 'inactive'])->default('active'); // 上架狀態
            $table->foreign('category_id')->references('category_id')->on('product_categories')->onDelete('restrict');
            $table->foreign('supplier_id')->references('supplier_id')->on('suppliers')->onDelete('set null');
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        // 9. Orders Table
        Schema::create('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('order_id')->autoIncrement()->primary(); // 訂單編號
            $table->unsignedBigInteger('user_id'); // 使用者編號
            $table->timestamp('order_date'); // 訂單日期
            $table->enum('order_status', ['pending', 'paid', 'shipped', 'completed', 'canceled']); // 訂單狀態
            $table->integer('total_amount'); // 總金額，含折扣後、含運費與服務費用，單位：元
            $table->integer('shipping_fee'); // 運費
            $table->string('recipient_name', 255); // 收貨人姓名
            $table->string('recipient_phone', 20); // 收貨人電話
            $table->string('shipping_address', 255); // 收貨地址
            $table->enum('payment_method', ['credit_card', 'cash_on_delivery', 'bank_transfer', 'e_wallet']); // 付款方式
            $table->enum('payment_status', ['unpaid', 'paid', 'failed']); // 付款狀態
            $table->unsignedInteger('service_id')->nullable(); // 服務編號，可為空
            $table->timestamps(); // 建立時間、更新時間
            $table->foreign('user_id')->references('id')->on('users')->onDelete('restrict');
            $table->foreign('service_id')->references('service_id')->on('assembly_services')->onDelete('set null');
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        // 10. Order Details Table
        Schema::create('order_details', function (Blueprint $table) {
            $table->unsignedBigInteger('detail_id')->autoIncrement()->primary(); // 明細編號
            $table->unsignedBigInteger('order_id'); // 訂單編號
            $table->unsignedInteger('product_id'); // 商品編號
            $table->unsignedInteger('discount_id')->nullable(); // 折價編號，可為空
            $table->integer('quantity'); // 數量
            $table->integer('unit_price'); // 單價，未折扣
            $table->integer('discount_amount'); // 折扣金額
            $table->integer('subtotal'); // 小計，單價 * 數量 - 折扣金額
            $table->foreign('order_id')->references('order_id')->on('orders')->onDelete('cascade');
            $table->foreign('product_id')->references('product_id')->on('products')->onDelete('restrict');
            $table->foreign('discount_id')->references('discount_id')->on('discounts')->onDelete('set null');
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        // 11. Inventory Table
        Schema::create('inventory', function (Blueprint $table) {
            $table->unsignedInteger('inventory_id')->autoIncrement()->primary(); // 庫存編號
            $table->unsignedInteger('product_id'); // 商品編號
            $table->integer('stock_quantity'); // 庫存數量
            $table->integer('low_stock_threshold'); // 低庫存門檻
            $table->foreign('product_id')->references('product_id')->on('products')->onDelete('cascade');
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        // 12. Custom Configurations Table
        Schema::create('custom_configurations', function (Blueprint $table) {
            $table->unsignedBigInteger('config_id')->autoIncrement()->primary(); // 配置編號
            $table->unsignedBigInteger('user_id'); // 使用者編號
            $table->string('config_name', 50); // 配置名稱
            $table->integer('total_price'); // 總價，含折扣
            $table->string('order_id')->nullable();
            $table->timestamps(); // 建立時間、更新時間
            $table->foreign('user_id')->references('id')->on('users')->onDelete('restrict');
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        // 13. Configuration Details Table
        Schema::create('configuration_details', function (Blueprint $table) {
            $table->unsignedBigInteger('detail_id')->autoIncrement()->primary(); // 明細編號
            $table->unsignedBigInteger('config_id'); // 配置編號
            $table->unsignedInteger('product_id'); // 商品編號
            $table->integer('quantity'); // 數量
            $table->integer('unit_price'); // 單價，未折扣
            $table->integer('discount_amount'); // 折扣金額
            $table->integer('subtotal'); // 小計，單價 * 數量 - 折扣金額
            $table->foreign('config_id')->references('config_id')->on('custom_configurations')->onDelete('cascade');
            $table->foreign('product_id')->references('product_id')->on('products')->onDelete('restrict');
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        // 14. Discount Product Mappings Table
        Schema::create('discount_product_mappings', function (Blueprint $table) {
            $table->unsignedInteger('discount_id'); // 折價編號
            $table->unsignedInteger('product_id'); // 商品編號
            $table->primary(['discount_id', 'product_id']);
            $table->foreign('discount_id')->references('discount_id')->on('discounts')->onDelete('cascade');
            $table->foreign('product_id')->references('product_id')->on('products')->onDelete('cascade');
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });


        Schema::create('user_settings', function (Blueprint $table) {
            $table->unsignedBigInteger('setting_id')->autoIncrement()->primary(); // 設定編號
            $table->unsignedBigInteger('user_id')->unique(); // 使用者編號，唯一
            $table->string('preferred_language', 10); // 偏好語言
            $table->enum('theme', ['dark', 'light']); // 佈景主題
            $table->string('display_currency', 10); // 顯示貨幣
            $table->timestamps(); // 建立時間、更新時間
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        // 17. Inventory Status View
        DB::statement("
            CREATE VIEW inventory_status_view AS
            SELECT
                inventory.inventory_id, -- 庫存編號
                inventory.product_id, -- 商品編號
                inventory.stock_quantity, -- 庫存數量
                inventory.low_stock_threshold, -- 低庫存門檻
                CASE
                    WHEN products.launch_status = 'inactive' THEN 'discontinued' -- 停售
                    WHEN inventory.stock_quantity = 0 THEN 'out_of_stock' -- 缺貨
                    WHEN inventory.stock_quantity < inventory.low_stock_threshold THEN 'low_stock' -- 低庫存
                    ELSE 'normal' -- 正常
                END AS stock_status -- 庫存狀態
            FROM inventory
            LEFT JOIN products ON inventory.product_id = products.product_id
        ");
    }

    public function down(): void
    {
        DB::statement('DROP VIEW IF EXISTS inventory_status_view');

        Schema::dropIfExists('user_settings');
        Schema::dropIfExists('discount_product_mappings');
        Schema::dropIfExists('configuration_details');
        Schema::dropIfExists('custom_configurations');
        Schema::dropIfExists('inventory');
        Schema::dropIfExists('order_details');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('products');
        Schema::dropIfExists('assembly_services');
        Schema::dropIfExists('suppliers');
        Schema::dropIfExists('employees');
        Schema::dropIfExists('discounts');
        Schema::dropIfExists('product_categories');
        Schema::dropIfExists('ads');
        Schema::dropIfExists('users');
        Schema::dropIfExists('permissions');
    }
};
