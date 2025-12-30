@extends('layouts.user.user')

@section('content')

<div class="px-4 sm:px-8 md:px-20 lg:px-40 py-10">

     <h1 class="text-4xl lg:text-5xl font-black text-text-light dark:text-text-dark mb-6">
        Find the Perfect Game for Your Picnic
    </h1>

    

    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4 mb-12">

        @foreach ($data as $item)
        <a href="{{ route('games.category', $item['category']->id) }}">
            <button class="group flex flex-col items-center gap-2 rounded-lg p-3 border border-transparent 
                           hover:border-primary/50 hover:bg-white dark:hover:bg-background-dark/50 transition-all">
                <div class="flex size-14 items-center justify-center rounded-full bg-primary/10 text-primary">
                    <span class="material-symbols-outlined !text-3xl">sports_esports</span>
                </div>
                <span class="text-sm font-medium text-center text-text-light dark:text-text-dark">
                    {{ $item['category']->name }}
                </span>
            </button>
        </a>
        @endforeach

    </div>




    @foreach ($data as $item)
    <div class="mb-12" id="category-{{ $item['category']->id }}">

        <h2 class="text-2xl font-bold mb-6">
            {{ $item['category']->name }}
        </h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

            @foreach ($item['games'] as $game)
            <div class="group flex flex-col overflow-hidden rounded-xl border border-border-light dark:border-border-dark 
                        bg-white dark:bg-background-dark/50 transition-transform duration-300 hover:scale-105 hover:shadow-lg">

                <div class="aspect-video overflow-hidden">
                    <img src="{{ asset('storage/games/images/' . ($game->image ?? 'no-image.jpg')) }}"
                        class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-110"
                        alt="{{ $game->name }}">
                </div>


                <div class="flex flex-col gap-4 p-5 flex-grow">
                    <h3 class="text-lg font-bold">{{ $game->name }}</h3>

                    <div class="flex flex-wrap gap-x-4 gap-y-2 text-sm text-subtle-light dark:text-subtle-dark">
                        <div class="flex items-center gap-1.5">
                            <span class="material-symbols-outlined !text-lg">timer</span>
                            <span>{{ $game->duration ?? 'N/A' }} min</span>
                        </div>

                        <div class="flex items-center gap-1.5">
                            <span class="material-symbols-outlined !text-lg">signal_cellular_alt</span>
                            <span>{{ $game->difficulty }}</span>
                        </div>
                    </div>

                    <a href="{{ route('games.detail', $game->id) }}"
                        class="mt-auto flex w-full items-center justify-center rounded-lg h-10 px-4 
                               bg-primary/10 text-primary text-sm font-bold hover:bg-primary hover:text-white transition">
                        View Details
                    </a>
                </div>

            </div>
            @endforeach

        </div>

    </div>
    @endforeach

</div>

@endsection