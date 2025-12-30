@extends('layouts.user.user')

@section('content')

<!-- 🌄 HERO -->
<section class="w-full pt-32 pb-12 bg-gradient-to-b from-primary/10 to-background-light dark:from-primary/20 dark:to-background-dark">
    <div class="max-w-6xl mx-auto px-6 text-center">
        <h1 class="text-4xl font-bold tracking-tight">Checkout</h1>
        <p class="text-text-light/70 dark:text-text-dark/70 mt-2">
            <a href="{{ url('user/dashboard') }}" class="hover:underline">Home</a>
            <span class="px-1">•</span>
            Checkout
        </p>
    </div>
</section>

<!-- 🌟 MAIN CHECKOUT SECTION -->
<div class="max-w-7xl mx-auto px-6 py-12 grid grid-cols-1 lg:grid-cols-3 gap-10">

    <!-- 🧾 BILLING FORM -->
    <div class="lg:col-span-2 space-y-6">

        <div class="bg-card-light dark:bg-card-dark p-6 rounded-xl shadow border border-border-light dark:border-border-dark">
            <h2 class="text-2xl font-bold mb-4">Billing Information</h2>

            <form id="billing-form" class="grid grid-cols-1 md:grid-cols-2 gap-5">
                @csrf

                <div class="flex flex-col gap-1">
                    <label class="font-medium">Full Name</label>
                    <input type="text" name="full_name" required
                        class="input-style">
                </div>

                <div class="flex flex-col gap-1">
                    <label class="font-medium">Email</label>
                    <input type="email" name="email" required
                        class="input-style">
                </div>

                <div class="flex flex-col gap-1">
                    <label class="font-medium">Phone Number</label>
                    <input type="text" name="phone" required
                        class="input-style">
                </div>

                <div class="flex flex-col gap-1">
                    <label class="font-medium">Address</label>
                    <input type="text" name="address" required
                        class="input-style">
                </div>

                <!-- Payment method -->
                <div class="col-span-full mt-4">
                    <label class="font-medium mb-2 block">Payment Method</label>

                    <div class="flex flex-col gap-3">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="radio" name="payment_method" value="Pick up at store" checked class="w-4 h-4 text-primary">
                            <span>Pick up at store</span>
                        </label>

                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="radio" name="payment_method" value="Home delivery" class="w-4 h-4 text-primary">
                            <span>Home delivery</span>
                        </label>
                    </div>
                </div>

            </form>
        </div>
    </div>

    <!-- 🛒 ORDER SUMMARY -->
    <div class="space-y-6">

        <div class="bg-card-light dark:bg-card-dark p-6 rounded-xl shadow border border-border-light dark:border-border-dark">
            <h2 class="text-2xl font-bold mb-4">Order Summary</h2>

            <div class="space-y-4">
                @foreach($carts as $cart)
                <div class="flex justify-between items-start pb-4 border-b border-border-light dark:border-border-dark">
                    <div class="w-2/3">
                        <p class="font-semibold line-clamp-1">{{ $cart->product->name }}</p>
                        <p class="text-sm text-text-light/60 dark:text-text-dark/60">
                            Qty: {{ $cart->quantity }}
                        </p>
                    </div>

                    <p class="font-bold text-primary">
                        ${{ number_format($cart->product->price * $cart->quantity, 2) }}
                    </p>
                </div>
                @endforeach
            </div>

            <div class="flex justify-between items-center mt-5 text-lg font-bold">
                <span>Total:</span>
                <span>${{ number_format($subtotal, 2) }}</span>
            </div>
        </div>

        <!-- 💳 PayPal Button -->
        <div class="bg-card-light dark:bg-card-dark p-6 rounded-xl shadow border border-border-light dark:border-border-dark">
            <h2 class="text-xl font-semibold mb-4">Payment</h2>
            <div id="paypal-button-container"></div>
        </div>

    </div>

</div>


<!-- PAYPAL -->
<script src="https://www.paypal.com/sdk/js?client-id={{ config('services.paypal.client_id') }}&currency=USD"></script>

<script>
    // 🛑 Validate Billing Form before allowing PayPal payment
    function validateBilling() {
        const fields = ["full_name", "email", "phone", "address"];
        for (let f of fields) {
            const value = document.querySelector(`[name=${f}]`).value.trim();
            if (!value) {
                alert("Please fill in all billing information before proceeding.");
                return false;
            }
        }
        return true;
    }

    paypal.Buttons({

        // ============================
        // CREATE ORDER
        // ============================
        createOrder: function(data, actions) {
            // 🛑 Prevent order creation if the form is incomplete
            if (!validateBilling()) {
                return Promise.reject('Please fill in all billing information');
            }

            return fetch("{{ route('paypal.createOrder') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({})
                })
                .then(res => {
                    if (!res.ok) throw new Error('Network response was not ok');
                    return res.json();
                })
                .then(orderData => {
                    if (!orderData.id) {
                        throw new Error('No order ID in response: ' + JSON.stringify(orderData));
                    }
                    return orderData.id;
                })
                .catch(err => {
                    console.error('Create Order Error:', err);
                    alert('Error creating PayPal order: ' + err.message);
                    return Promise.reject(err);
                });
        },

        // ============================
        // CAPTURE ORDER
        // ============================
        onApprove: function(data, actions) {
            // 🛑 Double-check the form (to ensure completeness)
            if (!validateBilling()) {
                return Promise.reject('Please fill in all billing information');
            }

            return fetch("{{ route('paypal.captureOrder') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    },
                    body: JSON.stringify({
                        orderID: data.orderID,
                        full_name: document.querySelector('[name=full_name]').value,
                        email: document.querySelector('[name=email]').value,
                        phone: document.querySelector('[name=phone]').value,
                        address: document.querySelector('[name=address]').value,
                        payment_method: document.querySelector('[name=payment_method]:checked').value,
                    }),
                })
                .then(res => {
                    if (!res.ok) throw new Error('Network response was not ok');
                    return res.json();
                })
                .then(result => {
                    if (result.success) {
                        window.location.href = result.redirect;
                    } else {
                        throw new Error(result.error || 'Payment Failed!');
                    }
                })
                .catch(err => {
                    console.error('Capture Order Error:', err);
                    alert('Payment Error: ' + err.message);
                    return Promise.reject(err);
                });
        },

        // ============================
        // ERROR HANDLER
        // ============================
        onError: function(err) {
            console.error('PayPal Error:', err);
            alert('PayPal Error: ' + (err.message || err));
        },

        // ============================
        // CANCEL HANDLER
        // ============================
        onCancel: function(data) {
            console.log('Payment cancelled by user');
            alert('Payment was cancelled.');
        }
    }).render('#paypal-button-container');
</script>



{{-- 🎨 Custom Tailwind Utilities --}}
<style>
    .input-style {
        @apply px-4 py-2 rounded-lg border border-border-light dark:border-border-dark bg-card-light dark:bg-card-dark focus:ring-primary focus:border-primary transition;
    }
</style>

@endsection