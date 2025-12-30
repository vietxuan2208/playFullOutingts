@extends('admin.dashboard')
@section('page-title', 'Itinerary')

@section('content')
<style>
.game-thumb {
    width: 40px;
    height: 40px;
    object-fit: cover;
    border-radius: 50%;
    transition: 0.25s;
    cursor: pointer;
}
.game-thumb:hover {
    transform: scale(1.1);
    box-shadow: 0 4px 15px rgba(0,0,0,0.3);
}
#viewImage {
    width: 250px;   
    height: 180px;
    object-fit: cover;
    display: block;
    margin: 0 auto 20px auto;
    border-radius: 10px;
}
</style>

<div class="main-content">
    <div class="card mt-4">
        <div class="card-header">
            <h5 class="card-title">Itinerary</h5>
        </div>

        <div class="card-body">

            {{-- SUCCESS --}}
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            {{-- ERRORS --}}
            @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show">
                <strong>Error:</strong>
                <ul class="mb-0">
                    @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
                </ul>
                <button class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            {{-- FILTER + ADD --}}
            <div class="row g-2 mb-4">
                <div class="col-auto">
                    <select id="searchStatus" class="form-select">
                        <option value="">All</option>
                        <option value="0">Active</option>
                        <option value="1">Inactive</option>
                    </select>
                </div>

                <div class="col-auto">
                    <button id="addItineraryBtn" class="btn btn-success">
                        Add Itinerary
                    </button>
                </div>

                <div class="col-auto ms-auto">
                    <input type="text" id="searchInput" class="form-control" placeholder="Search itinerary...">
                </div>
            </div>

            {{-- TABLE --}}
            <div class="table-responsive">
                <table class="table table-hover table-sm" id="itineraryTable">
                    <thead class="table-dark">
                        <tr>
                            <th>Img</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Games</th>
                            <th>Days</th>
                            <th>Status</th>
                            <th style="width:120px;">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($itineraries as $it)
                        <tr>
                            <td>
                                @if($it->image)
                                    <img class="game-thumb" src="{{ asset('storage/itineraries/'.$it->image) }}">
                                @else
                                    <img class="game-thumb" src="{{ asset('storage/avatars/no-image.jpg') }}">
                                @endif
                            </td>

                            <td>{{ $it->name }}</td>
                            <td>{{ Str::limit($it->description, 50) }}</td>
                            <td>
                                @foreach($it->games as $g)
                                    <span>{{ $g->name }}</span>
                                @endforeach
                            </td>

                            <td>{{ $it->days }}</td>

                            <td>
                                <span class="badge {{ $it->status ? 'bg-success' : 'bg-danger' }}">
                                    {{ $it->status ? 'Active' : 'Inactive' }}
                                </span>
                            </td>

                            <td>
                                <div class="btn-group">
                                <button class="btn btn-sm btn-outline-info viewItineraryBtn"
                                    data-name="{{ $it->name }}"
                                    data-description="{{ $it->description }}"
                                    data-days="{{ $it->days }}"
                                    data-status="{{ $it->status }}"
                                    data-image="{{ $it->image }}"
                                     data-games='@json($it->games)'
                                    data-locations='@json($it->locations)'>
                                    <i class="fa-regular fa-eye"></i>
                                </button>

                                <button class="btn btn-sm btn-outline-success editItineraryBtn"
                                    data-id="{{ $it->id }}"
                                    data-name="{{ $it->name }}"
                                    data-description="{{ $it->description }}"
                                    data-days="{{ $it->days }}"
                                    data-status="{{ $it->status }}"
                                    data-image="{{ $it->image }}"
                                     data-games='@json($it->games)'
                                    data-locations='@json($it->locations)'>
                                    <i class="fa-solid fa-pencil"></i>
                                </button>

                                <button class="btn btn-sm btn-outline-danger deleteItineraryBtn"
                                    data-id="{{ $it->id }}"
                                    data-name="{{ $it->name }}">
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

{{-- ========================= VIEW MODAL ========================= --}}
<div class="modal fade" id="viewItineraryModal">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header bg-primary text-white">
                <h5>Itinerary Details</h5>
                <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <img id="viewImage" class="img-fluid rounded mb-3" alt="Image">

                <p><label>Name:</label> <span id="viewName"></span></p>
                <p><label>Description:</label> <span id="viewDescription"></span></p>
                <p><label>Days:</label> <span id="viewDays"></span></p>
                <p><label>Games:</label> <span id="viewGames"></span></p>
                <p><label>Status:</label> <span id="viewStatus"></span></p>

                <hr>

                <h6>Locations</h6>
                <div id="viewLocations"></div>
            </div>
        </div>
    </div>
