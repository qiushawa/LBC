<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Product;
use App\Models\ProductCategorie;
use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Models\Ad;
use App\Models\Inventory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Services\ServerStatusService;


class DashboardController extends Controller
{
    protected $serverStatusService;

    public function __construct(ServerStatusService $serverStatusService)
    {
        $this->serverStatusService = $serverStatusService;
    }
    public function index()
    {
        $serverStatus = $this->serverStatusService->checkStatus();
        $serverStatus = $serverStatus['overall']; // 獲取整體狀態字串
        $onlineUsers = $this->serverStatusService->getOnlineUsers();

        $employees = Employee::count();
        $pendingReviews = 810; // 假設數據，需從實際資料來源替換
        $currentEmployee = Auth::guard('employee')->user();
        $permissionLevel = $currentEmployee->permission ? $currentEmployee->permission->permission_level : '未知';


        return view('admin.dashboard', compact(
            'employees',
            'pendingReviews',
            'permissionLevel',
            'serverStatus',
            'onlineUsers'
        ));
    }

    public function ads()
    {
        // 第一個廣告
        $ad1 = Ad::where('ad_id', 1)->first();
        $markdownText = $ad1 ? $ad1->ad_content : '';

        // Convert Markdown to HTML
        $converter = new \League\CommonMark\CommonMarkConverter(['html_input' => 'strip', 'allow_unsafe_links' => false]);
        $htmlContent = $converter->convert($markdownText)->getContent();

        // Pass to Blade view
        return view('admin.dashboard', compact('htmlContent'));
    }

    public function products(Request $request)
    {
        // 類別id
        $categoryId = $request->query('category_id', null);

        // 商品 with category
        if ($categoryId) {
            $category = ProductCategorie::find($categoryId);
            if (!$category) {
                return redirect()->route('admin.products')->withErrors(['error' => 'Category not found']);
            }
            $products = Product::with('category')
                ->byCategory($categoryId)
                ->paginate(15);
        } else {
            // 如果沒有提供類別ID，則獲取所有上架產品
            $products = Product::with('category')
                ->paginate(12);
        }
        // 批量處理Supplier資料
        for ($i = 0; $i < count($products); $i++) {

            $supplier = Supplier::find($products[$i]->supplier_id);
            if ($supplier) {
                $products[$i]->supplier_name = $supplier->supplier_name;
            } else {
                $products[$i]->supplier_name = '未知供應商';
            }
        }
        $categorys = ProductCategorie::all();
        $suppliers = Supplier::all();
        // 商品總數
        $totalProducts = Product::count();
        return view('admin.dashboard', compact(
            'products',
            'totalProducts',
            'categorys',
            'categoryId',
            'suppliers'
        ));
    }




