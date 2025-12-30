@extends('layouts.user.user')

@section('content')

<section class="w-full pt-32 pb-16 bg-gradient-to-b from-primary/10 to-white dark:from-primary/20 dark:to-gray-900">
    <div class="max-w-6xl mx-auto px-6 text-center">
        <h1 class="text-4xl font-bold tracking-tight">Your Profile</h1>
        <p class="text-gray-600 dark:text-gray-300 mt-2">
            Manage your account & view your order history
        </p>
    </div>
</section>

@if(request('success') === 'ordered')
<div id="order-toast"
    class="fixed left-1/2 top-24 -translate-x-1/2 z-[500] 
            bg-primary text-white font-semibold px-6 py-3 
            rounded-xl shadow-lg opacity-0 transition-all duration-500">
    🎉 Your order has been placed successfully!
</div>

<style>
    @keyframes fade {
        0% {
            opacity: 0;
            transform: translate(-50%, -10px);
        }

        10% {
            opacity: 1;
            transform: translate(-50%, 0);
        }

        90% {
            opacity: 1;
        }

        100% {
            opacity: 0;
        }
    }

    .animate-fade {
        animation: fade 2.5s ease-in-out forwards;
    }
</style>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const toast = document.getElementById("order-toast");

        // Fade-in
        setTimeout(() => {
            toast.classList.remove("opacity-0");
            toast.classList.add("opacity-100");
        }, 50);

        // Fade-out + remove
        setTimeout(() => {
            toast.classList.remove("opacity-100");
            toast.classList.add("opacity-0");
        }, 2000);

        // Remove from DOM
        setTimeout(() => toast.remove(), 2600);
    });
</script>
@endif

<div class="max-w-7xl mx-auto px-6 py-12 grid grid-cols-1 lg:grid-cols-3 gap-10">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 border border-gray-200 dark:border-gray-700">

        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold">Profile Information</h2>

            <button
                class="px-3 py-1 rounded-lg bg-primary text-white text-sm hover:bg-primary/80 transition"
                onclick="openEditModal()">
                <span class="material-symbols-outlined text-sm">edit</span> Edit
            </button>
        </div>

        @php $user = Auth::user(); @endphp

        {{-- Avatar --}}
        <div class="flex justify-center mb-4">
            <img src="{{ $user->photo 
                ? asset('storage/avatars/' . $user->photo) . '?t=' . $user->updated_at->timestamp 
                : asset('storage/avatars/no-image.jpg') }}"
                class="w-28 h-28 rounded-full object-cover shadow-md">
        </div>

        {{-- Info --}}
        <div class="space-y-2 text-gray-700 dark:text-gray-300">
            <p><span class="font-semibold">Name:</span> {{ $user->name }}</p>
            <p><span class="font-semibold">Email:</span> {{ $user->email }}</p>
            <p><span class="font-semibold">Phone:</span> {{ $user->phone }}</p>
            <p><span class="font-semibold">Birthday:</span> {{ $user->birthday }}</p>
            <p><span class="font-semibold">Address:</span> {{ $user->address }}</p>
            <p><span class="font-semibold">Gender:</span> {{ $user->gender }}</p>
            <p><span class="font-semibold">Member Since:</span> {{ $user->created_at->format('d/m/Y') }}</p>
        </div>

    </div>

    <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 border border-gray-200 dark:border-gray-700">

        <h2 class="text-2xl font-bold mb-6 text-center">Order History</h2>

        @if ($orders->isEmpty())
        <p class="text-center text-gray-500 dark:text-gray-300">No orders yet.</p>

        @else
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                        <th class="px-4 py-3 font-semibold">Order ID</th>
                        <th class="px-4 py-3 font-semibold">Date</th>
                        <th class="px-4 py-3 font-semibold">Total</th>
                        <th class="px-4 py-3 font-semibold">Status</th>
                        <th class="px-4 py-3 font-semibold">Action</th>
                    </tr>
                </thead>

                <tbody id="orderTableBody">
                    @foreach ($orders as $order)
                    <tr class="border-b dark:border-gray-700">
                        <td class="px-4 py-3 font-semibold">#{{ $order->id }}</td>

                        <td class="px-4 py-3">
                            {{ $order->purchase_date->format('d/m/Y') }}
                        </td>

                        <td class="px-4 py-3">
                            ${{ number_format($order->pay, 2) }}
                        </td>

                        {{-- STATUS BADGE --}}
                        <td class="px-4 py-3">
                            @php
                            $status = strtolower($order->status);
                            $colors = [
                            'pending' => 'bg-yellow-500 text-white',
                            'shipped' => 'bg-blue-500 text-white',
                            'delivered' => 'bg-green-600 text-white',
                            'canceled' => 'bg-gray-400 text-white',
                            ];
                            @endphp

                            <span class="px-3 py-1 rounded-lg text-sm {{ $colors[$status] ?? 'bg-gray-300 text-black' }}">
                                {{ ucfirst($status) }}
                            </span>
                        </td>

                        <td class="px-4 py-3 flex items-center gap-2">

                            <button
                                class="px-3 py-1 bg-primary text-white rounded-lg text-sm hover:bg-primary/80 transition"
                                onclick='openOrderModal(@json($order))'>
                                View
                            </button>

                            @if(in_array(strtolower($order->status), ['pending', 'shipped']))
                            <button
                                onclick="openCancelDialog()"
                                class="px-3 py-1 bg-red-500 text-white rounded-lg text-sm hover:bg-red-600 transition">
                                Cancel
                            </button>
                            @endif

                        </td>
                    </tr>
                    @endforeach
                </tbody>

            </table>
            <div id="orderPagination" class="flex justify-center mt-6 gap-2"></div>
        </div>
        @endif
    </div>

