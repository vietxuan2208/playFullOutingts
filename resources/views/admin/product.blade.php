@extends('admin.dashboard')
@section('page-title', 'Product')
@section('content')

<style>
/* Hình sản phẩm trong table */
.product-thumb {
    width: 55px;
    height: 55px;
    object-fit: cover;
    border-radius: 8px;
    cursor: pointer;
    transition: transform .25s ease, box-shadow .25s ease;
    border: 1px solid #e5e7eb;
}
.product-thumb:hover {
    transform: scale(1.10);
    z-index: 10;
    box-shadow: 0 6px 18px rgba(0,0,0,.25);
}
/* Image modal */
#viewProductImage,
#editProductImagePreview {
    width:150px; height:150px; object-fit:cover; border-radius:10px;
}
</style>





<div class="main-content">
    <div class="card mt-4">
        <div class="card-header">
            <h5 class="card-title">Products</h5>
        </div>

        <div class="card-body">

            {{-- ALERT --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show">
                    <ul class="mb-0">
                        @foreach($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                    <button class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- ACTION BAR --}}
            <div class="row g-2 align-items-center mb-4">
                <div class="col-auto">
                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addProductModal">
                        Add
                    </button>
                </div>

                <div class="col-auto ms-auto">
                    <div class="position-relative">
                        <input type="text" id="searchInput" class="form-control" placeholder="Search product...">
                        <i class="fa fa-search position-absolute top-50 end-0 translate-middle-y me-3 text-muted"></i>
                    </div>
                </div>
            </div>

            {{-- TABLE --}}
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle text-center" id="productTable">
                    <thead class="table-dark">
                        <tr>
                            <th width="90">Image</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th width="120">Price</th>
                            <th width="90">Stock</th>
                            <th width="140">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $p)
                        <tr>
                            <td>
                                <img src="{{ $p->photo ? asset('storage/images/'.$p->photo) : asset('storage/images/no-image.jpg') }}"
                                     class="product-thumb">
                            </td>
                            <td>{{ $p->name }}</td>
                            <td>{{ Str::limit($p->description,60) }}</td>
                            <td>${{ number_format($p->price,2) }}</td>
                            <td>{{ $p->stock }}</td>
                            <td>
                                <div class="btn-group">
                                    <button type="button"
                                            class="btn btn-sm btn-outline-info"
                                            onclick="showProductModal({{ $p->id }})">
                                        <i class="fa-regular fa-eye"></i>
                                    </button>

                                    <button type="button"
                                            class="btn btn-sm btn-outline-success"
                                            onclick="showEditModal({{ $p->id }})">
                                        <i class="fa fa-pencil"></i>
                                    </button>

                                    <button type="button"
                                            class="btn btn-sm btn-outline-danger"
                                            onclick="confirmDelete({{ $p->id }})">
                                        <i class="fa fa-trash"></i>
                                    </button>

                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <nav>
                <ul class="pagination justify-content-center" id="pagination"></ul>
            </nav>

        </div>
    </div>
</div>




{{-- ====================== MODALS ====================== --}}

{{-- ADD --}}
<div class="modal fade" id="addProductModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form action="{{ route('product_admin.store') }}" method="POST" enctype="multipart/form-data"
              class="modal-content">
            @csrf
            <div class="modal-header">
                <h5>Add Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <div class="mb-3">
                    <label>Name</label>
                    <input type="text" name="name" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Image</label>
                    <input type="file" name="photo" class="form-control">
                </div>

                <div class="mb-3">
                    <label>Description</label>
                    <input type="text" name="description" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Price (USD)</label>
                    <input type="number" name="price" class="form-control"
                        step="0.01" min="0" placeholder="Ex: 19.99" required>
                </div>


                <div class="mb-3">
                    <label>Stock</label>
                    <input type="number" name="stock" class="form-control" required>
                </div>

            </div>

            <div class="modal-footer">
                <button class="btn btn-primary">Save</button>
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </form>
    </div>
</div>



{{-- VIEW --}}
<div class="modal fade" id="productModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">

            <div class="modal-header bg-primary text-white">
                <h5>Product Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body" id="modalContent"></div>

        </div>
    </div>
</div>

