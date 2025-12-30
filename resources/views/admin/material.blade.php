@extends('admin.dashboard')
@section('page-title', 'Materials')

@section('content')
<style>
.location-thumb {
    width: 50px;
    height: 50px;
    object-fit: cover;
    border-radius: 8px;
    cursor: pointer;
    transition: transform 0.25s ease, box-shadow 0.25s ease;
}
.location-thumb:hover {
    transform: scale(1.1);
    z-index: 10;
    position: relative;
    box-shadow: 0 4px 15px rgba(0,0,0,0.3);
}
</style>

<div class="main-content">
    <div class="card mt-4">
        <div class="card-header">
            <h5 class="card-title">Materials</h5>
        </div>
        <div class="card-body">

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="row g-2 align-items-center mb-4">
                <div class="col-auto">
                    <select class="form-select" id="searchStatus">
                        <option value="">All</option>
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
                <div class="col-auto">
                    <button class="btn btn-success" id="addMaterialBtn">Add Material</button>
                </div>
                <div class="col-auto ms-auto">
                    <input type="text" class="form-control" id="searchInput" placeholder="Search Materials...">
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover text-center align-middle" id="materialTable">
                    <thead class="table-dark">
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($materials as $material)
                        <tr>
                            <td>
                                <img src="{{ $material->image ? asset('storage/materials/'.$material->image) : asset('storage/materials/no-image.jpg') }}" class="location-thumb">
                            </td>
                            <td>{{ $material->name }}</td>
                            <td>
                                <span class="badge {{ $material->status ? 'bg-success' : 'bg-danger' }}">
                                    {{ $material->status ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-outline-info viewMaterialBtn"
                                        data-id="{{ $material->id }}"
                                        data-name="{{ $material->name }}"
                                        data-status="{{ $material->status }}"
                                        data-image="{{ $material->image ? asset('storage/materials/'.$material->image) : asset('storage/materials/no-image.jpg') }}"
                                        data-games="{{ $material->games->pluck('name')->join(', ') }}">
                                        <i class="fa-regular fa-eye"></i>
                                    </button>

                                    <button class="btn btn-sm btn-outline-success editMaterialBtn"
                                        data-id="{{ $material->id }}"
                                        data-name="{{ $material->name }}"
                                        data-status="{{ $material->status }}"
                                        data-games="{{ $material->games->pluck('name')->join(', ') }}"
                                        data-game_ids="{{ $material->games->pluck('id')->join(',') }}"
                                        data-game_names="{{ $material->games->pluck('name')->join(',') }}"
                                        data-image="{{ $material->image ? asset('storage/materials/'.$material->image) : asset('storage/materials/no-image.jpg') }}">
                                        <i class="fa-solid fa-pencil"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger deleteMaterialBtn"
                                        data-id="{{ $material->id }}"
                                        data-name="{{ $material->name }}">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <ul class="pagination justify-content-center" id="pagination"></ul>
        </div>
    </div>
</div>

<!-- View Modal -->
<div class="modal fade" id="viewMaterialModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">View Material</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img id="viewMaterialImage" style="width:150px;height:150px;object-fit:cover;" class="rounded mb-2">
                <p><strong>Name:</strong> <span id="viewMaterialName"></span></p>
                <p><strong>Status:</strong> <span id="viewMaterialStatus"></span></p>
                <p><strong>Games using this material:</strong></p>
                    <ul id="viewMaterialGames" class="list-group text-start mb-2"></ul>
            </div>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addMaterialModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">Add Material</h5>
                <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.material.add') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body text-center">
                    <img id="addMaterialImagePreview" src="{{ asset('storage/materials/no-image.jpg') }}" style="width:150px;height:150px;" class="rounded mb-2">
                    <input type="file" name="image" class="form-control mb-2" id="addMaterialImageInput">
                    <input type="text" name="name" class="form-control mb-2" placeholder="Name" required>
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

<!-- Edit Modal -->
<div class="modal fade" id="editMaterialModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title">Edit Material</h5>
                <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="editMaterialForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body text-center">
                    <img id="editMaterialImagePreview" src="{{ asset('storage/materials/no-image.jpg') }}" style="width:150px;height:150px;" class="rounded mb-2">
                    <input type="file" name="image" class="form-control mb-2" id="editMaterialImageInput">
                    <input type="text" name="name" class="form-control mb-2" id="editMaterialName" placeholder="Name" required>
                    <select name="status" class="form-control mb-2" id="editMaterialStatus">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                    <div class="text-start mb-3">
                        <strong>Select Games:</strong>
                        <div id="editMaterialGames"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-warning">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteMaterialModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Delete Material</h5>
                <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                Are you sure to delete <strong id="deleteMaterialName"></strong>?
            </div>
            <div class="modal-footer">
                <form id="deleteMaterialForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
     const allGames = @json($games);
const rowsPerPage = 5;
let currentPage = 1;
let filteredRows = [];

function filterRows() {
    const searchText = document.getElementById('searchInput').value.toLowerCase();
    const selectedStatus = document.getElementById('searchStatus').value;

    filteredRows = Array.from(document.querySelectorAll("#materialTable tbody tr")).filter(row => {
        const name = row.cells[1].innerText.toLowerCase();
        const status = row.cells[2].innerText.includes('Active') ? '1' : '0';
        return name.includes(searchText) && (selectedStatus === "" || status === selectedStatus);
    });

    currentPage = 1;
    paginate();
}

function paginate() {
    const totalPages = Math.ceil(filteredRows.length / rowsPerPage);
    document.querySelectorAll("#materialTable tbody tr").forEach(r => r.style.display='none');
    filteredRows.slice((currentPage-1)*rowsPerPage, currentPage*rowsPerPage).forEach(r => r.style.display='');

    const pagination = document.getElementById('pagination');
    pagination.innerHTML = '';
    for(let i=1;i<=totalPages;i++){
        const btn = document.createElement('button');
        btn.textContent=i;
        btn.className='btn btn-sm btn-outline-primary mx-1';
        if(i===currentPage) btn.classList.add('active');
        btn.onclick = () => { currentPage=i; paginate(); };
        pagination.appendChild(btn);
    }
}

function initMaterialModals() {
    const addPreview = document.getElementById('addMaterialImagePreview');
    addPreview.dataset.default = addPreview.src;

    const editPreview = document.getElementById('editMaterialImagePreview');
    editPreview.dataset.default = editPreview.src;

    previewImage('addMaterialImageInput', 'addMaterialImagePreview');
    previewImage('editMaterialImageInput', 'editMaterialImagePreview');
    document.getElementById('addMaterialBtn').addEventListener('click', ()=>new bootstrap.Modal(document.getElementById('addMaterialModal')).show());

    // View
    // Preview image khi chọn file
function previewImage(inputId, previewId) {
    const input = document.getElementById(inputId);
    const preview = document.getElementById(previewId);

    input.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
            }
            reader.readAsDataURL(file);
        } else {
            preview.src = preview.dataset.default; // nếu muốn reset về default
        }
    });
}
    document.querySelectorAll('.viewMaterialBtn').forEach(btn=>{
        btn.addEventListener('click', function(){
            document.getElementById('viewMaterialName').textContent = this.dataset.name;
            document.getElementById('viewMaterialStatus').textContent = this.dataset.status == '1' ? 'Active' : 'Inactive';
            document.getElementById('viewMaterialImage').src = this.dataset.image;

            const gamesList = document.getElementById('viewMaterialGames');
            gamesList.innerHTML = "";

            let games = this.dataset.games;

            if (!games || games.trim() === "") {
                gamesList.innerHTML = "<li class='list-group-item'>No games using this material</li>";
            } else {
                games.split(',').forEach(g => {
                    gamesList.innerHTML += `<li class="list-group-item">${g.trim()}</li>`;
                });
            }

            new bootstrap.Modal(document.getElementById('viewMaterialModal')).show();
        });
    });


    // Edit
        document.querySelectorAll('.editMaterialBtn').forEach(btn => {
        btn.addEventListener('click', function() {

            const form = document.getElementById('editMaterialForm');
            form.action = `/admin/material/${this.dataset.id}`;

            document.getElementById('editMaterialName').value = this.dataset.name;
            document.getElementById('editMaterialStatus').value = this.dataset.status;
            document.getElementById('editMaterialImagePreview').src = this.dataset.image;

            // Parse selected games
            const gameIds = this.dataset.game_ids ? this.dataset.game_ids.split(',').map(Number) : [];

            // Build checklist
            const gamesListDiv = document.getElementById('editMaterialGames');
            gamesListDiv.innerHTML = "";

            allGames.forEach(g => {
                const checked = gameIds.includes(g.id) ? "checked" : "";
                gamesListDiv.innerHTML += `
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="games[]" value="${g.id}" ${checked}>
                        <label class="form-check-label">${g.name}</label>
                    </div>
                `;
            });

            new bootstrap.Modal(document.getElementById('editMaterialModal')).show();
        });
    });



    // Delete
    document.querySelectorAll('.deleteMaterialBtn').forEach(btn=>{
        btn.addEventListener('click', function(){
            document.getElementById('deleteMaterialName').textContent=this.dataset.name;
            document.getElementById('deleteMaterialForm').action=`/admin/material/${this.dataset.id}`;
            new bootstrap.Modal(document.getElementById('deleteMaterialModal')).show();
        });
    });
}

document.addEventListener('DOMContentLoaded', ()=>{
    
    filteredRows = Array.from(document.querySelectorAll("#materialTable tbody tr"));
    paginate();
    document.getElementById('searchInput').addEventListener('input', filterRows);
    document.getElementById('searchStatus').addEventListener('change', filterRows);
    initMaterialModals();
});
</script>
@endsection