</div>

<div id="editModal"
    class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden items-center justify-center z-[999]">

    <div class="bg-white dark:bg-gray-900 w-full max-w-md rounded-2xl shadow-lg p-6">
        <h3 class="text-xl font-semibold mb-4">Edit Profile</h3>

        <form action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf

            {{-- Name --}}
            <div>
                <label class="font-semibold"> Name</label>
                <input type="text" name="name"
                    value="{{ $user->name }}"
                    class="w-full mt-1 px-4 py-2 rounded-lg border bg-gray-50 dark:bg-gray-800 dark:border-gray-700">
            </div>
            <div>
                <label class="font-semibold">Phone</label>
                <input type="text" name="phone"
                    value="{{ $user->phone }}"
                    class="w-full mt-1 px-4 py-2 rounded-lg border bg-gray-50 dark:bg-gray-800 dark:border-gray-700">
            </div>
            <div>
                <label class="font-semibold">Address</label>
                <input type="text" name="address"
                    value="{{ $user->address }}"
                    class="w-full mt-1 px-4 py-2 rounded-lg border bg-gray-50 dark:bg-gray-800 dark:border-gray-700">
            </div>
           <div>
                <label class="font-semibold">Birthday</label>
                    <input
                        type="date"
                        name="birthday"
                        id="birthday"
                        value="{{ $user->birthday }}"
                        class="w-full mt-1 px-4 py-2 rounded-lg border bg-gray-50 dark:bg-gray-800 dark:border-gray-700"
                    >

                    <small id="birthday-error" class="text-red-500 text-sm hidden"></small>


            </div>

            <div>
                <label class="font-semibold">Gender</label>
                <select name="gender"
                    class="w-full mt-1 px-4 py-2 rounded-lg border bg-gray-50 dark:bg-gray-800 dark:border-gray-700">

                    <option value="male" {{ $user->gender == 'male' ? 'selected' : '' }}>Male</option>
                    <option value="female" {{ $user->gender == 'female' ? 'selected' : '' }}>Female</option>
                    <option value="other" {{ $user->gender == 'other' ? 'selected' : '' }}>Other</option>

                </select>
            </div>

            <div>
                <label class="font-semibold">New Avatar</label>
                <input type="file" name="photo"
                    class="w-full mt-1 px-4 py-2 rounded-lg border bg-gray-50 dark:bg-gray-800 dark:border-gray-700">
            </div>

            <div class="flex justify-end gap-3">
                <button type="button"
                    onclick="closeEditModal()"
                    class="px-4 py-2 bg-gray-300 dark:bg-gray-700 rounded-lg">
                    Cancel
                </button>

                <button
                    type="submit"
                    id="saveProfileBtn"
                    class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary/80 transition">
                    Save
                </button>

            </div>
        </form>
    </div>
