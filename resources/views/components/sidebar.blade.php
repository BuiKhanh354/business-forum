<!-- ======================================================================
     SIDEBAR TRÁI CHUẨN MEDIAN UI (KẾT NỐI BUSINESS COMMUNITY)
     ====================================================================== -->
<aside class="app-sidebar">
    <!-- LOGO CHUẨN MEDIAN UI (ICON ALIGN-LEFT 3 THANH THỤC RA THỤC VÔ) -->
    <div class="brand-box median-brand">
        <div style="display: flex; align-items: center; gap: 0.65rem;">
            <button type="button" class="btn-sidebar-toggle" id="btnToggleSidebar" title="Thu gọn / Mở rộng Sidebar">
                <span class="icon-align-left" aria-hidden="true">
                    <span class="bar bar-1"></span>
                    <span class="bar bar-2"></span>
                    <span class="bar bar-3"></span>
                </span>
            </button>
            <a href="{{ route('home') }}" class="brand-link">
                <span class="brand-name">{{ $siteSettings['site_name'] ?? 'KẾT NỐI' }}</span>
            </a>
        </div>
        <!-- NÚT ĐÓNG SIDEBAR TRÊN MOBILE -->
        <button type="button" class="btn-sidebar-close-mobile" id="btnCloseSidebarMobile" title="Đóng menu">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>

    @if(request()->routeIs('admin.*'))
        {{-- ================= GIAO DIỆN CHỈ XUẤT HIỆN Ở TRANG QUẢN TRỊ (ADMIN) ================= --}}
        <div>
            <div class="sidebar-section-label">WORKSPACE</div>
            <ul class="sidebar-nav-list">
                <li class="sidebar-nav-item">
                    <a href="{{ route('home') }}">
                        <span class="nav-dot"></span>
                        <span>Xem trang chủ</span>
                    </a>
                </li>
                <li class="sidebar-nav-item {{ request()->routeIs('admin.posts') ? 'active' : '' }}">
                    <a href="{{ route('admin.posts') }}">
                        <span class="nav-dot"></span>
                        <span>Quản lý bài viết</span>
                    </a>
                </li>
                <li class="sidebar-nav-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.categories.index') }}">
                        <span class="nav-dot" style="background-color: var(--executive-blue);"></span>
                        <span>Quản lý danh mục</span>
                    </a>
                </li>
                <li class="sidebar-nav-item {{ request()->routeIs('admin.comments') ? 'active' : '' }}">
                    <a href="{{ route('admin.comments') }}">
                        <span class="nav-dot"></span>
                        <span>Quản lý bình luận</span>
                    </a>
                </li>
                <li class="sidebar-nav-item {{ request()->routeIs('admin.proposals.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.proposals.index') }}" style="display: flex; align-items: center; justify-content: space-between;">
                        <div style="display: flex; align-items: center; gap: 0.65rem;">
                            <span class="nav-dot" style="background-color: #f59e0b;"></span>
                            <span>Đề xuất hợp tác</span>
                        </div>
                        @if(($pendingProposalsCount ?? 0) > 0)
                            <span style="background: #ef4444; color: #ffffff; font-size: 0.65rem; font-weight: 800; padding: 2px 7px; border-radius: 999px; line-height: 1;">
                                {{ $pendingProposalsCount }}
                            </span>
                        @endif
                    </a>
                </li>
            </ul>
        </div>

        <div>
            <div class="sidebar-section-label">TÀI KHOẢN & HỆ THỐNG</div>
            <ul class="sidebar-nav-list">
                <li class="sidebar-nav-item {{ request()->routeIs('admin.profile') ? 'active' : '' }}">
                    <a href="{{ route('admin.profile') }}">
                        <span class="nav-dot"></span>
                        <span>Thông tin cá nhân</span>
                    </a>
                </li>
                <li class="sidebar-nav-item {{ request()->routeIs('admin.theme') ? 'active' : '' }}">
                    <a href="{{ route('admin.theme') }}">
                        <span class="nav-dot"></span>
                        <span>Cài đặt giao diện</span>
                    </a>
                </li>
                <li class="sidebar-nav-item">
                    <form id="adminLogoutForm" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                    <a href="javascript:void(0)" onclick="if(confirm('Bạn có chắc chắn muốn đăng xuất khỏi trang quản trị?')) document.getElementById('adminLogoutForm').submit();" style="color: #ef4444;">
                        <span class="nav-dot" style="background-color: #ef4444;"></span>
                        <span>Đăng xuất</span>
                    </a>
                </li>
            </ul>
        </div>
    @else
        {{-- ================= GIAO DIỆN BÀI VIẾT CHI TIẾT & TRANG CÔNG KHAI (CHỈ CÓ KHÁM PHÁ - CHỈ XEM) ================= --}}
        <div>
            <div class="sidebar-section-label">KHÁM PHÁ</div>
            <ul class="sidebar-nav-list">
                <li class="sidebar-nav-item {{ request()->routeIs('home') ? 'active' : '' }}">
                    <a href="{{ route('home') }}">
                        <span class="nav-dot"></span>
                        <span>Trang chủ</span>
                    </a>
                </li>

                <!-- CHỦ ĐỀ BÀI VIẾT: TỰ ĐỘNG ĐỒNG BỘ TỪ DATABASE (CHỈ ĐỂ NGƯỜI ĐỌC CHỌN LỌC) -->
                <li class="sidebar-nav-item has-submenu open" id="menuTopicDropdown">
                    <a href="javascript:void(0)" class="submenu-toggle" id="topicToggleBtn">
                        <span class="nav-dot"></span>
                        <span style="flex: 1;">Chủ đề bài viết</span>
                        <svg class="submenu-arrow" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </a>
                    <ul class="sidebar-submenu" id="sidebarTopicsSubmenu">
                        <li>
                            <a href="{{ route('home') }}#chu-de-bai-viet" data-category="all" class="submenu-link active">
                                <span class="sub-dot"></span>
                                <span>Tất cả bài viết</span>
                                <span class="sub-count">{{ isset($globalCategories) ? $globalCategories->sum('posts_count') : 0 }}</span>
                            </a>
                        </li>
                        
                        {{-- CHỈ HIỂN THỊ CÁC DANH MỤC THỰC TẾ TRONG DATABASE --}}
                        @if(isset($globalCategories) && count($globalCategories) > 0)
                            @foreach($globalCategories as $cat)
                            <li>
                                <a href="{{ route('home') }}#chu-de-bai-viet" data-category="{{ Str::lower($cat->slug) }}" data-name="{{ Str::lower($cat->name) }}" class="submenu-link">
                                    <span class="sub-dot" style="background-color: {{ $cat->color ?? '#2563eb' }};"></span>
                                    <span>{{ $cat->name }}</span>
                                    <span class="sub-count">{{ $cat->posts_count > 0 ? $cat->posts_count : 0 }}</span>
                                </a>
                            </li>
                            @endforeach
                        @endif
                    </ul>
                </li>

                <li class="sidebar-nav-item {{ request()->routeIs('about') ? 'active' : '' }}">
                    <a href="{{ route('about') }}">
                        <span class="nav-dot"></span>
                        <span>Về tôi</span>
                    </a>
                </li>

                <li class="sidebar-nav-item {{ request()->routeIs('contact') ? 'active' : '' }}">
                    <a href="{{ route('contact') }}">
                        <span class="nav-dot"></span>
                        <span>Liên hệ</span>
                    </a>
                </li>
            </ul>
        </div>
    @endif

    <!-- HỘP THÔNG ĐIỆP GÓC DOANH NGHIỆP & CÁC ICON KẾT NỐI (ẨN HOÀN TOÀN TRONG KHU VỰC ADMIN) -->
    @if(!request()->routeIs('admin.*'))
    <div class="community-note-box">
        <div class="community-note-title">
            <span class="community-note-icon"><i class="fa-solid fa-briefcase"></i></span>
            <span>Góc doanh nghiệp</span>
        </div>
        <p class="community-note-desc">Chia sẻ chiến lược, kiến thức điều hành &amp; đầu tư thực chiến</p>

        <!-- CÁC ICON KẾT NỐI MẠNG XÃ HỘI (TỰ ĐỘNG BIẾN THÀNH ICON TAB CỦA MỌI WEBSITE) -->
        @php
            $sidebarSocials = !empty($siteSettings['social_links']) ? json_decode($siteSettings['social_links'], true) : [];
            if (!is_array($sidebarSocials) || empty($sidebarSocials)) {
                $sidebarSocials = array_values(array_filter([
                    $siteSettings['social_facebook'] ?? 'https://facebook.com',
                    $siteSettings['social_linkedin'] ?? 'https://linkedin.com',
                    $siteSettings['social_youtube'] ?? 'https://youtube.com',
                    $siteSettings['social_telegram'] ?? 'https://telegram.org',
                ]));
            }
        @endphp
        @if(count($sidebarSocials) > 0)
        <div class="sidebar-social-connect">
            @foreach($sidebarSocials as $sUrl)
                @php
                    $sHost = parse_url($sUrl, PHP_URL_HOST) ?? '';
                    $cleanHost = preg_replace('/^www\./', '', strtolower($sHost));
                    $faviconUrl = "https://www.google.com/s2/favicons?domain=" . ($cleanHost ?: 'example.com') . "&sz=64";
                @endphp
                <a href="{{ $sUrl }}" target="_blank" rel="noopener noreferrer" class="social-connect-btn" title="{{ $cleanHost ?: $sUrl }}">
                    <img src="{{ $faviconUrl }}" alt="{{ $cleanHost }}" class="social-connect-favicon" onerror="this.src='https://www.google.com/s2/favicons?domain=example.com&sz=64'">
                </a>
            @endforeach
        </div>
        @endif
    </div>
    @endif
</aside>
