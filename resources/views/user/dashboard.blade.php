@extends('layouts.user.user')

@section('content')

<div x-data="slider()" class="relative h-96 md:h-[65vh] overflow-hidden">
    <template x-for="(banner, index) in banners" :key="index">
        <img
            x-show="current === index"
            x-transition.opacity.duration.700ms
            :src="banner"
            class="absolute inset-0 w-full h-full object-cover object-center select-none pointer-events-none"
            alt="banner">
    </template>

    <div class="absolute inset-0 bg-gradient-to-b from-black/10 to-black/40 pointer-events-none"></div>

    <button @click="prev()"
        class="absolute left-4 top-1/2 -translate-y-1/2 bg-black/40 hover:bg-black/60 text-white p-3 rounded-full">
        ❮
    </button>

    <button @click="next()"
        class="absolute right-4 top-1/2 -translate-y-1/2 bg-black/40 hover:bg-black/60 text-white p-3 rounded-full">
        ❯
    </button>
</div>



<div class="flex justify-center py-5 px-4 sm:px-8 lg:px-10">
    <div class="flex flex-col w-full max-w-7xl">

        {{-- ================= HERO ================= --}}
        <section class="@container py-10 md:py-16 -mt-32 md:-mt-40 relative z-10">
            <div class="flex flex-col gap-6 items-center text-center bg-background-light dark:bg-background-dark p-6 md:p-10 rounded-xl shadow-lg">
                <h1 class="text-text-light dark:text-text-dark text-4xl font-black leading-tight tracking-[-0.033em]">
                    Unleash the Fun on Your Next Picnic
                </h1>
                <h2 class="text-text-light/80 dark:text-text-dark/80 max-w-xl mx-auto">
                    Your ultimate guide to outdoor games and easy-to-plan adventures.
                </h2>

                <a href="{{ route('user.game') }}"
                    class="flex items-center justify-center rounded-lg bg-primary text-white h-12 px-6 font-bold hover:opacity-90">
                    Explore Games
                </a>
            </div>
        </section>



        {{-- ================= GAME SECTION (DYNAMIC) ================= --}}
        <section class="py-12 md:py-20">
            <h2 class="text-text-light dark:text-text-dark text-[22px] font-bold text-center md:text-3xl">
                Find the Perfect Game
            </h2>

             <div class="grid grid-cols-1 md:grid-cols-3 gap-4 p-4">

                @foreach ($categories as $cat)
                @foreach ($cat->limited_games as $game)

                <a href="{{ route('games.detail', $game->id) }}"
                    class="relative rounded-xl overflow-hidden aspect-[4/3] group block">

                    {{-- Background Image --}}
                    <div class="absolute inset-0 bg-cover bg-center transition-transform duration-300 group-hover:scale-110"
                        style='background-image:
                        linear-gradient(0deg, rgba(0, 0, 0, 0.4) 0%, rgba(0, 0, 0, 0) 100%),
                        url("{{ $game->image ? asset('storage/games/images/'.$game->image) : asset('storage/games/no-image.jpg') }}")'>
                    </div>


                    {{-- Game Name --}}
                    <div class="absolute bottom-3 left-3 right-3">
                        <p class="text-black  text-base font-bold leading-tight drop-shadow-md">
                            {{ $cat->name }} - {{ $game->name }}
                        </p>
                    </div>

                </a>

                @endforeach
                @endforeach

            </div>
        </section>



        {{-- ================= ITINERARY SECTION (DYNAMIC) ================= --}}
        <section class="py-12 md:py-20">
            <h2 class="text-[22px] font-bold text-center md:text-3xl">
                Get Inspired for Your Next Outing
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 p-4">

                @foreach ($itineraries as $item)
                

                    <div class="bg-card-light dark:bg-card-dark rounded-xl overflow-hidden shadow-lg hover:shadow-2xl transition-shadow">
                        <img class="w-full h-56 object-cover" src="{{ asset('storage/itineraries/' . ($item->image ?? 'no-image.jpg')) }}" alt="{{ $item->name }}"/>

                        <div class="p-6">
                            <h3 class="text-xl font-bold mb-2">{{ $item->name }}</h3>

                            <p class="text-text-light/80 dark:text-text-dark/80 mb-4">
                                {{ Str::limit($item->description, 120) }}
                            </p>

                            <a class="text-primary font-bold hover:underline"
                               href="{{route('user.itinerary.detail', $item->id)}}">
                                View Itinerary
                            </a>
                        </div>
                    </div>

                @endforeach

            </div>
        </section>

    </div>
</div>
@endsection