<div class="modal fade" id="editProductModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <form id="editProductForm" action="{{ route('admin.product.update') }}" method="POST" enctype="multipart/form-data"
              class="modal-content">
            @csrf
            @method('PUT')

            <input type="hidden" name="id" id="editProductId">

            <div class="modal-header bg-success text-white">
                <h5>Edit Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body row">

            <div class="col-md-4 text-center mb-3">

                <img id="editProductImagePreview"
                    src="/storage/images/no-image.jpg"
                    class="img-thumbnail mb-2" style="width:150px;height:150px;object-fit:cover;border-radius:10px;">

                <!-- Nút chọn ảnh -->
                <label class="btn btn-outline-primary btn-sm mt-2" style="cursor:pointer;">
                    <i class="fa-solid fa-image"></i> Change Photo
                    <input type="file" name="photo" id="editPhotoInput" class="d-none" accept="image/*">
                </label>

            </div>

                <div class="col-md-8">

                    <div class="mb-3">
                        <label>Name</label>
                        <input type="text" name="name" id="editName" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label>Description</label>
                        <input type="text" name="description" id="editDescription" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label>Price (USD)</label>
                        <input type="number" name="price" id="editPrice"
                            class="form-control" step="0.01" min="0">
                    </div>


                    <div class="mb-3">
                        <label>Stock</label>
                        <input type="number" name="stock" id="editStock" class="form-control">
                    </div>

                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-success">Update</button>
            </div>

        </form>
    </div>
</div>

<div class="modal fade" id="confirmDeleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <form action="{{ route('admin.product.delete') }}" method="POST" class="modal-content">
            @csrf

            <input type="hidden" name="id" id="deleteProductId">

            <div class="modal-header bg-danger text-white">
                <h5>Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body text-center">
                Are you sure you want to delete this product?
            </div>

            <div class="modal-footer">
                <button class="btn btn-danger">OK</button>
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>

        </form>
    </div>
</div>



<div id="productsData" data-json='@json($products)'></div>

<script>
const rowsPerPage = 10;
let currentPage = 1;
let allRows = [];
let filteredRows = [];

document.addEventListener("DOMContentLoaded", () => {
    allRows = Array.from(document.querySelectorAll("#productTable tbody tr"));
    filteredRows = [...allRows];

    renderTable();
    document.getElementById("searchInput").addEventListener("input", searchProducts);
});
document.getElementById("editPrice").addEventListener("blur", function () {
    if (this.value) {
        this.value = parseFloat(this.value).toFixed(2);
    }
});


/* SEARCH */
function searchProducts() {
    const keyword = document.getElementById("searchInput").value.toLowerCase();

    filteredRows = allRows.filter(row => {
        const name = row.cells[1].innerText.toLowerCase();
        const description = row.cells[2].innerText.toLowerCase();
        return name.includes(keyword) || description.includes(keyword);
    });

    currentPage = 1;
    renderTable();
}


/* RENDER TABLE */
function renderTable() {
    const totalPages = Math.ceil(filteredRows.length / rowsPerPage);

    allRows.forEach(r => r.style.display = "none");

    const start = (currentPage - 1) * rowsPerPage;
    filteredRows.slice(start, start + rowsPerPage).forEach(r => r.style.display = "");

    renderPagination(totalPages);
}


/* PAGINATION */
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


/* VIEW PRODUCT */
const products = JSON.parse(document.getElementById("productsData").dataset.json);

function showProductModal(id) {
    const p = products.find(item => item.id === id);

    document.getElementById("modalContent").innerHTML = `
        <div class="row">
            <div class="col-md-4 text-center">
                <img src="/storage/images/${p.photo ?? 'no-image.jpg'}"
                     class="img-thumbnail mb-3" style="max-width:150px;">
            </div>

            <div class="col-md-8">
                <p><strong>Name:</strong> ${p.name}</p>
                <p><strong>Description:</strong> ${p.description}</p>
                <p><strong>Price:</strong> $${Number(p.price).toLocaleString('en-US', { minimumFractionDigits: 2 })}</p>
                <p><strong>Stock:</strong> ${p.stock}</p>
            </div>
        </div>
    `;

    new bootstrap.Modal(document.getElementById("productModal")).show();
}


/* EDIT PRODUCT */
function showEditModal(id) {
    const p = products.find(item => item.id === id);

    document.getElementById("editProductId").value = p.id;
    document.getElementById("editName").value = p.name;
    document.getElementById("editDescription").value = p.description;
    document.getElementById("editPrice").value = p.price;
    document.getElementById("editStock").value = p.stock;

    document.getElementById("editProductImagePreview").src =
        p.photo ? `/storage/images/${p.photo}` : '/storage/images/no-image.jpg';

    new bootstrap.Modal(document.getElementById("editProductModal")).show();
}


/* DELETE */
function confirmDelete(id) {
    document.getElementById("deleteProductId").value = id;
    new bootstrap.Modal(document.getElementById("confirmDeleteModal")).show();
}
/* PREVIEW ẢNH MỚI KHI CHỌN */
document.addEventListener("DOMContentLoaded", () => {
    const photoInput = document.getElementById("editPhotoInput");
    const previewImg = document.getElementById("editProductImagePreview");

    if (photoInput) {
        photoInput.addEventListener("change", function () {
            if (this.files && this.files[0]) {
                previewImg.src = URL.createObjectURL(this.files[0]);
            }
        });
    }
});

</script>




@endsection
