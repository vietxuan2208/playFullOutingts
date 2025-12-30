<!DOCTYPE html>
<html class="light" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Set New Password - PlayFullOutings</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com" rel="preconnect" />
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect" />
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;700;900&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght@400&display=swap" rel="stylesheet" />

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
                },
            },
        };
    </script>

    <style>
        body {
            font-family: 'Be Vietnam Pro', sans-serif;
        }

        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            font-size: 24px;
        }
    </style>
</head>

<body class="font-display">

    <div class="relative flex min-h-screen w-full flex-col items-center justify-center bg-background-light dark:bg-background-dark p-4">

        <div class="absolute inset-0 z-0 h-full w-full bg-[url('https://images.unsplash.com/photo-1500964757637-c85e8a162699?q=80&w=3000&auto=format&fit=crop')] bg-cover bg-center">
            <div class="absolute inset-0 bg-background-light/50 dark:bg-background-dark/70"></div>
        </div>

        <div class="relative z-10 flex w-full max-w-md flex-col items-center rounded-xl bg-white/80 dark:bg-background-dark/80 backdrop-blur-lg shadow-2xl p-6 sm:p-10">

            <div class="flex flex-col items-center gap-2 mb-8">
                <span class="material-symbols-outlined text-5xl text-primary">grass</span>
                <h1 class="text-2xl font-bold text-[#111811] dark:text-white">PlayFullOutings</h1>
            </div>

            <div class="w-full">
                <p class="text-[#111811] dark:text-white text-4xl font-black">Set a New Password</p>
                <p class="text-[#618961] dark:text-gray-300 text-base mt-1">
                    Your new password must be different from previously used passwords.
                </p>
            </div>

            {{-- Hiển thị lỗi --}}
            @if($errors->any())
            <div class="text-red-600 mt-4 text-sm">{{ $errors->first() }}</div>
            @endif

            {{-- FORM --}}
            <form method="POST" action="{{ route('password.reset.post') }}" class="w-full mt-8 flex flex-col gap-6">
                @csrf

                {{-- NEW PASSWORD --}}
                <label class="flex flex-col w-full">
                    <p class="text-[#111811] dark:text-white font-medium pb-2">New Password</p>
                    <div class="flex items-stretch relative rounded-xl border border-[#dbe6db] dark:border-gray-600 bg-white dark:bg-gray-800 focus-within:ring-2 focus-within:ring-primary/50">
                        <input
                            id="new-password"
                            name="password"
                            type="password"
                            required
                            placeholder="Enter your new password"
                            class="flex w-full rounded-xl bg-transparent border-0 focus:ring-0 h-14 p-[15px] text-[#111811] dark:text-white pr-12" />
                        <!-- NÚT TOGGLE -->
                        <button
                            aria-label="Toggle new password visibility"
                            type="button"
                            onclick="togglePassword('new-password', this)"
                            class="absolute inset-y-0 right-0 flex items-center pr-4 text-[#618961] dark:text-gray-400">
                            <span class="material-symbols-outlined text-2xl">visibility_off</span>
                        </button>
                    </div>
                </label>

                {{-- CONFIRM PASSWORD --}}
                <label class="flex flex-col w-full">
                    <p class="text-[#111811] dark:text-white font-medium pb-2">Confirm Password</p>
                    <div class="flex items-stretch relative rounded-xl border border-[#dbe6db] dark:border-gray-600 bg-white dark:bg-gray-800 focus-within:ring-2 focus-within:ring-primary/50">
                        <input
                            id="confirm-password"
                            name="password_confirmation"
                            type="password"
                            required
                            placeholder="Confirm your new password"
                            class="flex w-full rounded-xl bg-transparent border-0 focus:ring-0 h-14 p-[15px] text-[#111811] dark:text-white pr-12" />
                        <!-- NÚT TOGGLE -->
                        <button
                            aria-label="Toggle confirm password visibility"
                            type="button"
                            onclick="togglePassword('confirm-password', this)"
                            class="absolute inset-y-0 right-0 flex items-center pr-4 text-[#618961] dark:text-gray-400">
                            <span class="material-symbols-outlined text-2xl">visibility_off</span>
                        </button>
                    </div>
                </label>

                {{-- SUBMIT --}}
                <button class="h-12 rounded-xl bg-primary text-[#111811] font-bold hover:bg-opacity-90 transition">
                    Change Password
                </button>
            </form>

            <div class="mt-8 text-center">
                <p class="text-[#618961] dark:text-gray-300 text-sm">
                    Remember your password?
                    <a href="{{ route('login') }}" class="font-bold text-primary hover:underline">Return to Login.</a>
                </p>
            </div>

        </div>
    </div>

    {{-- JS TOGGLE PASSWORD --}}
    <script>
        function togglePassword(id, btn) {
            const input = document.getElementById(id);
            const icon = btn.querySelector("span");
            if (input.type === "password") {
                input.type = "text";
                icon.textContent = "visibility";
            } else {
                input.type = "password";
                icon.textContent = "visibility_off";
            }
        }
    </script>

</body>

</html>
