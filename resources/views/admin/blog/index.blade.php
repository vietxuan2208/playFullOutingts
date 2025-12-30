@extends('admin.dashboard')

@section('page-title', 'Blogs')

@section('content')

<style>
.blog-thumb {
    width: 75px;
    height: 60px;
    object-fit: cover;
    border-radius: 8px;
}
.table td, .table th {
    vertical-align: middle;
}
</style>

<div class="main-content">
    <div class="card mt-4">

        {{-- HEADER --}}
        <div class="card-header">
            <h5 class="card-title mb-0">Blog Management</h5>
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
                    @foreach($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
                <button class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            {{-- FILTER + ADD + SEARCH --}}
            <div class="row g-2 mb-4">

                <div class="col-auto">
                    <a href="{{ route('admin.blog.create') }}" class="btn btn-success">
                        Add Blog
                    </a>
                </div>

            </div>

            {{-- TABLE --}}
            <div class="table-responsive">
                <table class="table table-hover table-sm" id="blogTable">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Image</th>
                            <th>Title</th>
                            <th>Author</th>
                            <th>Posted Date</th>
                            <th style="width:130px;">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($blogs as $blog)
                        <tr data-status="{{ $blog->is_delete }}">
                            <td>{{ $blog->id }}</td>

                            <td>
                                @if($blog->image)
                                    <img src="{{ asset('storage/'.$blog->image) }}" class="blog-thumb">
                                @else
                                    <span class="text-muted">No image</span>
                                @endif
                            </td>

                            <td>{{ $blog->blog_name }}</td>

                            <td>{{ $blog->user->name ?? 'N/A' }}</td>

                            <td>{{ \Carbon\Carbon::parse($blog->posted_date)->format('d/m/Y') }}</td>

                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('admin.blog.show',$blog->id) }}"
                                       class="btn btn-sm btn-outline-info">
                                        <i class="fa fa-eye"></i>
                                    </a>

                                    <a href="{{ route('admin.blog.edit',$blog->id) }}"
                                       class="btn btn-sm btn-outline-warning">
                                        <i class="fa fa-pencil"></i>
                                    </a>

                                    <button type="button"
                                            class="btn btn-sm btn-outline-danger deleteBlogBtn"
                                            data-id="{{ $blog->id }}"
                                            data-name="{{ $blog->blog_name }}">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- PAGINATION --}}
            <div class="d-flex justify-content-center mt-3">
                {{ $blogs->links('pagination::bootstrap-4') }}
            </div>

        </div>
    </div>
</div>
{{-- ================= DELETE MODAL ================= --}}
<div class="modal fade" id="deleteBlogModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Confirm Delete</h5>
                <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                Are you sure you want to delete
                <strong id="deleteBlogName"></strong> ?
            </div>

            <div class="modal-footer">
                <form id="deleteBlogForm" method="POST">
                    @csrf
                    @method('DELETE')

                    <button type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <button type="submit" class="btn btn-danger">
                        Delete
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>
<script>
document.addEventListener("DOMContentLoaded", function () {

    document.querySelectorAll('.deleteBlogBtn').forEach(btn => {
        btn.addEventListener('click', function () {

            const blogId   = this.dataset.id;
            const blogName = this.dataset.name;

            document.getElementById('deleteBlogName').innerText = blogName;
            document.getElementById('deleteBlogForm').action =
                `/admin/blog/delete/${blogId}`;

            new bootstrap.Modal(
                document.getElementById('deleteBlogModal')
            ).show();
        });
    });

});
</script>


@endsection
