@extends('layouts.user.user')

@section('content')

<main class="flex-grow">
    <div class="px-4 sm:px-10 py-8 max-w-6xl mx-auto">

        <div class="mb-6">
            <a href="{{ url()->previous() }}" class="text-primary hover:underline">
                ← Back
            </a>
        </div>

        <h1 class="text-3xl font-bold mb-4">{{ $location->name }}</h1>

        {{-- IMAGE --}}
        <img src="{{ $location->image ? asset('storage/locations/'.$location->image) : asset('storage/avatars/no-image.jpg') }}"
             class="w-full h-80 object-cover rounded-xl shadow-lg mb-6">

        {{-- DESCRIPTION --}}
        <p class="text-lg text-charcoal dark:text-off-white leading-relaxed mb-8">
            {{ $location->description }}
        </p>


        {{-- GOOGLE MAP --}}
    @if($location->address)
        <div class="w-full h-[320px] md:h-[500px] lg:h-[600px] rounded-xl overflow-hidden shadow-lg">
            <iframe
                class="w-full h-full border-0"
                loading="lazy"
                allowfullscreen
                referrerpolicy="no-referrer-when-downgrade"
                src="https://www.google.com/maps?q={{ urlencode($location->address) }}&output=embed">
            </iframe>
        </div>
    @endif


    </div>
</main>

@endsection