</div>

{{-- ========================= ADD MODAL ========================= --}}
<div class="modal fade" id="addItineraryModal">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header bg-success text-white">
                <h5>Add Itinerary</h5>
                <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <form method="POST" action="{{ route('admin.itineraries.add') }}" enctype="multipart/form-data">
                @csrf

                <div class="modal-body">
                    <label>Image</label>
                    <input type="file" name="image" class="form-control mb-2">
                    <label>Name</label>
                    <input name="name" class="form-control mb-2" required>

                    <label>Description</label>
                    <textarea name="description" class="form-control mb-2"></textarea>

                    <label>Days</label>
                    <label>Games</label>
<select name="game_ids[]" id="addGames" class="form-control mb-2" multiple>
    @foreach($games as $g)
        <option value="{{ $g->id }}">{{ $g->name }}</option>
    @endforeach
</select>

                    <input type="number" name="days" class="form-control mb-2" required>
                    <label>Locations</label>
                        <select name="location_ids[]" class="form-control mb-2" multiple>
                            @foreach($locations as $loc)
                                <option value="{{ $loc->id }}">{{ $loc->name }}</option>
                            @endforeach
                        </select>


                    <label>Status</label>
                    <select name="status" class="form-control mb-2">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>

                    
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-success">Add</button>
                </div>
            </form>

        </div>
    </div>
</div>


{{-- ========================= EDIT MODAL ========================= --}}
<div class="modal fade" id="editItineraryModal">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header bg-warning text-white">
                <h5>Edit Itinerary</h5>
                <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <form id="editItineraryForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method("PUT")

                <div class="modal-body">
                    <label>Image (optional)</label>
                    <input type="file" name="image" class="form-control mb-2">

                    <label>Name</label>
                    <input id="editName" name="name" class="form-control mb-2">

                    <label>Description</label>
                    <textarea id="editDescription" name="description" class="form-control mb-2"></textarea>
                    <label>Games</label>
<select name="game_ids[]" id="editGames" class="form-control mb-2" multiple>
    @foreach($games as $g)
        <option value="{{ $g->id }}">{{ $g->name }}</option>
    @endforeach
</select>



                    <label>Days</label>
                    <input id="editDays" name="days" type="number" class="form-control mb-2">
                    <label>Locations</label>
                        <select name="location_ids[]" id="editLocations" class="form-control mb-2" multiple>
                            @foreach($locations as $loc)
                                <option value="{{ $loc->id }}">{{ $loc->name }}</option>
                            @endforeach
                        </select>


                    <label>Status</label>
                    <select id="editStatus" name="status" class="form-control mb-2">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>

                    

                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary">Cancel</button>
                    <button class="btn btn-warning">Save</button>
                </div>

            </form>

        </div>
    </div>
</div>


{{-- ========================= DELETE MODAL ========================= --}}
<div class="modal fade" id="deleteItineraryModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header bg-danger text-white">
                <h5>Confirm Delete</h5>
                <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                Do you want to delete <strong id="deleteName"></strong>?
            </div>

            <div class="modal-footer">
                <form id="deleteItineraryForm" method="POST">
                    @csrf
                    @method("DELETE")
                    <button class="btn btn-secondary">Cancel</button>
                    <button class="btn btn-danger">Delete</button>
                </form>
            </div>

        </div>
    </div>
</div>


<script>
let rowsPerPage = 5;
let currentPage = 1;
let filteredRows = [];

/* ========================= FILTER / SEARCH ========================= */
function filterRows() {
    const keyword = document.getElementById('searchInput').value.toLowerCase();
    const selectedStatus = document.getElementById('searchStatus').value;

    filteredRows = Array.from(document.querySelectorAll("#itineraryTable tbody tr")).filter(row => {

        const name = row.cells[1].innerText.toLowerCase();
        const desc = row.cells[2].innerText.toLowerCase();
        const days = row.cells[3].innerText.toLowerCase();

        const statusCell = row.cells[4].innerText.trim();
        const status = statusCell.includes("Active") ? "1" : "0";

        const matchKeyword =
            name.includes(keyword) ||
            desc.includes(keyword) ||
            days.includes(keyword);

        const matchStatus =
            selectedStatus === "" || selectedStatus === status;

        return matchKeyword && matchStatus;
    });

    currentPage = 1;
    paginate();
}

