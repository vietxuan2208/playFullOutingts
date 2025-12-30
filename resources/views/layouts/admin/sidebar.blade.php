<style>

.sidebar-menu li.open > .sidebar-submenu {
    display: block !important;
}

.sidebar-submenu {
    display: none !important;
}
</style>

<div class="sidebar">
    <div class="sidebar-header">
        <img src="{{asset('/admin/assets/images/logo.png')}}" style="width: 80%;">
    </div>

    <div class="sidebar-body custom-scrollbar">
        <ul class="sidebar-menu">

            <li class="sidebar-label">Main</li>

            <!-- DASHBOARD -->
            <li>
                <a href="{{ route('admin.dashboard') }}" 
                   class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fa-solid fa-gauge"></i>
                    <p>Dashboard</p>
                </a>
            </li>

            <!-- GAME -->
            <li>
                <a href="{{ route('admin.game') }}" 
                   class="sidebar-link {{ request()->routeIs('admin.game*') ? 'active' : '' }}">
                    <i class="fa-solid fa-gamepad"></i>
                    <p>Game</p>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.contact') }}" 
                   class="sidebar-link {{ request()->routeIs('admin.contact*') ? 'active' : '' }}">
                    <i class="fa-solid fa-address-book"></i>
                    <p>Contact</p>
                </a>
            </li>

            <!-- CATEGORY -->
            <li>
                <a href="{{ route('admin.category') }}" 
                   class="sidebar-link {{
                        request()->routeIs('admin.category') ||
                        request()->routeIs('admin.category.*')
                        ? 'active' : '' }}">
                    <i class="fa-solid fa-list"></i>
                    <p>Category</p>
                </a>
            </li>

            <!-- PRODUCTS -->
            <li>
                <a href="{{ route('product_admin') }}" 
                   class="sidebar-link {{ request()->routeIs('product_admin*') ? 'active' : '' }}">
                    <i class="fa-solid fa-box"></i>
                    <p>Products</p>
                </a>
            </li>

            <!-- ORDER (submenu) -->
            <li class="{{ request()->routeIs('orderPending_admin') ||
                          request()->routeIs('orderShipped_admin') ||
                          request()->routeIs('orderDelivered_admin') ||
                          request()->routeIs('orderCanceled_admin') 
                          ? 'open' : '' }}">

                <a href="#" class="sidebar-link submenu-parent
                    {{ request()->routeIs('orderPending_admin') ||
                       request()->routeIs('orderShipped_admin') ||
                       request()->routeIs('orderDelivered_admin') ||
                       request()->routeIs('orderCanceled_admin') 
                       ? 'active' : '' }}">
                    <i class="fa-solid fa-cart-shopping"></i>
                    <p>Order <i class="fa-solid fa-chevron-right right-icon"></i></p>
                </a>

                <ul class="sidebar-submenu">
                    <li><a href="{{ route('orderPending_admin') }}" class="submenu-link {{ request()->routeIs('orderPending_admin') ? 'active' : '' }}">
                        <i class="fa-solid fa-clock"></i><p>Pending</p></a></li>

                    <li><a href="{{ route('orderShipped_admin') }}" class="submenu-link {{ request()->routeIs('orderShipped_admin') ? 'active' : '' }}">
                        <i class="fa-solid fa-truck-fast"></i><p>Shipped</p></a></li>

                    <li><a href="{{ route('orderDelivered_admin') }}" class="submenu-link {{ request()->routeIs('orderDelivered_admin') ? 'active' : '' }}">
                        <i class="fa-solid fa-circle-check"></i><p>Delivered</p></a></li>

                    <li><a href="{{ route('orderCanceled_admin') }}" class="submenu-link {{ request()->routeIs('orderCanceled_admin') ? 'active' : '' }}">
                        <i class="fa-solid fa-ban"></i><p>Canceled</p></a></li>
                </ul>
            </li>

            <!-- CATEGORY LOCATION -->
            <li>
                <a href="{{ route('admin.categoryLocation') }}" 
                   class="sidebar-link {{ request()->routeIs('admin.categoryLocation*') ? 'active' : '' }}">
                    <i class="fa-solid fa-layer-group"></i>
                    <p>Category Location</p>
                </a>
            </li>

            <!-- USERS -->
            <li>
                <a href="{{ route('admin.user') }}" 
                   class="sidebar-link {{ request()->routeIs('admin.user*') ? 'active' : '' }}">
                    <i class="fa-solid fa-users"></i>
                    <p>Accounts</p>
                </a>
            </li>

            <!-- MATERIAL -->
            <li>
                <a href="{{ route('admin.material') }}" 
                   class="sidebar-link {{ request()->routeIs('admin.material*') ? 'active' : '' }}">
                    <i class="fa-solid fa-screwdriver-wrench"></i>
                    <p>Material</p>
                </a>
            </li>

            <!-- ITINERARY -->
            <li>
                <a href="{{ route('admin.itineraries') }}" 
                   class="sidebar-link {{ request()->routeIs('admin.itineraries*') ? 'active' : '' }}">
                    <i class="fa-solid fa-map"></i>
                    <p>Itinerary</p>
                </a>
            </li>

            <!-- LOCATION -->
            <li>
                <a href="{{ route('admin.locations') }}" 
                   class="sidebar-link {{ request()->routeIs('admin.locations*') ? 'active' : '' }}">
                    <i class="fa-solid fa-location-dot"></i>
                    <p>Location</p>
                </a>
            </li>

            <!-- BLOG -->
            <li>
                <a href="{{ route('admin.blog.index') }}" 
                   class="sidebar-link {{ request()->routeIs('admin.blog*') ? 'active' : '' }}">
                    <i class="fa-solid fa-blog"></i>
                    <p>Blogs</p>
                </a>
            </li>

            <li class="{{ 
                    request()->routeIs('admin.trashItineraries') ||
                    request()->routeIs('admin.trashCategory') ||
                    request()->routeIs('admin.trashGame') ||
                    request()->routeIs('admin.trashLocation') ||
                    request()->routeIs('admin.trashCategoryLocation') ||
                    request()->routeIs('admin.trashMaterial')
                        ? 'open' 
                        : '' }}">

                <a href="#" class="sidebar-link submenu-parent
                    {{ request()->routeIs('admin.trashItineraries') ||
                       request()->routeIs('admin.trashCategory') ||
                       request()->routeIs('admin.trashGame') ||
                       request()->routeIs('admin.trashLocation') ||
                       request()->routeIs('admin.trashCategoryLocation') ||
                       request()->routeIs('admin.trashMaterial') ||
                          request()->routeIs('admin.trashProduct')
                       ? 'active'
                       : '' }}">
                    <i class="fa-solid fa-trash"></i>
                    <p>Trash <i class="fa-solid fa-chevron-right right-icon"></i></p>
                </a>

                <ul class="sidebar-submenu">
                    <li><a href="{{ route('admin.trashItineraries') }}" class="submenu-link {{ request()->routeIs('admin.trashItineraries') ? 'active' : '' }}">
                        <i class="fa-solid fa-map"></i><p>Itinerary</p></a></li>

                    <li><a href="{{ route('admin.trashCategory') }}" class="submenu-link {{ request()->routeIs('admin.trashCategory') ? 'active' : '' }}">
                        <i class="fa-solid fa-list"></i><p>Category</p></a></li>

                    <li><a href="{{ route('admin.trashGame') }}" class="submenu-link {{ request()->routeIs('admin.trashGame') ? 'active' : '' }}">
                        <i class="fa-solid fa-gamepad"></i><p>Game</p></a></li>

                    <li><a href="{{ route('admin.trashLocation') }}" class="submenu-link {{ request()->routeIs('admin.trashLocation') ? 'active' : '' }}">
                        <i class="fa-solid fa-location-dot"></i><p>Location</p></a></li>

                    <li><a href="{{ route('admin.trashCategoryLocation') }}" class="submenu-link {{ request()->routeIs('admin.trashCategoryLocation') ? 'active' : '' }}">
                        <i class="fa-solid fa-layer-group"></i><p>Category Location</p></a></li>

                    <li><a href="{{ route('admin.trashMaterial') }}" class="submenu-link {{ request()->routeIs('admin.trashMaterial') ? 'active' : '' }}">
                        <i class="fa-solid fa-screwdriver-wrench"></i><p>Material</p></a></li>
                    <li><a href="{{ route('admin.trashProduct') }}" class="submenu-link {{ request()->routeIs('admin.trashProduct') ? 'active' : '' }}">
                        <i class="fa-solid fa-box"></i><p>Product</p></a></li>

                </ul>
            </li>

        </ul>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const parents = document.querySelectorAll(".submenu-parent");

    parents.forEach(parent => {
        parent.addEventListener("click", function (e) {
            e.preventDefault();

            const li = this.parentElement;

            if (li.classList.contains("open")) {
                li.classList.remove("open");
                return;
            }
            li.classList.add("open");
        });
    });
});
</script>

