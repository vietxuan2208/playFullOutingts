@extends('layouts.user.user')

@section('content')

@php
$subtotal = $carts->sum(fn($c) => $c->product->price * $c->quantity);
@endphp

<section class="w-full pt-32 pb-12 bg-gradient-to-b from-primary/10 to-background-light dark:from-primary/20 dark:to-background-dark">
    <div class="max-w-6xl mx-auto px-6 text-center">
        <h1 class="text-4xl font-bold tracking-tight">Your Shopping Cart</h1>
        <p class="text-text-light/70 dark:text-text-dark/70 mt-2">
            Review your items & proceed to secure checkout
        </p>
    </div>
</section>

<div class="max-w-6xl mx-auto px-6 py-12 grid grid-cols-1 lg:grid-cols-3 gap-10">

    <div class="lg:col-span-2 bg-card-light dark:bg-card-dark rounded-2xl shadow-md p-6">
        <h2 class="text-2xl font-bold mb-6">Items in Cart</h2>

        @forelse($carts as $cart)
        <div class="cart-item grid grid-cols-12 items-center gap-4 border-b border-border-light dark:border-border-dark py-5"
            data-price="{{ $cart->product->price }}"
            data-name="{{ $cart->product->name }}"
            data-stock="{{ $cart->product->stock }}">


            <div class="col-span-2 flex justify-center">
                <img src="{{ asset('storage/images/' . $cart->product->photo) }}"
                    class="w-20 h-20 rounded-xl object-cover shadow">
            </div>

            <div class="col-span-4">
                <h3 class="font-semibold text-lg">{{ $cart->product->name }}</h3>
                <p class="text-primary font-bold text-lg">
                    ${{ number_format($cart->product->price, 2) }}
                </p>
            </div>

            <div class="col-span-3">
                <form action="{{ route('cart.update', $cart->id) }}"
                    method="POST"
                    class="update-cart-form flex items-center gap-2 justify-center">

                    @csrf

                    <button type="button"
                        class="btn-minus w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center hover:bg-primary/80">-</button>

                    <input type="text"
                        name="quantity"
                        value="{{ $cart->quantity }}"
                        class="qty-input w-12 text-center rounded border border-border-light dark:border-border-dark bg-background-light dark:bg-background-dark">

                    <button type="button"
                        class="btn-plus w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center hover:bg-primary/80">+</button>
                </form>
            </div>

            <div class="col-span-2">
                <p class="item-total font-bold mt-1">
                    ${{ number_format($cart->product->price * $cart->quantity, 2) }}
                </p>
            </div>


            <div class="col-span-1 flex justify-end">
                <form action="{{ route('cart.remove', $cart->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button class="text-red-500 hover:text-red-700 text-2xl"
                        onclick="return confirm('Remove from cart?');">
                        ✕
                    </button>
                </form>
            </div>

        </div>

        @empty


        <p class="text-center py-10 text-text-light/60 dark:text-text-dark/60">
            Your cart is empty.
        </p>

        @endforelse

    </div>

    <div class="bg-card-light dark:bg-card-dark rounded-2xl shadow-md p-6 h-fit sticky top-28">

        <h2 class="text-2xl font-bold mb-4">Order Summary</h2>


        <div id="summaryItems" class="space-y-4"></div>

        <div class="flex justify-between items-center pt-4 border-t border-border-light dark:border-border-dark mt-4">
            <h3 class="text-xl font-bold">Subtotal</h3>
            <h3 class="text-xl font-bold" id="summarySubtotal">$0.00</h3>
        </div>

        <button id="btnCheckout"
            class="w-full mt-8 bg-primary text-white py-3 rounded-xl font-semibold hover:bg-primary/80 transition">
            Proceed to Checkout
        </button>

    </div>


