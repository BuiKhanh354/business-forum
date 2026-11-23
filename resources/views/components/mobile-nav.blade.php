<!-- ======================================================================
     MEDIAN UI MOBILE BOTTOM NAVIGATION (CHUẨN HÌNH MẪU MEDIAN UI)
     ====================================================================== -->
<nav class="median-mobile-nav" id="mobileBottomNav">
    <a href="{{ route('home') }}" class="mobile-nav-item {{ request()->routeIs('home') ? 'active' : '' }}" title="Trang chủ">
        <i class="fa-solid fa-house"></i>
        <span>Trang chủ</span>
    </a>
    <button type="button" class="mobile-nav-item" id="mobileSearchBtn" onclick="toggleMobileSearch()" title="Tìm kiếm">
        <i class="fa-solid fa-magnifying-glass"></i>
        <span>Tìm kiếm</span>
    </button>
    <button type="button" class="mobile-nav-item" id="mobileTopicsBtn" title="Danh mục">
        <i class="fa-solid fa-shapes"></i>
        <span>Chủ đề</span>
    </button>
    <button type="button" class="mobile-nav-item" id="mobileThemeToggleBtn" title="Giao diện Tối/Sáng">
        <i class="fa-solid fa-circle-half-stroke"></i>
        <span>Giao diện</span>
    </button>
    @auth
    <a href="{{ route('admin.profile') }}" class="mobile-nav-item {{ request()->routeIs('admin.profile') ? 'active' : '' }}" title="Cá nhân">
        <i class="fa-solid fa-user"></i>
        <span>Cá nhân</span>
    </a>
    @else
    <a href="{{ route('about') }}" class="mobile-nav-item {{ request()->routeIs('about') ? 'active' : '' }}" title="Về tôi">
        <i class="fa-solid fa-user-tie"></i>
        <span>Về tôi</span>
    </a>
    @endauth
</nav>
