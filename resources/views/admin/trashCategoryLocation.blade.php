@extends('admin.dashboard')
@section('page-title', 'Trash Category Location ')

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
            <h5 class="card-title">Trash Category Location</h5>
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

            <!-- Search -->
            <div class="row g-2 align-items-center mb-4">
                <div class="col-auto">
                    <select class="form-select" name="status" id="searchStatus">
                        <option value="">All</option>
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
                <div class="col-auto ms-auto">
                    <div class="position-relative">
                        <input type="text" class="form-control" id="searchInput" placeholder="Search Category Location...">
                        <span class="fa fa-search position-absolute top-50 end-0 translate-middle-y me-3 text-muted"></span>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover text-center align-middle" id="categoryLocationTable">
                    <thead class="table-dark">
                        <tr>
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Description</th>
                            <th>Location</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categoryLocations as $categoryLocation)
                        <tr>
                            <td>{{ $categoryLocation->name }}</td>
                            <th>{{$categoryLocation->slug}}</th>
                            <td>{{ Str::limit($categoryLocation->description, 60) }}</td>
                            <td>
                                @forelse($categoryLocation->locations as $location)
                                    @forelse($location->itineraries as $it)
                                        <span>{{ $it->name }}</span>
                                    @empty
                                        <span>No itineraries</span>
                                    @endforelse
                                @empty
                                    <span>No locations</span>
                                @endforelse

                            </td>
                            <td>
                                <span class="badge {{ $categoryLocation->status ? 'bg-success' : 'bg-danger' }}">
                                    {{ $categoryLocation->status ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-outline-success restoreCategoryLocationBtn" 
                                        data-bs-toggle="tooltip" 
                                        title="Restore"
                                        data-id="{{ $categoryLocation->id }}"
                                        data-name="{{ $categoryLocation->name }}">
                                        <i class="fa-solid fa-rotate-left"></i>
                                    </button>

                                    <button class="btn btn-sm btn-outline-danger deleteCategoryLocationBtn"
                                        data-id="{{ $categoryLocation->id }}"
                                        data-name="{{ $categoryLocation->name }}">
                                        <i class="fa fa-trash"></i>
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
    </div>
</div>

<!-- Restore Modal -->
<div class="modal fade" id="restoreCategoryLocationModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">Confirm Restore</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to restore <strong id="restoreCategoryLocationName"></strong>?
            </div>
            <div class="modal-footer">
                <form id="restoreCategoryLocationForm" method="POST" action="">
                    @csrf
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Yes, Restore</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteCategoryLocationModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to permanently delete <strong id="deleteCategoryLocationName"></strong>?
            </div>
            <div class="modal-footer">
                <form id="deleteCategoryLocationForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Yes, Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
const rowsPerPage = 10;
let currentPage = 1;
let filteredRows = [];

function filterRows() {
    const searchText = document.getElementById('searchInput').value.toLowerCase();
    const selectedStatus = document.getElementById('searchStatus').value;

    filteredRows = Array.from(document.querySelectorAll("#categoryLocationTable tbody tr")).filter(row => {

        // Cột Name
        const name = row.cells[1].innerText.toLowerCase();

        // Cột Status (badge)
        const statusText = row.cells[4].querySelector("span").textContent.trim().toLowerCase();
        const statusValue = statusText === "active" ? "1" : "0";

        // Điều kiện lọc
        const matchName = name.includes(searchText);
        const matchStatus = selectedStatus === "" || selectedStatus === statusValue;

        return matchName && matchStatus;
    });

    currentPage = 1;
    paginationTable();
}

function paginationTable() {
    const totalRows = filteredRows.length;
    const totalPages = Math.ceil(totalRows / rowsPerPage);

    // Ẩn toàn bộ trước
    document.querySelectorAll("#categoryLocationTable tbody tr").forEach(row => row.style.display = "none");
    // Hiện đúng page
    filteredRows
        .slice((currentPage - 1) * rowsPerPage, currentPage * rowsPerPage)
        .forEach(row => row.style.display = "");

    renderPagination(totalPages);
}

function renderPagination(totalPages) {
    const ul = document.getElementById("pagination");
    ul.innerHTML = "";

    for (let i = 1; i <= totalPages; i++) {
        const btn = document.createElement("button");
        btn.textContent = i;
        btn.className = "btn btn-sm btn-outline-primary mx-1";
        if (i === currentPage) btn.classList.add("active");
        btn.onclick = () => {
            currentPage = i;
            paginationTable();
        };

        ul.appendChild(btn);
    }
}

document.addEventListener("DOMContentLoaded", () => {

    filteredRows = Array.from(document.querySelectorAll("#categoryLocationTable tbody tr"));
    paginationTable();


    document.getElementById("searchInput").addEventListener("input", filterRows);

    document.getElementById("searchStatus").addEventListener("change", filterRows);
document.querySelectorAll(".restoreCategoryLocationBtn").forEach(btn => {
    btn.addEventListener("click", function () {
        document.getElementById("restoreCategoryLocationName").textContent = this.dataset.name;

        document.getElementById("restoreCategoryLocationForm").action =
            `/admin/recycle-category-location/restore/${this.dataset.id}`;

        new bootstrap.Modal(document.getElementById("restoreCategoryLocationModal")).show();
    });
});


    document.querySelectorAll(".deleteCategoryLocationBtn").forEach(btn => {
        btn.addEventListener("click", function () {
            document.getElementById("deleteCategoryLocationName").textContent = this.dataset.name;

            document.getElementById("deleteCategoryLocationForm").action =
    `/admin/recycle-category-location/delete/${this.dataset.id}`;


            new bootstrap.Modal(document.getElementById("deleteCategoryLocationModal")).show();
        });
    });
});
</script>


@endsection