</div>

<div id="orderModal"
    class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden items-center justify-center z-[999]">

    <div class="bg-white dark:bg-gray-900 w-full max-w-3xl rounded-2xl shadow-lg p-6">
        <h3 class="text-xl font-semibold mb-4">Order Details</h3>

        <div id="orderModalBody" class="space-y-3"></div>

        <div class="text-right mt-4">
            <button onclick="closeOrderModal()" class="px-4 py-2 bg-primary text-white rounded-lg">
                Close
            </button>
        </div>
    </div>
</div>

<div id="cancelModal"
    class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden items-center justify-center z-[999]">

    <div class="bg-white dark:bg-gray-900 w-full max-w-md rounded-2xl shadow-lg p-6 text-center">

        <span class="material-symbols-outlined text-red-500 text-5xl mb-3">
            report_problem
        </span>

        <h3 class="text-xl font-semibold mb-2">Cancel Order</h3>

        <p class="text-gray-600 dark:text-gray-300 mb-6">
            To cancel this order, please contact the administrator or customer support.
        </p>

        <div class="flex justify-center gap-3">
            <button onclick="closeCancelDialog()"
                class="px-4 py-2 bg-gray-300 dark:bg-gray-700 rounded-lg">
                Close
            </button>
        </div>
    </div>
</div>



