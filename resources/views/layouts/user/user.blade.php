<!DOCTYPE html>
<html class="light" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>PlayFullOutings - Your Guide to Outdoor Fun</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com" rel="preconnect" />
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect" />
    <link
        href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;700;900&amp;display=swap"
        rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200"
        rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="{{ asset('user/js/layout.js') }}"></script>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#13ec13",
                        "background-light": "#f6f8f6",
                        "background-dark": "#102210",
                        "text-light": "#111811",
                        "text-dark": "#e0f2e0",
                        "card-light": "#ffffff",
                        "card-dark": "#1a381a",
                        "border-light": "#e0f2e0",
                        "border-dark": "#2a502a"
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
            font-variation-settings:
                'FILL' 0,
                'wght' 400,
                'GRAD' 0,
                'opsz' 24
        }

        .dropdown-menu {
            position: absolute;
            transform: translateX(-50%);
            opacity: 0;
            pointer-events: none;
            transition: opacity .2s ease;
            z-index: 50;
        }

        .dropdown-open {
            opacity: 1 !important;
            pointer-events: auto !important;
        }

        #games-menu {
            top: 100%;
            left: 100%;
            width: 180px;
            margin-top: 8px;
        }

        #avatar-menu {
            top: 100%;
            left: 100%;
            margin-top: 8px;
        }

        #navbar.default {
            background-color: rgba(255, 255, 255, 1) !important;
        }

        #navbar.scrolled {
            background-color: rgba(255, 255, 255, 0.25) !important;
            backdrop-filter: blur(18px) !important;
            -webkit-backdrop-filter: blur(18px) !important;
            border-bottom: none !important;
        }

        html.dark #navbar.scrolled {
            background-color: rgba(0, 0, 0, 0.25) !important;
        }

        @keyframes marquee {
            0% {
                transform: translateX(100%);
            }

            100% {
                transform: translateX(-100%);
            }
        }

        .animate-marquee {
            display: inline-block;
            animation: marquee 18s linear infinite;
        }
    </style>
</head>

<body class="bg-background-light dark:bg-background-dark font-display text-text-light dark:text-text-dark">
    <div class="relative flex min-h-screen w-full flex-col overflow-x-hidden">
        <header class="fixed top-0 left-0 w-full z-50">
            <nav id="navbar" class="fixed top-0 w-full z-50 bg-white">
                <div class="flex items-center justify-between mx-auto max-w-7xl py-3 h-auto">


                    <div class="flex flex-col items-start pl-0 ml-0 leading-tight">
                        <a href="{{route('user.dashboard')}}" class="flex items-center">
                            <img src="{{ asset('user/images/logouser.png') }}"
                                alt="Logo"
                                class="w-20 h-20 object-contain">
                        </a>
                        <!-- cd  -->

                        <div class="text-sm font-bold text-text-light dark:text-text-dark mt-0">

                            <span>{{ $onlineUsers }} online</span>


                        </div>
                    </div>


                    <div class="hidden md:flex flex-1 justify-center">
                        <ul class="flex items-center gap-x-6 lg:gap-x-8 text-sm font-medium">
                            <li>
                                <a href="{{ url('user/dashboard') }}"
                                    class="{{ Request::is('user/dashboard*') ? 'text-primary' : 'hover:text-primary' }}">
                                    Home
                                </a>
                            </li>


                            <li class="relative nav-item select-none">
                                <a href="{{ route('user.game') }}"
                                    id="games-button"
                                    class="flex items-center gap-1 px-4 py-2 text-sm cursor-pointer
          {{ Request::is('user/game*') ? 'text-primary' : 'hover:text-primary' }}
          hover:bg-primary/10">

                                    <span>Games</span>
                                    <span class="material-symbols-outlined text-base">expand_more</span>
                                </a>


                                <div id="games-menu"
                                    class="dropdown-menu absolute left-0 mt-3 w-56 bg-card-light dark:bg-card-dark 
               rounded-lg shadow-xl py-2 border border-border-light dark:border-border-dark">

                                    <!-- Category -->
                                    @foreach ($categoriesList as $cat)
                                    <a class="block px-4 py-2 text-sm
                                        {{ $category && $category->id == $cat->id ? 'bg-primary/20 text-primary font-bold' : '' }}
                                        hover:bg-primary/10 hover:text-primary"
                                        href="{{ route('games.category', $cat->id) }}#picnic-title">
                                        {{ $cat->name }}
                                    </a>
                                    @endforeach
                                </div>
                            </li>

                            <li>
                                <a href="{{ route('user_shop') }}"
                                    class="{{ Route::currentRouteNamed('user_shop') ? 'text-primary' : 'hover:text-primary' }}">
                                    Shop
                                </a>
                            </li>
                            <a href="{{ route('user.blog.index') }}"
                                class="{{ Route::currentRouteNamed('user.blog.*') ? 'text-primary' : 'hover:text-primary' }}">
                                Blogs
                            </a>
                            </li>

                            <li>
                                <a href="{{ url('user/itinerary') }}"
                                    class="{{ Request::is('user/itinerary') ? 'text-primary' : 'hover:text-primary' }}">
                                    Itinerary
                                </a>
                            </li>

                            <li>
                                <a href="{{ url('user/aboutus') }}"
                                    class="{{ Request::is('user/aboutus') ? 'text-primary' : 'hover:text-primary' }}">
                                    About Us
                                </a>
                            </li>

                            <li>
                                <a href="{{ url('user/contact') }}"
                                    class="{{ Request::is('user/contact') ? 'text-primary' : 'hover:text-primary' }}">
                                    Contact Us
                                </a>
                            </li>

                        </ul>
                    </div>

                    <div id="header-right" class="flex items-center gap-2">
                        {{-- CART ICON --}}
                        <a id="cart-icon" href="{{ route('cart_user') }}"
                            class="relative flex items-center justify-center w-12 h-12 rounded-full hover:bg-black/5 dark:hover:bg-white/5 transition">


                            <span class="material-symbols-outlined text-[28px]">
                                shopping_cart
                            </span>

                            @auth
                            @php
                            $cartCount = \App\Models\Cart::where('user_id', Auth::id())->sum('quantity');
                            @endphp

                            @if($cartCount > 0)
                            <span id="cart-count-badge" class="absolute -top-1 -right-1 bg-primary text-white text-[12px] font-bold
