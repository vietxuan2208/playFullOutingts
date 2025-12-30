@extends('admin.dashboard')

@section('content')
<div class="container mt-4">
    <h3 class="fw-bold mb-3">Edit Blog</h3>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('admin.blog.update', $blog->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- Title --}}
        <div class="mb-3">
            <label class="form-label">Blog Title</label>
            <input type="text" class="form-control" name="blog_name"
                value="{{ old('blog_name', $blog->blog_name) }}" required>
        </div>

        {{-- Author --}}
        <div class="mb-3">
            <label class="form-label">Author</label>
            <select name="user_id" class="form-select" required>
                <option value="">-- Select User --</option>
                @foreach ($users as $u)
                    <option value="{{ $u->id }}" {{ $blog->user_id == $u->id ? 'selected' : '' }}>
                        {{ $u->name ?? $u->email }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Image Upload --}}
        <div class="mb-3">
            <label class="form-label">Blog Image</label>
            <input type="file" name="image" class="form-control" id="imageInput">

            @if ($blog->image)
                <div class="mt-2">
                    <p class="fw-bold mb-1">Current Image:</p>
                    <img src="{{ asset('storage/' . $blog->image) }}" width="150" class="rounded border" id="currentImage">
                </div>
            @endif

            <img id="previewImage" class="mt-2 rounded" width="150" style="display:none;">
        </div>

        {{-- Content --}}
        <div class="mb-3">
            <label class="form-label">Content</label>
            <textarea name="content" id="summernote" class="form-control">
                {!! old('content', $blog->content) !!}
            </textarea>
        </div>

        <button class="btn btn-primary">Save Changes</button>
        <a href="{{ route('admin.blog.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

<script>
$(document).ready(function() {
    // Summernote
    $('#summernote').summernote({
        placeholder: 'Write blog content here...',
        tabsize: 2,
        height: 350,
        toolbar: [
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['font', ['fontsize', 'color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['insert', ['picture', 'link', 'video']],
            ['view', ['fullscreen', 'codeview']]
        ]
    });

    // Image preview
    $('#imageInput').on('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            $('#previewImage').attr('src', URL.createObjectURL(file)).show();
            $('#currentImage').hide();
        }
    });
});
</script>
@endpush