<script>
    function openEditModal() {
        document.getElementById("editModal").classList.remove("hidden");
        document.getElementById("editModal").classList.add("flex");
    }

    function closeEditModal() {
        document.getElementById("editModal").classList.add("hidden");
    }

    function openOrderModal(order) {

        let rows = "";
        let grandTotal = 0;

        (order.order_details || []).forEach(item => {

            const product = item.product || {};
            const img = product.photo ? `/storage/images/${product.photo}` : `/storage/images/no-image.jpg`;
            const qty = item.quantity;
            const price = parseFloat(item.price);
            const total = qty * price;

            grandTotal += total;

            rows += `
            <tr class="border-b dark:border-gray-700">
                <td class="p-3">
                    <img src="${img}" class="w-16 h-16 rounded-lg object-cover shadow">
                </td>
                <td class="p-3 font-semibold">${product.name}</td>
                <td class="p-3 text-center">${qty}</td>
                <td class="p-3">$${price.toFixed(2)}</td>
                <td class="p-3 font-bold">$${total.toFixed(2)}</td>
            </tr>
        `;
        });

        const html = `
        <h3 class="text-xl font-semibold mb-4">Order ID: #${order.id}</h3>

        <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-xl border border-gray-200 dark:border-gray-700 mb-5">
            <h4 class="text-lg font-bold mb-2">Receiver Information</h4>

            <p><span class="font-semibold">Full Name:</span> ${order.receiver_name ?? 'N/A'}</p>
            <p><span class="font-semibold">Email:</span> ${order.receiver_email ?? 'N/A'}</p>
            <p><span class="font-semibold">Phone:</span> ${order.delivery_phone ?? 'N/A'}</p>
            <p><span class="font-semibold">Address:</span> ${order.delivery_address ?? 'N/A'}</p>
            <p><span class="font-semibold">Payment Method:</span> ${order.payment_method ?? 'N/A'}</p>
        </div>

        <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-700">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                    <tr>
                        <th class="p-3 font-semibold">Photo</th>
                        <th class="p-3 font-semibold">Product Name</th>
                        <th class="p-3 font-semibold text-center">Quantity</th>
                        <th class="p-3 font-semibold">Price</th>
                        <th class="p-3 font-semibold">Total</th>
                    </tr>
                </thead>
                <tbody>
                    ${rows}
                </tbody>
            </table>
        </div>

        <!-- ⭐ GRAND TOTAL -->
        <div class="text-right mt-4 text-lg font-bold">
            Total Amount: <span class="text-primary">$${grandTotal.toFixed(2)}</span>
        </div>
        `;

        document.getElementById("orderModalBody").innerHTML = html;

        document.getElementById("orderModal").classList.remove("hidden");
        document.getElementById("orderModal").classList.add("flex");
    }



    function closeOrderModal() {
        document.getElementById("orderModal").classList.add("hidden");
    }

    function openCancelDialog() {
        const modal = document.getElementById("cancelModal");
        modal.classList.remove("hidden");
        modal.classList.add("flex");
    }

    function closeCancelDialog() {
        document.getElementById("cancelModal").classList.add("hidden");
    }

    document.addEventListener("DOMContentLoaded", function() {

        const rowsPerPage = 4;
        const tableBody = document.getElementById("orderTableBody");
        const rows = Array.from(tableBody.querySelectorAll("tr"));
        const pagination = document.getElementById("orderPagination");

        let currentPage = 1;
        const totalPages = Math.ceil(rows.length / rowsPerPage);

        function renderTable() {
            tableBody.innerHTML = "";

            const start = (currentPage - 1) * rowsPerPage;
            const end = start + rowsPerPage;

            rows.slice(start, end).forEach(row => {
                tableBody.appendChild(row);
            });

            renderPagination();
        }

        function renderPagination() {
            pagination.innerHTML = "";

            for (let i = 1; i <= totalPages; i++) {

                const btn = document.createElement("button");
                btn.textContent = i;

                btn.className =
                    "px-4 py-2 rounded-lg border text-sm transition " +
                    (i === currentPage ?
                        "bg-primary text-white border-primary" :
                        "bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-700 hover:bg-primary/10");

                btn.onclick = () => {
                    currentPage = i;
                    renderTable();
                };

                pagination.appendChild(btn);
            }
        }

        renderTable();
    });
document.addEventListener('DOMContentLoaded', function () {
    const birthdayInput = document.getElementById('birthday');
    const errorText = document.getElementById('birthday-error');
    const saveBtn = document.getElementById('saveProfileBtn');

    if (!birthdayInput || !saveBtn) return;

    function validateAge() {
        if (!birthdayInput.value) {
            errorText.classList.add('hidden');
            birthdayInput.classList.remove('border-red-500');
            saveBtn.disabled = false;
            saveBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            return true;
        }

        const birthday = new Date(birthdayInput.value);
        const today = new Date();

        let age = today.getFullYear() - birthday.getFullYear();
        const m = today.getMonth() - birthday.getMonth();

        if (m < 0 || (m === 0 && today.getDate() < birthday.getDate())) {
            age--;
        }

        if (age < 16) {
            errorText.textContent = 'You must be at least 16 years old.';
            errorText.classList.remove('hidden');
            birthdayInput.classList.add('border-red-500');

            // 🚫 CHẶN SAVE
            saveBtn.disabled = true;
            saveBtn.classList.add('opacity-50', 'cursor-not-allowed');

            return false;
        }

        // ✅ HỢP LỆ
        errorText.classList.add('hidden');
        birthdayInput.classList.remove('border-red-500');
        saveBtn.disabled = false;
        saveBtn.classList.remove('opacity-50', 'cursor-not-allowed');

        return true;
    }

    // Validate khi chọn ngày
    birthdayInput.addEventListener('change', validateAge);

    // Validate ngay khi mở modal (nếu có birthday cũ)
    validateAge();
});
</script>

@endsection