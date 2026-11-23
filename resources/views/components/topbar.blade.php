<!-- ======================================================================
     TOPBAR MEDIAN UI (TÌM KIẾM, DARK MODE & THÔNG TIN ADMIN)
     ====================================================================== -->
<header class="main-topbar">
    <div style="display: flex; align-items: center; gap: 0.65rem;">
        <!-- NÚT MỞ SIDEBAR TRÊN ĐIỆN THOẠI (HAMBURGER MENU) -->
        <button type="button" class="btn-mobile-sidebar-toggle" id="btnMobileSidebarToggle" title="Mở danh mục menu">
            <i class="fa-solid fa-bars"></i>
        </button>

        <!-- LOGO NHỎ TRÊN TOPBAR MOBILE -->
        <a href="{{ route('home') }}" class="mobile-topbar-brand">
            <span class="mobile-brand-name">{{ $siteSettings['site_name'] ?? 'KẾT NỐI' }}</span>
        </a>

        <span class="desktop-breadcrumb" style="font-size: 0.8rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">
            @yield('breadcrumb', 'WORKSPACE / DIỄN ĐÀN DOANH NGHIỆP')
        </span>
    </div>

    <!-- THANH TÌM KIẾM CHUẨN MEDIAN UI (CTRL + K & LIVE FILTER) -->
    <div class="topbar-search-box">
        <button type="button" class="topbar-search-icon" id="btnTopSearchSubmit" onclick="performGlobalSearch()" title="Tìm kiếm" style="background: none; border: none; cursor: pointer; color: var(--text-muted); display: flex; align-items: center; justify-content: center; padding: 0 0.5rem;">
            <i class="fa-solid fa-magnifying-glass"></i>
        </button>
        <input type="text" id="topSearchInput" placeholder="Tìm kiếm bài viết, chủ đề, chiến lược..." value="{{ request('q') }}" autocomplete="off" onkeydown="if(event.key==='Enter') performGlobalSearch()">
        <span class="topbar-kbd-shortcut">Ctrl K</span>
    </div>

    <!-- KHU VỰC THAO TÁC: DARK MODE, THÔNG BÁO & USER -->
    <div class="topbar-user-area">
        <!-- NÚT MỞ TÌM KIẾM TRÊN MOBILE -->
        <button type="button" class="btn-mobile-search-toggle" id="btnMobileSearchToggle" onclick="toggleMobileSearch()" title="Tìm kiếm bài viết">
            <i class="fa-solid fa-magnifying-glass"></i>
        </button>

        <!-- Nút Dark Mode Median UI -->
        <button type="button" class="median-icon-btn" id="themeToggleBtn" title="Chuyển chế độ Sáng / Tối">
            <i class="fa-solid fa-moon"></i>
        </button>

        <!-- Nút & Trung tâm Thông báo Hệ thống Median UI (Chỉ hiển thị khi đã đăng nhập Admin) -->
        @auth
        <div class="topbar-notif-wrapper" style="position: relative;">
            <button type="button" class="median-icon-btn" id="topbarNotifBtn" title="Thông báo hệ thống" aria-expanded="false" style="position: relative;">
                <i class="fa-regular fa-bell"></i>
                <span class="median-badge-dot notif-badge-dot" style="display: {{ ($unreadNotifCount ?? 0) > 0 ? 'block' : 'none' }};"></span>
                @if(($unreadNotifCount ?? 0) > 0)
                <span class="notif-badge-counter">{{ $unreadNotifCount > 9 ? '9+' : $unreadNotifCount }}</span>
                @endif
            </button>

            <!-- DROPDOWN TRUNG TÂM THÔNG BÁO CHO ADMIN -->
            <div id="topbarNotifDropdown" class="notif-dropdown-menu" style="display: none;">
                <div class="notif-header">
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <h4 style="margin: 0; font-size: 0.92rem; font-weight: 800; color: var(--text-title); display: flex; align-items: center; gap: 0.45rem;">
                            <i class="fa-solid fa-bell" style="color: var(--executive-blue); font-size: 0.85rem;"></i> Thông báo
                        </h4>
                        <span class="notif-pill-count" id="notifPillCount">{{ $unreadNotifCount ?? 0 }} mới</span>
                    </div>
                    <button type="button" class="btn-mark-all-read" id="btnMarkAllNotifsRead" title="Đánh dấu đã đọc">
                        <i class="fa-solid fa-check-double"></i> Đã đọc
                    </button>
                </div>

                <!-- BỘ LỌC THÔNG BÁO (TẤT CẢ / ĐỀ XUẤT / BÌNH LUẬN) -->
                <div class="notif-filter-tabs">
                    <button type="button" class="notif-tab active" data-filter="all">Tất cả</button>
                    <button type="button" class="notif-tab" data-filter="proposal">
                        Đề xuất @if(($pendingProposalsCount ?? 0) > 0)<span class="notif-tab-badge" id="notifTabProposalBadge">{{ $pendingProposalsCount }}</span>@endif
                    </button>
                    <button type="button" class="notif-tab" data-filter="comment">
                        Bình luận @if(($recentCommentsCount ?? 0) > 0)<span class="notif-tab-badge" id="notifTabCommentBadge">{{ $recentCommentsCount }}</span>@endif
                    </button>
                </div>

                <!-- DANH SÁCH CÁC THÔNG BÁO -->
                <div class="notif-body-list" id="notifItemsList">
                    @php
                        $hasAnyNotif = (isset($latestProposals) && $latestProposals->count() > 0) || (isset($latestComments) && $latestComments->count() > 0);
                    @endphp

                    @if(!$hasAnyNotif)
                        <div class="notif-empty-state">
                            <i class="fa-regular fa-bell-slash"></i>
                            <p>Không có thông báo mới nào</p>
                        </div>
                    @else
                        {{-- 1. Danh sách Đề xuất hợp tác --}}
                        @if(isset($latestProposals))
                            @foreach($latestProposals as $p)
                                <a href="{{ route('admin.proposals.index', ['proposal_id' => $p->id]) }}" class="notif-item notif-item-proposal {{ !$p->is_read ? 'unread' : '' }}" data-notif-type="proposal" data-notif-id="{{ $p->id }}" data-is-read="{{ $p->is_read ? '1' : '0' }}">
                                    <div class="notif-icon-circle icon-proposal">
                                        <i class="fa-solid fa-handshake"></i>
                                    </div>
                                    <div class="notif-item-info">
                                        <div class="notif-item-top">
                                            <span class="notif-item-author">{{ $p->name }}</span>
                                            <span class="notif-item-badge proposal-badge">Đề xuất</span>
                                            <span class="notif-item-time">{{ $p->created_at ? $p->created_at->diffForHumans() : '' }}</span>
                                        </div>
                                        <div class="notif-item-content">
                                            {{ \Illuminate\Support\Str::limit($p->subject ?: $p->message, 68) }}
                                        </div>
                                        @if($p->company)
                                            <div class="notif-item-subtext"><i class="fa-regular fa-building"></i> {{ $p->company }}</div>
                                        @endif
                                    </div>
                                </a>
                            @endforeach
                        @endif

                        {{-- 2. Danh sách Bình luận mới --}}
                        @if(isset($latestComments))
                            @foreach($latestComments as $c)
                                <a href="{{ route('admin.comments', ['comment_id' => $c->id]) }}" class="notif-item notif-item-comment {{ !$c->is_read ? 'unread' : '' }}" data-notif-type="comment" data-notif-id="{{ $c->id }}" data-is-read="{{ $c->is_read ? '1' : '0' }}">
                                    <div class="notif-icon-circle icon-comment">
                                        <i class="fa-regular fa-comment-dots"></i>
                                    </div>
                                    <div class="notif-item-info">
                                        <div class="notif-item-top">
                                            <span class="notif-item-author">{{ $c->author_name }}</span>
                                            <span class="notif-item-badge comment-badge">Bình luận</span>
                                            <span class="notif-item-time">{{ $c->created_at ? $c->created_at->diffForHumans() : '' }}</span>
                                        </div>
                                        <div class="notif-item-content">
                                            "{{ \Illuminate\Support\Str::limit($c->content, 65) }}"
                                        </div>
                                        @if($c->post)
                                            <div class="notif-item-subtext"><i class="fa-solid fa-newspaper"></i> {{ \Illuminate\Support\Str::limit($c->post->title, 38) }}</div>
                                        @endif
                                    </div>
                                </a>
                            @endforeach
                        @endif
                    @endif
                </div>

                <!-- FOOTER CHUYỂN HƯỚNG TRANG QUẢN TRỊ -->
                <div class="notif-footer">
                    <a href="{{ route('admin.proposals.index') }}" class="notif-footer-link">
                        <i class="fa-solid fa-inbox"></i> Xem tất cả Đề xuất
                    </a>
                    <span style="color: var(--border-light, #e2e8f0);">|</span>
                    <a href="{{ route('admin.comments') }}" class="notif-footer-link">
                        <i class="fa-solid fa-comments"></i> Xem tất cả Bình luận
                    </a>
                </div>
            </div>
        </div>

        <!-- THÔNG TIN TÀI KHOẢN ADMIN ĐÃ ĐĂNG NHẬP -->
        <a href="{{ route('admin.profile') }}" class="topbar-admin-profile-link" style="display: flex; align-items: center; gap: 0.5rem; text-decoration: none; color: inherit; flex-shrink: 0;" title="Hồ sơ quản trị viên">
            <div class="topbar-avatar" style="border-radius: 6px; overflow: hidden; width: 34px; height: 34px; flex-shrink: 0; border: 1.5px solid var(--border-card);">
                <img id="topbarAvatarImg" src="{{ asset($currentUser?->avatar ?? 'assets/images/admin-khanh.jpg') }}" alt="Avatar Admin" style="width: 100%; height: 100%; object-fit: cover; display: block;" onerror="this.onerror=null; this.src='{{ asset('assets/images/admin-khanh.jpg') }}';">
            </div>
            <div class="topbar-user-info" style="display: flex; flex-direction: column; line-height: 1.2;">
                <span class="topbar-user-name" style="font-size: 0.84rem; font-weight: 800; color: var(--text-title); white-space: nowrap;">{{ $currentUser?->name ?? 'Admin' }}</span>
                <span style="font-size: 0.72rem; color: var(--text-muted); font-weight: 600; white-space: nowrap;">{{ $currentUser?->role_title ?? 'Quản trị viên' }}</span>
            </div>
        </a>

        <form id="topbarLogoutForm" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
        <button type="button" class="median-icon-btn btn-topbar-logout" onclick="if(confirm('Bạn có chắc chắn muốn đăng xuất khỏi quản trị?')) document.getElementById('topbarLogoutForm').submit();" title="Đăng xuất khỏi hệ thống" style="color: #ef4444; flex-shrink: 0;">
            <i class="fa-solid fa-arrow-right-from-bracket"></i>
        </button>
        @endauth
    </div>

    <!-- THANH TÌM KIẾM MỞ RỘNG TRÊN MOBILE (SLIDE DOWN) -->
    <div class="mobile-search-bar" id="mobileSearchBar" style="display: none;">
        <form action="{{ route('home') }}" method="GET" class="mobile-search-form" id="mobileSearchForm" onsubmit="event.preventDefault(); performGlobalSearch();">
            <button type="submit" class="mobile-search-icon" style="background:none;border:none;cursor:pointer;color:var(--text-muted);display:flex;align-items:center;padding:0;">
                <i class="fa-solid fa-magnifying-glass"></i>
            </button>
            <input 
                type="text" 
                name="q" 
                id="mobileSearchInput" 
                class="mobile-search-input" 
                placeholder="Tìm kiếm bài viết, chủ đề..." 
                value="{{ request('q') }}"
                autocomplete="off"
            >
            <button type="button" class="btn-clear-mobile-search" id="btnClearMobileSearch" onclick="clearGlobalSearch()" style="display: none;" title="Xóa nội dung">
                <i class="fa-solid fa-xmark"></i>
            </button>
            <button type="button" class="btn-close-mobile-search" id="btnCloseMobileSearch" onclick="toggleMobileSearch()" title="Đóng tìm kiếm">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </form>
    </div>
</header>