</div>
<!-- Cart End -->
<script>
    document.addEventListener("DOMContentLoaded", function() {

        function showToast(message, isError = false) {
            let toast = document.getElementById("toast");

            if (!toast) {
                toast = document.createElement("div");
                toast.id = "toast";
                toast.className =
                    "fixed left-1/2 top-24 -translate-x-1/2 z-[500] bg-primary text-white font-semibold px-6 py-3 rounded-xl shadow-lg opacity-0 pointer-events-none transition-all duration-500";
                document.body.appendChild(toast);
            }

            toast.textContent = message;
            toast.style.backgroundColor = isError ? "#e11d48" : "#10b981";

            toast.classList.remove("opacity-0");
            toast.classList.add("opacity-100");

            setTimeout(() => {
                toast.classList.remove("opacity-100");
                toast.classList.add("opacity-0");
            }, 1500);
        }
        function updateHeaderCartBadge() {
            let totalQty = 0;

            document.querySelectorAll(".qty-input").forEach(input => {
                totalQty += parseInt(input.value);
            });

            let badge = document.querySelector("#cart-count-badge");

            if (!badge) {
                const cartIcon = document.querySelector('[href="{{ route("cart_user") }}"]');
                badge = document.createElement("span");
                badge.id = "cart-count-badge";
                badge.className =
                    "absolute -top-1 -right-1 bg-primary text-white text-[12px] font-bold w-5 h-5 flex items-center justify-center rounded-full shadow";
                cartIcon.appendChild(badge);
            }

            badge.textContent = totalQty;
        }

        function updateSummary() {
            let subtotal = 0;
            const summaryItems = document.getElementById("summaryItems");

            summaryItems.innerHTML = "";

            document.querySelectorAll(".cart-item").forEach(item => {
                const price = parseFloat(item.dataset.price);
                const qty = parseInt(item.querySelector(".qty-input").value);

                subtotal += price * qty;

                summaryItems.innerHTML += `
                <div class="summary-item flex justify-between items-center border-b border-border-light dark:border-border-dark pb-3">
                    <div>
                        <p class="font-semibold">${item.dataset.name}</p>
                        <p class="text-sm text-text-light/60 dark:text-text-dark/60">Qty: ${qty}</p>
                    </div>
                    <p class="font-bold">$${(price * qty).toFixed(2)}</p>
                </div>
            `;
            });

            document.getElementById("summarySubtotal").textContent = "$" + subtotal.toFixed(2);
        }
        function updateCartAJAX(form, qtyInput, cartItem) {
            fetch(form.action, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json",
                        "X-CSRF-TOKEN": form.querySelector('[name="_token"]').value
                    },
                    body: JSON.stringify({
                        quantity: qtyInput.value
                    })
                })
                .then(res => res.json())
                .then(data => {

                    if (data.error === "not_enough_stock") {
                        showToast("Not enough stock. Only " + data.available + " left", true);

                        qtyInput.value = data.available;

                        const price = parseFloat(cartItem.dataset.price);
                        cartItem.querySelector(".item-total").textContent = "$" + (price * data.available).toFixed(2);

                        updateSummary();
                        updateHeaderCartBadge();
                        return;
                    }
                    if (data.success) {
                        showToast("Updated ");

                        const price = parseFloat(cartItem.dataset.price);
                        const total = price * parseInt(qtyInput.value);
                        cartItem.querySelector(".item-total").textContent = "$" + total.toFixed(2);

                        updateSummary();
                        updateHeaderCartBadge();
                    }
                });
        }

        document.querySelectorAll(".cart-item").forEach(cartItem => {

            const form = cartItem.querySelector(".update-cart-form");
            const qtyInput = cartItem.querySelector(".qty-input");
            const stock = parseInt(cartItem.dataset.stock);

            cartItem.querySelector(".btn-plus").addEventListener("click", function() {
                let newQty = parseInt(qtyInput.value) + 1;

                if (newQty > stock) {
                    showToast("Not enough stock. Maximum: " + stock, true);
                    return;
                }

                qtyInput.value = newQty;
                updateCartAJAX(form, qtyInput, cartItem);
            });

            cartItem.querySelector(".btn-minus").addEventListener("click", function() {
                if (qtyInput.value > 1) {
                    qtyInput.value = parseInt(qtyInput.value) - 1;
                    updateCartAJAX(form, qtyInput, cartItem);
                }
            });
        });

        updateSummary();
    });
    document.getElementById("btnCheckout").addEventListener("click", function(e) {
        e.preventDefault();

        let hasError = false;

        document.querySelectorAll(".cart-item").forEach(item => {
            const stock = parseInt(item.dataset.stock);
            const qty = parseInt(item.querySelector(".qty-input").value);

            if (qty > stock) {
                showToast(`Product "${item.dataset.name}" only has ${stock} left in stock`, true);
                hasError = true;
            }
        });

        if (hasError) return; 
        window.location.href = "{{ route('checkout_user') }}";
    });
</script>



@endsection