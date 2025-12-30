@extends('layouts.user.user')

@section('content')

<main class="flex-1 w-full max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-20">
    <div class="flex flex-col gap-8">
        <div class="flex flex-col gap-3 text-center">
            <h1 class="text-4xl md:text-5xl font-black leading-tight tracking-[-0.033em]">Get in Touch</h1>
            <p class="text-base font-normal leading-normal text-gray-500 dark:text-gray-400">Fill out the form below, and we'll get back to you as soon as possible.</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12 lg:gap-16 items-start">
            <div class="w-full flex flex-col gap-6 p-6 md:p-8 bg-card-light dark:bg-card-dark rounded-xl shadow-sm">
                <form id="contactForm" method="POST" action="{{ route('user.contact.send') }}" class="flex flex-col gap-6">
                    @csrf
                    @php($success = session()->pull('success'))

                    @if($success)
                    <div class="text-sm text-green-600">
                        {{ $success }}
                    </div>
                    @endif




                    @if($errors->any())
                    <div class="text-sm text-red-600">
                        <ul class="list-disc pl-5">
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <label class="flex flex-col flex-1">
                        <p class="text-base font-medium leading-normal pb-2">Your Name</p>
                        <input name="name" value="{{ old('name') }}" class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-text-light dark:text-text-dark focus:outline-0 focus:ring-2 focus:ring-primary/50 border border-border-light dark:border-border-dark bg-background-light dark:bg-background-dark h-12 placeholder:text-text-placeholder-light dark:placeholder:text-text-placeholder-dark p-3 text-base font-normal leading-normal" placeholder="Enter your full name" type="text" />
                    </label>
                    <label class="flex flex-col flex-1">
                        <p class="text-base font-medium leading-normal pb-2">Your Email</p>
                        <input name="email" value="{{ old('email') }}" class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-text-light dark:text-text-dark focus:outline-0 focus:ring-2 focus:ring-primary/50 border border-border-light dark:border-border-dark bg-background-light dark:bg-background-dark h-12 placeholder:text-text-placeholder-light dark:placeholder:text-text-placeholder-dark p-3 text-base font-normal leading-normal" placeholder="you@example.com" type="email" />
                    </label>
                    <label class="flex flex-col flex-1">
                        <p class="text-base font-medium leading-normal pb-2">Your Message</p>
                        <textarea name="message" class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-text-light dark:text-text-dark focus:outline-0 focus:ring-2 focus:ring-primary/50 border border-border-light dark:border-border-dark bg-background-light dark:bg-background-dark min-h-36 placeholder:text-text-placeholder-light dark:placeholder:text-text-placeholder-dark p-3 text-base font-normal leading-normal" placeholder="Write your message here...">{{ old('message') }}</textarea>
                    </label>

                    <div id="captchaBlock" class="hidden">
                        <label class="flex flex-col flex-1">
                            <p class="text-base font-medium leading-normal pb-2">Captcha</p>
                            <div class="flex items-center gap-3 mb-2">
                                <img id="captchaImage" src="{{ $captcha_image ?? '' }}" alt="captcha" class="h-12 rounded border" />
                                <button id="refreshCaptcha" type="button" class="px-3 py-1 bg-gray-200 dark:bg-gray-700 rounded">Refresh</button>
                            </div>
                            <input id="captchaInput" name="captcha" value="{{ old('captcha') }}" class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-text-light dark:text-text-dark focus:outline-0 focus:ring-2 focus:ring-primary/50 border border-border-light dark:border-border-dark bg-background-light dark:bg-background-dark h-12 placeholder:text-text-placeholder-light dark:placeholder:text-text-placeholder-dark p-3 text-base font-normal leading-normal" placeholder="Enter text from image" type="text" />
                        </label>
                    </div>



                    <button type="submit" class="flex min-w-[84px] w-full cursor-pointer items-center justify-center overflow-hidden rounded-lg h-12 px-4 bg-primary text-white text-base font-bold leading-normal tracking-[0.015em] hover:bg-blue-600 transition-colors">
                        <span class="truncate">Send Message</span>
                    </button>
                </form>
                <script>
                    (function() {
                        var form = document.getElementById('contactForm');
                        if (!form) return;
                        var captchaBlock = document.getElementById('captchaBlock');
                        var captchaInput = document.getElementById('captchaInput');
                        var captchaImage = document.getElementById('captchaImage');
                        var refreshBtn = document.getElementById('refreshCaptcha');

                        function refreshCaptchaAjax() {
                            try {
                                fetch("{{ route('user.contact.captcha') }}", {
                                        credentials: 'same-origin',
                                        headers: {
                                            'X-Requested-With': 'XMLHttpRequest'
                                        }
                                    })
                                    .then(function(r) {
                                        return r.json();
                                    })
                                    .then(function(j) {
                                        if (j && j.image) {
                                            captchaImage.src = j.image;
                                        }
                                    })
                                    .catch(function() {
                                        console.warn('Could not refresh captcha');
                                    });
                            } catch (e) {
                                console.warn(e);
                            }
                        }

                        if (refreshBtn) {
                            refreshBtn.addEventListener('click', function() {
                                refreshCaptchaAjax();
                            });
                        }

                        form.addEventListener('submit', function(e) {
                            // if captcha block is hidden, reveal it and stop the submit
                            if (captchaBlock && captchaBlock.classList.contains('hidden')) {
                                e.preventDefault();
                                captchaBlock.classList.remove('hidden');
                                // small delay to allow layout, then focus and scroll
                                setTimeout(function() {
                                    if (captchaInput) {
                                        captchaInput.focus();
                                        captchaInput.scrollIntoView({
                                            behavior: 'smooth',
                                            block: 'center'
                                        });
                                    }
                                }, 50);
                                return;
                            }

                            // if visible, ensure user filled captcha
                            if (captchaInput) {
                                var v = (captchaInput.value || '').toString().trim();
                                if (v === '') {
                                    e.preventDefault();
                                    alert('Please answer the captcha before sending.');
                                    captchaInput.focus();
                                    return;
                                }
                                // allow submit; server will validate correctness
                            }
                        });

                        // when captcha is revealed, ensure image exists (if not, try refresh once)
                        captchaBlock.addEventListener('transitionend', function() {
                            if (captchaBlock && !captchaBlock.classList.contains('hidden')) {
                                if (captchaImage && (!captchaImage.src || captchaImage.src.trim() === '')) {
                                    refreshCaptchaAjax();
                                }
                            }
                        });
                    })();
                </script>
            </div>
            <div class="w-full flex flex-col gap-6">
                <div class="relative w-full h-[320px] md:h-[520px] lg:h-[600px] rounded-xl overflow-hidden shadow-sm">
                    <iframe
                        class="w-full h-full border-0"
                        loading="lazy"
                        allowfullscreen
                        referrerpolicy="no-referrer-when-downgrade"
                        src="https://www.google.com/maps?q={{ urlencode($address ?? '778/10 Nguyen Kiem, Ho Chi Minh City, Vietnam') }}&output=embed">
                    </iframe>
                </div>

                <div class="p-6 bg-card-light dark:bg-card-dark rounded-xl shadow-sm flex flex-col gap-4">
                    <h3 class="text-lg font-bold">Or Contact Us Directly</h3>
                    <div class="flex flex-col gap-3">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-orange-500 text-xl">location_on</span>
                           <p class="text-sm">
                                {{ $address ?? 'Ho Chi Minh City, Vietnam' }}
                            </p>

                        </div>
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-orange-500 text-xl">mail</span>
                            <p class="text-sm">support@playfulloutings.com</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-orange-500 text-xl">call</span>
                            <p class="text-sm">0123 456 789</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>



</main>

@endsection