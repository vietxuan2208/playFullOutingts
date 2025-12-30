<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Cart;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderInvoiceMail;


class PayPalController extends Controller
{
    private function paypal()
    {
        $mode = config('services.paypal.mode');

        return [
            'base_url'   => $mode === 'live'
                ? 'https://api-m.paypal.com'
                : 'https://api-m.sandbox.paypal.com',
            'client_id'  => config('services.paypal.client_id'),
            'secret'     => config('services.paypal.client_secret'),
            'currency'   => config('services.paypal.currency', 'USD'),
        ];
    }

    private function accessToken()
    {
        $paypal = $this->paypal();

        $response = Http::asForm()
            ->withoutVerifying()  // ⚠️ CHỈ DÙNG DEVELOPMENT
            ->withBasicAuth($paypal['client_id'], $paypal['secret'])
            ->post($paypal['base_url'] . '/v1/oauth2/token', [
                'grant_type' => 'client_credentials',
            ]);

        return $response['access_token'];
    }


    public function createOrder(Request $request)
    {
        try {
            $user = Auth::user();
            $carts = Cart::with('product')->where('user_id', $user->id)->get();

            if ($carts->isEmpty()) {
                return response()->json(['error' => 'Cart is empty'], 400);
            }

            $total = $carts->sum(fn($c) => $c->product->price * $c->quantity);

            if ($total <= 0) {
                return response()->json(['error' => 'Invalid cart total'], 400);
            }

            $paypal      = $this->paypal();
            $accessToken = $this->accessToken();

            $response = Http::timeout(10)
                ->withoutVerifying()  // ⚠️ CHỈ DÙNG DEVELOPMENT
                ->withToken($accessToken)
                ->post($paypal['base_url'] . '/v2/checkout/orders', [
                    "intent" => "CAPTURE",
                    "purchase_units" => [[
                        "amount" => [
                            "currency_code" => $paypal['currency'],
                            "value"         => number_format($total, 2, '.', '')
                        ]
                    ]],
                    "application_context" => [
                        "brand_name"          => "Aptech Shop",
                        "landing_page"        => "LOGIN",
                        "user_action"         => "PAY_NOW",
                        "shipping_preference" => "NO_SHIPPING"
                    ]
                ]);

            if (!$response->successful()) {
                Log::error('PayPal createOrder failed', [
                    'status' => $response->status(),
                    'body'   => $response->body(),
                ]);
                return response()->json(['error' => 'PayPal API error'], 400);
            }

            $data = $response->json();

            if (!isset($data['id'])) {
                Log::error('PayPal createOrder missing ID', ['response' => $data]);
                return response()->json(['error' => 'Invalid PayPal response'], 400);
            }

            return response()->json([
                'id' => $data['id']
            ]);
        } catch (\Exception $e) {
            Log::error('PayPal createOrder Exception', ['message' => $e->getMessage()]);
            return response()->json(['error' => 'Server error'], 500);
        }
    }

    public function captureOrder(Request $request)
    {
        try {
            $orderID = $request->orderID;

            if (!$orderID) {
                return response()->json(['error' => 'orderID missing'], 400);
            }

            $paypal      = $this->paypal();
            $accessToken = $this->accessToken();
            $url         = $paypal['base_url'] . "/v2/checkout/orders/{$orderID}/capture";

            $capture = Http::withToken($accessToken)
                ->withoutVerifying()  // ⚠️ CHỈ DÙNG DEVELOPMENT
                ->withHeaders(['Content-Type' => 'application/json'])
                ->send('POST', $url);

            Log::info('PayPal Capture Response', [
                'status' => $capture->status(),
                'body'   => $capture->body(),
            ]);

            $data = $capture->json();

            $rootStatus  = $data['status'] ?? null;
            $innerStatus = $data['purchase_units'][0]['payments']['captures'][0]['status'] ?? null;

            if ($rootStatus !== 'COMPLETED' && $innerStatus !== 'COMPLETED') {
                return response()->json([
                    'error'           => 'PayPal capture error',
                    'paypal_response' => $data,
                ], 400);
            }

            $user  = Auth::user();
            $carts = Cart::with('product')->where('user_id', $user->id)->get();

            if ($carts->isEmpty()) {
                return response()->json(['error' => 'Cart is empty'], 400);
            }

            $total = $carts->sum(fn($c) => $c->product->price * $c->quantity);

            $order = Order::create([
                'user_id'          => $user->id,

                'receiver_name'    => $request->full_name,
                'receiver_email'   => $request->email,
                'delivery_phone'   => $request->phone,
                'delivery_address' => $request->address,
                'payment_method'   => $request->payment_method,

                'total_price'      => $total,
                'pay'              => $total,
                'purchase_date'    => now(),
                'status'           => 'pending',
            ]);


            foreach ($carts as $item) {

                $product = $item->product;

                if (!$product) continue;


                if ($product->stock < $item->quantity) {
                    return response()->json([
                        'error' => "Product {$product->name} does not have enough stock!",
                        'available' => $product->stock
                    ], 400);
                }

                $product->stock -= $item->quantity;
                $product->save();

                OrderDetail::create([
                    'order_id'      => $order->id,
                    'product_id'    => $item->product_id,
                    'quantity'      => $item->quantity,
                    'price'         => $product->price,
                    'selling_price' => $product->price,
                    'purchase_date' => now(),
                    'status'        => 'pending',
                ]);
            }


            // Xoá giỏ hàng
            Cart::where('user_id', $user->id)->delete();

            // Gửi email hóa đơn (queue để tránh timeout)
            $orderDetails = OrderDetail::where('order_id', $order->id)->with('product')->get();
            Mail::to($order->receiver_email)->queue(new OrderInvoiceMail($order, $orderDetails));

            return response()->json([
                'success'  => true,
                'redirect' => route('user.profile', ['success' => 'ordered']),
            ]);
        } catch (\Exception $e) {
            Log::error("PayPal Capture Exception", ['message' => $e->getMessage()]);

            return response()->json([
                'error'   => 'Internal Server Error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
