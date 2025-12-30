<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Your Order Invoice</title>
    <style>
        body {
            background: #f5f7fa;
            font-family: 'Helvetica', 'Arial', sans-serif;
            margin: 0;
            padding: 0;
        }

        .email-container {
            max-width: 650px;
            margin: 30px auto;
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .header {
            background: linear-gradient(135deg, #4e73df, #224abe);
            padding: 35px;
            text-align: center;
            color: white;
        }

        .header h1 {
            margin: 0;
            font-size: 26px;
            font-weight: 700;
        }

        .content {
            padding: 30px;
            color: #333;
        }

        .content p {
            font-size: 15px;
            line-height: 1.6;
        }

        .order-info {
            background: #f1f4ff;
            border-left: 4px solid #4e73df;
            padding: 15px 20px;
            margin-top: 20px;
            border-radius: 8px;
        }

        .order-info p {
            margin: 4px 0;
            font-size: 15px;
        }

        .product-table {
            width: 100%;
            margin-top: 25px;
            border-collapse: collapse;
        }

        .product-table th {
            text-align: left;
            background: #eef1f7;
            padding: 12px;
            font-size: 14px;
            text-transform: uppercase;
            color: #555;
        }

        .product-table td {
            padding: 12px;
            border-bottom: 1px solid #eee;
        }

        .total-box {
            text-align: right;
            font-size: 18px;
            font-weight: 700;
            padding: 20px 0;
        }

        .footer {
            background: #f8f9fb;
            text-align: center;
            padding: 20px;
            color: #888;
            font-size: 14px;
        }

        .footer a {
            color: #4e73df;
            text-decoration: none;
        }
    </style>
</head>

<body>

    <div class="email-container">

        <!-- Header -->
        <div class="header">
            <h1>Thank You for Your Purchase!</h1>
        </div>

        <!-- Content -->
        <div class="content">

            <p>Hello <strong>{{ $order->receiver_name }}</strong>,</p>

            <p>
                Your order has been successfully placed.
                Below is your invoice and order summary.
            </p>

            <!-- Order Info -->
            <div class="order-info">
                <p><strong>Order ID:</strong> {{ $order->id }}</p>
                <p><strong>Purchase Date:</strong> {{ $order->purchase_date }}</p>
                <p><strong>Payment Method:</strong> PayPal</p>
            </div>

            <!-- Products Table -->
            <table class="product-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Qty</th>
                        <th style="text-align:right;">Price</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($orderDetails as $item)
                    <tr>
                        <td>{{ $item->product->name }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td style="text-align:right;">
                            ${{ number_format($item->price, 2) }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Total -->
            <div class="total-box">
                Total: ${{ number_format($order->total_price, 2) }}
            </div>

            <p>
                If you have any questions about your order, feel free to reply to this email.
                We are always here to help!
            </p>

        </div>

        <!-- Footer -->
        <div class="footer">
            PlayFullOutings © {{ date('Y') }} —
            <a href="https://yourwebsite.com">Visit our website</a>
            <br>
            This is an automated email, please do not reply.
        </div>

    </div>

</body>

</html>