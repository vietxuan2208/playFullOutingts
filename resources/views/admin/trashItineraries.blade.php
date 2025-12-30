@extends('admin.dashboard')
@section('page-title', 'Trash Itineraries ')

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
            <h5 class="card-title">Trash Itineraries</h5>
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
                        <input type="text" class="form-control" id="searchInput" placeholder="Search Itineraries...">
                        <span class="fa fa-search position-absolute top-50 end-0 translate-middle-y me-3 text-muted"></span>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover text-center align-middle" id="itineraryTable">
                    <thead class="table-dark">
                        <tr>
                            <th>Name</th>
                            <th>Days</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($itineraries as $itinerary)
                        <tr>
                            <td>{{ $itinerary->name }}</td>
                            <td>{{ $itinerary->days }}</td>
                            <td>
                                @if($itinerary->status == 1)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-danger">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-outline-success restoreItineraryBtn" 
                                        data-bs-toggle="tooltip" 
                                        title="Restore"
                                        data-id="{{ $itinerary->id }}"
                                        data-name="{{ $itinerary->name }}">
                                        <i class="fa-solid fa-rotate-left"></i>
                                    </button>

                                    <button class="btn btn-sm btn-outline-danger deleteItineraryBtn"
                                        data-id="{{ $itinerary->id }}"
                                        data-name="{{ $itinerary->name }}">
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
<div class="modal fade" id="restoreItineraryModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">Confirm Restore</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to restore <strong id="restoreItineraryName"></strong>?
            </div>
            <div class="modal-footer">
                <form id="restoreItineraryForm" method="POST" action="">
                    @csrf
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Yes, Restore</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteItineraryModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to permanently delete <strong id="deleteItineraryName"></strong>?
            </div>
            <div class="modal-footer">
                <form id="deleteItineraryForm" method="POST" action="">
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
const rowsPerPage = 6;
let currentPage = 1;
let filteredRows = [];

function filterRows() {
    const search = document.getElementById('searchInput').value.toLowerCase();
    filteredRows = Array.from(document.querySelectorAll("#itineraryTable tbody tr")).filter(row => {
        const name = row.cells[0].innerText.toLowerCase();
        const statusText = row.cells[2].innerText.trim() === "Active" ? "1" : "0";

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
    document.querySelectorAll("#itineraryTable tbody tr").forEach(r => r.style.display = "none");
    filteredRows.slice((currentPage-1) * rowsPerPage, currentPage * rowsPerPage)
                .forEach(r => r.style.display = "");
    renderPagination(totalPages);
}

function renderPagination(totalPages) {
    const ul = document.getElementById("pagination");
    ul.innerHTML = "";
    for(let i=1; i<=totalPages; i++){
        const btn = document.createElement("button");
        btn.textContent = i;
        btn.className = "btn btn-sm btn-outline-primary mx-1";
        if(i === currentPage) btn.classList.add("active");
        btn.onclick = ()=>{ currentPage = i; paginationTable(); };
        ul.appendChild(btn);
    }
}

document.addEventListener("DOMContentLoaded", () => {
    filteredRows = Array.from(document.querySelectorAll("#itineraryTable tbody tr"));
    paginationTable();

    document.getElementById("searchInput").addEventListener("input", filterRows);

    // Restore
    document.querySelectorAll(".restoreItineraryBtn").forEach(btn => {
        btn.addEventListener("click", function(){
            document.getElementById("restoreItineraryName").textContent = this.dataset.name;
            document.getElementById("restoreItineraryForm").action =
    `/admin/recycle-itineraries/restore/${this.dataset.id}`;

            new bootstrap.Modal(document.getElementById("restoreItineraryModal")).show();
        });
    });

    // Delete
    document.querySelectorAll(".deleteItineraryBtn").forEach(btn => {
        btn.addEventListener("click", function(){
            document.getElementById("deleteItineraryName").textContent = this.dataset.name;
            document.getElementById("deleteItineraryForm").action =
    `/admin/recycle-itineraries/delete/${this.dataset.id}`;

            new bootstrap.Modal(document.getElementById("deleteItineraryModal")).show();
        });
    });
});
</script>

@endsection
