@extends('admin.dashboard')
@section('page-title', 'Category')

@section('content')
<style>
.category-thumb {
    width: 40px;
    height: 40px;
    object-fit: cover;
    border-radius: 50%;
    transition: transform 0.25s ease, box-shadow 0.25s ease;
    cursor: pointer;
}
.category-thumb:hover {
    transform: scale(1.1);
    z-index: 10;
    position: relative;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
}
</style>

<div class="main-content">
    <div class="card mt-4">
        <div class="card-header">
            <h5 class="card-title">Category</h5>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error:</strong>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            <div class="row g-2 align-items-center mb-4">
                <!-- Dropdown Role -->
                <div class="col-auto">
                    <select class="form-select" name="status" id="searchStatus">
                        <option value="">All</option>
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>

                <div class="col-auto">
                    <button class="btn btn-success" id="addCategoryBtn">
                        Add
                    </button>
                </div>

                <div class="col-auto ms-auto">
                    <div class="position-relative">
                        <input type="text" class="form-control" id="searchInput" placeholder="Search">
                        <span class="fa fa-search position-absolute top-50 end-0 translate-middle-y me-3 text-muted"></span>
                    </div>
                </div>
            </div>

                
            <div class="table-responsive" >
                <table class="table table-hover" id="categoryTable">
                    <thead class="table-dark">
                        <tr>
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $category)
                        <tr>
                            <td>
                            <div class="d-flex align-items-center">
                                <div>{{$category -> name}}</div>
                            </div>
                            </td>
                            <td>{{$category -> slug }}</td>
                            <td>{{$category -> description}}</td>
                            <td>
                                @if($category->status == 1)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-danger">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                <button class="btn btn-sm btn-outline-info viewCategoryBtn" data-bs-toggle="tooltip" title="View"
                                        data-id="{{ $category->id }}"
                                        data-name="{{ $category->name }}"
                                        data-slug="{{ $category->slug }}"
                                        data-description="{{ $category->description }}"
                                        data-status="{{ $category->status }}">
                                    <i class="fa-regular fa-eye"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger deleteCategoryBtn" data-bs-toggle="tooltip" title="Delete"
                                        data-id="{{ $category->id }}"
                                        data-name="{{ $category->name }}">
                                    <i class="fa fa-trash"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-success restoreCategoryBtn" 
                                        data-bs-toggle="tooltip" 
                                        title="Restore"
                                        data-id="{{ $category->id }}"
                                        data-name="{{ $category->name }}">
                                    <i class="fa-solid fa-rotate-left"></i>
                                </button>


                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center" id="pagination"></ul>
                </nav>
            </div>

            <!-- View -->
            <div class="modal fade" id="viewCategoryModal" tabindex="-1">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content shadow-lg rounded-4">
                    <div class="modal-header bg-primary text-white rounded-top-4">
                        <h5 class="modal-title">
                            <i class="bi bi-person-lines-fill"></i> Category Details
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">

                        <div class="row">

                            <!-- Information -->
                            <div class="col-md-8">

                                <div class="mb-2">
                                    <strong>ID:</strong>
                                    <span id="viewId"></span>
                                </div>

                                <div class="mb-2">
                                    <strong>Name:</strong>
                                    <span id="viewName"></span>
                                </div>

                                <div class="mb-2">
                                    <strong>Slug:</strong>
                                    <span id="viewSlug"></span>
                                </div>
                                <div class="mb-2">
                                    <strong>Description:</strong>
                                    <span id="viewDescription"></span>
                                </div>
                                <div class="mb-2">
                                    <strong>Status:</strong>
                                    <span id="viewStatus"></span>
                                </div>

                            </div>

                        </div>

                    </div>

                </div>
            </div>
        </div>

        <!-- Delete -->
        <div class="modal fade" id="deleteCategoryModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">

                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title">Confirm Delete</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <p>Are you sure you want to delete <strong id="deleteCategoryName"></strong>?</p>
                    </div>

                    <div class="modal-footer">
                        <form id="deleteCategoryForm" method="POST" action="">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger">Yes, Delete</button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
        <!-- Restore Modal -->
<div class="modal fade" id="restoreCategoryModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">Confirm Restore</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                Are you sure you want to restore 
                <strong id="restoreCategoryName"></strong>?
            </div>

            <div class="modal-footer">
                <form id="restoreCategoryForm" method="POST" action="">
                    @csrf
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Yes, Restore</button>
                </form>
            </div>

        </div>
    </div>
</div>

