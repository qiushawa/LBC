<?php

namespace App\Http\Controllers\Shop;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Models\CustomConfiguration;
use App\Models\ConfigurationDetail;
use App\Models\Product;
use App\Models\AssemblyService;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use App\Models\Discount;
use Illuminate\Support\Facades\DB;


class ConfigurationController extends Controller
{
    public function submitCustomConfiguration(Request $request): JsonResponse
    {
        $data = $request->validate([
            'config_name' => 'required|string|max:255',
            'details' => 'required|array',
            'details.*.product_id' => 'required|exists:products,product_id',
            'details.*.quantity' => 'required|integer|min:1',
            'details.*.unit_price' => 'required|numeric|min:0',
            'details.*.discount_amount' => 'nullable|numeric|min:0',
        ]);
        // 建立新的客製化配置
        $configuration = CustomConfiguration::create([
            'user_id' => $request->user()->id,
            'config_name' => $data['config_name'],
            'total_price' => 0,
        ]);
        $totalPrice = 0;
        // 處理配置細節
        foreach ($data['details'] as $detail) {
            $product = Product::findOrFail($detail['product_id']);
            $unitPrice = $detail['unit_price'] ?? $product->price;
            $subtotal = ($unitPrice - $detail['discount_amount']) * $detail['quantity'];
            $totalPrice += $subtotal;
            ConfigurationDetail::create([
                'config_id' => $configuration->config_id,
                'product_id' => $detail['product_id'],
                'quantity' => $detail['quantity'],
                'unit_price' => $detail['unit_price'],
                'discount_amount' => $detail['discount_amount'] ?? 0,
                'subtotal' => $subtotal,
            ]);
        }
        // 更新配置的總價
        $configuration->update(['total_price' => $totalPrice]);
        return response()->json([
            'message' => 'Custom configuration submitted successfully.',
            'configuration_id' => $configuration->config_id,
        ]);
    }
    public function showConfiguration($config_id, Request $request)
    {
        $configuration = CustomConfiguration::with('details.product', 'assemblyService')
            ->where('user_id', $request->user()->id)
            ->findOrFail($config_id);
        $productIds = $configuration->details->pluck('product_id')->unique();
        $discounts = Discount::whereHas('products', function ($query) use ($productIds) {
            $query->whereIn('products.product_id', $productIds);
        })
            ->with(['products' => function ($query) use ($productIds) {
                $query->whereIn('products.product_id', $productIds);
            }])->get()->flatMap(function ($discount) {
                return $discount->products->map(function ($product) use ($discount) {
                    return [
                        'discount_id' => $discount->discount_id,
                        'discount_name' => $discount->discount_name,
                        'discount_type' => $discount->discount_type,
                        'discount_value' => $discount->discount_value,
                        'start_date' => $discount->start_date,
                        'end_date' => $discount->end_date,
                        'product_id' => $product->product_id,
                    ];
                });
            });
        // 預設組裝服務
        $assemblyService = AssemblyService::where('availability_status', 'available')->first();
        return view(
            'shop.configuration.show',
            compact(
                'configuration',
                'discounts',
                'assemblyService'
            )
        );
    }

    public function updateDiscounts($config_id, Request $request): JsonResponse
    {
        Log::info('Received discount update data:', $request->all());

        $configuration = CustomConfiguration::where('user_id', $request->user()->id)
            ->findOrFail($config_id);

        $data = $request->validate([
            'discounts' => 'required|array',
            'discounts.*' => 'nullable|exists:discounts,discount_id',
            'assembly_service' => 'sometimes|boolean',
        ]);

        $totalPrice = 0;
        foreach ($configuration->details as $detail) {
            $discountId = $data['discounts'][$detail->detail_id] ?? null;
            $discountAmount = 0;

            if ($discountId) {
                $discount = Discount::where('discount_id', $discountId)
                    ->whereHas('products', function ($query) use ($detail) {
                        $query->where('products.product_id', $detail->product_id);
                    })
                    ->whereDate('start_date', '<=', now())
                    ->whereDate('end_date', '>=', now())
                    ->firstOrFail();

                $discountAmount = $discount->discount_type === 'percentage'
                    ? ($detail->unit_price * $discount->discount_value) / 100
                    : $discount->discount_value;
            }

            $subtotal = ($detail->unit_price - $discountAmount) * $detail->quantity;

            $detail->update([
                'discount_id' => $discountId,
                'discount_amount' => $discountAmount,
                'subtotal' => $subtotal,
            ]);

            $totalPrice += $subtotal;
        }

        // Handle assembly service
        $serviceId = null;
        if ($data['assembly_service']) {
            $assemblyService = AssemblyService::where('availability_status', 'available')->firstOrCreate([
                'service_name' => '標準組裝服務',
                'service_fee' => 100, // Example fee
                'service_description' => '提供專業的電腦組裝服務，確保所有組件正確安裝並運行。',
                'availability_status' => 'available',
            ]);
            $serviceId = $assemblyService->service_id;
            $totalPrice += $assemblyService->service_fee;
        }

        $configuration->update([
            'total_price' => $totalPrice,
        ]);

        return response()->json([
            'message' => 'Discounts and assembly service updated successfully.',
            'configuration_id' => $configuration->config_id,
            'redirect' => route('order.confirm', ['config_id' => $config_id, 'service_id' => $serviceId]),
        ]);
    }
    public function showOrderConfirmation($config_id, Request $request)
    {
        $configuration = CustomConfiguration::with('details.product', 'assemblyService')
            ->where('user_id', $request->user()->id)
            ->find($config_id);
        if (!$configuration) {
            return redirect()->route('shop.index')->with('error', '未找到配置或配置不屬於你。');
        }
        if ($configuration->order_id) {
            return redirect()->route('shop.index')->with('error', '此配置已下訂單');
        }
        $service_id = $request->input('service_id', null);
        $shipping_fee = 100; // 預設運費
        $service = AssemblyService::find($service_id);
        $configuration->service_id = $service_id;
        $configuration->assemblyService = $service;
        return response()
            ->view('shop.order.confirm', compact('configuration', 'shipping_fee'))
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
    }

