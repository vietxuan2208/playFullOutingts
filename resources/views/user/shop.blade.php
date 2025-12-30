@extends('layouts.user.user')

@section('content')

<section class="w-full pt-32 pb-12 bg-gradient-to-b from-primary/10 to-background-light dark:from-primary/20 dark:to-background-dark">
    <div class="max-w-6xl mx-auto px-6 text-center">
        <h1 class="text-4xl font-bold tracking-tight">Our Shop</h1>
        <p class="text-text-light/70 dark:text-text-dark/70 mt-2">
            <a href="{{url('user/dashboard')}}">Home</a>
            <span class="px-1">•</span>
            Shop
        </p>
    </div>
</section>

<div class="max-w-7xl mx-auto px-4 py-10 grid grid-cols-1 md:grid-cols-4 gap-10">

    <aside class="bg-card-light dark:bg-card-dark rounded-xl p-6 shadow-md border border-border-light dark:border-border-dark">

        <h2 class="text-xl font-semibold mb-4">Filter by Price</h2>

        <form method="GET" action="{{ route('user_shop') }}" class="space-y-3">

            {{-- All Price --}}
            <label class="flex items-center gap-3 cursor-pointer">
                <input type="checkbox" name="price[]" value="all"
                    class="w-4 h-4 rounded text-primary"
                    {{ in_array('all', request()->input('price', ['all'])) ? 'checked' : '' }}>
                <span>All Price</span>
            </label>


            {{-- Dynamic Ranges --}}
            @php
            $ranges = [
            [0, 300],
            [300, 600],
            [600, 900],
            [900, 1200],
            [1200, 2000]
            ];
            @endphp

            @foreach ($ranges as $index => $r)
            @php $value = $r[0].'-'.$r[1]; @endphp
            <label class="flex items-center gap-3 cursor-pointer">
                <input type="checkbox" name="price[]" value="{{ $value }}"
                    class="w-4 h-4 rounded text-primary"
                    {{ in_array($value, request()->input('price', [])) ? 'checked' : '' }}>
                <span>${{ $r[0] }} – ${{ $r[1] }}</span>
            </label>

            @endforeach

            <button class="mt-3 w-full py-2 bg-primary text-white rounded-lg hover:bg-primary/90 transition">
                Apply Filter
            </button>

        </form>
    </aside>


    <div class="md:col-span-3">

        <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">

            {{-- Search --}}
            <!-- <form method="GET" action="{{ route('user_shop') }}" class="w-full md:w-1/2">
                <div class="relative">
                    <input type="text" name="keyword"
                        value="{{ request('keyword') }}"
                        class="w-full px-4 py-2 rounded-lg border border-border-light dark:border-border-dark bg-card-light dark:bg-card-dark"
                        placeholder="Search product..." />
                    <button class="absolute right-3 top-2.5 text-primary">
                        <span class="material-symbols-outlined">search</span>
                    </button>
                </div>
            </form> -->
            <form method="GET" action="{{ route('user_shop') }}" class="w-full md:w-1/2">
                <div class="relative">

                    <input
                        type="text"
                        name="keyword"
                        value="{{ request('keyword') }}"
                        placeholder="Search product..."
                        class="w-full px-4 py-2 pr-16 rounded-lg border border-border-light dark:border-border-dark bg-card-light dark:bg-card-dark" />

                    <span
                        id="voice-search-product"
                        class="material-symbols-outlined absolute right-10 top-2.5 cursor-pointer text-gray-400 hover:text-primary">
                        mic
                    </span>

                    <button type="submit" class="absolute right-3 top-2.5 text-primary">
                        <span class="material-symbols-outlined">search</span>
                    </button>

                </div>
            </form>

            <div class="relative group">
                <button
                    class="flex items-center gap-2 px-4 py-2 bg-card-light dark:bg-card-dark border border-border-light dark:border-border-dark rounded-lg hover:shadow transition">
                    <span class="material-symbols-outlined text-primary">sort</span>
                    <span class="font-semibold">
                        {{ request('sort') === 'asc' ? 'Low → High' : (request('sort') === 'desc' ? 'High → Low' : 'Sort by Price') }}
                    </span>
                    <span class="material-symbols-outlined text-[20px] text-text-light/70 group-hover:text-primary transition">
                        expand_more
                    </span>
                </button>

                {{-- Dropdown --}}
                <div
                    class="absolute z-50 mt-2 w-48 bg-card-light dark:bg-card-dark border border-border-light dark:border-border-dark rounded-lg shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all">


                    <a href="{{ route('user_shop', ['sort'=>'asc', 'keyword'=>request('keyword')]) }}"
                        class="flex items-center gap-2 px-4 py-2 hover:bg-primary/10 dark:hover:bg-primary/20 cursor-pointer">
                        <span class="material-symbols-outlined text-primary">south</span>
                        Low → High
                    </a>

                    <a href="{{ route('user_shop', ['sort'=>'desc', 'keyword'=>request('keyword')]) }}"
                        class="flex items-center gap-2 px-4 py-2 hover:bg-primary/10 dark:hover:bg-primary/20 cursor-pointer">
                        <span class="material-symbols-outlined text-primary">north</span>
                        High → Low
                    </a>

                    <a href="{{ route('user_shop', ['keyword'=>request('keyword')]) }}"
                        class="flex items-center gap-2 px-4 py-2 hover:bg-primary/10 dark:hover:bg-primary/20 cursor-pointer">
                        <span class="material-symbols-outlined text-primary">close</span>
                        Reset Sort
                    </a>

                </div>
            </div>


        </div>


        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">

            @foreach ($products as $p)
            <div class="rounded-xl overflow-hidden bg-card-light dark:bg-card-dark shadow-md hover:shadow-xl transform hover:-translate-y-1 transition">

                {{-- Image --}}
                <a href="{{ route('user_detail', $p->id) }}">
                    <img src="{{ asset('storage/images/'.$p->photo) }}"
                        class="w-full h-52 object-cover hover:scale-105 transition duration-300">
                </a>

                {{-- Content --}}
                <div class="p-4">
                    <h3 class="font-semibold text-lg line-clamp-1">{{ $p->name }} </h3>

                    <p class="text-primary font-bold text-xl mt-1">
                        ${{ number_format($p->price, 2) }}
                    </p>
                </div>

                {{-- Actions --}}
                <div class="flex justify-between items-center px-4 py-3 border-t border-border-light dark:border-border-dark">

                    <a href="{{ route('user_detail', $p->id) }}"
                        class="text-sm text-primary font-medium hover:underline flex items-center gap-1">
                        <span class="material-symbols-outlined text-base">visibility</span>
                        View
                    </a>

                    <form method="POST" class="add-to-cart-form flex items-center gap-1 cursor-pointer">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $p->id }}">
                        <input type="hidden" name="quantity" value="1">

                        <button type="button"
                            class="add-to-cart-btn flex items-center gap-1 text-primary font-medium hover:text-primary/80 transition">
                            <span class="material-symbols-outlined text-base">add_shopping_cart</span>
                            Add
                        </button>
                    </form>

                </div>

            </div>
            @endforeach
        </div>


        {{-- PAGINATION --}}
        <div class="mt-10 flex justify-center">
            {{ $products->links('pagination::tailwind') }}
        </div>

    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const SpeechRecognition =
            window.SpeechRecognition || window.webkitSpeechRecognition;

        if (!SpeechRecognition) {
            console.warn('Speech Recognition not supported');
            return;
        }

        const recognition = new SpeechRecognition();
        // recognition.lang = 'vi-VN';
        recognition.lang = 'en-US';
        recognition.continuous = false;
        recognition.interimResults = false;

        const mic = document.getElementById('voice-search-product');
        if (!mic) return;

        const input = mic.closest('form').querySelector('input[name="keyword"]');

        mic.addEventListener('click', function() {
            recognition.start();
        });

        recognition.onstart = function() {
            mic.classList.add('text-primary');
        };

        recognition.onresult = function(event) {
            const text =
                event.results[event.results.length - 1][0].transcript;

            input.value = text.trim();
        };

        recognition.onend = function() {
            mic.classList.remove('text-primary');
        };

        recognition.onerror = function() {
            mic.classList.remove('text-primary');
            alert('Unable to recognize speech');
        };
    });
</script>
@endsection