    public function inventorySearch(Request $request)
    {
        $query = Inventory::select(
            'inventory.inventory_id',
            'products.product_name',
            'product_categories.category_name',
            'suppliers.supplier_name',
            'products.product_price',
            'inventory.stock_quantity',
            'inventory.low_stock_threshold',
            DB::raw("
            CASE
                WHEN products.launch_status = 'inactive' THEN 'discontinued'
                WHEN inventory.stock_quantity = 0 THEN 'out_of_stock'
                WHEN inventory.stock_quantity < inventory.low_stock_threshold THEN 'low_stock'
                ELSE 'normal'
            END AS stock_status
        ")
        )
            ->join('products', 'inventory.product_id', '=', 'products.product_id')
            ->join('product_categories', 'products.category_id', '=', 'product_categories.category_id')
            ->join('suppliers', 'products.supplier_id', '=', 'suppliers.supplier_id');

        // Handle search
        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('products.product_name', 'like', '%' . $search . '%')
                    ->orWhere('inventory.inventory_id', 'like', '%' . $search . '%');
            });
        }

        // Handle category filter (independent category ID query)
        if ($categoryId = $request->query('category_id')) {
            $query->where('products.category_id', $categoryId);
        }

        // Handle standalone category ID query without other filters
        if ($request->query('only_category') && $categoryId = $request->query('category_id')) {
            $query->where('products.category_id', $categoryId);
        }

        $products = $query->paginate(10);
        $categorys = ProductCategorie::all();
        $totalProducts = Inventory::count();

        return view('admin.dashboard', compact('products', 'categorys', 'totalProducts'));
    }
    public function getServerStatus()
    {
        $status = $this->serverStatusService->checkStatus();
        $onlineUsers = $this->serverStatusService->getOnlineUsers();

        return response()->json([
            'serverStatus' => $status['overall'], // Return the 'overall' string
            'onlineUsers' => $onlineUsers,
        ]);
    }
    public function createProduct()
    {
        $suppliers = Supplier::all();
        $categorys = ProductCategorie::all();
        // 建立庫存資料表

        return view('admin.products.create', compact('suppliers', 'categorys'));
    }

    // storeProduct
    public function storeProduct(Request $request)
    {
        $validatedData = $request->validate([
            'category_id' => 'required|exists:product_categories,category_id', // Changed 'id' to 'category_id'
            'supplier_id' => 'required|exists:suppliers,supplier_id',
            'product_name' => 'required|string|max:255',
            'product_description' => 'nullable|string',
            'product_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'product_price' => 'required|numeric|min:0',
        ]);

        // 預設上架日期為當天，狀態為 active
        $validatedData['launch_date'] = now()->toDateString();
        $validatedData['launch_status'] = 'active';

        // 處理圖片上傳
        if ($request->hasFile('product_image')) {
            // 生成隨機 10 個字元的檔案名稱
            $randomString = Str::random(10);
            $fileName = $randomString . '.png';
            // 儲存圖片到 public/images/products
            $request->file('product_image')->storeAs('images/products', $fileName, 'public');
            // 將檔案名稱存入 validatedData
            $validatedData['product_image'] = $randomString;
        }

        // 儲存產品
        $product = Product::create($validatedData);
        Inventory::create([
            'product_id' => $product->product_id, // 使用剛剛建立的產品ID
            'stock_quantity' => 0, // 預設庫存數量為0
            'low_stock_threshold' => 0, // 預設低庫存警示為0
        ]);
        return redirect()->route('admin.products')->with('success', 'Product created successfully.');
    }

    public function destroyProduct(Product $product)
    {
        // 刪除產品
        $product->delete();
        // 刪除相關庫存資料
        $inventory = Inventory::where('product_id', $product->product_id)->first();
        if ($inventory) {
            $inventory->delete();
        }
        return redirect()->route('admin.products')->with('success', 'Product deleted successfully.');
    }

    //editProduct
    public function editProduct(Product $product)
    {
        $suppliers = Supplier::all();
        $categorys = ProductCategorie::all();
        return view('admin.products.edit', compact('product', 'suppliers', 'categorys'));
    }
    public function updateProduct(Request $request, Product $product)
    {
        $validatedData = $request->validate([
            'category_id' => 'required|exists:product_categories,category_id',
            'supplier_id' => 'required|exists:suppliers,supplier_id',
            'product_name' => 'required|string|max:255',
            'product_description' => 'nullable|string',
            'product_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'product_price' => 'required|numeric|min:0',
        ]);

        // 處理圖片上傳
        if ($request->hasFile('product_image')) {
            // 生成隨機 10 個字元的檔案名稱
            $randomString = Str::random(10);
            $fileName = $randomString . '.png';
            // 儲存圖片到 public/images/products
            $request->file('product_image')->storeAs('images/products', $fileName, 'public');
            // 將檔案名稱存入 validatedData
            $validatedData['product_image'] = $randomString;
        }

        // 更新產品
        $product->update($validatedData);

        return redirect()->route('admin.products')->with('success', 'Product updated successfully.');
    }
    public function inventory(Request $request)
    {
        $categoryId = $request->query('category_id');
        $categorys = ProductCategorie::all();

        // Query inventory_status_view with product and category details
        $query = DB::table('inventory_status_view')
            ->join('products', 'inventory_status_view.product_id', '=', 'products.product_id')
            ->join('product_categories', 'products.category_id', '=', 'product_categories.category_id')
            ->join('suppliers', 'products.supplier_id', '=', 'suppliers.supplier_id')
            ->select(
                'inventory_status_view.*',
                'products.product_name',
                'products.product_image',
                'products.product_price',
                'product_categories.category_name',
                'suppliers.supplier_name'
            );

        if ($categoryId) {
            $query->where('products.category_id', $categoryId);
        }

        $products = $query->paginate(10);
        $totalProducts = Product::count();
        return view('admin.dashboard', compact('products', 'categorys', 'totalProducts', 'categoryId'));
    }

    public function updateInventory(Request $request, Inventory $inventory)
    {
        $validatedData = $request->validate([
            'stock_adjustment' => 'required|integer',
            'low_stock_threshold' => 'nullable|integer|min:0',
        ]);

        $newStock = $inventory->stock_quantity + $validatedData['stock_adjustment'];

        if ($newStock < 0) {
            return back()->withErrors(['stock_adjustment' => '庫存數量不能為負數']);
        }

        $inventory->update([
            'stock_quantity' => $newStock,
            'low_stock_threshold' => $validatedData['low_stock_threshold'] ?? $inventory->low_stock_threshold,
        ]);

        return redirect()->route('admin.inventory')->with('success', '庫存更新成功');
    }
    public function inventorySearchApi(Request $request)
    {
        $query = Product::query();

        // 處理搜尋
        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('product_name', 'like', '%' . $search . '%')
                    ->orWhere('inventory_id', 'like', '%' . $search . '%');
            });
        }

        // 處理類別篩選
        if ($categoryId = $request->query('category_id')) {
            $query->where('category_id', $categoryId);
        }

        $products = $query->paginate(10);
        $categorys = ProductCategorie::all();
        $totalProducts = Product::count();

        return view('admin.dashboard', compact('products', 'categorys', 'totalProducts'));
    }
}
