@extends('layouts.user.user')

@section('content')

<!DOCTYPE html>
<html class="light" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Picnic Games - Game Categories</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com" rel="preconnect" />
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect" />
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;700;900&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet" />

    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#13ec13",
                        "background-light": "#f6f8f6",
                        "background-dark": "#102210",
                        "text-light": "#111811",
                        "text-dark": "#e3f3e3",
                        "subtle-light": "#618961",
                        "subtle-dark": "#8caa8c",
                        "border-light": "#dbe6db",
                        "border-dark": "#2a4b2a"
                    },
                    fontFamily: {
                        "display": ["Be Vietnam Pro", "sans-serif"]
                    },
                    borderRadius: {
                        "DEFAULT": "0.5rem",
                        "lg": "1rem",
                        "xl": "1.5rem",
                        "full": "9999px"
                    },
                },
            },
        }
    </script>
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0,
                'wght' 400,
                'GRAD' 0,
                'opsz' 24;
            font-size: 24px;
        }
    </style>
</head>

<body class="bg-background-light dark:bg-background-dark font-display text-text-light dark:text-text-dark">
    <div class="relative flex h-auto min-h-screen w-full flex-col group/design-root overflow-x-hidden">
        <div class="layout-container flex h-full grow flex-col">
            <div class="px-4 sm:px-8 md:px-20 lg:px-40 flex flex-1 justify-center py-5">
                <div class="layout-content-container flex flex-col w-full max-w-5xl flex-1">
                    <header id="picnic-title" class="flex items-center justify-between whitespace-nowrap border-b border-solid border-border-light dark:border-border-dark px-6 md:px-10 py-4">
                        <div class="flex items-center gap-4">
                            <h2 class="text-text-light dark:text-text-dark text-xl font-bold leading-tight tracking-[-0.015em]">
                                Picnic Games
                            </h2>
                        </div>
                        <nav class="hidden md:flex flex-1 justify-end items-center gap-8">

                            <button class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-primary text-text-light text-sm font-bold leading-normal tracking-[0.015em] hover:opacity-90 transition-opacity">
                                <span class="truncate">Get Started</span>
                            </button>
                        </nav>
                        <button class="md:hidden text-text-light dark:text-text-dark">
                            <span class="material-symbols-outlined">menu</span>
                        </button>
                    </header>
                    <main class="flex-grow p-4 sm:p-6 md:p-10">
                        <div class="flex flex-wrap justify-between gap-4 mb-8">
                            <div class="flex min-w-72 flex-col gap-3">
                                <h1 class="text-text-light dark:text-text-dark text-4xl lg:text-5xl font-black leading-tight tracking-[-0.033em]">Find the Perfect Game for Your Picnic</h1>
                                <p class="text-subtle-light dark:text-subtle-dark text-base font-normal leading-normal">Select a category below to get started.</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-10">
                            @foreach($categories as $cat)
                            <a href="{{ route('user.categoryGame', $cat->slug) }}#picnic-title">
                                <button class="group flex flex-col items-center gap-2 rounded-lg p-3 transition-all hover:bg-white">
                                    <div class="flex size-14 items-center justify-center rounded-full bg-primary/10 text-primary">
                                        <span class="material-symbols-outlined !text-3xl">
                                            @switch($cat->name)
                                            @case('Indoor Games') home @break
                                            @case('Outdoor Games') sunny @break
                                            @case('Games for Kids') child_care @break
                                            @case('Games for Males') man @break
                                            @case('Games for Females') woman @break
                                            @case('Family Games') family_restroom @break
                                            @default help
                                            @endswitch
                                        </span>
                                    </div>
                                    <span class="text-sm font-medium text-center">
                                        {{ $cat->name }}
                                    </span>
                                </button>
                            </a>
                            @endforeach
                        </div>


                        <!-- list game -->
                        <div class="mt-8" id="game-list">
                            <h2 class="text-2xl font-bold mb-6">{{ $category->name }}</h2>

                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                                @foreach($games as $game)
                                <div class="group flex flex-col overflow-hidden rounded-xl border border-border-light dark:border-border-dark bg-white dark:bg-background-dark/50 transition-transform duration-300 ease-in-out hover:scale-105 hover:shadow-lg">
                                    <div class="aspect-video overflow-hidden">
                                        <img alt="{{ $game->name }}" class="h-full w-full object-cover transition-transform duration-300 ease-in-out group-hover:scale-110" src="{{ $game->image }}" />
                                    </div>

                                    <div class="flex flex-col gap-4 p-5 flex-grow">
                                        <h3 class="text-lg font-bold">{{ $game->name }}</h3>

                                        <div class="flex flex-wrap gap-x-4 gap-y-2 text-sm text-subtle-light dark:text-subtle-dark">
                                            <div class="flex items-center gap-1.5">
                                                <span class="material-symbols-outlined !text-lg">timer</span>
                                                <span>{{ $game->duration }} min</span>
                                            </div>

                                            <div class="flex items-center gap-1.5">
                                                <span class="material-symbols-outlined !text-lg">signal_cellular_alt</span>
                                                <span>{{ $game->level }}</span>
                                            </div>
                                        </div>

                                        <a href="{{ route('user.detailGame', $game->slug) }}#detail"
                                            class="mt-auto flex w-full items-center justify-center rounded-lg h-10 px-4 bg-primary/10 text-primary text-sm font-bold leading-normal transition-colors hover:bg-primary hover:text-text-light">
                                            View Details
                                        </a>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                    </main>

                </div>
            </div>
        </div>
    </div>

</body>

</html>

@endsection