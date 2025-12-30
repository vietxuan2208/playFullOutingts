@extends('admin.dashboard')
@section('page-title', 'Profile Settings')

@section('content')

<style>
/* Avatar */
.profile-avatar {
    width: 140px;
    height: 140px;
    border-radius: 50%;
    object-fit: cover;
    box-shadow: 0 4px 16px rgba(0,0,0,0.15);
    border: 4px solid #fff;
    transition: 0.25s ease;
}
.profile-avatar:hover {
    transform: scale(1.05);
}

/* Section Card */
.setting-card {
    border-radius: 16px;
    border: none;
    box-shadow: 0 8px 26px rgba(0,0,0,0.06);
}

/* Title */
.section-title {
    font-weight: 700;
    font-size: 20px;
    border-left: 4px solid #007bff;
    padding-left: 12px;
}

/* Input */
.form-control {
    border-radius: 10px;
    padding: 10px 14px;
}

/* Buttons */
.btn-custom {
    padding: 10px 18px;
    border-radius: 10px;
}
.toggle-password {
    position: absolute;
    right: 12px;
    top: 39px;
    cursor: pointer;
    font-size: 18px;
    color: #666;
    transition: 0.2s;
}
.toggle-password:hover {
    color: #000;
}


</style>

<div class="main-content">

    <div class="container mt-4">

        {{-- ROW WRAPPER --}}
        <div class="row g-4">

            {{-- LEFT SIDE: AVATAR + BASIC INFO --}}
            <div class="col-lg-4">
                <div class="card setting-card p-4 text-center">

                    <div class="mb-3">
                        <img id="avatarPreview"
                             class="profile-avatar"
                             src="{{ $user->photo ? asset('storage/avatars/'.$user->photo) : asset('images/no_image.jpg') }}">
                    </div>

                    {{-- Change Avatar --}}
                    <form id="photoForm" action="{{ route('admin.profile.updatePhoto', $user->id) }}" 
                        method="POST" enctype="multipart/form-data">
                        @csrf

                        <input type="file" name="photo" id="photoInput" class="d-none" accept="image/*">

                        <button type="button" class="btn btn-outline-primary btn-custom" id="changePhotoBtn">
                            <i class="fa-regular fa-image"></i> Change Photo
                        </button>
                    </form>


                    <hr class="my-4">

                    <h4 class="fw-bold">{{ $user->username }}</h4>
                    <p class="text-muted mb-1"><i class="fa-regular fa-envelope"></i> {{ $user->email }}</p>
                    <p class="text-muted mb-1"><i class="fa-solid fa-phone"></i> {{ $user->phone ?? '-' }}</p>
                    <p class="text-muted mb-1"><i class="fa-regular fa-calendar"></i> {{ $user->birthday ?? '-' }}</p>
                </div>
            </div>

            {{-- RIGHT SIDE: FORMS --}}
            <div class="col-lg-8">

                {{-- SUCCESS --}}
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                {{-- ERRORS --}}
                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show">
                        <ul class="mb-0">
                            @foreach($errors->all() as $e)
                                <li>{{ $e }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                {{-- EDIT PROFILE --}}
                <div class="card setting-card p-4 mb-4">
                    <div class="section-title mb-3">Edit Profile</div>

                    <form action="{{ route('admin.profile.update', $user->id) }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="fw-semibold">Username</label>
                            <input type="text" class="form-control" name="username"
                                   value="{{ old('username', $user->username) }}">
                        </div>

                        <div class="mb-3">
                            <label class="fw-semibold">Full Name</label>
                            <input type="text" class="form-control" name="fullname"
                                   value="{{ old('fullname', $user->fullname) }}">
                        </div>

                        <div class="mb-3">
                            <label class="fw-semibold">Email</label>
                            <input type="email" class="form-control" name="email"
                                   value="{{ $user->email }}" readonly>
                        </div>

                        <div class="mb-3">
                            <label class="fw-semibold">Phone Number</label>
                            <input type="text" class="form-control" name="phone"
                                   value="{{ old('phone', $user->phone) }}">
                        </div>
                        <div class="mb-3">
                            <label class="fw-semibold">Birth Day</label>
                            <input
                                type="date"
                                name="birthday"
                                id="birthday"
                                value="{{ $user->birthday }}"
                                class="form-control"
                            >

                            <small id="birthday-error" class="text-danger d-none"></small>


                        </div>
                        <div class="mb-3">
                            <label class="fw-semibold">Gender</label>
                            <input type="text" class="form-control" name="gender"
                                   value="{{ old('gender', $user->gender) }}">
                        </div>

                        <button
                            type="submit"
                            id="saveProfileBtn"
                            class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary/80 transition">
                            Save
                        </button>

                    </form>
                </div>

                {{-- CHANGE PASSWORD --}}
                <div class="card setting-card p-4">
                    <div class="section-title mb-3">Change Password</div>

                    <form action="{{ route('admin.profile.changePassword', $user->id) }}" method="POST">
                        @csrf

                        <div class="mb-3 position-relative">
                            <label class="form-label">Current Password</label>
                            <input type="password" class="form-control password-field" name="currentPassword" required>
                            <i class="fa-solid fa-eye toggle-password"></i>
                        </div>

                        <div class="mb-3 position-relative">
                            <label class="form-label">New Password</label>
                            <input type="password" class="form-control password-field" name="newPassword" required>
                            <i class="fa-solid fa-eye toggle-password"></i>
                        </div>

                        <div class="mb-3 position-relative">
                            <label class="form-label">Confirm New Password</label>
                            <input type="password" class="form-control password-field" name="newPassword_confirmation" required>
                            <i class="fa-solid fa-eye toggle-password"></i>
                        </div>


                        <button class="btn btn-warning btn-custom" type="submit">
                            <i class="fa-solid fa-key"></i> Update Password
                        </button>

                    </form>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('photoInput').addEventListener('change', function(){
    if(this.files && this.files[0]){
        document.getElementById('avatarPreview').src = URL.createObjectURL(this.files[0]);
    }
});

document.getElementById('changePhotoBtn').addEventListener('click', function () {
    document.getElementById('photoInput').click();
});

document.getElementById('photoInput').addEventListener('change', function () {
    if (this.files && this.files[0]) {

        // Preview ngay
        document.getElementById('avatarPreview').src = URL.createObjectURL(this.files[0]);

        // Upload tự động
        document.getElementById('photoForm').submit();
    }
});
document.querySelectorAll('.toggle-password').forEach(icon => {
    icon.addEventListener('click', function () {
        let input = this.previousElementSibling;

        if (input.type === "password") {
            input.type = "text";
            this.classList.remove("fa-eye");
            this.classList.add("fa-eye-slash");
        } else {
            input.type = "password";
            this.classList.remove("fa-eye-slash");
            this.classList.add("fa-eye");
        }
    });
});
document.addEventListener('DOMContentLoaded', function () {
    const birthdayInput = document.getElementById('birthday');
    const errorText = document.getElementById('birthday-error');
    const saveBtn = document.getElementById('saveProfileBtn');

    if (!birthdayInput || !saveBtn) return;

    function validateAge() {
        if (!birthdayInput.value) {
            errorText.classList.add('d-none');
            birthdayInput.classList.remove('is-invalid');
            saveBtn.disabled = false;
            return true;
        }

        const birthday = new Date(birthdayInput.value);
        const today = new Date();

        let age = today.getFullYear() - birthday.getFullYear();
        const m = today.getMonth() - birthday.getMonth();

        if (m < 0 || (m === 0 && today.getDate() < birthday.getDate())) {
            age--;
        }

        if (age < 16) {
            errorText.textContent = 'You must be at least 16 years old.';
            errorText.classList.remove('d-none');
            birthdayInput.classList.add('is-invalid');

            // 🚫 CHẶN SAVE
            saveBtn.disabled = true;
            return false;
        }

        // ✅ HỢP LỆ
        errorText.classList.add('d-none');
        birthdayInput.classList.remove('is-invalid');
        saveBtn.disabled = false;
        return true;
    }

    birthdayInput.addEventListener('change', validateAge);

    // Check ngay khi load (nếu đã có birthday)
    validateAge();
});
</script>

@endsection
