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

        return view('user.orders', compact('orders'));
    }

    // Show details of a specific order
    public function show($order_id)
    {
        $user = Auth::user();
        $order = Order::where('order_id', $order_id)
            ->where('user_id', $user->id)
            ->with(['assemblyService', 'orderDetails.product', 'orderDetails.discount'])
            ->firstOrFail();

        return view('user.order-details', compact('order'));
    }
}
