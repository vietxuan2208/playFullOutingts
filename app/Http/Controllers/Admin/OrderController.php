<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    /**
     * Lấy danh sách đơn hàng theo trạng thái
     */
    private function getOrdersByStatus($status)
    {
        return Order::with(['user', 'orderDetails.product'])
            ->where('status', $status)
            ->where('is_delete', 0)
            ->orderBy('id', 'desc')
            ->get();
    }

    public function pending()
    {
        $orders = $this->getOrdersByStatus('pending');
        return view('admin.orderPending', compact('orders'));
    }

    public function shipped()
    {
        $orders = $this->getOrdersByStatus('shipped');
        return view('admin.orderShipped', compact('orders'));
    }

    public function delivered()
    {
        $orders = $this->getOrdersByStatus('delivered');
        return view('admin.orderDelivered', compact('orders'));
    }

    public function canceled()
    {
        $orders = $this->getOrdersByStatus('canceled');
        return view('admin.orderCanceled', compact('orders'));
    }

    /**
     * Cập nhật trạng thái đơn hàng
     */
    public function updateStatus(Request $request)
    {
        $request->validate([
            'id'     => 'required|exists:orders,id',
            'status' => 'required|string',
        ]);

        $order = Order::with('orderDetails.product')->findOrFail($request->id);

        if ($request->status === 'canceled' && $order->status !== 'canceled') {

            foreach ($order->orderDetails as $detail) {
                $product = $detail->product;

                if ($product) {
                    $product->stock += $detail->quantity;
                    $product->save();
                }
            }
        }

        $order->status = $request->status;
        $order->save();

        return redirect()->back()->with('success', 'Order status updated successfully.');
    }
}
