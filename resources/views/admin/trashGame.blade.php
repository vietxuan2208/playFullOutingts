@extends('admin.dashboard')
@section('page-title', 'Recycle Games')

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
            <h5 class="card-title">Trash Games</h5>
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
                <div class="col-12 col-md-4 col-lg-3 ms-auto">
                    <div class="position-relative">
                        <input type="text" class="form-control" id="searchInput" placeholder="Search">
                        <span class="fa fa-search position-absolute top-50 end-0 translate-middle-y me-3 text-muted"></span>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover" id="gameTable">
                    <thead class="table-dark">
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Duration</th>
                            <th>Category</th>
                            <th>Material</th>
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
                                        data-duration="{{ $game->duration }}"
                                        data-instructions="{{ htmlspecialchars($game->instructions, ENT_QUOTES) }}"
                                        data-status="{{ $game->status }}"
                                        data-image="{{ $game->image ? asset('storage/games/images/'.$game->image) : asset('storage/games/no-image.jpg') }}"
                                        data-video="{{ $game->video_url }}"
                                        data-file="{{ $game->download_file }}"
                                        data-categories='@json($game->categories->pluck("id"))'
                                        data-materials='@json($game->materials->pluck("id"))'>
                                        <i class="fa-regular fa-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-success restoreGameBtn" data-id="{{ $game->id }}" data-name="{{ $game->name }}">
                                        <i class="fa-solid fa-rotate-left"></i>
                                    </button>

                                    <button class="btn btn-sm btn-outline-danger deleteGameBtn" data-id="{{ $game->id }}" data-name="{{ $game->name }}">
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
                        <p><strong>Instructions:</strong> <span id="viewGameInstructions"></span></p>
                        <p><strong>Categories:</strong> <span id="viewGameCategories"></span></p>
                        <p><strong>Materials:</strong> <span id="viewGameMaterials"></span></p>
                        <div id="viewGameVideoContainer" class="mb-3" style="display:none;">
                            <iframe id="viewGameVideo" width="100%" height="300" frameborder="0" allowfullscreen></iframe>
                        </div>
                        <div id="viewGameFileContainer" class="mb-2" style="display:none;">
                            <strong>File:</strong><br>
                            <iframe id="viewGameFile" width="100%" height="300px" style="border:1px solid #ccc;"></iframe>
                        </div>
                        <p><strong>Status:</strong> <span id="viewGameStatus"></span></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- Confirm Restore/Delete Modal -->
<div class="modal fade" id="confirmActionModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header" id="confirmActionHeader">
        <h5 class="modal-title" id="confirmActionTitle">Confirm Action</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body" id="confirmActionBody">
        Are you sure?
      </div>
      <div class="modal-footer">
        <form id="confirmActionForm" method="POST">
            @csrf
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn" id="confirmActionBtn">Yes</button>
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
    const searchText = document.getElementById('searchInput').value.toLowerCase();
    filteredRows = Array.from(document.querySelectorAll("#gameTable tbody tr")).filter(row => {
        const name = row.cells[1].innerText.toLowerCase();
        return name.includes(searchText);
    });
    currentPage = 1;
    paginationTable();
}

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
    for (let i=1;i<=totalPages;i++){
        const btn = document.createElement("button");
        btn.textContent = i;
        btn.className = "btn btn-sm btn-outline-primary mx-1";
        if(i===currentPage) btn.classList.add("active");
        btn.onclick = ()=>{ currentPage=i; paginationTable(); };
        pagination.appendChild(btn);
    }
}

