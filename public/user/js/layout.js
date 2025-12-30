// nav
function setupDropdown(buttonId, menuId) {
    const button = document.getElementById(buttonId);
    const menu = document.getElementById(menuId);
    let isOpen = false;

    // Click mở / đóng menu
    button.addEventListener("click", function (e) {
        e.stopPropagation();
        isOpen = !isOpen;
        if (isOpen) menu.classList.add("dropdown-open");
        else menu.classList.remove("dropdown-open");
    });

    // Nếu click ra ngoài → đóng
    document.addEventListener("click", function (e) {
        if (!button.contains(e.target) && !menu.contains(e.target)) {
            menu.classList.remove("dropdown-open");
            isOpen = false;
        }
    });

    // Giữ menu mở khi hover chuột vào
    menu.addEventListener("mouseenter", function () {
        menu.classList.add("dropdown-open");
    });

    // Khi rời khỏi menu + rời khỏi nút → ẩn
    menu.addEventListener("mouseleave", function (e) {
        if (!button.matches(":hover")) {
            menu.classList.remove("dropdown-open");
            isOpen = false;
        }
    });

    // Khi rời khỏi nút → kiểm tra có hover vào menu không
    button.addEventListener("mouseleave", function () {
        setTimeout(() => {
            if (!menu.matches(":hover")) {
                menu.classList.remove("dropdown-open");
                isOpen = false;
            }
        }, 100);
    });
}

// Áp dụng cho Games và Avatar
document.addEventListener("DOMContentLoaded", function () {
    setupDropdown("games-button", "games-menu");
    setupDropdown("avatar-button", "avatar-menu");
});

// banner
function slider() {
    return {
        current: 0,
        banners: [
            "https://vj360.vn/wp-content/uploads/2021/11/8-meo-huu-ich-de-co-mot-buoi-chup-anh-picnic-hoan-hao.jpg",
            "https://i.pinimg.com/736x/7f/6f/6f/7f6f6fd1315cba78d841baee9286f06b.jpg",
            "https://i.pinimg.com/1200x/e8/65/75/e86575435f18e50136452d2d1f92621d.jpg",
            "https://i.pinimg.com/1200x/96/9c/91/969c91d99064fbbed81a3868ce624ac9.jpg"
        ],


        next() {
            this.current = (this.current + 1) % this.banners.length;
        },
        prev() {
            this.current = (this.current - 1 + this.banners.length) % this.banners.length;
        },

        // Auto slide
        init() {
            setInterval(() => {
                this.next();
            }, 4000); // 4 giây chuyển banner
        }
    }
    
}
// Khi bấm đăng ký hoặc đăng nhập → ẩn nút và hiện avatar
document.addEventListener("DOMContentLoaded", function () {
    const btnRegister = document.getElementById('btn-register');
    const btnLogin = document.getElementById('btn-login');
    const authButtons = document.getElementById('auth-buttons');
    const avatarDropdown = document.getElementById('avatar-dropdown');

    function loginUser() {
        authButtons.classList.add('hidden'); // ẩn 2 nút
        avatarDropdown.classList.remove('hidden'); // hiện avatar
    }


    if (btnRegister) btnRegister.addEventListener('click', loginUser);
    if (btnLogin) btnLogin.addEventListener('click', loginUser);
});


