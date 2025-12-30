@extends('admin.dashboard')
@section('page-title', 'Locations')

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
            <h5 class="card-title">Locations</h5>
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
                <div class="col-auto">
                    <select class="form-select" id="searchStatus">
                        <option value="">All</option>
                        <option value="0">Active</option>
                        <option value="1">Inactive</option>
                    </select>
                </div>
                <div class="col-auto">
                    <button class="btn btn-success" id="addLocationBtn">Add Location</button>
                </div>
                <div class="col-auto ms-auto">
                    <input type="text" class="form-control" id="searchInput" placeholder="Search Locations...">
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover text-center align-middle" id="locationTable">
                    <thead class="table-dark">
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Itinerary</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($locations as $location)
                        <tr>
                            <td>
                                <img src="{{ $location->image ? asset('storage/locations/'.$location->image) : asset('storage/locations/no-image.jpg') }}" class="location-thumb">
                            </td>
                            <td>{{ $location->name }}</td>
                            <td>{{ Str::limit($location->description, 60) }}</td>
                            <td>
                                @forelse($location->itineraries as $it)
                                    <span >{{ $it->name }}</span>
                                @empty
                                    <span>None</span>
                                @endforelse
                            </td>
                            <td>
                                @foreach ($location->categoryLocations as $cat)
                                    <span>{{ $cat->name }}</span>
                                @endforeach
                            </td>
                            <td>
                                <span class="badge {{ $location->status ? 'bg-success' : 'bg-danger' }}">
                                    {{ $location->status ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-outline-info viewLocationBtn"
                                        data-id="{{ $location->id }}"
                                        data-name="{{ $location->name }}"
                                        data-description="{{ $location->description }}"
                                        data-itineraries='@json($location->itineraries->pluck("name"))'
                                        data-address = "{{$location->address}}"
                                        data-categories='@json($location->categoryLocations->pluck("name"))'
                                        data-status="{{ $location->status }}"
                                        data-image="{{ $location->image ? asset('storage/locations/'.$location->image) : asset('storage/locations/no-image.jpg') }}">
                                        <i class="fa-regular fa-eye"></i>
                                    </button>

                                    <button class="btn btn-sm btn-outline-success editLocationBtn"
                                        data-id="{{ $location->id }}"
                                        data-name="{{ $location->name }}"
                                        data-description="{{ $location->description }}"
                                        data-status="{{ $location->status }}"
                                        data-itinerary-id="{{ $location->itinerary_id }}"
                                        data-address = "{{$location->address}}"
                                        data-category-ids='@json($location->categoryLocations->pluck("name"))'
                                      data-itineraries='@json($location->itineraries->pluck("name"))'

                                        data-image="{{ $location->image ? asset('storage/locations/'.$location->image) : asset('storage/locations/no-image.jpg') }}">
                                        <i class="fa-solid fa-pencil"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger deleteLocationBtn"
                                        data-id="{{ $location->id }}"
                                        data-name="{{ $location->name }}">
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
<div class="modal fade" id="viewLocationModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">View Location</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img id="viewLocationImage" style="width:150px;height:150px;object-fit:cover;" class="rounded mb-2">
                <p><strong>Name:</strong> <span id="viewLocationName"></span></p>
                <p><strong>Description:</strong> <span id="viewLocationDescription"></span></p>
                <p><strong>Address:</strong> <span id="viewLocationAddress"></span></p>
                <p><strong>Status:</strong> <span id="viewLocationStatus"></span></p>
                <p><strong>Category:</strong> <span id="viewLocationCategory"></span></p>
                <p><strong>Itinerary:</strong> <span id="viewLocationItinerary"></span></p>
            </div>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addLocationModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">Add Location</h5>
                <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.locations.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body text-center">
                    <img id="addLocationImagePreview" src="{{ asset('storage/locations/no-image.jpg') }}" style="width:150px;height:150px;" class="rounded mb-2">
                    <input type="file" name="image" class="form-control mb-2" id="addLocationImageInput">
                    <input type="text" name="name" class="form-control mb-2" placeholder="Name" required>
                    <textarea name="description" class="form-control mb-2" placeholder="Description"></textarea>
                    <select name="itinerary_ids[]" class="form-control mb-2" multiple>
                        @foreach($itineraries as $itinerary)
                            <option value="{{ $itinerary->id }}">{{ $itinerary->name }}</option>
                        @endforeach
                    </select>
                    <select name="category_ids[]" class="form-select" multiple>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Address</label>
                        <input type="text" name="address" class="form-control"
                            placeholder="Enter location address..." required>
                    </div>

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
<div class="modal fade" id="editLocationModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title">Edit Location</h5>
                <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="editLocationForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body text-center">
                    <img id="editLocationImagePreview" src="{{ asset('storage/locations/no-image.jpg') }}" style="width:150px;height:150px;" class="rounded mb-2">
                    <input type="file" name="image" class="form-control mb-2" id="editLocationImageInput">
                    <input type="text" name="name" class="form-control mb-2" id="editLocationName" placeholder="Name" required>
                    <textarea name="description" class="form-control mb-2" id="editLocationDescription" placeholder="Description"></textarea>
                    <select name="itinerary_ids[]" id="editLocationItineraries" class="form-control mb-2" multiple>
                        @foreach($itineraries as $itinerary)
                            <option value="{{ $itinerary->id }}">{{ $itinerary->name }}</option>
                        @endforeach
                    </select>
                    <select name="category_ids[]" id="editLocationCategories" class="form-select" multiple>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                        <div class="mb-3">
                            <input type="text" name="address" id="editLocationAddress"
                                class="form-control" placeholder="Enter location address..." required>
                        </div>

                    <select name="status" class="form-control mb-2" id="editLocationStatus">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
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
<div class="modal fade" id="deleteLocationModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Delete Location</h5>
                <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                Are you sure to delete <strong id="deleteLocationName"></strong>?
            </div>
            <div class="modal-footer">
                <form id="deleteLocationForm" method="POST">
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
const rowsPerPage = 5;
let currentPage = 1;
let filteredRows = [];

function filterRows() {
    const searchText = document.getElementById('searchInput').value.toLowerCase();
    const selectedStatus = document.getElementById('searchStatus').value;

    filteredRows = Array.from(document.querySelectorAll("#locationTable tbody tr")).filter(row => {
        const name = row.cells[1].innerText.toLowerCase();
        const status = row.cells[3].innerText.includes('Active') ? '1' : '0';
        return name.includes(searchText) && (selectedStatus === "" || status === selectedStatus);
    });

    currentPage = 1;
    paginate();
}

function paginate() {
    const totalPages = Math.ceil(filteredRows.length / rowsPerPage);
    document.querySelectorAll("#locationTable tbody tr").forEach(r => r.style.display='none');
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

function previewImage(inputId, previewId) {
    const input = document.getElementById(inputId);
    const preview = document.getElementById(previewId);
    preview.dataset.default = preview.src;

    input.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
            }
            reader.readAsDataURL(file);
        } else {
            preview.src = preview.dataset.default;
        }
    });
}

