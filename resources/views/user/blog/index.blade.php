@extends('layouts.user.user')

@section('content')

<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Merriweather:wght@300;400;700&display=swap">

<style>
    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }

    body {
        font-family: 'Merriweather', serif;
        background-color: #fff;
        color: #333;
        margin: 0;
        padding: 0;
    }


    .blog-container {
        display: flex;
        flex-wrap: wrap;
        gap: 30px;
        justify-content: center;
        padding: 40px;
        perspective: 1000px;
        overflow: visible;
    }


    .blog-card {
        width: 430px;
        background: #fff;
        border-radius: 5px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        display: flex;
        flex-direction: column;

        /* 👇 Animation on load */
        opacity: 0;
        animation: fadeInUp 0.8s ease forwards;

        will-change: transform;
    }


    .blog-card:hover {
        transform: translateY(-6px) scale(1.03);
        box-shadow: 0 12px 28px rgba(0, 0, 0, 0.15);
        cursor: pointer;
        z-index: 2;
        position: relative;
    }


    .blog-image {
        width: 100%;
        height: 260px;
        overflow: hidden;
    }

    .blog-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.4s ease, filter 0.4s ease;
    }

    .blog-card:hover .blog-image img {
        transform: scale(1.07);
        filter: brightness(1.1) contrast(1.05);
    }

    .image-icon {
        position: absolute;
        bottom: 0;
        right: 0;
        background: #b46a42;
        padding: 10px 12px;
        border-top-left-radius: 8px;
    }

    .blog-content {
        padding: 22px;
    }

    .blog-meta {
        font-size: 13px;
        color: #777;
        margin-bottom: 10px;
    }

    .blog-meta .category {
        font-weight: 700;
        color: #b46a42;
    }

    .blog-title {
        font-size: 20px;
        font-weight: 500;
        line-height: 1.45;
        margin-bottom: 16px;
    }

    .blog-title a {
        color: #222;
        text-decoration: none;
    }

    .blog-title a:hover {
        color: #b46a42;
    }

    .read-more {
        margin-top: auto;
        font-weight: 600;
        text-transform: uppercase;
        color: #333;
        font-size: 13px;
        text-decoration: none;
    }

    .read-more:hover {
        color: #b46a42;
    }


    .read-more::after {
        content: ' →';
        transition: margin-left 0.2s ease;
    }

    @media (max-width: 768px) {
        .blog-card {
            width: 100%;
        }
    }

    .hero-banner {
        position: relative;
        width: 100%;
        height: 370px;
        overflow: hidden;
        margin-left: calc(-50vw + 50%);

    }

    .hero-banner .hero-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .hero-content {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        text-align: center;
    }

    .hero-content h1 {
        font-family: cursive;
        font-size: 48px;
        font-weight: 700;
        margin-bottom: 10px;

        background: linear-gradient(90deg, #ff416c, #4d400f, #2bcbba);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        animation: shine 5s linear infinite;
    }

    @keyframes shine {
        0% {
            background-position: 0%;
        }

        100% {
            background-position: 200%;
        }
    }

    .hero-content .breadcrumb {
        font-size: 16px;
        background: linear-gradient(90deg, #ff416c, #4d400f, #2bcbba);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        animation: shine 5s linear infinite;
    }


    @media (max-width: 768px) {
        .hero-content h1 {
            font-size: 36px;
        }

        .hero-content .breadcrumb {
            font-size: 14px;
        }
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }


    .blog-card:nth-child(1) {
        animation-delay: 0.3s;
    }

    .blog-card:nth-child(2) {
        animation-delay: 0.6s;
    }

    .blog-card:nth-child(3) {
        animation-delay: 0.9s;
    }

    .blog-card:nth-child(4) {
        animation-delay: 1.2s;
    }

    .blog-card:nth-child(5) {
        animation-delay: 1.5s;
    }

    .blog-card:nth-child(6) {
        animation-delay: 1.8s;
    }

    .blog-card:nth-child(7) {
        animation-delay: 2.1s;
    }

    .blog-card:nth-child(8) {
        animation-delay: 2.4s;
    }

    .blog-card:nth-child(9) {
        animation-delay: 2.7s;
    }
</style>

<div class="hero-banner pb-5">
    <img src="{{ asset('user/images/Summer.png') }}" alt="Banner" class="hero-img">
    <div class="hero-content">
        <h1>Blog Grid</h1>
        <div class="breadcrumb">Home / Blog Grid</div>
    </div>
</div>


<div class="blog-container mt-5">

    @foreach($blogs as $blog)

    <div class="blog-card">
        <div class="blog-image">
            @if ($blog->image)
            <a href="{{ route('user.blog.show', $blog->id) }}">
                <img src="{{ asset('storage/' . $blog->image) }}" alt="Blog Image">
            </a>
            @else
            <span>No Image</span>
            @endif


        </div>

        <div class="blog-content">

            <div class="blog-meta">
                <span class="category">{{ $blog->user->name ?? 'Unknown' }}</span>
                — {{ date('F d, Y', strtotime($blog->posted_date)) }}
            </div>

            <h3 class="blog-title">
                <a href="{{ route('user.blog.show', $blog->id) }}">{{ $blog->blog_name }}</a>
            </h3>

            <a href="{{ route('user.blog.show', $blog->id) }}" class="read-more">Read more</a>
        </div>

    </div>

    @endforeach

</div>

<div class="w-full flex justify-center pb-10">
    {{ $blogs->links() }}
</div>


@endsection