document.addEventListener("DOMContentLoaded", () => {
    filteredRows = Array.from(document.querySelectorAll("#gameTable tbody tr"));
    paginationTable();
    document.getElementById('searchInput').addEventListener('input', filterRows);

    const confirmModal = new bootstrap.Modal(document.getElementById('confirmActionModal'));
    const confirmForm = document.getElementById('confirmActionForm');
    const confirmBody = document.getElementById('confirmActionBody');
    const confirmTitle = document.getElementById('confirmActionTitle');
    const confirmHeader = document.getElementById('confirmActionHeader');
    const confirmBtn = document.getElementById('confirmActionBtn');

    // View
    document.querySelectorAll('.viewGameBtn').forEach(btn => btn.addEventListener('click', function() {
            document.getElementById('viewGameId').textContent = this.dataset.id;
            document.getElementById('viewGameName').textContent = this.dataset.name;
            document.getElementById('viewGameSlug').textContent = this.dataset.slug;
            document.getElementById('viewGameDuration').textContent = this.dataset.duration;

            let cleanInstructions = (this.dataset.instructions || "")
                .replace(/<br\s*\/?>/gi, '\n')
                .replace(/<\/p>/gi, '\n')
                .replace(/<p[^>]*>/gi, '')
                .replace(/<[^>]+>/g, '');

            document.getElementById('viewGameInstructions').textContent = cleanInstructions.trim();



            // Status
            document.getElementById('viewGameStatus').textContent = this.dataset.status == '1' ? 'Active' : 'Inactive';

            // Image
            document.getElementById('viewGameImage').src = this.dataset.image;

            // Categories
            const catIds = JSON.parse(this.dataset.categories || '[]');
            const catNames = catIds.map(id => {
                const opt = document.querySelector(`#addGameCategories option[value='${id}']`);
                return opt ? opt.textContent : '';
            }).filter(Boolean);
            document.getElementById('viewGameCategories').textContent = catNames.join(', ');

            // Materials
            const matIds = JSON.parse(this.dataset.materials || '[]');
            const matNames = matIds.map(id => {
                const opt = document.querySelector(`#addGameMaterials option[value='${id}']`);
                return opt ? opt.textContent : '';
            }).filter(Boolean);
            document.getElementById('viewGameMaterials').textContent = matNames.join(', ');

            // Video
            const videoContainer = document.getElementById('viewGameVideoContainer');

            if(this.dataset.video){
                let url = this.dataset.video;
                if(url.includes("watch?v=")) url = url.replace("watch?v=", "embed/");
                if(url.includes("youtu.be/")) url = url.replace("youtu.be/", "youtube.com/embed/");

                videoContainer.innerHTML =
                    `<iframe width="100%" height="300" frameborder="0" allowfullscreen src="${url}"></iframe>`;
                videoContainer.style.display = "block";
            } 
            else {
                videoContainer.innerHTML = "";
                videoContainer.style.display = "none";
            }



            // File
           const fileContainer = document.getElementById('viewGameFileContainer');
            if(this.dataset.file){
                const fileUrl = `/storage/games/files/${this.dataset.file}`;
                const ext = this.dataset.file.split('.').pop().toLowerCase();

                if(ext === "pdf"){
                    fileContainer.innerHTML = 
                        `<strong>File:</strong><br>
                        <iframe src="${fileUrl}" width="100%" height="300" style="border:1px solid #ccc;"></iframe>`;
                } else {
                    fileContainer.innerHTML = 
                        `<strong>File:</strong> <a href="${fileUrl}" target="_blank">Open File</a>`;
                }
                fileContainer.style.display = "block";
            }
            else {
                fileContainer.innerHTML = "";
                fileContainer.style.display = "none";
            }
            new bootstrap.Modal(document.getElementById('viewGameModal')).show();
        }));

    // Restore
    document.querySelectorAll('.restoreGameBtn').forEach(btn=>{
        btn.addEventListener('click', function(){
            const id = this.dataset.id;
            const name = this.dataset.name;
            confirmTitle.textContent = 'Restore Game';
            confirmBody.textContent = `Are you sure you want to restore "${name}"?`;
            confirmBtn.className = 'btn btn-success';
            confirmHeader.className = 'modal-header bg-success text-white';
            confirmForm.action = `/admin/recycle-game/restore/${id}`;
            confirmForm.method = 'POST';
            confirmModal.show();
        });
    });

    // Delete
    document.querySelectorAll('.deleteGameBtn').forEach(btn=>{
        btn.addEventListener('click', function(){
            const id = this.dataset.id;
            const name = this.dataset.name;
            confirmTitle.textContent = 'Delete Game Permanently';
            confirmBody.textContent = `Are you sure you want to delete "${name}" permanently?`;
            confirmBtn.className = 'btn btn-danger';
            confirmHeader.className = 'modal-header bg-danger text-white';
            confirmForm.action = `/admin/recycle-game/delete/${id}`;
            confirmForm.method = 'POST';
            if(!confirmForm.querySelector('input[name="_method"]')){
                const methodInput = document.createElement('input');
                methodInput.type='hidden';
                methodInput.name='_method';
                methodInput.value='DELETE';
                confirmForm.appendChild(methodInput);
            }
            confirmModal.show();
        });
    });
});
</script>

@endsection
