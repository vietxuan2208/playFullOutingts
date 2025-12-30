@extends('admin.dashboard')
@section('page-title', 'Game')

@section('content')
<style>
.game-thumb {
    width: 40px;
    height: 40px;
    object-fit: cover;
    border-radius: 50%;
    transition: transform 0.25s ease, box-shadow 0.25s ease;
    cursor: pointer;
}
.game-thumb:hover {
    transform: scale(1.1);
    z-index: 10;
    position: relative;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
}
</style>

<div class="main-content">
    <div class="card mt-4">
        <div class="card-header">
            <h5 class="card-title">Games</h5>
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
            <!-- Filters & Add Button -->
            <div class="row g-2 align-items-center mb-4">
                <div class="col-auto">
                    <select class="form-select" name="status" id="searchStatus">
                        <option value="">All</option>
                        <option value="0">Active</option>
                        <option value="1">Inactive</option>
                    </select>
                </div>
                <div class="col-auto">
                    <button class="btn btn-success" id="addGameBtn">Add</button>
                </div>
                <div class="col-auto ms-auto">
                    <div class="position-relative">
                        <input type="text" class="form-control" id="searchInput" placeholder="Search Game">
                        <span class="fa fa-search position-absolute top-50 end-0 translate-middle-y me-3 text-muted"></span>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover text-center align-middle" id="gameTable">
                    <thead class="table-dark">
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Duration</th>
                            <th>Players</th>
                            <th>Category</th>
                            <th>Material</th>
                            <th>Itinerary</th>
                            <th>Video</th>
                            <th>File</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($games as $game)
                        <tr>
                            <td>
                                <img src="{{ $game->image ? asset('storage/games/images/'.$game->image) : asset('storage/games/no-image.jpg') }}" width="50" height="50" class="rounded">
                            </td>
                            <td>{{ $game->name }}</td>
                            <td>{{ $game->slug }}</td>
                            <td>{{ $game->duration }} min</td>
                            <td>{{ $game->players }}</td>
                            <td>
                                @foreach($game->categories as $category)
                                    <span>{{ $category->name }}</span>
                                @endforeach
                            </td>
                            <td>
                                @foreach($game->materials as $material)
                                    <span>{{ $material->name }}</span>
                                @endforeach
                            </td>
                            <td>
                                @foreach($game->itineraries as $itinerary)
                                    <span>{{ $itinerary->name }}</span>
                                @endforeach
                            </td>

                            <td>@if($game->video_url)<a href="{{ $game->video_url }}" target="_blank">View Video</a>@endif</td>
                            <td>@if($game->download_file)<a href="{{ asset('storage/games/files/'.$game->download_file) }}" download>Download</a>@endif</td>
                            <td>
                                <span class="badge {{ $game->status ? 'bg-success' : 'bg-danger' }}">
                                    {{ $game->status ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-outline-info viewGameBtn"
                                        data-id="{{ $game->id }}"
                                        data-name="{{ $game->name }}"
                                        data-slug="{{ $game->slug }}"
                                        data-game_setup="{{ $game->game_setup }}"
                                        data-game_rules="{{ $game->game_rules }}"
                                        data-duration="{{ $game->duration }}"
                                        data-instructions="{{ $game->instructions }}"
                                        data-status="{{ $game->status }}"
                                        data-image="{{ $game->image ? asset('storage/games/images/'.$game->image) : asset('storage/games/no-image.jpg') }}"
                                        data-video="{{ $game->video_url }}"
                                        data-players="{{ $game->players }}"
                                        data-file="{{ $game->download_file }}"
                                        data-difficulty="{{ $game->difficulty }}"
                                        data-itineraries='@json($game->itineraries->pluck("id"))'

                                        data-categories='@json($game->categories->pluck("id"))'
                                        data-materials='@json($game->materials->pluck("id"))'>
                                        <i class="fa-regular fa-eye"></i>
                                    </button>

                                    <button class="btn btn-sm btn-outline-success editGameBtn"
                                        data-id="{{ $game->id }}"
                                        data-name="{{ $game->name }}"
                                        data-slug="{{ $game->slug }}"
                                        data-players="{{ $game->players }}"
                                        data-game_setup="{{ $game->game_setup }}"
                                        data-game_rules="{{ $game->game_rules }}"
                                        data-duration="{{ $game->duration }}"
                                        data-difficulty="{{ $game->difficulty }}"
                                        data-instructions="{{ $game->instructions }}"
                                        data-status="{{ $game->status }}"
                                        data-image="{{ $game->image ? asset('storage/games/images/'.$game->image) : asset('storage/games/no-image.jpg') }}"
                                        data-video="{{ $game->video_url }}"
                                        data-itineraries='@json($game->itineraries->pluck("id"))'

                                        data-file="{{ $game->download_file }}"
                                        data-categories='@json($game->categories->pluck("id"))'
                                        data-materials='@json($game->materials->pluck("id"))'>
                                        <i class="fa-solid fa-pencil"></i>
                                    </button>

                                    <button class="btn btn-sm btn-outline-danger deleteGameBtn"
                                        data-id="{{ $game->id }}"
                                        data-name="{{ $game->name }}">
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

<!-- View Game Modal -->
<div class="modal fade" id="viewGameModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Game Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body row">
                <div class="col-md-4 text-center mb-3">
                    <img id="viewGameImage" class="rounded" style="width:150px;height:150px;object-fit:cover;">
                </div>
                <div class="col-md-8">
                    <p><strong>ID:</strong> <span id="viewGameId"></span></p>
                    <p><strong>Name:</strong> <span id="viewGameName"></span></p>
                    <p><strong>Slug:</strong> <span id="viewGameSlug"></span></p>
                    <p><strong>Duration:</strong> <span id="viewGameDuration"></span> min</p>
                    <p><strong>Players:</strong> <span id="viewGamePlayers"></span></p>
                    <p><strong>Difficulty:</strong> <span id="viewGameDifficulty"></span></p>
                    <p><strong>Game Setup:</strong> <span id="viewGameSetup"></span></p>
                    <p><strong>Game Rules:</strong> <span id="viewGameRules"></span></p>
                    <p><strong>Instructions:</strong> <span id="viewGameInstructions"></span></p>
                    <p><strong>Categories:</strong> <span id="viewGameCategories"></span></p>
                    <p><strong>Materials:</strong> <span id="viewGameMaterials"></span></p>
                    <p><strong>Itineraries:</strong> <span id="viewGameItineraries"></span></p>

                    <div id="viewGameVideoContainer" class="mb-3" style="display:none;"></div>
                    <div id="viewGameFileContainer" class="mb-2" style="display:none;"></div>
                    <p><strong>Status:</strong> <span id="viewGameStatus"></span></p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Game Modal -->
<div class="modal fade" id="addGameModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">Add Game</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.game.add') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body row">
                    <div class="col-md-4 text-center">
                        <img id="addGameImagePreview" class="rounded mb-2" style="width:150px;height:150px;" src="{{ asset('storage/games/no-image.jpg') }}">
                        <input type="file" class="form-control mb-2" name="image" id="addGameImageInput">
                        <label>Video URL</label>
                        <input type="text" class="form-control mb-2" name="video_url">
                        <label>Upload File</label>
                        <input type="file" class="form-control mb-2" name="download_file">
                    </div>
                    <div class="col-md-8">
                        <input type="text" class="form-control mb-2" name="name" placeholder="Name" required>
                        <input type="text" class="form-control mb-2" name="slug" placeholder="Slug" required>
                        <input type="number" class="form-control mb-2" name="duration" placeholder="Duration">
                        <input type="number" class="form-control mb-2" name="players" placeholder="Players">
                        <input type="text" class="form-control mb-2" name="difficulty" placeholder="Difficulty">
                        <input type="text" class="form-control mb-2" name="game_setup" placeholder="Game Setup">
                        <input type="text" class="form-control mb-2" name="game_rules" placeholder="Game Rules">
                        <textarea class="form-control mb-2" name="instructions" id="instructions" placeholder="Instructions"></textarea>

                        <label>Categories</label>
                        <select name="categories[]" id="addGameCategories" class="form-control mb-2" multiple>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>

                        <label>Materials</label>
                        <select name="materials[]" id="addGameMaterials" class="form-control mb-2" multiple>
                            @foreach($materials as $material)
                                <option value="{{ $material->id }}">{{ $material->name }}</option>
                            @endforeach
                        </select>
                        <label>Itineraries</label>
                            <select name="itineraries[]" id="addGameItineraries" class="form-control mb-2" multiple>
                                @foreach($itineraries as $itinerary)
                                    <option value="{{ $itinerary->id }}">{{ $itinerary->name }}</option>
                                @endforeach
                            </select>


                        <label>Status</label>
                        <select class="form-control" name="status">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Add Game</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Game Modal -->
<div class="modal fade" id="editGameModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title">Edit Game</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="editGameForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body row">
                    <div class="col-md-4 text-center">
                        <img id="editGameImagePreview" class="rounded mb-2" style="width:150px;height:150px;" src="{{ asset('storage/games/no-image.jpg') }}">
                        <input type="file" class="form-control mb-2" name="image" id="editGameImageInput">
                        <label>Video URL</label>
                        <input type="text" class="form-control mb-2" name="video_url" id="editGameVideo">
                        <label>Upload File</label>
                        <input type="file" class="form-control mb-2" name="download_file">
                    </div>
                    <div class="col-md-8">
                        <input type="text" class="form-control mb-2" name="name" id="editGameName" placeholder="Name" required>
                        <input type="text" class="form-control mb-2" name="slug" id="editGameSlug" placeholder="Slug" required>
                        <input type="number" class="form-control mb-2" name="duration" id="editGameDuration" placeholder="Duration">
                        <input type="number" class="form-control mb-2" name="players" id="editGamePlayers" placeholder="Players">
                        <input type="text" class="form-control mb-2" name="difficulty" id="editGameDifficulty" placeholder="Difficulty">
                        <input type="text" class="form-control mb-2" name="game_setup" id="editGameSetup" placeholder="Game Setup">
                        <input type="text" class="form-control mb-2" name="game_rules" id="editGameRules" placeholder="Game Rules">
                        <textarea class="form-control mb-2" name="instructions" id="editGameInstructions" placeholder="Instructions"></textarea>

                        <label>Categories</label>
                        <select name="categories[]" id="editGameCategories" class="form-control mb-2" multiple>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>

                        <label>Materials</label>
                        <select name="materials[]" id="editGameMaterials" class="form-control mb-2" multiple>
                            @foreach($materials as $material)
                                <option value="{{ $material->id }}">{{ $material->name }}</option>
                            @endforeach
                        </select>
                        <label>Itineraries</label>
                            <select name="itineraries[]" id="editGameItineraries" class="form-control mb-2" multiple>
                                @foreach($itineraries as $itinerary)
                                    <option value="{{ $itinerary->id }}">{{ $itinerary->name }}</option>
                                @endforeach
                            </select>


                        <label>Status</label>
                        <select class="form-control" name="status" id="editGameStatus">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
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

<!-- Delete Game Modal -->
<div class="modal fade" id="deleteGameModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Are you sure to delete <strong id="deleteGameName"></strong>?
            </div>
            <div class="modal-footer">
                <form id="deleteGameForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
const rowsPerPage = 6;
let currentPage = 1;
let filteredRows = [];

// Filter
function filterRows() {
    const searchText = document.getElementById('searchInput').value.toLowerCase();
    const selectedStatus = document.getElementById('searchStatus').value;

    filteredRows = Array.from(document.querySelectorAll("#gameTable tbody tr")).filter(row => {
        const name = row.cells[1].innerText.toLowerCase();
        const statusText = row.cells[8].innerText.toLowerCase();
        const status = statusText.includes('active') ? '1' : '0';
        return name.includes(searchText) && (selectedStatus === "" || status === selectedStatus);
    });

    currentPage = 1;
    paginationTable();
}

// Pagination
function paginationTable() {
    const totalRows = filteredRows.length;
    const totalPages = Math.ceil(totalRows / rowsPerPage);
    document.querySelectorAll("#gameTable tbody tr").forEach(r => r.style.display = "none");
    filteredRows.slice((currentPage-1)*rowsPerPage, currentPage*rowsPerPage).forEach(r => r.style.display = "");
    renderPagination(totalPages);
}

function renderPagination(totalPages) {
    const pagination = document.getElementById("pagination");
    pagination.innerHTML = "";
    for (let i=1; i<=totalPages; i++) {
        const btn = document.createElement("button");
        btn.textContent = i;
        btn.className = "btn btn-sm btn-outline-primary mx-1";
        if(i===currentPage) btn.classList.add("active");
        btn.onclick = () => { currentPage=i; paginationTable(); };
        pagination.appendChild(btn);
    }
}

// Initialize Modals
function initGameModals() {
    // Add Image Preview
    const addImageInput = document.getElementById('addGameImageInput');
    const addImagePreview = document.getElementById('addGameImagePreview');
    addImageInput.addEventListener('change', e => {
        if(e.target.files && e.target.files[0]){
            const reader = new FileReader();
            reader.onload = e => addImagePreview.src = e.target.result;
            reader.readAsDataURL(e.target.files[0]);
        } else {
            addImagePreview.src = "{{ asset('storage/games/no-image.jpg') }}";
        }
    });

    document.getElementById('addGameBtn').addEventListener('click', () => {
        new bootstrap.Modal(document.getElementById('addGameModal')).show();
    });

    // View Game
    document.querySelectorAll('.viewGameBtn').forEach(btn => btn.addEventListener('click', function() {
        document.getElementById('viewGameId').textContent = this.dataset.id;
        document.getElementById('viewGameName').textContent = this.dataset.name;
        document.getElementById('viewGameSlug').textContent = this.dataset.slug;
        document.getElementById('viewGameDuration').textContent = this.dataset.duration;
        document.getElementById('viewGamePlayers').textContent = this.dataset.players;
        document.getElementById('viewGameDifficulty').textContent = this.dataset.difficulty;
        document.getElementById('editGameItineraries').value = this.dataset.itineraries;
        document.getElementById('viewGameSetup').textContent = this.dataset.game_setup;
        document.getElementById('viewGameRules').textContent = this.dataset.game_rules;
        document.getElementById('viewGameInstructions').textContent = this.dataset.instructions;
        document.getElementById('viewGameStatus').textContent = this.dataset.status == '1' ? 'Active' : 'Inactive';
        document.getElementById('viewGameImage').src = this.dataset.image;
    // Itineraries
    const itiIds = JSON.parse(this.dataset.itineraries || '[]');
    const itiNames = itiIds
        .map(id => document.querySelector(`#addGameItineraries option[value='${id}']`)?.textContent)
        .filter(Boolean);

    document.getElementById('viewGameItineraries').textContent = itiNames.join(', ');

        // Categories
        const catIds = JSON.parse(this.dataset.categories || '[]');
        const catNames = catIds.map(id => document.querySelector(`#addGameCategories option[value='${id}']`)?.textContent).filter(Boolean);
        document.getElementById('viewGameCategories').textContent = catNames.join(', ');

        // Materials
        const matIds = JSON.parse(this.dataset.materials || '[]');
        const matNames = matIds.map(id => document.querySelector(`#addGameMaterials option[value='${id}']`)?.textContent).filter(Boolean);
        document.getElementById('viewGameMaterials').textContent = matNames.join(', ');

        // Video
        const videoContainer = document.getElementById('viewGameVideoContainer');
        if(this.dataset.video){
            let url = this.dataset.video;
            if(url.includes("watch?v=")) url = url.replace("watch?v=", "embed/");
            if(url.includes("youtu.be/")) url = url.replace("youtu.be/", "youtube.com/embed/");
            videoContainer.innerHTML = `<iframe width="100%" height="300" frameborder="0" allowfullscreen src="${url}"></iframe>`;
            videoContainer.style.display = "block";
        } else {
            videoContainer.innerHTML = "";
            videoContainer.style.display = "none";
        }

        // File
        const fileContainer = document.getElementById('viewGameFileContainer');
        if(this.dataset.file){
            const fileUrl = `/storage/games/files/${this.dataset.file}`;
            const ext = this.dataset.file.split('.').pop().toLowerCase();
            if(ext === "pdf"){
                fileContainer.innerHTML = `<strong>File:</strong><br><iframe src="${fileUrl}" width="100%" height="300" style="border:1px solid #ccc;"></iframe>`;
            } else {
                fileContainer.innerHTML = `<strong>File:</strong> <a href="${fileUrl}" target="_blank">Open File</a>`;
            }
            fileContainer.style.display = "block";
        } else {
            fileContainer.innerHTML = "";
            fileContainer.style.display = "none";
        }

        new bootstrap.Modal(document.getElementById('viewGameModal')).show();
    }));

    // Edit Game
    document.querySelectorAll('.editGameBtn').forEach(btn => btn.addEventListener('click', function(){
        const form = document.getElementById('editGameForm');
        form.action = `/admin/game/${this.dataset.id}`;

        document.getElementById('editGameName').value = this.dataset.name;
        document.getElementById('editGameSlug').value = this.dataset.slug;
        document.getElementById('editGameDuration').value = this.dataset.duration;
        document.getElementById('editGamePlayers').value = this.dataset.players;
        document.getElementById('editGameDifficulty').value = this.dataset.difficulty;
        document.getElementById('editGameSetup').value = this.dataset.game_setup;
        document.getElementById('editGameRules').value = this.dataset.game_rules;
        document.getElementById('editGameItineraries').value = this.dataset.itineraries;
        document.getElementById('editGameInstructions').value = this.dataset.instructions;
        document.getElementById('editGameStatus').value = this.dataset.status;
        document.getElementById('editGameImagePreview').src = this.dataset.image || "{{ asset('storage/games/no-image.jpg') }}";
        document.getElementById('editGameVideo').value = this.dataset.video || "";
        // Itineraries
        const selectIti = document.getElementById('editGameItineraries');
        const selectedItis = JSON.parse(this.dataset.itineraries || '[]');
        Array.from(selectIti.options).forEach(opt => {
            opt.selected = selectedItis.includes(parseInt(opt.value));
        });

        

        // Categories
        const selectCat = document.getElementById('editGameCategories');
        const selectedCats = JSON.parse(this.dataset.categories || '[]');
        Array.from(selectCat.options).forEach(opt => opt.selected = selectedCats.includes(parseInt(opt.value)));

        // Materials
        const selectMat = document.getElementById('editGameMaterials');
        const selectedMats = JSON.parse(this.dataset.materials || '[]');
        Array.from(selectMat.options).forEach(opt => opt.selected = selectedMats.includes(parseInt(opt.value)));

        new bootstrap.Modal(document.getElementById('editGameModal')).show();
    }));

    // Delete Game
    document.querySelectorAll('.deleteGameBtn').forEach(btn => btn.addEventListener('click', function(){
        document.getElementById('deleteGameName').textContent = this.dataset.name;
        document.getElementById('deleteGameForm').action = `/admin/game/${this.dataset.id}`;
        new bootstrap.Modal(document.getElementById('deleteGameModal')).show();
    }));
}

document.addEventListener('DOMContentLoaded', () => {
    filteredRows = Array.from(document.querySelectorAll("#gameTable tbody tr"));
    paginationTable();
    document.getElementById('searchInput').addEventListener('input', filterRows);
    document.getElementById('searchStatus').addEventListener('change', filterRows);
    initGameModals();
});
</script>

@endsection
