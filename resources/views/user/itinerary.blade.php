@extends('layouts.user.user')

@section('content')
<style>
    .active-category {
        background: rgba(19, 236, 19, 0.15) !important;
        color: #13ec13 !important;
        border: 1px solid #13ec13 !important;
        box-shadow: 0 0 6px rgba(19, 236, 19, 0.3);
    }
</style>

<main class="flex-grow">
    <div class="px-4 sm:px-10 py-5">
        <div class="mx-auto max-w-7xl">

            <!-- 🔎 SEARCH + DAYS FILTER -->
            <div class="mt-8 flex flex-col gap-4">
                <div class="flex flex-col md:flex-row gap-4 items-center">

                    <!-- SEARCH INPUT BEAUTIFUL -->
                    <div class="w-full md:w-1/2 lg:w-2/3">
                        <div class="relative group">

                            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-primary transition">
                                search
                            </span>

                            <input id="searchInputBox"
                                class="w-full h-12 pl-12 pr-4 rounded-xl bg-white dark:bg-charcoal border border-gray-300 dark:border-gray-700
                   text-charcoal dark:text-off-white placeholder-gray-400 dark:placeholder-gray-500
                   shadow-sm focus:ring-2 focus:ring-primary/50 focus:border-primary transition-all"
                                placeholder="Search itineraries..."
                                type="text">
                        </div>
                    </div>


                    <div class="w-full md:w-auto">
                        <select id="daySelect"
                            class="h-12 px-4 w-40 rounded-xl bg-white dark:bg-charcoal border border-gray-300 dark:border-gray-700
                text-charcoal dark:text-off-white text-sm shadow-sm
                focus:ring-2 focus:ring-primary/50 focus:border-primary transition">
                            <option value="all">All Days</option>
                            <option value="1-2">1 – 2 Days</option>
                            <option value="3-4">3 – 4 Days</option>
                            <option value="5+">5+ Days</option>
                        </select>
                    </div>



                </div>

                <!-- CATEGORY FILTER -->
                <div class="flex gap-2 flex-wrap">

                    <!-- ALL Button -->
                    <div class="category-filter active-category flex h-9 cursor-pointer items-center rounded-full px-4
                bg-primary/10 text-primary border border-primary/40 font-medium
                hover:bg-primary/20 transition"
                        data-cat="all">
                        All
                    </div>

                    @foreach ($categories as $cat)
                    <div class="category-filter flex h-9 cursor-pointer items-center rounded-full px-4
                    bg-white dark:bg-charcoal border border-gray-300 dark:border-gray-700
                    text-sm text-charcoal dark:text-off-white hover:bg-primary/10 hover:text-primary
                    transition font-medium"
                        data-cat="{{ Str::slug($cat->name) }}">
                        {{ $cat->name }}
                    </div>
                    @endforeach

                </div>



            </div>

            <!-- BREADCRUMB -->
            <div class="mt-10">
                <div class="flex flex-wrap gap-2 p-4 bg-white/50 dark:bg-charcoal/50 rounded-lg">
                    <a class="text-primary font-medium" href="{{route('user.dashboard')}}">Home</a>
                    <span class="text-charcoal/50">/</span>
                    <span class="text-charcoal dark:text-off-white font-medium">Itineraries</span>
                </div>
            </div>

            <!-- ITINERARY LIST -->
            <div class="mt-10">
                <h3 class="text-2xl font-bold">Suggested Itineraries</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mt-6">

                    @foreach ($itineraries as $itinerary)

                    @php
                    $catNames = $itinerary->locations
                        ->flatMap(fn($loc) => $loc->categoryLocations->pluck('name'))
                        ->unique()
                        ->map(fn($name) => Str::slug($name))
                        ->implode(',');
                    @endphp


                    <div class="itinerary-card bg-white dark:bg-charcoal rounded-xl overflow-hidden shadow-md 
                        transition-transform hover:scale-105 hover:shadow-xl flex flex-col"
                        data-name="{{ strtolower($itinerary->name) }}"
                        data-days="{{ $itinerary->days }}"
                        data-category="{{ $catNames }}">

                        <img class="w-full h-48 object-cover"
                            src="{{ $itinerary->image ? asset('storage/itineraries/' . $itinerary->image) : asset('storage/avatars/no-image.jpg') }}"
                            alt="{{ $itinerary->name }}">

                        <div class="p-6 flex flex-col flex-grow">

                            <div class="flex gap-2 mb-2">
                                <span class="text-xs font-semibold bg-primary/20 text-primary px-2 py-1 rounded-full">
                                    {{ $itinerary->days }} Days
                                </span>

                                @foreach($itinerary->locations as $loc)
                                <span class="text-xs font-semibold bg-primary/20 text-primary px-2 py-1 rounded-full">
                                    {{ $loc->name }}
                                </span>
                                @endforeach
                            </div>

                            <h4 class="text-xl font-bold">{{ $itinerary->name }}</h4>

                            <p class="text-sm text-charcoal/80 dark:text-off-white/80 mt-2 flex-grow">
                                {{ Str::limit($itinerary->description, 120) }}
                            </p>

                            <a href="{{route('user.itinerary.detail', $itinerary->id)}}"
                                class="mt-auto flex w-full items-center justify-center rounded-lg h-10 px-4 
                                    bg-primary/10 text-primary font-bold hover:bg-primary hover:text-white transition">
                                View Details
                            </a>

                        </div>
                    </div>
                    @endforeach

                </div>
            </div>

        </div>
    </div>
</main>



<script>
    let filterKeyword = "";
    let filterDays = "all";
    let filterCategory = "all";

    // Keyword Search
    document.getElementById("searchInputBox").addEventListener("input", function() {
        filterKeyword = this.value.trim().toLowerCase();
        applyFilters();
    });

    // Days Select
    document.getElementById("daySelect").addEventListener("change", function() {
        filterDays = this.value;
        applyFilters();
    });

    // Category Filter Click Handler
    document.querySelectorAll(".category-filter").forEach(btn => {
        btn.addEventListener("click", () => {

            // bỏ active khỏi tất cả
            document.querySelectorAll(".category-filter").forEach(b => b.classList.remove("active-category"));

            // thêm active cho cái đang bấm
            btn.classList.add("active-category");

            filterCategory = btn.dataset.cat;
            applyFilters();
        });
    });

    // Day Range Logic
    function matchDays(days) {
        days = parseInt(days);

        if (filterDays === "all") return true;
        if (filterDays === "1-2") return days >= 1 && days <= 2;
        if (filterDays === "3-4") return days >= 3 && days <= 4;
        if (filterDays === "5+") return days >= 5;

        return true;
    }

function applyFilters() {
    document.querySelectorAll(".itinerary-card").forEach(card => {

        let name = card.dataset.name || "";
        let days = parseInt(card.dataset.days || 0);

        let categories = (card.dataset.category || "")
            .split(",")
            .map(c => c.trim());

        let matchKeyword = filterKeyword === "" || name.includes(filterKeyword);
        let matchDayRange = matchDays(days);

        let matchCategory =
            filterCategory === "all" ||
            categories.includes(filterCategory);

        card.style.display =
            (matchKeyword && matchDayRange && matchCategory)
                ? "block"
                : "none";
    });
}
</script>

@endsection