<!-- Add -->
<div class="modal fade" id="addCategoryModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">Add Category</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="addCategoryForm" action="{{route('admin.category.store')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body row">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label>Name</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label>Slug</label>
                            <input type="text" class="form-control" name="slug" required>
                        </div>
                        <div class="mb-3">
                            <label>Description</label>
                            <input type="text" class="form-control" name="description" required>
                        </div>
                        <div class="mb-3">
                            <label>Status</label>
                            <select class="form-control" name="status">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Add Category</button>
                </div>
            </form>
        </div>
    </div>
</div>




<!-- Edit -->
        <div class="modal fade" id="editCategoryModal" tabindex="-1">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-warning text-white">
                        <h5 class="modal-title">Edit Category</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>

                    <form id="editCategoryForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-body row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label>Name</label>
                                <input type="text" class="form-control" name="name" id="editName">
                            </div>
                            <div class="mb-3">
                                <label>Slug</label>
                                <input type="email" class="form-control" name="slug" id="editSlug">
                            </div>
                            <div class="mb-3">
                                <label>Description</label>
                                <input type="email" class="form-control" name="description" id="editDescription">
                            </div>
                            <div class="mb-3">
                                <label>Status</label>
                                <select class="form-control" name="status" id="editStatus">
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-warning">Save Changes</button>
                    </div>
                </form>
                </div>
            </div>
        </div>
        </div>
    </div>
<script>
const rowsPerPage = 4;
let currentPage = 1;
let filteredRows = [];

function filterRows() {
    const searchText = document.getElementById('searchInput').value.toLowerCase();
    const selectedStatus = document.getElementById('searchStatus').value;

    filteredRows = Array.from(document.querySelectorAll("#categoryTable tbody tr")).filter(row => {
        const name = row.cells[0].innerText.toLowerCase();
        const statusText = row.cells[3].innerText.trim() === "Active" ? "1" : "0";

        const matchName = name.includes(searchText);
        const matchStatus = selectedStatus === "" || statusText === selectedStatus;

        return matchName && matchStatus;
    });

    currentPage = 1;
    paginationTable();
}

function paginationTable() {
    const totalRows = filteredRows.length;
    const totalPages = Math.ceil(totalRows / rowsPerPage);

    document.querySelectorAll("#categoryTable tbody tr").forEach(row => row.style.display = "none");

    const start = (currentPage - 1) * rowsPerPage;
    const end = start + rowsPerPage;

    filteredRows.slice(start, end).forEach(row => row.style.display = "");

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
            paginationTable();
        };

        pagination.appendChild(btn);
    }
}

document.addEventListener("DOMContentLoaded", () => {

    filteredRows = Array.from(document.querySelectorAll("#categoryTable tbody tr"));
    paginationTable();


    document.getElementById('searchInput').addEventListener('input', filterRows);
    document.getElementById('searchStatus').addEventListener('change', filterRows);

    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    initCategoryModals();
});

function initCategoryModals() {
// View
    document.querySelectorAll(".viewCategoryBtn").forEach(btn => {
        btn.addEventListener("click", function () {
            const id = this.dataset.id;
            const name = this.dataset.name;
            const slug = this.dataset.slug;
            const description = this.dataset.description;
            const status = this.dataset.status == 1 ? 'Active' : 'Inactive';

            document.getElementById("viewId").textContent = id;
            document.getElementById("viewName").textContent = name;
            document.getElementById("viewSlug").textContent = slug;
            document.getElementById("viewDescription").textContent = description;
            document.getElementById("viewStatus").textContent = status;

            new bootstrap.Modal(document.getElementById("viewCategoryModal")).show();
        });
    });

    // Delete permanently
    document.querySelectorAll(".deleteCategoryBtn").forEach(btn => {
        btn.addEventListener("click", function () {
            const id = this.dataset.id;
            const name = this.dataset.name;

            document.getElementById("deleteCategoryName").textContent = name;
            document.getElementById("deleteCategoryForm").action = `/admin/recycle-category/delete/${id}`;

            new bootstrap.Modal(document.getElementById("deleteCategoryModal")).show();
        });
    });

    // Restore Category
    document.querySelectorAll(".restoreCategoryBtn").forEach(btn => {
        btn.addEventListener("click", function () {
            const id = this.dataset.id;
            const name = this.dataset.name;

            document.getElementById("restoreCategoryName").textContent = `"${name}"`;
            document.getElementById("restoreCategoryForm").action = `/admin/recycle-category/restore/${id}`;

            new bootstrap.Modal(document.getElementById("restoreCategoryModal")).show();
        });
    });

}

document.addEventListener("DOMContentLoaded", () => {
    const addCategoryBtn = document.getElementById("addCategoryBtn");
    const addCategoryModal = document.getElementById("addCategoryModal");

    addCategoryBtn.addEventListener("click", () => {
        const addModal = new bootstrap.Modal(addCategoryModal);
        addModal.show();
    });
});


</script>
@endsection