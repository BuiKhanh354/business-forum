@extends('layouts.app')

@php
    $founder = $authorUser ?? $currentUser ?? \App\Models\User::first();
@endphp

@section('title', 'Về tôi - ' . ($founder->name ?? 'Admin Khánh') . ' | KẾT NỐI Business Community')
@section('breadcrumb', 'WORKSPACE / VỀ TÔI & NHÀ SÁNG LẬP')

@section('content')
<main class="main-content-scroll">
    <!-- BREADCRUMB -->
    <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.82rem; color: var(--text-muted); margin-bottom: 1.25rem;">
        <a href="{{ route('home') }}" style="color: var(--text-muted); text-decoration: none;">Trang chủ</a>
        <span>/</span>
        <span style="color: var(--text-title); font-weight: 600;">Về tôi (Founder Admin)</span>
    </div>

    <!-- ====================================================================
         LAYOUT ADMIN-CONNECT-GRID: 1 BÊN HÌNH ADMIN - 1 BÊN THÔNG TIN & FORM KẾT NỐI
         ==================================================================== -->
    <section class="admin-connect-hero-card" id="ve-toi-admin" style="margin-bottom: 2rem;">
        <div class="admin-connect-grid">

            <!-- CỘT TRÁI: HÌNH ẢNH & THÔNG TIN HỒ SƠ ADMIN KHÁNH (ĐỒNG BỘ TỪ DATABASE) -->
            <div class="admin-photo-col">
                <div class="admin-portrait-wrap">
                    <img src="{{ $founder->avatar_url ?? asset('assets/images/admin-khanh.jpg') }}" alt="{{ $founder->name ?? 'Admin Khánh' }} - {{ $founder->role_title ?? 'Founder KẾT NỐI' }}" class="admin-portrait-img" id="adminPortraitImgAbout" onerror="this.onerror=null; this.src='{{ asset('assets/images/admin-khanh.jpg') }}';">                </div>

                <span class="admin-role-badge">
                    <i class="fa-solid fa-crown" style="font-size: 0.7rem;"></i> {{ strtoupper($founder->role_title ?? 'FOUNDER & QUẢN TRỊ VIÊN') }}
                </span>
                <h2 class="admin-name-title">{{ $founder->name ?? 'Admin Khánh' }}</h2>
                <div class="admin-title-sub">{{ $founder->role_title ?? 'Founder & Managing Director' }}</div>

                <div class="admin-quote-box">
                    <i class="fa-solid fa-quote-left" style="font-size: 0.7rem; color: var(--gold-accent); margin-right: 0.25rem;"></i>
                    {{ $founder->bio ?? 'Kinh tế không chỉ là những con số, mà là hành trình cộng hưởng tri thức và tạo dựng giá trị thực cho cộng đồng lãnh đạo.' }}
                </div>

                <!-- Thống kê năng lực -->
                <div class="admin-mini-stats-row">
                    <div class="admin-mini-stat-item">
                        <div class="admin-mini-stat-num">10+ Năm</div>
                        <div class="admin-mini-stat-lbl">Điều hành & Cố vấn</div>
                    </div>
                    <div class="admin-mini-stat-item">
                        <div class="admin-mini-stat-num">150+</div>
                        <div class="admin-mini-stat-lbl">Đối tác & Quỹ đầu tư</div>
                    </div>
                </div>

                <!-- Nút mạng xã hội cá nhân (Tự động nhận diện Icon Tab của mọi website) -->
                @php
                    $aboutSocials = !empty($siteSettings['social_links']) ? json_decode($siteSettings['social_links'], true) : [];
                    if (!is_array($aboutSocials) || empty($aboutSocials)) {
                        $aboutSocials = array_values(array_filter([
                            $siteSettings['social_linkedin'] ?? '',
                            $siteSettings['social_telegram'] ?? '',
                            $siteSettings['social_facebook'] ?? '',
                            $siteSettings['social_youtube'] ?? '',
                        ]));
                    }
                @endphp
                <div class="admin-social-pills-list">
                    @foreach($aboutSocials as $sUrl)
                        @php
                            $sHost = parse_url($sUrl, PHP_URL_HOST) ?? '';
                            $cleanHost = preg_replace('/^www\./', '', strtolower($sHost));
                            $faviconUrl = "https://www.google.com/s2/favicons?domain=" . ($cleanHost ?: 'example.com') . "&sz=64";
                        @endphp
                        <a href="{{ $sUrl }}" target="_blank" rel="noopener noreferrer" class="admin-social-pill" title="{{ $cleanHost ?: $sUrl }}" style="display: inline-flex; align-items: center; justify-content: center; padding: 6px;">
                            <img src="{{ $faviconUrl }}" alt="{{ $cleanHost }}" style="width: 16px; height: 16px; object-fit: contain;" onerror="this.src='https://www.google.com/s2/favicons?domain=example.com&sz=64'">
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- CỘT PHẢI: LỜI CHÀO, KÊNH KẾT NỐI VÀ FORM NHẮN TIN TRỰC TIẾP CHO ADMIN -->
            <div class="admin-info-col">
                <div class="admin-welcome-top">
                    <div class="admin-connect-tagline">
                        <i class="fa-solid fa-handshake"></i> KẾT NỐI TRỰC TIẾP VỚI FOUNDER • BUSINESS COMMUNITY
                    </div>
                    <h1 class="admin-welcome-headline">Chào mừng Quý Lãnh đạo & Đối tác Doanh nghiệp</h1>
                    <p class="admin-welcome-paragraph">
                        Tôi luôn trân trọng từng cơ hội được lắng nghe, trao đổi góc nhìn chiến lược và hợp tác cùng các nhà sáng lập, chuyên gia quản trị và các định chế tài chính. Mọi đề xuất kết nối gửi qua đây đều được tôi trực tiếp xem xét và phản hồi trong thời gian sớm nhất.
                    </p>
                </div>

                <!-- Kênh kết nối văn phòng tiếp đối tác -->
                <div class="admin-direct-channels-row" style="grid-template-columns: 1fr;">
                    <div class="admin-channel-badge">
                        <i class="fa-solid fa-location-dot"></i>
                        <div>
                            <div class="admin-channel-badge-text">Văn phòng tiếp đối tác</div>
                            <div class="admin-channel-badge-val">{{ $siteSettings['contact_address'] ?? 'Bitexco Q.1 & Keangnam HN' }}</div>
                        </div>
                    </div>
                </div>

                <!-- Khung nhắn tin trực tiếp nhanh cho Admin -->
                <div class="admin-quick-message-box">
                    <div class="admin-quick-message-title">
                        <i class="fa-solid fa-paper-plane" style="color: var(--executive-blue);"></i>
                        <span>Gửi tin nhắn / Thư ngỏ riêng cho Founder Admin</span>
                    </div>

                    @if(session('success_quick_message'))
                        <div style="background-color: #dcfce7; border: 1px solid #86efac; color: #166534; padding: 0.75rem 1rem; border-radius: 6px; margin-bottom: 1rem; font-size: 0.85rem; font-weight: 600; display: flex; align-items: center; gap: 0.5rem;">
                            <i class="fa-solid fa-circle-check" style="color: #16a34a; font-size: 1.1rem; flex-shrink: 0;"></i>
                            <span>{{ session('success_quick_message') }}</span>
                        </div>
                    @endif

                    @if($errors->any())
                        <div style="background-color: #fef2f2; border: 1px solid #fecaca; color: #991b1b; padding: 0.75rem 1rem; border-radius: 6px; margin-bottom: 1rem; font-size: 0.82rem;">
                            <ul style="margin: 0; padding-left: 1.15rem;">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form id="quickMessageToAdminForm" action="{{ route('quick.message') }}" method="POST">
                        @csrf
                        <div class="admin-quick-grid-inputs">
                            <input type="text" name="name" id="quickSenderName" class="admin-quick-input" placeholder="Họ và tên của bạn..." value="{{ old('name') }}" required>
                            <input type="text" name="contact" id="quickSenderContact" class="admin-quick-input" placeholder="Email hoặc Số điện thoại / Zalo..." value="{{ old('contact') }}" required>
                        </div>
                        <textarea name="message" id="quickMessageText" class="admin-quick-textarea" placeholder="Nhập tóm tắt lời nhắn hoặc đề xuất bạn muốn trao đổi riêng cùng Founder Admin..." required>{{ old('message') }}</textarea>
                        <div class="admin-quick-footer">
                            <span class="admin-reply-time-note">
                                <i class="fa-regular fa-clock"></i> Phản hồi trong vòng 24 giờ làm việc
                            </span>
                            <button type="submit" class="btn-send-admin-quick">
                                <i class="fa-solid fa-paper-plane"></i> Gửi riêng cho Admin
                            </button>
                        </div>
                    </form>
                </div>

            </div>

        </div>
    </section>

    <!-- KHU VỰC TRIẾT LÝ HOẠT ĐỘNG & BÀI VIẾT NỔI BẬT CỦA TÁC GIẢ -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 2rem;">
        <div class="widget-card" style="padding: 1.75rem;">
            <h3 style="font-size: 1.05rem; font-weight: 800; color: var(--text-title); margin-bottom: 0.85rem; display: flex; align-items: center; gap: 0.5rem;">
                <i class="fa-solid fa-compass" style="color: var(--executive-blue);"></i>
                Tầm nhìn & Sứ mệnh KẾT NỐI
            </h3>
            <p style="font-size: 0.88rem; color: var(--text-muted); line-height: 1.65; margin-bottom: 1rem;">
                Xây dựng diễn đàn tri thức tinh gọn, chất lượng cao dành riêng cho các nhà lãnh đạo và doanh nhân Việt Nam. Từng bài viết đều được chọn lọc kỹ càng, gắn liền với thực tiễn thị trường và chiến lược thực chiến.
            </p>
            <a href="{{ route('home') }}" class="btn-median-read" style="display: inline-flex; align-items: center; gap: 0.45rem;">
                Khám phá kho bài viết <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>

        <div class="widget-card" style="padding: 1.75rem;">
            <h3 style="font-size: 1.05rem; font-weight: 800; color: var(--text-title); margin-bottom: 0.85rem; display: flex; align-items: center; gap: 0.5rem;">
                <i class="fa-solid fa-handshake-angle" style="color: var(--gold-accent);"></i>
                Hợp tác & Đồng hành
            </h3>
            <p style="font-size: 0.88rem; color: var(--text-muted); line-height: 1.65; margin-bottom: 1rem;">
                Chúng tôi sẵn sàng phối hợp cùng các quỹ đầu tư, ngân hàng, định chế tài chính và các tổ chức đào tạo lãnh đạo để tổ chức hội thảo, nghiên cứu báo cáo chuyên đề và kết nối đầu tư.
            </p>
            <a href="{{ route('contact') }}" class="btn-median-read" style="background: var(--bg-card); color: var(--executive-blue); border: 1px solid var(--executive-blue); display: inline-flex; align-items: center; gap: 0.45rem;">
                Liên hệ hợp tác <i class="fa-solid fa-paper-plane"></i>
            </a>
        </div>
    </div>
</main>
@endsection