function initLocationModals() {
    // Preview cho Add/Edit
    previewImage('addLocationImageInput', 'addLocationImagePreview');
    previewImage('editLocationImageInput', 'editLocationImagePreview');

    // Add Location
    document.getElementById('addLocationBtn').addEventListener('click', () => {
        // reset về default khi mở modal
        const addModal = document.getElementById('addLocationModal');
        document.getElementById('addLocationImagePreview').src = document.getElementById('addLocationImagePreview').dataset.default;
        addModal.querySelector('form').reset();
        new bootstrap.Modal(addModal).show();
    });

    // View
    document.querySelectorAll('.viewLocationBtn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.getElementById('viewLocationName').textContent = this.dataset.name;
            document.getElementById('viewLocationAddress').textContent = this.dataset.address;
            document.getElementById('viewLocationDescription').textContent = this.dataset.description;
            let itineraries = JSON.parse(this.dataset.itineraries);
            document.getElementById('viewLocationItinerary').innerHTML =
                itineraries.length
                    ? itineraries.join(", ")
                    : "None";   

                
            let categories = JSON.parse(this.dataset.categories);
            document.getElementById('viewLocationCategory').innerHTML =
                categories.length
                    ? categories.join(", ")
                    : "None";

            document.getElementById('viewLocationStatus').textContent = this.dataset.status == '1' ? 'Active' : 'Inactive';
            document.getElementById('viewLocationImage').src = this.dataset.image;
            new bootstrap.Modal(document.getElementById('viewLocationModal')).show();
        });
    });

    // Edit
    document.querySelectorAll('.editLocationBtn').forEach(btn => {
        btn.addEventListener('click', function () {

            const form = document.getElementById('editLocationForm');
            form.action = `/admin/locations/${this.dataset.id}`;

            document.getElementById('editLocationName').value = this.dataset.name;
            document.getElementById('editLocationAddress').value = this.dataset.address;
            document.getElementById('editLocationDescription').value = this.dataset.description;
            document.getElementById('editLocationStatus').value = this.dataset.status;
            document.getElementById('editLocationImagePreview').src = this.dataset.image;


            

            let selectedIds = JSON.parse(this.dataset.itineraries);
            let select = document.getElementById('editLocationItineraries');

            [...select.options].forEach(opt => {
                opt.selected = selectedIds.includes(parseInt(opt.value));
            });

            let selectedCategoryIds = JSON.parse(this.dataset.categoryIds);
            let categorySelect = document.getElementById('editLocationCategories');

            [...categorySelect.options].forEach(opt => {
                opt.selected = selectedCategoryIds.includes(parseInt(opt.value));
            });


            new bootstrap.Modal(document.getElementById('editLocationModal')).show();
        });
    });

    // Delete
    document.querySelectorAll('.deleteLocationBtn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.getElementById('deleteLocationName').textContent = this.dataset.name;
            document.getElementById('deleteLocationForm').action = `/admin/locations/${this.dataset.id}`;
            new bootstrap.Modal(document.getElementById('deleteLocationModal')).show();
        });
    });
}

document.addEventListener('DOMContentLoaded', () => {
    filteredRows = Array.from(document.querySelectorAll("#locationTable tbody tr"));
    paginate();
    document.getElementById('searchInput').addEventListener('input', filterRows);
    document.getElementById('searchStatus').addEventListener('change', filterRows);
    initLocationModals();
});

</script>
@endsection