    public function submitOrder($config_id, Request $request)
    {
        // 根據 config_id 取得當前使用者的客製化配置，並載入其細節與產品資料
        $configuration = CustomConfiguration::with('details.product')
            ->where('user_id', $request->user()->id)
            ->find($config_id);
        // 若查無此配置，或此配置不屬於當前使用者，導回首頁並顯示錯誤訊息
        if (!$configuration) {
            return redirect()->route('shop.index')->with('error', '未找到配置或配置不屬於你。');
        }
        // 若該組合已經建立過訂單，不允許重複下單
        if ($configuration->order_id) {
            return redirect()->route('shop.index')->with('error', '此配置已下訂單，無法再次下單。');
        }
        // 驗證前端送來的表單資料
        $data = $request->validate([
            'recipient_name' => 'required|string|max:255',       // 收件人姓名
            'recipient_phone' => 'required|string|max:20',       // 收件人電話
            'shipping_address' => 'required|string|max:255',     // 收件人地址
            'payment_method' => 'required|in:credit_card,cash_on_delivery,bank_transfer,e_wallet', // 付款方式
            'service_id' => 'nullable|exists:assembly_services,service_id', // 裝機服務（可選）
        ]);
        // 設定固定運費
        $shipping_fee = 100;
        // 計算總金額（商品總價 + 運費）
        $total_amount = $configuration->total_price + $shipping_fee;
        // 使用資料庫交易，確保訂單建立過程中的資料一致性
        return DB::transaction(function () use ($request, $configuration, $data, $shipping_fee, $total_amount) {
            // 建立訂單主資料
            $order = Order::create([
                'user_id' => $request->user()->id,
                'order_date' => now(),                            // 下單時間
                'order_status' => 'pending',                      // 初始狀態為待處理
                'total_amount' => $total_amount,                  // 總金額
                'shipping_fee' => $shipping_fee,                  // 運費
                'recipient_name' => $data['recipient_name'],      // 收件人姓名
                'recipient_phone' => $data['recipient_phone'],    // 收件人電話
                'shipping_address' => $data['shipping_address'],  // 寄送地址
                'payment_method' => $data['payment_method'],      // 付款方式
                'payment_status' => 'unpaid',                     // 初始為未付款
                'service_id' => $data['service_id'] ?? null,      // 裝機服務 ID（可為 null）
            ]);
            // 建立訂單細節（每個商品）
            foreach ($configuration->details as $detail) {
                OrderDetail::create([
                    'order_id' => $order->order_id,
                    'product_id' => $detail->product_id,
                    'discount_id' => $detail->discount_id,
                    'quantity' => $detail->quantity,
                    'unit_price' => $detail->unit_price,
                    'discount_amount' => $detail->discount_amount,
                    'subtotal' => $detail->subtotal,
                ]);
            }
            // 刪除該組合的細節（代表已下單完成）
            $configuration->details()->delete();
            // 刪除該組合主體（表示這組合已不再可用）
            $configuration->delete();
            // 回到首頁並顯示成功訊息與訂單編號
            return redirect()->route('shop.index')->with('success', '訂單已成功建立，訂單編號為：' . $order->order_id);
        });
    }
}
