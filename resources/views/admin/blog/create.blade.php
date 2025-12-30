@extends('admin.dashboard')

@section('content')
<div class="container mt-4">

    <h3 class="fw-bold mb-3">Create New Blog</h3>

    <form action="{{ route('admin.blog.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- Title --}}
        <div class="mb-3">
            <label class="form-label">Blog Title</label>
            <input type="text" name="blog_name" class="form-control" required value="{{ old('blog_name') }}">
        </div>

        {{-- Author --}}
        <div class="mb-3">
            <label class="form-label">Author</label>
            <select name="user_id" class="form-select" required>
                <option value="">-- Select User --</option>
                @foreach ($users as $u)
                    <option value="{{ $u->id }}">{{ $u->name ?? $u->email }}</option>
                @endforeach
            </select>
        </div>

        {{-- Image --}}
        <div class="mb-3">
            <label class="form-label">Blog Image</label>
            <input type="file" name="image" class="form-control" id="imageInput">
            <img id="previewImage" class="mt-2 rounded" width="200" style="display:none;">
        </div>

        {{-- Content --}}
        <div class="mb-3">
            <label class="form-label">Content</label>
            <textarea name="content" id="summernote" class="form-control" required>{{ old('content') }}</textarea>
        </div>

        <button class="btn btn-primary">Create Blog</button>
        <a href="{{ route('admin.blog.index') }}" class="btn btn-secondary">Back</a>
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
        height: 300,
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
        }
    });
});
</script>
@endpush