/* ========================= PAGINATION ========================= */
function paginate() {
    const totalPages = Math.ceil(filteredRows.length / rowsPerPage);

    document.querySelectorAll("#itineraryTable tbody tr").forEach(row => row.style.display = "none");

    const start = (currentPage - 1) * rowsPerPage;
    const end = start + rowsPerPage;

    filteredRows.slice(start, end).forEach(row => row.style.display = "");

    renderPagination(totalPages);
}

function renderPagination(totalPages) {
    const container = document.getElementById("pagination");
    container.innerHTML = "";

    for (let i = 1; i <= totalPages; i++) {
        const btn = document.createElement("button");
        btn.textContent = i;
        btn.className = "btn btn-sm btn-outline-primary mx-1";

        if (i === currentPage) btn.classList.add("active");

        btn.onclick = () => {
            currentPage = i;
            paginate();
        };

        container.appendChild(btn);
    }
}

/* ========================= MODALS (VIEW / EDIT / DELETE) ========================= */
function initModals() {

    // --- VIEW ---
    document.querySelectorAll(".viewItineraryBtn").forEach(btn => {
        btn.onclick = () => {
            document.getElementById("viewName").innerText = btn.dataset.name;
            document.getElementById("viewDescription").innerText = btn.dataset.description;
            document.getElementById("viewDays").innerText = btn.dataset.days;
            document.getElementById("viewStatus").innerText = btn.dataset.status == 1 ? "Active" : "Inactive";

            const img = btn.dataset.image ? `/storage/itineraries/${btn.dataset.image}` : "/no-image.jpg";
            document.getElementById("viewImage").src = img;

            let locations = JSON.parse(btn.dataset.locations || "[]");
            let html = "";
            locations.forEach(loc => {
                html += `
                    <div class="border rounded p-2 mb-2">
                        <strong>${loc.name}</strong><br>
                        ${loc.description ?? ""}<br>
                        ${loc.image ? `<img src="/storage/locations/${loc.image}" width="120">` : ""}
                    </div>`;
            });
            let games = JSON.parse(btn.dataset.games || "[]");
            let ghtml = "";
            games.forEach(g => {
                ghtml += `<span class="badge bg-primary me-1">${g.name}</span>`;
            });
            document.getElementById("viewGames").innerHTML = ghtml || "No games";


            document.getElementById("viewLocations").innerHTML = html;

            new bootstrap.Modal(document.getElementById("viewItineraryModal")).show();
        };
    });

    // --- ADD ---
    document.getElementById("addItineraryBtn").onclick = () => {
        new bootstrap.Modal(document.getElementById("addItineraryModal")).show();
    };

    // --- EDIT ---
    document.querySelectorAll(".editItineraryBtn").forEach(btn => {
        btn.onclick = () => {
            const id = btn.dataset.id;

            document.getElementById("editName").value = btn.dataset.name;
            document.getElementById("editDescription").value = btn.dataset.description;
            document.getElementById("editDays").value = btn.dataset.days;
            document.getElementById("editStatus").value = btn.dataset.status;
            let selectedLocations = JSON.parse(btn.dataset.locations).map(l => l.id);

            [...document.getElementById('editLocations').options].forEach(opt => {
                opt.selected = selectedLocations.includes(parseInt(opt.value));
            });
            let games = JSON.parse(btn.dataset.games || "[]");
            let gameIds = games.map(g => g.id);

            [...document.getElementById("editGames").options].forEach(o => {
                o.selected = gameIds.includes(parseInt(o.value));
            });



            document.getElementById("editItineraryForm").action = `/admin/itineraries/update/${id}`;

            new bootstrap.Modal(document.getElementById("editItineraryModal")).show();
        };
    });

    // --- DELETE ---
    document.querySelectorAll(".deleteItineraryBtn").forEach(btn => {
        btn.onclick = () => {
            document.getElementById("deleteName").innerText = btn.dataset.name;
            document.getElementById("deleteItineraryForm").action = `/admin/itineraries/delete/${btn.dataset.id}`;
            new bootstrap.Modal(document.getElementById("deleteItineraryModal")).show();
        };
    });
}

/* ========================= INITIAL LOAD ========================= */
document.addEventListener("DOMContentLoaded", () => {

    filteredRows = Array.from(document.querySelectorAll("#itineraryTable tbody tr"));
    paginate();

    document.getElementById("searchInput").addEventListener("input", filterRows);
    document.getElementById("searchStatus").addEventListener("change", filterRows);

    initModals();
});
</script>


@endsection
