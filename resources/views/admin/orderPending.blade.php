@extends('admin.dashboard')
@section('page-title', 'Order Pending')

@section('content')
<div class="row mt-4">
    <div class="col-md-12">
        <h3>Order Pending List</h3>
    </div>

    <!-- Search bar -->
    <div class="col-md-6 mb-3">
            <div class="d-flex">
                <input class="form-control" type="search" id="searchInput" placeholder="Search Order ID...">
            </div>
    </div>

    {{-- Display success message --}}
    @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    @if (session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <!-- Table -->
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-bordered table-hover mt-2" id="orderTable">
                <thead class="table-dark text-center align-middle">
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Recipient</th>
                        <th>Phone</th>
                        <th>Address</th>
                        <th>Payment</th>
                        <th>Purchase Date</th>
                        <th>Total Price</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="text-center align-middle">
                    @foreach ($orders as $order)
                    <tr>
                        <td>{{ $order->id }}</td>
                        <td>{{ $order->user->name ?? 'Unknown' }}</td>
                        <td>{{ $order->receiver_name }}</td>
                        <td>{{ $order->delivery_phone }}</td>
                        <td>{{ $order->delivery_address }}</td>
                        <td>{{ strtoupper($order->payment_method) }}</td>
                        <td>{{ $order->purchase_date->format('d/m/Y') }}</td>
                        <td>${{ number_format($order->pay, 2) }}</td>
                        <td><span class="badge bg-danger">{{ $order->status }}</span></td>
                        <td class="btn-group">
                            <button class="btn btn-sm btn-outline-info btn-view-order"
                                data-bs-toggle="modal"
                                data-bs-target="#viewOrderModal"
                                data-order="{{ $order->toJson() }}">
                                <i class="fa-regular fa-eye"></i>
                            </button>

                            <button class="btn btn-sm btn-outline-warning"
                                data-bs-toggle="modal"
                                data-bs-target="#confirmStatusModal"
                                data-id="{{ $order->id }}"
                                data-status="shipped"
                                data-message="Do you want to change order #{{ $order->id }} to Shipped status?">
                                <i class="fa-solid fa-truck"></i>
                            </button>

                            <button class="btn btn-sm btn-outline-danger"
                                data-bs-toggle="modal"
                                data-bs-target="#confirmStatusModal"
                                data-id="{{ $order->id }}"
                                data-status="canceled"
                                data-message="Do you want to cancel order #{{ $order->id }}?">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div id="pagination" class="d-flex justify-content-center mt-3"></div>
        </div>
        <!-- Modal confirm status update -->
        <div class="modal fade" id="confirmStatusModal" tabindex="-1">
            <div class="modal-dialog">
                <form method="POST" action="{{ route('order.updateStatus') }}">
                    @csrf
                    <input type="hidden" name="id" id="orderIdInput">
                    <input type="hidden" name="status" id="statusInput">
                    <div class="modal-content">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title">Confirm</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body" id="confirmMessage">
                            <!-- Dynamic message -->
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">OK</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal view order details -->
        <div class="modal fade" id="viewOrderModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content">

                    <div class="modal-header bg-info text-white">
                        <h5 class="modal-title">Order Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">


                        <h5 class="mb-3">Order ID: <span id="modalOrderId"></span></h5>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="border rounded p-3 mb-4 bg-light">
                                    <h6 class="fw-bold mb-2">Recipient Information</h6>

                                    <p><strong>Name: </strong><span id="receiverName"></span></p>
                                    <p><strong>Email: </strong><span id="receiverEmail"></span></p>
                                    <p><strong>Phone: </strong><span id="receiverPhone"></span></p>
                                    <p><strong>Address: </strong><span id="receiverAddress"></span></p>
                                    <p><strong>Payment Method: </strong><span id="paymentMethod"></span></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="border rounded p-3 mb-4 bg-light">
                                    <h6 class="fw-bold mb-2">Customer Information</h6>

                                    <p><strong>Name: </strong><span id="Name"></span></p>
                                    <p><strong>Username: </strong><span id="Username"></span></p>
                                    <p><strong>Email: </strong><span id="Email"></span></p>
                                    <p><strong>Phone: </strong><span id="Phone"></span></p>
                                    <p><strong>Address: </strong><span id="Address"></span></p>
                                </div>
                            </div>
                        </div>


                        <table class="table table-bordered">
                            <thead class="table-secondary">
                                <tr>
                                    <th>Image</th>
                                    <th>Product Name</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody id="orderItemsBody"></tbody>
                        </table>

                        <p class="text-end mt-3">
                            <strong>Total: </strong>
                            <span id="orderTotal" class="fw-bold"></span>
                        </p>

                    </div>

                </div>
            </div>
        </div>


    </div>
</div>

<script>
const rowsPerPage = 5;
let currentPage = 1;
let allRows = [];
let filteredRows = [];

document.addEventListener("DOMContentLoaded", () => {
    allRows = Array.from(document.querySelectorAll("#orderTable tbody tr"));
    filteredRows = [...allRows];

    renderTable();

    document.getElementById("searchInput").addEventListener("input", filterTable);
});

function filterTable() {
    const keyword = document.getElementById("searchInput").value.trim().toLowerCase();

    filteredRows = allRows.filter(row => {
        const id = row.cells[0].innerText.toLowerCase();
        const customer = row.cells[1].innerText.toLowerCase();
        const receiver = row.cells[2].innerText.toLowerCase();

        return (
            id.includes(keyword) ||
            customer.includes(keyword) ||
            receiver.includes(keyword)
        );
    });

    currentPage = 1;
    renderTable();
}

function renderTable() {
    const totalPages = Math.ceil(filteredRows.length / rowsPerPage);

    allRows.forEach(r => r.style.display = "none");

    const start = (currentPage - 1) * rowsPerPage;
    filteredRows.slice(start, start + rowsPerPage).forEach(r => r.style.display = "");

    renderPagination(totalPages);
}

function renderPagination(totalPages) {
    const pagination = document.getElementById("pagination");
    pagination.innerHTML = "";

    for (let i = 1; i <= totalPages; i++) {
        const btn = document.createElement("button");
        btn.textContent = i;
        btn.className = "btn btn-sm btn-outline-primary mx-1";
        if (i === currentPage) btn.classList.add("active");

        btn.onclick = () => {
            currentPage = i;
            renderTable();
        };

        pagination.appendChild(btn);
    }
}

    const confirmModal = document.getElementById('confirmStatusModal');
    confirmModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const id = button.getAttribute('data-id');
        const status = button.getAttribute('data-status');
        const message = button.getAttribute('data-message');

        document.getElementById('orderIdInput').value = id;
        document.getElementById('statusInput').value = status;
        document.getElementById('confirmMessage').textContent = message;
    });

    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.btn-view-order').forEach(button => {
            button.addEventListener('click', () => {

                const order = JSON.parse(button.getAttribute('data-order'));


                document.getElementById('modalOrderId').textContent = order.id;


                document.getElementById('receiverName').textContent = order.receiver_name ?? 'N/A';
                document.getElementById('receiverEmail').textContent = order.receiver_email ?? 'N/A';
                document.getElementById('receiverPhone').textContent = order.delivery_phone ?? 'N/A';
                document.getElementById('receiverAddress').textContent = order.delivery_address ?? 'N/A';
                document.getElementById('paymentMethod').textContent = order.payment_method ?? 'N/A';

                const user = order.user || {
                    name: 'N/A',
                    username: 'N/A',
                    email: 'N/A',
                    phone: 'N/A',
                    address: 'N/A'
                };
                document.getElementById('Name').textContent = user.name;
                document.getElementById('Username').textContent = user.username;
                document.getElementById('Email').textContent = user.email;
                document.getElementById('Phone').textContent = user.phone;
                document.getElementById('Address').textContent = user.address;


                const tbody = document.getElementById('orderItemsBody');
                tbody.innerHTML = '';

                let total = 0;

                order.order_details.forEach(detail => {
                    const product = detail.product || {
                        name: 'N/A',
                        photo: null
                    };
                    const imagePath = product.photo ?
                        `/storage/images/${product.photo}` :
                        '/storage/images/no-image.jpg';

                    const quantity = detail.quantity;
                    const price = detail.price;
                    const subtotal = quantity * price;
                    total += subtotal;

                    tbody.innerHTML += `
                    <tr>
                        <td class="text-center">
                            <img src="${imagePath}" class="img-thumbnail" style="max-width: 80px;">
                        </td>
                        <td>${product.name}</td>
                        <td>${quantity}</td>
                        <td>$${price.toLocaleString('en-US', { minimumFractionDigits: 2 })}</td>
                        <td>$${subtotal.toLocaleString('en-US', { minimumFractionDigits: 2 })}</td>

                    </tr>
                `;
                });

                document.getElementById('orderTotal').textContent =
    '$' + total.toLocaleString('en-US', { minimumFractionDigits: 2 });

            });
        });
    });
</script>
@endsection