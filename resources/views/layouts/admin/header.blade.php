<style>
.header .user-avatar {
    width: 48px;            
    height: 48px;
    object-fit: cover;     
    border-radius: 50%;
    border: 2px solid #fff;  
    box-shadow: 0 0 8px rgba(0,0,0,0.15);
    transition: transform 0.25s ease;
}

.header .user-avatar:hover {
    transform: scale(1.08);
}
</style>

<div class="header-overlay"></div>
    <div class="header d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center">
            <div><h1 class="page-title">@yield('page-title', 'Dashboard')</h1></div>
        </div>
        <div class="d-flex align-items-center">
        
                <!-- User Profile -->
                   <div class="nav-item dropdown">
    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" 
       data-bs-toggle="dropdown" aria-expanded="false">
        <img src="{{ Auth::user() && Auth::user()->photo ? asset('storage/avatars/' . Auth::user()->photo) : asset('images/no_image.jpg') }}" 
        class="user-avatar" alt="avatar">
    </a>

<ul class="dropdown-menu dropdown-menu-end mt-2">
    <li>
        <h6 class="dropdown-header">Settings</h6>
    </li>

    <li>
        <a class="dropdown-item d-flex align-items-center gap-2"
           href="{{ route('admin.profile', auth()->user()->id) }}">
            <i class="fa-regular fa-user"></i>
            <span>Profile Settings</span>
        </a>
    </li>

    <li>
        <a class="dropdown-item d-flex align-items-center gap-2"
           href="{{ url('user/dashboard') }}">
            <i class="fa-solid fa-house"></i>
            <span>Home</span>
        </a>
    </li>

    <li>
        <hr class="dropdown-divider">
    </li>

    <li>
        <a class="dropdown-item d-flex align-items-center gap-2 text-danger"
           href="{{ route('logout') }}"
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="fa-solid fa-right-from-bracket"></i>
            <span>Sign out</span>
        </a>
    </li>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
    </form>
</ul>

</div>


            </ul>
        </div>
    </div>