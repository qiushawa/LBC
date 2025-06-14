<!-- resources/views/orders.blade.php -->
@extends('layouts.dashboard')

@section('title', '訂單紀錄')

@section('content')
    <div class="p-6 bg-white rounded-lg shadow">
        @if ($orders->isEmpty())
            <p class="text-gray-600">您目前沒有任何訂單。</p>
        @else
            <div class="space-y-4">
                @foreach ($orders as $order)
                    <div class="p-4 border rounded-lg">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold">訂單 #{{ $order->order_id }}</h3>
                            <span class="px-2 py-1 text-sm rounded {{ $order->order_status == 'completed' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                {{$order->order_status}}
                            </span>
                        </div>
                        <p class="text-gray-600">訂單日期：{{ $order->order_date }}</p>
                        <p class="text-gray-600">總金額：NT${{ number_format($order->total_amount) }}</p>
                        <span class="text-gray-600">付款狀態：</span>
                        @if ($order->payment_status == '未付款')
                            <span class="text-red-600">{{ $order->payment_status }}</span>
                        @elseif ($order->payment_status == '已付款')
                            <span class="text-green-600">{{ $order->payment_status }}</span>
                        @else
                            <span class="text-gray-600">{{ $order->payment_status }}</span>
                        @endif
                        <br>
                        <a href="{{ route('user.order.show', $order->order_id) }}"
                           class="text-blue-600 hover:underline">查看詳情</a>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
