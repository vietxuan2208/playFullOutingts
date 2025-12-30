@extends('layouts.user.user')

@section('content')

<div class="px-4 sm:px-8 md:px-20 lg:px-40 py-10">


    <h1 class="text-4xl lg:text-5xl font-black text-text-light dark:text-text-dark mb-6">
        Find the Perfect Game for Your Picnic
    </h1>
    <p class="text-subtle-light dark:text-subtle-dark mb-10">
        Select a category below to get started.
    </p>

    {{-- CATEGORY FILTER LIST --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4 mb-12">
        @foreach ($categoriesList as $cat)
        <a href="{{ route('games.category', $cat->id) }}">
            <button class="group flex flex-col items-center gap-2 rounded-lg p-3 border 
                {{ optional($category)->id == $cat->id ? 'border-primary bg-primary/10' : 'border-transparent' }}
                hover:border-primary/50 hover:bg-white dark:hover:bg-background-dark/50 transition-all">

                <div class="flex size-14 items-center justify-center rounded-full 
                    {{ optional($category)->id == $cat->id ? 'bg-primary text-white' : 'bg-primary/10 text-primary' }}">
                    <span class="material-symbols-outlined !text-3xl">
                        {{ $icons[$cat->name] ?? 'sports_esports' }}
                    </span>
                </div>

                <span class="text-sm font-medium text-center text-text-light dark:text-text-dark">
                    {{ $cat->name }}
                </span>
            </button>
        </a>
        @endforeach
    </div>

    {{-- SEARCH FORM --}}
    <form id="gameFilterForm" method="GET" action="{{ route('user.game') }}" class="mb-10" onsubmit="return false;">
        <div class="flex flex-col md:flex-row gap-4 items-center">

            <input type="text" name="keyword" placeholder="Search by keyword..."
                value="{{ request('keyword') }}"
                class="flex-1 h-12 px-4 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary">

            <select name="difficulty"
                class="flex-none w-auto h-12 px-4 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-primary">
                <option value="">All</option>
                <option value="Easy"   {{ request('difficulty') == 'Easy' ? 'selected' : '' }}>Easy</option>
                <option value="Medium" {{ request('difficulty') == 'Medium' ? 'selected' : '' }}>Medium</option>
                <option value="Hard"   {{ request('difficulty') == 'Hard' ? 'selected' : '' }}>Hard</option>
            </select>

            <select name="players"
                class="flex-none w-auto h-12 px-4 pr-8 rounded-lg border border-gray-300 dark:border-gray-600 
                    bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-primary appearance-none">
                <option value="">Players</option>
                <option value="1-2" {{ request('players')=='1-2' ? 'selected' : '' }}>1-2</option>
                <option value="3-4" {{ request('players')=='3-4' ? 'selected' : '' }}>3-4</option>
                <option value="5+" {{ request('players')=='5+' ? 'selected' : '' }}>5+</option>
            </select>
        </div>
    </form>

    {{-- CATEGORY TITLE --}}
    <h2 class="text-2xl font-bold mb-6">
        {{ $category->name ?? 'All Games' }}
    </h2>

    {{-- GAME LIST --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

        @foreach($games as $game)
            @php
                $max = (int) ($game->players ?? 1);
                $min = 1;
                $playersGroup =
                    ($max <= 2) ? '1-2' :
                    (($max <= 4) ? '3-4' : '5+');
            @endphp

            <div
                class="game-card group flex flex-col overflow-hidden rounded-xl"
                data-difficulty="{{ strtolower($game->difficulty) }}"
                data-players="{{ $playersGroup }}"
                data-min="{{ $min }}"
                data-max="{{ $max }}"
            >

                <div class="aspect-video overflow-hidden">
                    <img
                        src="{{ asset('storage/games/images/' . ($game->image ?? 'no-image.jpg')) }}"
                        alt="{{ $game->name }}"
                        class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-110" />
                </div>

                <div class="flex flex-col gap-4 p-5 flex-grow">
                    <h3 class="text-lg font-bold text-text-light dark:text-text-dark">
                        {{ $game->name }}
                    </h3>

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

                    <a href="{{ route('games.detail', $game->id) }}#detail"
                        class="mt-auto flex justify-center items-center h-10 rounded-lg
                            bg-primary/10 text-primary text-sm font-bold hover:bg-primary hover:text-white transition">
                        View Details
                    </a>
                </div>
            </div>
        @endforeach
    </div>

    <div class="flex justify-center mt-6" id="gamesPagination"></div>
</div>

<script>
const gamesPerPage = 9;
let currentPage = 1;
let filteredGames = [];
let allGameCards = [];

function filterGames() {
    const keyword = document.querySelector('input[name="keyword"]').value.toLowerCase();
    const difficulty = document.querySelector('select[name="difficulty"]').value.toLowerCase();
    const players = document.querySelector('select[name="players"]').value;

    filteredGames = allGameCards.filter(card => {
        const gameName = card.querySelector('h3')?.innerText.toLowerCase() || '';
        const gameDifficulty = (card.dataset.difficulty || '').toLowerCase();
        const max = parseInt(card.dataset.max || '1', 10);

        const matchName = gameName.includes(keyword);
        const matchDifficulty = !difficulty || gameDifficulty === difficulty;

        let matchPlayers = true;
        if (players === '1-2') matchPlayers = max <= 2;
        else if (players === '3-4') matchPlayers = max >= 3 && max <= 4;
        else if (players === '5+') matchPlayers = max >= 5;

        return matchName && matchDifficulty && matchPlayers;
    });

    currentPage = 1;
    paginateGames();
}

function paginateGames() {
    const totalGames = filteredGames.length;
    const totalPages = Math.ceil(totalGames / gamesPerPage);

    document.querySelectorAll('.game-card').forEach(card => card.style.display = 'none');

    const start = (currentPage - 1) * gamesPerPage;
    const end = start + gamesPerPage;

    filteredGames.slice(start, end).forEach(card => card.style.display = 'block');

    renderPagination(totalPages);
}

function renderPagination(totalPages) {
    const paginationContainer = document.getElementById('gamesPagination');
    if (!paginationContainer) return;

    paginationContainer.innerHTML = '';

    for (let i = 1; i <= totalPages; i++) {
        const btn = document.createElement('button');
        btn.textContent = i;
        btn.className = 'px-3 py-1 border rounded mx-1';
        if (i === currentPage) btn.classList.add('bg-primary', 'text-white');

        btn.addEventListener('click', () => {
            currentPage = i;
            paginateGames();
        });

        paginationContainer.appendChild(btn);
    }
}

document.addEventListener('DOMContentLoaded', () => {
    allGameCards = Array.from(document.querySelectorAll('.game-card'));
    filteredGames = [...allGameCards];
    paginateGames();

    document.querySelector('input[name="keyword"]').addEventListener('input', filterGames);
    document.querySelector('select[name="difficulty"]').addEventListener('change', filterGames);
    document.querySelector('select[name="players"]').addEventListener('change', filterGames);
});
</script>

@endsection