w-5 h-5 flex items-center justify-center rounded-full shadow">
                                {{ $cartCount }}
                            </span>

                            @endif
                            @endauth
                        </a>

                        {{-- HIỆN NÚT REGISTER + LOGIN KHI CHƯA LOGIN --}}
                        @if(!Auth::check() || Auth::user() == null)
                        <div id="auth-buttons" class="flex items-center gap-3">
                            <a href="{{ route('register') }}"
                                class="px-4 py-2 rounded-lg bg-primary text-white font-medium hover:bg-primary/90 transition">
                                Sign up
                            </a>
                            <a href="{{ route('login') }}"
                                class="px-4 py-2 rounded-lg bg-primary/10 text-primary border border-primary hover:bg-primary/20 transition font-medium">
                                Sign in
                            </a>
                        </div>
                        @endif


                        @if(Auth::check() && Auth::user() != null)
                        <div id="avatar-dropdown" class="relative">
                            <button id="avatar-button"
                                class="flex items-center justify-center size-12 rounded-full hover:bg-black/5 dark:hover:bg-white/5 transition-colors">
                                <img id="avatarPreview"
                                    class="w-12 h-12 rounded-full object-cover transition-transform duration-300 hover:scale-110 hover:shadow-lg"
                                    src="{{ Auth::user()->photo ? asset('storage/avatars/' . Auth::user()->photo) : asset('storage/avatars/no-image.jpg') }}"
                                    alt="{{ Auth::user()->name }}">
                            </button>

                            <div id="avatar-menu"
                                class="dropdown-menu absolute right-0 mt-3 w-56 bg-card-light dark:bg-card-dark rounded-lg shadow-xl py-2 border border-border-light dark:border-border-dark">
                                <a class="flex items-center gap-3 px-4 py-2 text-sm hover:bg-primary/10 hover:text-primary transition nav-link"
                                    href="{{ route('user.profile') }}">
                                    <span class="material-symbols-outlined text-base">account_circle</span>
                                    {{ Auth::user()->name }}
                                </a>
                                <form action="{{ url('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="flex items-center gap-3 px-4 py-2 text-sm hover:bg-primary/10 hover:text-primary transition w-full text-left nav-link">
                                        <span class="material-symbols-outlined text-base">logout</span>
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                        @endif

                    </div>

                    <button class="md:hidden text-text-light dark:text-text-dark">
                        <span class="material-symbols-outlined text-2xl" data-icon="menu"></span>
                    </button>
                </div>
            </nav>

        </header>

        <main class="flex-1 mt-[120px]">


            @yield('content')
        </main>
        <footer class="bg-card-dark text-text-dark pt-10 pb-6">
            <div class="max-w-7xl mx-auto px-6">

                <!-- GRID 4 CỘT -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-10">

                    <!-- 1️⃣ ABOUT + LOGO -->
                    <div>
                        <div class="flex items-center gap-4 mb-4">
                            <div class="text-primary text-3xl">
                                <span class="material-symbols-outlined">nature_people</span>
                            </div>
                            <div class="w-16 h-16 rounded-full bg-white flex items-center justify-center shadow">
                                <img src="{{ asset('user/images/logouser.png') }}"
                                    class="w-14 h-14 object-contain rounded-full">
                            </div>
                        </div>

                        <p class="text-sm text-text-dark/70 leading-relaxed">
                            PlayFullOutings brings joyful outdoor experiences to families and friends.
                            Discover picnics, short trips, and fun activities for every occasion.
                        </p>
                    </div>

                    <!-- 2️⃣ QUICK LINKS -->
                    <div class="">
                        <h3 class="font-bold mb-4 text-lg">Quick Links</h3>
                        <ul class="space-y-2 text-sm">
                            <li><a href="#" class="hover:text-primary transition">Games</a></li>
                            <li><a href="#" class="hover:text-primary transition">Itineraries</a></li>
                            <li><a href="#" class="hover:text-primary transition">About Us</a></li>
                            <li><a href="#" class="hover:text-primary transition">Contact</a></li>
                        </ul>
                    </div>

                    <!-- 3️⃣ CONTACT INFO -->
                    <div>
                        <h3 class="font-bold mb-4 text-lg">Contact Info</h3>
                        <ul class="space-y-3 text-sm">
                            <li class="flex gap-2">
                                <span class="material-symbols-outlined text-primary text-base">location_on</span>
                                778/10 Nguyễn Kiệm, Phú Nhuận, HCM
                            </li>
                            <li class="flex gap-2">
                                <span class="material-symbols-outlined text-primary text-base">mail</span>
                                support@playfulloutings.com
                            </li>
                            <li class="flex gap-2">
                                <span class="material-symbols-outlined text-primary text-base">call</span>
                                0123 456 789
                            </li>
                        </ul>
                    </div>

                    <!-- 4️⃣ FOLLOW US + NEWSLETTER -->
                    <div>
                        <h3 class="font-bold mb-4 text-lg">Follow Us</h3>
                        <div class="flex space-x-4 mb-6">

                            <!-- Facebook -->
                            <a class="hover:text-primary transition" href="#">
                                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 320 512">
                                    <path d="M279.14 288l14.22-92.66h-88.91V127.58c0-25.35 
                12.42-50.06 52.24-50.06H293V6.26S259.5 0 225.36 0c-73.22 
                0-121.17 44.38-121.17 124.72V195.3H22.89V288h81.3v224h100.2V288z" />
                                </svg>
                            </a>

                            <!-- Instagram -->
                            <a class="hover:text-primary transition" href="#">
                                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 448 512">
                                    <path d="M224.1 141c-63.6 0-114.9 51.3-114.9 114.9S160.5 
                370.9 224.1 370.9 339 319.6 339 255.9 287.7 141 224.1 
                141zm146.4-41c0 14.9-12 26.9-26.9 26.9s-26.9-12-26.9-26.9 
                12-26.9 26.9-26.9 26.9 12 26.9 26.9zM224.1 338.3c-45.5 
                0-82.3-36.8-82.3-82.3s36.8-82.3 82.3-82.3 82.3 36.8 
                82.3 82.3-36.8 82.3-82.3 82.3zM398.8 80c-10.9-29.1-31.4-51.6-60.5-62.5C312.7 
                0 256.3 0 224 0S135.3 0 109.7 17.5 51.6 70.9 40.7 100c-11 29.1-11 85.5-11 
                118.8s0 89.7 11 118.8c11 29.1 31.4 51.6 60.5 62.5 26.6 
                10.7 83 10.7 115.3 10.7s88.7 0 115.3-10.7c29.1-10.9 49.5-33.4 
                60.5-62.5 10.9-29.1 10.9-85.5 10.9-118.8S409.7 109.1 398.8 80z" />
                                </svg>
                            </a>

                            <!-- Twitter -->
                            <a class="hover:text-primary transition" href="#">
                                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 512 512">
                                    <path d="M459.37 151.716c.325 4.548 
                .325 9.097.325 13.645 0 138.72-105.583 
                298.558-298.558 298.558-59.452 0-114.68-17.219-161.137-47.106 
                8.447.974 16.568 1.299 25.34 1.299 49.055 0 
                94.213-16.568 130.274-44.832-46.132-.975-84.792-31.188-98.112-72.772 
                6.498.974 12.995 1.624 20.142 1.624 9.421 0 
                18.843-1.299 27.614-3.573-48.081-9.747-84.143-51.98-84.143-102.985v-1.299
                c13.969 7.797 30.214 12.67 47.431 13.319-28.264-18.843-46.781-51.005-46.781-87.391 
                0-19.492 5.197-37.36 14.294-52.954 51.655 
                63.675 129.3 105.258 216.365 109.807-1.624-7.797-2.599-15.918-2.599-24.04 
                0-57.828 46.782-104.934 104.934-104.934 30.213 
                0 57.502 12.67 76.67 33.137 23.715-4.548 
                46.456-13.32 66.599-25.34-7.798 24.366-24.366 
                44.833-46.132 57.827 21.117-2.273 41.584-8.122 
                60.426-16.243-14.292 20.791-32.161 39.308-52.628 54.253z" />
                                </svg>
                            </a>
                        </div>
                    </div>

                </div>

                <!-- MAP FULL WIDTH -->
                <div class="mt-10">
                    <h3 class="font-bold mb-4 text-lg">Our Location</h3>

                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3919.113845338381!2d106.67799917465458!3d10.801927589348335!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31752934c609c5bd%3A0x751f71739b98ebc4!2sAptech%20Computer%20Education%20-%20H%E1%BB%87%20th%E1%BB%91ng%20%C4%90%C3%A0o%20t%E1%BA%A1o%20L%E1%BA%ADp%20tr%C3%ACnh%20vi%C3%AAn%20Qu%E1%BB%91c%20t%E1%BA%BF%20Aptech!5e0!3m2!1svi!2s!4v1704916133942!5m2!1svi!2s"
                        class="w-full h-80 rounded-lg border border-white/20 shadow"
                        style="border:0;"
                        allowfullscreen=""
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>

                </div>
                <div id="ticker"
                    class="fixed bottom-0 left-0 w-full backdrop-blur-md bg-black/20 dark:bg-white/10 text-white dark:text-text-dark py-1.5 z-[999] overflow-hidden border-t border-white/20 dark:border-white/10">

                    <div class="animate-marquee whitespace-nowrap px-4 text-sm tracking-wide flex items-center gap-6">
                        <span id="ticker-text">Loading location...</span>
                    </div>
                </div>
                <!-- COPYRIGHT -->
                <div class="text-center text-sm text-text-dark/60 mt-10 border-t border-white/10 pt-4">
                    © 2025 PlayFullOutings. All rights reserved.
                </div>

            </div>
        </footer>

    </div>
    </div>
   
      



    @include('layouts.user.chat-widget')
    <script>
        window.addEventListener("scroll", function() {
            const header = document.getElementById("main-header");

            if (window.scrollY > 50) {
                header.classList.remove("header-transparent");
                header.classList.remove("absolute");
                header.classList.add("sticky");
                header.classList.add("header-scrolled");
            } else {
                header.classList.add("absolute");
                header.classList.add("header-transparent");
                header.classList.remove("sticky");
                header.classList.remove("header-scrolled");
            }
        });

        window.dispatchEvent(new Event("scroll"));

        function showToast(message, isError = false) {
            const toast = document.createElement("div");
            toast.className = `
            fixed left-1/2 top-20 -translate-x-1/2 z-[500] 
            px-6 py-3 rounded-xl shadow-lg opacity-0 pointer-events-none 
            transition-all duration-500 font-semibold 
        `;
            toast.style.backgroundColor = isError ? "#dc2626" : "#10b981";
            toast.style.color = "white";
            toast.textContent = message;

            document.body.appendChild(toast);

            setTimeout(() => {
                toast.style.opacity = "1";
                toast.style.transform = "translate(-50%, -50%) scale(1.05)";
            }, 10);

            setTimeout(() => {
                toast.style.opacity = "0";
                toast.style.transform = "translate(-50%, -50%) scale(0.9)";
            }, 1800);

            setTimeout(() => toast.remove(), 2400);
        }

        function updateHeaderCartBadge(total) {
            let badge = document.querySelector("#cart-count-badge");
            const cartIcon = document.querySelector("#cart-icon");

            if (!cartIcon) return;

            if (!badge) {
                badge = document.createElement("span");
                badge.id = "cart-count-badge";
                badge.className =
                    "absolute -top-1 -right-1 bg-primary text-white text-[12px] font-bold w-5 h-5 flex items-center justify-center rounded-full shadow";
                cartIcon.appendChild(badge);
            }

            badge.textContent = total;
        }
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll(".add-to-cart-btn").forEach(btn => {
                btn.addEventListener("click", function(e) {
                    e.preventDefault();

                    let form = this.closest("form");
                    let formData = new FormData(form);
                    let productCard = this.closest(".product-card") ?? this.closest(".rounded-xl");
                    let stock = parseInt(productCard.dataset.stock);
                    let quantity = parseInt(form.querySelector('[name="quantity"]').value);


                    if (quantity > stock) {
                        showToast("⚠ Insufficient stock. Remaining: " + stock, true);
                        return;
                    }

                    fetch("{{ route('cart.add') }}", {
                            method: "POST",
                            headers: {
                                "X-CSRF-TOKEN": form.querySelector('input[name="_token"]').value,
                                "X-Requested-With": "XMLHttpRequest",
                            },
                            body: formData
                        })
                        .then(res => res.json())
                        .then(data => {
                            console.log("ADD CART RESPONSE:", data);

                            // ❗Case: Stock không đủ từ server
                            if (data.error === "not_enough_stock") {
                                showToast("⚠ Insufficient stock. Remaining: " + data.available, true);
                                return;
                            }

                            // ✔ Thành công
                            if (data.success) {
                                showToast("Added to cart ✔");
                                updateHeaderCartBadge(data.total);
                            }

                            // ❗Chưa đăng nhập
                            else if (data.error === "unauthenticated") {
                                window.location.href = "{{ url('login') }}";
                            }

                        })
                        .catch(err => console.error(err));
                });
            });
        });
    </script>


    <style>
        @keyframes fade {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade {
            animation: fade .3s ease-out;
        }
    </style>

    <script>
        document.addEventListener("scroll", function() {
            const navbar = document.getElementById("navbar");
            if (window.scrollY > 50) {
                navbar.classList.remove("default");
                navbar.classList.add("scrolled");
            } else {
                navbar.classList.add("default");
                navbar.classList.remove("scrolled");
            }
        });
        document.addEventListener("DOMContentLoaded", () => {

            const backToTopBtn = document.getElementById("backToTopBtn");

            // 🟢 Hiện nút khi scroll xuống 200px
            window.addEventListener("scroll", () => {
                if (window.scrollY > 200) {
                    backToTopBtn.classList.remove("hidden");
                    backToTopBtn.classList.add("flex");
                } else {
                    backToTopBtn.classList.add("hidden");
                    backToTopBtn.classList.remove("flex");
                }
            });

            // 🟢 Cuộn lên đầu trang khi click
            backToTopBtn.addEventListener("click", () => {
                window.scrollTo({
                    top: 0,
                    behavior: "smooth"
                });
            });

        });

        // Click ra ngoài để đóng
        document.addEventListener("click", () => {
            avatarMenu.classList.remove("dropdown-open");
        });

        document.addEventListener("DOMContentLoaded", () => {

            const backToTopBtn = document.getElementById("backToTopBtn");

            window.addEventListener("scroll", () => {
                if (window.scrollY > 200) {
                    backToTopBtn.classList.remove("hidden");
                    backToTopBtn.classList.add("flex");
                } else {
                    backToTopBtn.classList.add("hidden");
                    backToTopBtn.classList.remove("flex");
                }
            });

            backToTopBtn.addEventListener("click", () => {
                window.scrollTo({
                    top: 0,
                    behavior: "smooth"
                });
            });

        });
    </script>
    <button id="backToTopBtn"
        class="hidden fixed bottom-6 left-6 z-50 
           w-12 h-12 rounded-full bg-primary text-white shadow-lg 
           hover:bg-primary/90 transition-all flex items-center justify-center">
        <span class="material-symbols-outlined text-[28px]">arrow_upward</span>
    </button>
    <div class="fixed bottom-24 right-6 flex flex-col gap-3 z-[60]">



        <!-- MESSENGER -->
        <!-- MESSENGER -->
        <a href="https://m.me/Playfulloutings" target="_blank"
            class="w-14 h-14 bg-[#0084FF] rounded-full shadow-lg flex items-center justify-center hover:scale-110 transition-all">
            <img src="https://vutruso.com/wp-content/uploads/2024/08/facebook.svg" alt="Messenger">
        </a>






        <!-- ZALO -->
        <button id="openZaloChat"
            class="w-14 h-14 rounded-full bg-blue-500 text-white flex items-center justify-center 
               shadow-xl hover:scale-110 transition-all">
            <img src="https://upload.wikimedia.org/wikipedia/commons/9/91/Icon_of_Zalo.svg" class="w-8 h-8">
        </button>

        <script>
            document.getElementById('openZaloChat').addEventListener('click', function() {
                // Thay YOUR_ZALO_ID bằng ID/Zalo username của bạn
                window.open('https://zalo.me/0941222916', '_blank');
            });
        </script>

    </div>


    <script>
        document.getElementById("openZaloChat").addEventListener("click", () => {
            document.getElementById("zaloModal").classList.remove("hidden");
            document.getElementById("zaloModal").classList.add("flex");
        });

        function updateTicker() {
            const el = document.getElementById("ticker-text");
            const d = new Date();

            let date = d.toLocaleDateString();
            let time = d.toLocaleTimeString();

            if (!navigator.geolocation) {
                el.textContent = `📅 ${date} | ⏰ ${time} | ❌ Location unavailable`;
                return;
            }

            navigator.geolocation.getCurrentPosition(async pos => {
                    const lat = pos.coords.latitude;
                    const lon = pos.coords.longitude;

                    try {
                        const res = await fetch(
                            `https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lon}&format=json`
                        );
                        const data = await res.json();


                        const loc = data.address.city ??
                            data.address.town ??
                            data.address.village ??
                            data.address.state ??
                            "Unknown location";

                        el.textContent = `📅 ${date} | ⏰ ${time} | 📍 ${loc}`;
                    } catch {
                        el.textContent = `📅 ${date} | ⏰ ${time} | 📍 ${lat.toFixed(2)}, ${lon.toFixed(2)}`;
                    }
                },
                () => {
                    el.textContent = `📅 ${date} | ⏰ ${time} | ❌ Location denied`;
                });
        }

        updateTicker();
        setInterval(updateTicker, 5000);
        document.addEventListener("DOMContentLoaded", () => {
            const gamesBtn = document.getElementById("games-button");
            const gamesMenu = document.getElementById("games-menu");

            let open = false;

            gamesBtn.addEventListener("click", (e) => {
                // Nếu menu đang đóng → mở menu và chặn chuyển trang
                if (!open) {
                    e.preventDefault(); // ❗ Không reload trang
                    open = true;
                    gamesMenu.classList.add("dropdown-open");
                } else {
                    // Nếu menu đang mở → cho phép click đi tới trang Games
                    open = false;
                    gamesMenu.classList.remove("dropdown-open");
                    // Không preventDefault → đi tới route user.game
                }
            });

            // Click ra ngoài đóng menu
            document.addEventListener("click", (e) => {
                if (!gamesBtn.contains(e.target) && !gamesMenu.contains(e.target)) {
                    open = false;
                    gamesMenu.classList.remove("dropdown-open");
                }
            });
        });
    </script>
</body>

</html>