@extends('admin.dashboard')

@section('content')

<style>
    .summernote-content img {
        max-width: 100%;
        border-radius: 6px;
        margin: 10px 0;
    }

    .summernote-content p {
        line-height: 1.6;
        margin-bottom: 12px;
    }
</style>
<div class="container mt-4">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold">{{ $blog->blog_name }}</h3>

        <div>
            <a href="{{ route('admin.blog.index') }}" class="btn btn-secondary me-2">Back</a>
            <a href="{{ route('admin.blog.edit', $blog->id) }}" class="btn btn-warning me-2">Edit</a>

            <form action="{{ route('admin.blog.delete', $blog->id) }}" method="POST" class="d-inline"
                onsubmit="return confirm('Are you sure you want to delete this blog?')">
                @csrf
                @method('DELETE')
                <button class="btn btn-danger">Delete</button>
            </form>
        </div>
    </div>

    {{-- Blog Image --}}
    @if ($blog->image)
    <div class="mb-4">
        <img src="{{ asset('storage/' . $blog->image) }}"
            class="img-fluid rounded shadow"
            style="max-height: 400px; object-fit: cover;">
    </div>
    @endif

    {{-- Blog Info --}}
    <div class="mb-3">
        <strong>Author:</strong>
        <span>{{ $blog->user->name ?? $blog->user->email ?? 'N/A' }}</span>
    </div>

    <div class="mb-3">
        <strong>Posted Date:</strong>
        <span>{{ $blog->posted_date }}</span>
    </div>

    {{-- Content --}}
    <div class="card mt-4">
        <div class="card-header fw-bold">Content</div>
        <div class="card-body">

            {{-- Render HTML Summernote content --}}
            <div class="summernote-content">
                {!! $blog->content !!}
            </div>

        </div>
    </div>

</div>
@endsection