<!DOCTYPE html>

<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta content="width=device-width, initial-scale=1.0" name="viewport" />
  <title>Register - PlayFullOutings</title>
  <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
  <link href="https://fonts.googleapis.com" rel="preconnect" />
  <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect" />
  <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;700;900&amp;display=swap" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />
  <script>
    tailwind.config = {
      darkMode: "class",
      theme: {
        extend: {
          colors: {
            "primary": "#4CAF50",
            "secondary": "#FFC107",
            "background-light": "#F5F5F5",
            "background-dark": "#1a1a1a",
            "text-light": "#333333",
            "text-dark": "#F5F5F5",
            "error": "#D32F2F",
            "success": "#388E3C"
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
      font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
      vertical-align: middle;
    }
  </style>
</head>

<body class="bg-background-light dark:bg-background-dark font-display text-text-light dark:text-text-dark">
  <div class="relative flex min-h-screen w-full flex-col overflow-x-hidden">
    <div class="flex-grow">
      <header class="absolute left-0 top-0 z-10 w-full px-4 py-5 sm:px-10 lg:px-20">
        <div class="flex items-center gap-4 text-text-light dark:text-text-dark">
          <div class="h-8 w-8 text-primary">
          </div>
          <div class="w-16 h-16 rounded-full bg-white flex items-center justify-center">
            <img src="{{ asset('user/images/logouser.png') }}"
              class="w-14 h-14 object-contain rounded-full">
          </div>
          <!-- <h2 class="text-xl font-bold tracking-tight">PlayFullOutings</h2> -->
        </div>
      </header>
      <main class="flex min-h-screen">
        <div class="hidden lg:block lg:w-1/2">
          <div class="h-full w-full bg-cover bg-center" data-alt="A cheerful picnic scene with a woven blanket, basket, and food laid out on a lush green lawn." style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuA4QCtV0rjVu-q-vqxAZu6nybKCTxLvgsQw8wy1IAO6qfU5Vov6TdpPZYl0Icy2qeoF8eVDKGSG28jNZboE-wfgu4ZwUYdqNlQ-Ptnru_VPAJSmXjoc8f2yI-a35HbgPPJh7pqBgFWaJjYUjErQ86M29cGcbS_XMA_zb44uD_c4vVGCjGSJFOrNWkyQZmv2FcgGaPDFjOxIv3n6KB-EbmovwGNJKROhxY5CF6gpLmC_QA7JPBoru_9yBs77bnavTGuz8ZsBk91rvU8');"></div>
        </div>
        <div class="flex w-full items-center justify-center p-6 lg:w-1/2 sm:p-12">
          <div class="w-full max-w-md space-y-8">
            <div>
              <h1 class="text-4xl font-black tracking-tighter text-text-light dark:text-text-dark">Create Your Account</h1>
              <p class="mt-2 text-base text-gray-600 dark:text-gray-400">Sign up to discover new adventures and games.</p>
            </div>
            <form class="space-y-6" method="POST" action="{{ route('register.post') }}">
              @csrf
              @if($errors->any())
              <div class="text-error">{{ $errors->first() }}</div>
              @endif
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300" for="username">Username</label>
                <div class="mt-1">
                  <input class="form-input block w-full rounded-lg border-gray-300 bg-white dark:bg-gray-800 dark:border-gray-600 dark:text-white dark:placeholder-gray-400 shadow-sm focus:border-primary focus:ring-primary sm:text-sm h-12 px-4" id="username" name="username" placeholder="Choose a username" required type="text" value="{{ old('username') }}" />
                </div>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300" for="email">Email Address</label>
                <div class="mt-1">
                  <input autocomplete="email" class="form-input block w-full rounded-lg border-gray-300 bg-white dark:bg-gray-800 dark:border-gray-600 dark:text-white dark:placeholder-gray-400 shadow-sm focus:border-primary focus:ring-primary sm:text-sm h-12 px-4" id="email" name="email" placeholder="Enter your email address" required type="email" value="{{ old('email') }}" />
                </div>
                <!-- Example of an error message -->
                <p class="mt-2 text-xs text-error {{ $errors->has('email') ? '' : 'hidden' }}">{{ $errors->first('email') }}</p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300" for="password">Password</label>
                <div class="relative mt-1">
                  <input autocomplete="new-password" class="form-input block w-full rounded-lg border-gray-300 bg-white dark:bg-gray-800 dark:border-gray-600 dark:text-white dark:placeholder-gray-400 shadow-sm focus:border-primary focus:ring-primary sm:text-sm h-12 px-4 pr-10" id="password" name="password" placeholder="Enter your password" required type="password" />
                  <button aria-label="Toggle password visibility" class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-500 dark:text-gray-400" type="button" onclick="togglePassword('password', this)">
                    <span class="material-symbols-outlined text-xl">visibility_off</span>
                  </button>
                </div>
                <div class="mt-2 flex items-center gap-2">
                  <div class="h-1 flex-1 rounded-full bg-gray-200 dark:bg-gray-700">
                    <div id="password-strength-bar" class="h-1 rounded-full bg-error" style="width:25%"></div>
                  </div>
                  <p id="password-strength-text" class="text-xs text-gray-500 dark:text-gray-400">Weak</p>
                </div>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300" for="confirm-password">Confirm Password</label>
                <div class="relative mt-1">
                  <input autocomplete="new-password" class="form-input block w-full rounded-lg border-gray-300 bg-white dark:bg-gray-800 dark:border-gray-600 dark:text-white dark:placeholder-gray-400 shadow-sm focus:border-primary focus:ring-primary sm:text-sm h-12 px-4 pr-10" id="confirm-password" name="password_confirmation" placeholder="Confirm your password" required type="password" />
                  <button aria-label="Toggle confirm password visibility" class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-500 dark:text-gray-400" type="button" onclick="togglePassword('confirm-password', this)">
                    <span class="material-symbols-outlined text-xl">visibility_off</span>
                  </button>
                </div>
                <!-- Password match message (updated dynamically, announced to screen readers) -->
                <p id="password-match-text" class="mt-2 text-xs hidden" role="status" aria-live="polite" aria-atomic="true"></p>
              </div>
              <div class="flex items-center">
                <input class="h-4 w-4 rounded border-gray-300 text-primary focus:ring-primary" id="terms" name="terms" type="checkbox" {{ old('terms') ? 'checked' : '' }} />
                <label class="ml-2 block text-sm text-gray-900 dark:text-gray-200" for="terms">I agree to the <a class="font-medium text-primary hover:text-primary/80" href="#">Terms and Conditions</a></label>
              </div>
              <div>
                <button class="flex w-full justify-center rounded-lg border border-transparent bg-primary px-4 py-3 text-sm font-bold text-white shadow-sm hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 dark:focus:ring-offset-background-dark" type="submit">Register</button>
              </div>
            </form>
            <div class="relative">
              <div aria-hidden="true" class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-gray-300 dark:border-gray-600"></div>
              </div>
              <div class="relative flex justify-center text-sm">
                <span class="bg-background-light dark:bg-background-dark px-2 text-gray-500 dark:text-gray-400">Or continue with</span>
              </div>
            </div>
            <div class="grid grid-cols-2 gap-3">
              <div>
                <a class="inline-flex w-full justify-center rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-4 py-3 text-sm font-medium text-gray-500 dark:text-gray-300 shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700" href="{{ route('oauth.google') }}">
                  <img class="h-5 w-5" data-alt="Google logo" src="https://cdn.pixabay.com/photo/2016/04/13/14/27/google-chrome-1326908_1280.png" />
                </a>
              </div>
              <div>
                <a class="inline-flex w-full justify-center rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-4 py-3 text-sm font-medium text-gray-500 dark:text-gray-300 shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700" href="#">
                  <img class="h-5 w-5" data-alt="Facebook logo" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCRIwV_4fKMfCcq5cKAGQ8CsjMuw8cnerRzknVbz8sFYz7ujWUDqun-Z_CVquDWvqw_0n29R34HU6s-7etI_HSi-YJkv3b_DMwKSufz0LS6-r0PI6oKQ8oGT8Kd1u-lJTWxnzFccmefsM0LGSvJC7I5zdBRukJlZSvOPgpleun2PLWOwm1YcXpeJu243J1yHJP0KaY3B7ipUZzxvJ_gAH1Ziqk6Uxa9HOY3V55aTio9TJosIo64EHNhvKAsPtcEDt0rC7a2uuTXviU" />
                </a>
              </div>
            </div>
            <p class="text-center text-sm text-gray-600 dark:text-gray-400">
              Already have an account?
              <a class="font-medium text-primary hover:text-primary/80" href="{{ route('login') }}">Login</a>
            </p>
          </div>
        </div>
      </main>
    </div>
  </div>
</body>

</html>

<script>
  function togglePassword(id, btn) {
    const input = document.getElementById(id);
    if (!input) return;
    if (input.type === 'password') {
      input.type = 'text';
      btn.querySelector('.material-symbols-outlined').textContent = 'visibility';
    } else {
      input.type = 'password';
      btn.querySelector('.material-symbols-outlined').textContent = 'visibility_off';
    }
  }

  // Password strength and match checks
  function evaluatePasswordStrength(pw) {
    if (!pw) return 0;
    let score = 0;
    // length
    if (pw.length >= 8) score += 40;
    else score += Math.max(0, (pw.length / 8) * 40);
    // variety: lowercase, uppercase, digits, special
    const checks = [/[a-z]/, /[A-Z]/, /[0-9]/, /[^A-Za-z0-9]/];
    let variety = 0;
    checks.forEach((r) => {
      if (r.test(pw)) variety++;
    });
    score += variety * 15; // up to +60 (but length caps)
    if (score > 100) score = 100;
    return Math.round(score);
  }

  function updateStrengthUI() {
    const pw = document.getElementById('password').value;
    const bar = document.getElementById('password-strength-bar');
    const text = document.getElementById('password-strength-text');
    if (!bar || !text) return;
    const score = evaluatePasswordStrength(pw);
    bar.style.width = score + '%';
    // reset classes
    bar.classList.remove('bg-error', 'bg-secondary', 'bg-success');
    if (score <= 30) {
      bar.classList.add('bg-error');
      text.textContent = 'Weak';
      text.className = 'text-xs text-gray-500 dark:text-gray-400';
    } else if (score <= 70) {
      bar.classList.add('bg-secondary');
      text.textContent = 'Fair';
      text.className = 'text-xs text-gray-500 dark:text-gray-400';
    } else {
      bar.classList.add('bg-success');
      text.textContent = 'Strong';
      text.className = 'text-xs text-gray-500 dark:text-gray-400';
    }
  }

  function updateMatchUI() {
    const pw = document.getElementById('password').value;
    const cpw = document.getElementById('confirm-password').value;
    const msg = document.getElementById('password-match-text');
    if (!msg) return;
    if (!cpw) {
      msg.classList.add('hidden');
      return;
    }
    if (pw === cpw) {
      msg.classList.remove('hidden');
      msg.textContent = 'Passwords match.';
      msg.classList.remove('text-error');
      msg.classList.add('text-success');
    } else {
      msg.classList.remove('hidden');
      msg.textContent = 'Passwords do not match.';
      msg.classList.remove('text-success');
      msg.classList.add('text-error');
    }
  }

  document.addEventListener('DOMContentLoaded', function() {
    const pwInput = document.getElementById('password');
    const cpwInput = document.getElementById('confirm-password');
    if (pwInput) pwInput.addEventListener('input', function() {
      updateStrengthUI();
      updateMatchUI();
    });
    if (cpwInput) cpwInput.addEventListener('input', updateMatchUI);
    // initial run
    updateStrengthUI();
    updateMatchUI();
  });
</script>