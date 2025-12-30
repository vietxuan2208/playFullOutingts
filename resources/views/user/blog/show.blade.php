@extends('layouts.user.user')

@section('content')
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Merriweather:wght@300;400;700&display=swap">

<style>
    * {
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
    }

    .btn-hover {
        width: 200px;
        font-size: 16px;
        font-weight: 600;
        color: #fff;
        cursor: pointer;
        margin: 20px;
        height: 55px;
        text-align: center;
        border: none;
        background-size: 300% 100%;

        border-radius: 50px;
        moz-transition: all 0.4s ease-in-out;
        -o-transition: all 0.4s ease-in-out;
        -webkit-transition: all 0.4s ease-in-out;
        transition: all 0.4s ease-in-out;
        display: inline-block;
        transition: background-color 0.2s, transform 0.2s;

    }

    .btn-hover:hover {
        background-position: 100% 0;
        moz-transition: all 0.4s ease-in-out;
        -o-transition: all 0.4s ease-in-out;
        -webkit-transition: all 0.4s ease-in-out;
        transition: all 0.4s ease-in-out;
        transform: translateY(-2px);
    }

    .btn-hover:focus {
        outline: none;
    }

    .btn-hover.color-1 {
        background-image: linear-gradient(to right,
                #25aae1,
                #40e495,
                #30dd8a,
                #2bb673);
        box-shadow: 0 4px 15px 0 rgba(49, 196, 190, 0.75);
    }

    body {
        font-family: 'Merriweather', serif;
        background-color: rgb(255, 255, 255) !important;
        color: #333;
        margin: 0;
        padding: 0;
    }

    .blog-detail-container {
        max-width: 800px;
        margin: 0 auto;
        padding: 40px 20px;
    }

    .breadcrumb {
        font-size: 0.9rem;
        color: #777;
        margin-bottom: 24px;
    }

    .breadcrumb a {
        color: #555;
        text-decoration: none;
        transition: color 0.2s;
    }

    .breadcrumb a:hover {
        color: #b46a42;
    }

    .post-title {
        font-size: 2.75rem;
        line-height: 1.2;
        margin-bottom: 16px;
        color: #222;
    }

    .post-meta {
        color: #888;
        font-size: 0.95rem;
        margin-bottom: 32px;
        display: flex;
        gap: 12px;
        align-items: center;
    }

    .post-meta strong {
        color: #b46a42;
        font-weight: 600;
    }

    .post-content {
        line-height: 1.8;
        font-size: 1.05rem;
        color: #444;
    }

    .post-content img {
        max-width: 100%;
        height: auto;
        margin: 24px 0;
        border-radius: 6px;
    }

    .post-content h2,
    .post-content h3,
    .post-content h4 {
        margin-top: 2.4em;
        margin-bottom: 1em;
        line-height: 1.3;
        color: #222;
    }

    .post-content p {
        margin-bottom: 1.4em;
    }

    .post-content a {
        color: #b46a42;
        text-decoration: underline;
    }

    .post-content a:hover {
        text-decoration: none;
        color: #8e472e;
    }



    @media (max-width: 768px) {
        .post-title {
            font-size: 2rem;
        }

        .blog-detail-container {
            padding: 30px 16px;
        }
    }
</style>

<div class="blog-detail-container">

    {{-- Breadcrumb --}}
    <nav class="breadcrumb">
        <a href="{{ route('user.dashboard') }}">Home</a>
        <span>/</span>
        <a href="{{ route('user.blog.index') }}">Blogs</a>
        <span>/</span>
        <span>{{ $blog->blog_name }}</span>
    </nav>

    {{-- Title --}}
    <h1 class="post-title">{{ $blog->blog_name }}</h1>

    {{-- Meta --}}
    <div class="post-meta">
        <span><strong>{{ $blog->user->name ?? 'Administrator' }}</strong></span>
        <span>•</span>
        <span>{{ \Carbon\Carbon::parse($blog->posted_date)->format('F d, Y') }}</span>
    </div>

    {{-- Content --}}
    <article class="prose prose-lg max-w-none dark:prose-invert prose-headings:text-gray-900 dark:prose-headings:text-gray-100 prose-a:text-primary"
        style="background-color: rgb(255, 255, 255) !important;">
        {!! $blog->content !!}
    </article>


    {{-- Back Button --}}

    <a href="{{ route('user.blog.index') }}"><button class="btn-hover color-1">← Back to Blogs</button></a>

</div>
@endsection