@extends('layouts.dashboard')

@section('title', '訂單紀錄')

@section('content')
                <!-- Order Details Content -->
                <div class="lg:w-3/4">
                    <div class="p-8 transition-all bg-white shadow-sm order-card rounded-xl hover:shadow-md">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-2xl font-bold text-gray-900">訂單詳情 #{{ $order->order_id }}</h2>
                            <span class="text-sm font-medium {{ $order->order_status == 'completed' ? 'text-success' : ($order->order_status == 'canceled' ? 'text-error' : 'text-gray-600') }}">
                                {{ $order->order_status}}
                            </span>
                        </div>

                        <!-- Order Information -->
                        <div class="grid grid-cols-1 gap-6 mb-6 sm:grid-cols-2">
                            <div>
                                <p class="text-sm font-medium text-gray-700">訂單日期</p>
                                <p class="text-gray-600">{{ $order->order_date }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-700">付款狀態</p>
                                @if ($order->payment_status == "未付款")
                                    <p class="text-red-600">{{ $order->payment_status }}</p>
                                @elseif ($order->payment_status == "已付款")
                                    <p class="text-green-600">{{ $order->payment_status }}</p>
                                @else
                                    <p class="text-gray-600">{{ $order->payment_status }}</p>
                                @endif
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-700">收貨人</p>
                                <p class="text-gray-600">{{ $order->recipient_name }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-700">聯絡電話</p>
                                <p class="text-gray-600">{{ $order->recipient_phone }}</p>
                            </div>
                            <div class="col-span-2">
                                <p class="text-sm font-medium text-gray-700">收貨地址</p>
                                <p class="text-gray-600">{{ $order->shipping_address }}</p>
                            </div>
                        </div>

                        <!-- Order Items -->
                        <h3 class="mb-4 text-lg font-semibold text-gray-900">訂購項目</h3>
                        <div class="border-t border-gray-200">
                            @foreach ($order->orderDetails as $detail)
                                <div class="flex justify-between py-4 border-b border-gray-200">
                                    <div>
                                        <p class="text-sm font-medium text-gray-700">{{ $detail['product']->product_name }}</p>
                                        <p class="text-sm text-gray-600">數量：{{ $detail['quantity'] }}</p>
                                        @if ($detail['discount_amount'])
                                            <p class="text-sm text-success">折扣：{{ $detail['discount_amount'] }} (NT${{ number_format($detail['discount_amount']) }})</p>
                                        @endif
                                    </div>
                                    <p class="text-sm font-medium text-gray-900">NT${{ number_format($detail['subtotal']) }}</p>
                                </div>
                            @endforeach
                        </div>

                        <!-- Assembly Service -->
                        @if ($order->assemblyService)
                            <div class="py-4 border-b border-gray-200">
                                <div class="flex justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-gray-700">組裝服務：{{ $order->assemblyService->service_name }}</p>
                                        <p class="text-sm text-gray-600">{{ $order->assemblyService->service_description }}</p>
                                    </div>
                                    <p class="text-sm font-medium text-gray-900">NT${{ number_format($order->assemblyService->service_fee) }}</p>
                                </div>
                            </div>
                        @endif

                        <!-- Order Summary -->
                        <div class="py-4">
                            <div class="flex justify-between text-sm">
                                <p class="text-gray-700">運費</p>
                                <p class="font-medium text-gray-900">NT${{ number_format($order->shipping_fee) }}</p>
                            </div>
                            <div class="flex justify-between mt-2 text-lg font-semibold">
                                <p class="text-gray-900">總金額</p>
                                <p class="text-gray-900">NT${{ number_format($order->total_amount) }}</p>
                            </div>
                        </div>

                        <div class="flex justify-end mt-6">
                            <a href="{{ route('user.orders') }}" class="px-6 py-3 text-gray-700 bg-gray-200 rounded-lg btn hover:bg-gray-300">返回訂單列表</a>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <!-- Footer -->

    </div>
@endsection
