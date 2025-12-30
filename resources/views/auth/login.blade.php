<!DOCTYPE html>
<html class="light" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Login - PlayFullOutings</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com" rel="preconnect" />
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect" />
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;700;900&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght@400&amp;display=swap" rel="stylesheet" />
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#13ec13",
                        "background-light": "#f6f8f6",
                        "background-dark": "#102210",
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
        body {
            font-family: 'Be Vietnam Pro', sans-serif;
        }

        .material-symbols-outlined {
            font-variation-settings:
                'FILL' 0,
                'wght' 400,
                'GRAD' 0,
                'opsz' 24;
            font-size: 24px;
        }
    </style>
</head>

<body class="font-display">
    <div class="relative flex min-h-screen w-full flex-col items-center justify-center bg-background-light dark:bg-background-dark p-4 group/design-root">
        <div class="absolute inset-0 z-0 h-full w-full bg-[url('https://images.unsplash.com/photo-1500964757637-c85e8a162699?q=80&amp;w=3000&amp;auto=format&amp;fit=crop')] bg-cover bg-center backdrop-blur-sm">
            <div class="absolute inset-0 bg-background-light/50 dark:bg-background-dark/70"></div>
        </div>
        <div class="relative z-10 flex w-full max-w-md flex-col items-center rounded-xl bg-white/80 dark:bg-background-dark/80 backdrop-blur-lg shadow-2xl p-6 sm:p-10">

            <div class="w-20 h-20 rounded-full bg-white flex items-center justify-center -mt-4">
                <img src="{{ asset('user/images/logouser.png') }}"
                    class="w-30 h-30 object-contain rounded-full">
            </div>

            <div class="w-full flex flex-col items-center text-center">
                <div class="flex w-full flex-col gap-3">
                    <p class="text-[#111811] dark:text-white text-4xl font-black leading-tight tracking-[-0.033em]">
                        Welcome Back!
                    </p>
                    <p class="text-[#618961] dark:text-gray-300 text-base font-normal leading-normal">
                        Log in to your PlayFullOutings account.
                    </p>
                </div>
            </div>



            <div class="w-full mt-8">


                <form class="flex flex-col gap-6" method="POST" action="{{ route('login.post') }}">
                    @csrf
                    @if(session('success'))
                    <div class="text-green-600">{{ session('success') }}</div>
                    @endif
                    @if($errors->any())
                    <div class="text-red-600">{{ $errors->first() }}</div>
                    @endif
                    <label class="flex flex-col w-full flex-1">
                        <p class="text-[#111811] dark:text-white text-base font-medium leading-normal pb-2">Email or Username</p>
                        <div class="flex w-full flex-1 items-stretch rounded-xl border border-[#dbe6db] dark:border-gray-600 bg-white dark:bg-gray-800 focus-within:ring-2 focus-within:ring-primary/50">
                            <input name="username" class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-l-xl text-[#111811] dark:text-white focus:outline-0 focus:ring-0 border-0 bg-transparent h-14 placeholder:text-[#618961] dark:placeholder:text-gray-400 p-[15px] pr-2 text-base font-normal leading-normal" placeholder="Enter your email or username" value="{{ old('username') }}" required />
                            <div class="text-[#618961] dark:text-gray-400 flex items-center justify-center pr-[15px]">
                                <span class="material-symbols-outlined">person</span>
                            </div>
                        </div>
                    </label>
                    <div class="flex flex-col w-full">
                        <label class="flex flex-col w-full flex-1">
                            <p class="text-[#111811] dark:text-white text-base font-medium leading-normal pb-2">Password</p>
                            <div class="flex w-full flex-1 items-stretch rounded-xl border border-[#dbe6db] dark:border-gray-600 bg-white dark:bg-gray-800 focus-within:ring-2 focus-within:ring-primary/50">
                                <!-- THÊM ID CHO INPUT -->
                                <input id="passwordInput" name="password" type="password" class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-l-xl text-[#111811] dark:text-white focus:outline-0 focus:ring-0 border-0 bg-transparent h-14 placeholder:text-[#618961] dark:placeholder:text-gray-400 p-[15px] pr-2 text-base font-normal leading-normal" placeholder="Enter your password" required />
                                <!-- THÊM ID CHO NÚT VÀ ICON -->
                                <button id="togglePasswordButton" class="text-[#618961] dark:text-gray-400 flex items-center justify-center pr-[15px] cursor-pointer" type="button">
                                    <span id="visibilityIcon" class="material-symbols-outlined">visibility</span>
                                </button>
                            </div>
                        </label>
                        <a class="text-[#618961] dark:text-gray-300 hover:text-primary dark:hover:text-primary text-sm font-normal leading-normal self-end pt-2 underline" href="{{ route('password.request') }}">Forgot Password?</a>
                    </div>
                    <button type="submit" class="flex min-w-[84px] w-full cursor-pointer items-center justify-center overflow-hidden rounded-xl h-12 px-5 bg-primary text-[#111811] text-base font-bold leading-normal tracking-[0.015em] hover:bg-opacity-90 transition-colors">
                        <span class="truncate">Log In</span>
                    </button>
                </form>
            </div>
            <div class="mt-8 text-center">
                <p class="text-[#618961] dark:text-gray-300 text-sm">
                    New to PlayFullOutings? <a class="font-bold text-primary hover:underline" href="{{ route('register') }}">Create an account.</a>
                </p>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const passwordInput = document.getElementById('passwordInput');
            const toggleButton = document.getElementById('togglePasswordButton');
            const visibilityIcon = document.getElementById('visibilityIcon');

            if (toggleButton && passwordInput && visibilityIcon) {
                toggleButton.addEventListener('click', function() {
                    const isPassword = passwordInput.getAttribute('type') === 'password';

                    if (isPassword) {
                        passwordInput.setAttribute('type', 'text');
                        visibilityIcon.textContent = 'visibility_off';
                    } else {
                        passwordInput.setAttribute('type', 'password');
                        visibilityIcon.textContent = 'visibility';
                    }
                });
            }
        });
    </script>

</body>

</html>