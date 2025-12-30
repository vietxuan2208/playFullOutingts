@extends('layouts.user.user')

@section('content')

<body class="bg-background-light dark:bg-background-dark font-display text-charcoal dark:text-off-white">
    <div class="relative flex h-auto min-h-screen w-full flex-col group/design-root overflow-x-hidden">
        <div class="layout-container flex h-full grow flex-col">
            <div class="flex flex-1 justify-center">
                <div class="layout-content-container flex flex-col w-full">

                    <main class="flex-grow">
                        <div class="px-4 sm:px-10 py-5 pb-16">
                            <div class="mx-auto max-w-7xl">

                                {{-- Breadcrumb --}}
                                <div class="mt-10 mb-8">
                                    <div class="flex flex-wrap gap-2 p-4 bg-white/40 dark:bg-white/10 
                                                backdrop-blur-md rounded-xl border border-border-light/40 
                                                dark:border-white/10 shadow-sm">
                                        <a class="text-primary font-medium hover:underline" href="{{route('user.itinerary')}}">
                                            Itineraries
                                        </a>

                                        <span class="text-charcoal/50 dark:text-off-white/50">/</span>

                                        <span class="text-charcoal dark:text-off-white font-semibold">
                                            {{ $itinerary->name }}
                                        </span>
                                    </div>
                                </div>

                                {{-- Cover Image --}}
                                <div class="w-full h-[480px] rounded-2xl overflow-hidden shadow-xl">
                                    <img class="w-full h-full object-cover transition-transform duration-700 hover:scale-105"
                                        src="{{ $itinerary->image 
                                            ? asset('storage/itineraries/' . $itinerary->image) 
                                            : asset('storage/avatars/no-image.jpg') }}">
                                </div>

                                <div class="mt-10 max-w-4xl mx-auto">

                                    {{-- Title --}}
                                    <h1 class="text-4xl sm:text-5xl font-black leading-tight">
                                        {{ $itinerary->name }}
                                    </h1>

                                    {{-- Description --}}
                                    <div class="mt-10 prose prose-lg dark:prose-invert max-w-none leading-relaxed">
                                        <p>{{ $itinerary->description }}</p>

                                        <h2 class="text-3xl font-bold text-center mt-16 mb-10">
                                            SUGGESTED LOCATIONS
                                        </h2>
@php
    $locations = $itinerary->locations ?? collect();

    $chunks = $locations->chunk(
        ceil(max($locations->count(), 1) / 2)
    );

    $leftColumn  = $chunks->get(0) ?? collect();
    $rightColumn = $chunks->get(1) ?? collect();
@endphp

<div class="grid grid-cols-1 lg:grid-cols-2 gap-12">

    {{-- LEFT COLUMN --}}
    <div class="space-y-6">
        @foreach($leftColumn as $location)
            <a href="{{ route('user.location.detail', $location->id) }}"
               class="group flex items-center gap-4 p-4 rounded-2xl shadow-md bg-white dark:bg-white/10 
                      border border-border-light/40 dark:border-white/10 
                      hover:scale-[1.03] hover:shadow-xl transition-all duration-300 cursor-pointer">

                <img src="{{ $location->image 
                    ? asset('storage/locations/' . $location->image) 
                    : asset('storage/avatars/no-image.jpg') }}"
                    class="w-24 h-24 object-cover rounded-xl" />

                <div class="flex-1">
                    <h3 class="text-lg font-semibold">{{ $location->name }}</h3>
                    <p class="text-sm opacity-60 line-clamp-2">
                        {{ $location->description }}
                    </p>
                </div>

                <span class="material-symbols-outlined text-primary opacity-0 group-hover:opacity-100 transition">
                    arrow_forward
                </span>
            </a>
        @endforeach
    </div>

    {{-- RIGHT COLUMN --}}
    <div class="space-y-6">
        @foreach($rightColumn as $location)
            <a href="{{ route('user.location.detail', $location->id) }}"
               class="group flex items-center gap-4 p-4 rounded-2xl shadow-md bg-white dark:bg-white/10 
                      border border-border-light/40 dark:border-white/10 
                      hover:scale-[1.03] hover:shadow-xl transition-all duration-300 cursor-pointer">

                <img src="{{ $location->image 
                    ? asset('storage/locations/' . $location->image) 
                    : asset('storage/avatars/no-image.jpg') }}"
                    class="w-24 h-24 object-cover rounded-xl" />

                <div class="flex-1">
                    <h3 class="text-lg font-semibold">{{ $location->name }}</h3>
                    <p class="text-sm opacity-60 line-clamp-2">
                        {{ $location->description }}
                    </p>
                </div>

                <span class="material-symbols-outlined text-primary opacity-0 group-hover:opacity-100 transition">
                    arrow_forward
                </span>
            </a>
        @endforeach
    </div>

</div> {{-- END GRID --}}
{{-- SUGGESTED GAMES --}}
<h2 class="text-3xl font-bold text-center mt-20 mb-10">
    SUGGESTED GAMES FOR THIS JOURNEY
</h2>

@if($itinerary->games->count())
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">

    @foreach($itinerary->games as $game)
        <a href="{{ route('games.detail', $game->id) }}"
           class="group p-6 rounded-2xl bg-white dark:bg-white/10 
                  border border-border-light/40 dark:border-white/10 
                  shadow-md hover:shadow-xl hover:-translate-y-1 
                  transition-all duration-300">

            {{-- IMAGE --}}
            <div class="w-full h-44 rounded-xl overflow-hidden mb-4">
                <img src="{{ $game->image 
    ? asset('storage/games/images/' . $game->image)
    : asset('storage/avatars/no-image.jpg') }}"

                    class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
            </div>

            {{-- TITLE --}}
            <h3 class="text-lg font-semibold mb-2">
                {{ $game->name }}
            </h3>

            {{-- DESCRIPTION --}}
            <p class="text-sm opacity-70 line-clamp-3">
                {{ $game->description }}
            </p>

            {{-- META --}}
            <div class="mt-4 flex items-center justify-between text-xs text-primary font-medium">
                <span>
                    {{ $game->category->name ?? 'General' }}
                </span>
                <span>
                    {{ $game->duration }} mins
                </span>
            </div>
        </a>
    @endforeach

</div>
@else
    <p class="text-center text-sm opacity-60">
        No games suggested for this journey yet.
    </p>
@endif



                                    </div>
                                </div>

                            </div>
                        </div>
                    </main>

                </div>
            </div>
        </div>
    </div>
</body>

@endsection
