<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;


class UserOrderController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $orders = Order::where('user_id', $user->id)
            ->with(['assemblyService']) // Eager load assembly service
            ->orderBy('order_date', 'desc')
            ->get();

        $orders->each(function ($order) {
            $order->orderDetails = $order->details->map(function ($detail) {
                return [
                    'product' => $detail->product,
                    'quantity' => $detail->quantity,
                    'unit_price' => $detail->unit_price,
                    'discount_amount' => $detail->discount_amount,
                    'subtotal' => $detail->subtotal,
                ];
            });

            if ($order->payment_status == 'unpaid') {
                $order->payment_status = '未付款';
            } elseif ($order->payment_status == 'paid') {
                $order->payment_status = '已付款';
            } elseif ($order->payment_status == 'failed') {
                $order->payment_status = '付款失敗';
            }
            if ($order->order_status == 'pending') {
                $order->order_status = '待處理';
            } elseif ($order->order_status == 'paid') {
                $order->order_status = '已付款';
            } elseif ($order->order_status == 'shipped') {
                $order->order_status = '已出貨';
            } elseif ($order->order_status == 'completed') {
                $order->order_status = '已完成';
            } elseif ($order->order_status == 'cancelled') {
                $order->order_status = '已取消';
            }
        });
        return view('user.orders', compact('orders'));
    }

    // Show details of a specific order
    public function show($order_id)
    {
        $user = Auth::user();
        $order = Order::where('order_id', $order_id)
            ->where('user_id', $user->id)
            ->with(['assemblyService', 'details.product', 'details.discount'])
            ->firstOrFail();

        $order_details = $order->details->map(function ($detail) {
            return [
                'product' => $detail->product,
                'quantity' => $detail->quantity,
                'unit_price' => $detail->unit_price,
                'discount_amount' => $detail->discount_amount,
                'subtotal' => $detail->subtotal,
            ];
        });
        $order->orderDetails = $order_details;

        $order->orderDetails  = $order_details;

        if ($order->payment_status == 'unpaid') {
            $order->payment_status = '未付款';
        } elseif ($order->payment_status == 'paid') {
            $order->payment_status = '已付款';
        } elseif ($order->payment_status == 'failed') {
            $order->payment_status = '付款失敗';
        }
        if ($order->order_status == 'pending') {
            $order->order_status = '待處理';
        } elseif ($order->order_status == 'paid') {
            $order->order_status = '已付款';
        } elseif ($order->order_status == 'shipped') {
            $order->order_status = '已出貨';
        } elseif ($order->order_status == 'completed') {
            $order->order_status = '已完成';
        } elseif ($order->order_status == 'cancelled') {
            $order->order_status = '已取消';
        }
        return view('user.order-details', compact('order'));
    }
}
