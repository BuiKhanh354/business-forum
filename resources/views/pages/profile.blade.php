@extends('layouts.app')

@section('title', 'Thông tin cá nhân - KẾT NỐI Admin')
@section('breadcrumb', 'WORKSPACE / TÀI KHOẢN & HỆ THỐNG / THÔNG TIN CÁ NHÂN')

@section('content')
<main class="main-content-scroll">
    <!-- TIÊU ĐỀ TRANG -->
    <div class="content-header-row" style="margin-bottom: 2rem;">
        <div>
            <h1 class="forum-main-title" style="font-size: 1.6rem;">Thông tin cá nhân</h1>
            <p class="forum-subtitle">Quản lý và cập nhật thông tin hiển thị trên tài khoản quản trị của bạn.</p>
        </div>
    </div>

    <!-- BỐ CỤC 2 CỘT CHUẨN MEDIAN UI -->
    <div style="display: grid; grid-template-columns: 320px 1fr; gap: 1.75rem; align-items: start; margin-bottom: 2.5rem;" class="profile-grid-layout">

        <!-- CỘT TRÁI: THẺ THÔNG TIN CÁ NHÂN & LIÊN KẾT MẠNG XÃ HỘI CHÍNH THỨC -->
        <div style="display: flex; flex-direction: column; gap: 1.75rem;">

            <!-- THẺ TÓM TẮT HỒ SƠ ADMIN (WIDGET-CARD THÔNG TIN CÁ NHÂN) -->
            <div class="widget-card" style="padding: 2.5rem 1.5rem; text-align: center; border: 1px solid var(--border-card); box-shadow: 0 4px 15px rgba(0,0,0,0.03);">
                <div style="width: 100px; height: 100px; border-radius: 50%; margin: 0 auto 1.25rem; overflow: hidden; border: 3px solid var(--executive-blue); box-shadow: 0 4px 14px rgba(37,99,235,0.2);">
                    <img src="{{ $user->avatar_url ?? asset('assets/images/admin-khanh.jpg') }}" id="profileLeftAvatar" alt="Ảnh đại diện Admin" style="width: 100%; height: 100%; object-fit: cover;" onerror="this.onerror=null; this.src='{{ asset('assets/images/admin-khanh.jpg') }}';">
                </div>
                <h3 style="font-size: 1.2rem; font-weight: 800; color: var(--text-title); margin-bottom: 0.35rem;" id="leftDisplayName">
                    {{ $user->name ?? 'Admin Khánh' }}
                </h3>
                <div style="font-size: 0.84rem; color: var(--executive-blue); font-weight: 700; margin-bottom: 1rem;" id="leftDisplayRole">
                    {{ $user->role_title ?? 'Founder & Ban Quản trị' }}
                </div>

                <p style="font-size: 0.82rem; color: var(--text-muted); line-height: 1.55; margin-bottom: 1.25rem;" id="leftDisplayBio">
                    {{ $user->bio ?? 'Chia sẻ chiến lược kinh doanh, quản trị dòng tiền và đầu tư tài chính.' }}
                </p>

                <div style="border-top: 1px solid #f1f5f9; padding-top: 1.25rem; font-size: 0.84rem; color: var(--text-muted); display: flex; flex-direction: column; gap: 0.55rem; text-align: left;">
                    <div style="display: flex; justify-content: space-between;">
                        <span>Vai trò:</span>
                        <strong style="color: var(--text-title);">Người sáng lập</strong>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <span>Bài viết chia sẻ:</span>
                        <strong style="color: var(--executive-blue);">{{ $postsCount ?? 0 }} bài</strong>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <span>Bình luận phản hồi:</span>
                        <strong style="color: var(--gold-accent);">{{ $commentsCount ?? 0 }} lượt</strong>
                    </div>
                </div>

                @if(!empty($socialLinks) && count($socialLinks) > 0)
                <div style="border-top: 1px solid #f1f5f9; padding-top: 1rem; margin-top: 1.25rem; display: flex; justify-content: center; gap: 0.5rem; flex-wrap: wrap;">
                    @foreach($socialLinks as $sLink)
                        @php
                            $sDomain = preg_replace('/^www\./', '', strtolower(parse_url($sLink, PHP_URL_HOST) ?? ''));
                        @endphp
                        @if($sDomain)
                        <a href="{{ $sLink }}" target="_blank" rel="noopener noreferrer" style="width: 34px; height: 34px; border-radius: 8px; background: var(--bg-subtle, #f8fafc); border: 1px solid var(--border-card, #e2e8f0); display: inline-flex; align-items: center; justify-content: center; transition: all 0.2s;" title="{{ $sDomain }}">
                            <img src="https://www.google.com/s2/favicons?domain={{ $sDomain }}&sz=64" alt="{{ $sDomain }}" style="width: 18px; height: 18px; object-fit: contain;">
                        </a>
                        @endif
                    @endforeach
                </div>
                @endif
            </div>

            <!-- THẺ WIDGET-CARD: LIÊN KẾT MẠNG XÃ HỘI CHÍNH THỨC -->
            <div class="widget-card" style="padding: 1.75rem 1.5rem; border: 1px solid var(--border-card); box-shadow: 0 4px 15px rgba(0,0,0,0.03);">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.65rem; flex-wrap: wrap; gap: 0.5rem;">
                    <div style="font-size: 0.95rem; font-weight: 800; color: var(--text-title); display: flex; align-items: center; gap: 0.5rem;">
                        <i class="fa-solid fa-share-nodes" style="color: var(--executive-blue);"></i>
                        <span>Mạng xã hội chính thức</span>
                    </div>
                    <button type="button" onclick="addSocialLinkRow()" class="cat-pill" style="font-size: 0.76rem; padding: 0.35rem 0.65rem; background: #ffffff; border: 1px solid #cbd5e1; cursor: pointer; display: flex; align-items: center; gap: 0.35rem; color: var(--executive-blue); font-weight: 700;">
                        <i class="fa-solid fa-plus"></i> Thêm link
                    </button>
                </div>
                <p style="font-size: 0.75rem; color: var(--text-muted); margin-bottom: 1rem; line-height: 1.45;">
                    Dán liên kết bất kỳ (Facebook, TikTok, YouTube, Zalo, Website...). Hệ thống tự nhận diện icon tab của website đó!
                </p>

                <!-- Danh sách liên kết mạng xã hội động -->
                <div id="socialLinksListContainer" style="display: flex; flex-direction: column; gap: 0.65rem; margin-bottom: 1.25rem;">
                    @foreach($socialLinks as $linkUrl)
                        @php
                            $linkHost = parse_url($linkUrl, PHP_URL_HOST) ?? '';
                            $cleanDomain = preg_replace('/^www\./', '', strtolower($linkHost));
                            $favUrl = "https://www.google.com/s2/favicons?domain=" . ($cleanDomain ?: 'example.com') . "&sz=64";
                        @endphp
                        <div class="social-link-item" style="display: flex; align-items: center; gap: 0.5rem; background: var(--bg-subtle, #f8fafc); padding: 0.5rem 0.65rem; border-radius: 8px; border: 1px solid var(--border-card, #e2e8f0); transition: all 0.2s ease;">
                            <div class="social-tab-icon-box" style="width: 32px; height: 32px; background: #ffffff; border: 1px solid #cbd5e1; border-radius: 6px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; padding: 4px;">
                                <img class="social-tab-favicon-img" src="{{ $favUrl }}" alt="Tab Icon" style="width: 18px; height: 18px; object-fit: contain;" onerror="this.src='https://www.google.com/s2/favicons?domain=example.com&sz=64'">
                            </div>
                            <div style="flex: 1; min-width: 0;">
                                <input type="url" name="social_links[]" form="profileUpdateForm" value="{{ $linkUrl }}" placeholder="https://..." style="width: 100%; padding: 0.4rem 0.6rem; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.8rem; outline: none; background: #ffffff;" oninput="updateSocialFavicon(this)">
                            </div>
                            <button type="button" onclick="this.closest('.social-link-item').remove()" style="background: #ffffff; border: 1px solid #fecaca; color: #dc2626; border-radius: 6px; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; cursor: pointer; flex-shrink: 0;" title="Xóa liên kết này">
                                <i class="fa-solid fa-trash-can" style="font-size: 0.8rem;"></i>
                            </button>
                        </div>
                    @endforeach
                </div>

                <button type="button" onclick="document.getElementById('profileUpdateForm').requestSubmit()" class="btn-publish-trigger" style="width: 100%; justify-content: center; background-color: var(--executive-blue); font-size: 0.82rem; padding: 0.55rem 1rem; border: none; border-radius: 6px; color: #fff; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 0.4rem; box-shadow: 0 2px 6px rgba(37, 99, 235, 0.2);">
                    <i class="fa-solid fa-floppy-disk"></i> Lưu liên kết mạng xã hội
                </button>
            </div>
        </div>

        <!-- CỘT PHẢI: FORM CHỈNH SỬA THÔNG TIN CÁ NHÂN & ĐỔI MẬT KHẨU -->
        <div style="display: flex; flex-direction: column; gap: 1.75rem;">

            <!-- CARD 1: CHỈNH SỬA THÔNG TIN TÀI KHOẢN -->
            <div class="widget-card" style="padding: 2.25rem 2rem; border: 1px solid var(--border-card); box-shadow: 0 4px 15px rgba(0,0,0,0.03);">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem; border-bottom: 1px solid #f1f5f9; padding-bottom: 1rem;">
                    <h3 style="font-size: 1.15rem; font-weight: 800; color: var(--text-title); margin: 0; display: flex; align-items: center; gap: 0.5rem;">
                        <i class="fa-solid fa-user-pen" style="color: var(--executive-blue);"></i>
                        <span>Chỉnh sửa thông tin tài khoản</span>
                    </h3>
                    <span style="font-size: 0.78rem; background: var(--executive-blue-light); color: var(--executive-blue); padding: 0.25rem 0.65rem; border-radius: 999px; font-weight: 700;">
                        Đồng bộ trực tiếp toàn hệ thống
                    </span>
                </div>

                <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data" id="profileUpdateForm">
                    @csrf

                    <!-- TRƯỜNG ẨN LƯU PRESET ẢNH ĐẠI DIỆN ĐANG CHỌN -->
                    <input type="hidden" name="avatar" id="selectedAvatarInput" value="{{ $user->avatar ?? 'assets/images/admin-khanh.jpg' }}">

                    <!-- KHU VỰC ẢNH ĐẠI DIỆN: TẢI TỪ MÁY HOẶC CHỌN PRESET CÓ SẴN -->
                    <div class="avatar-profile-manager-box" style="margin-bottom: 1.75rem; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 1.5rem;">
                        <div class="avatar-current-row" style="display: flex; align-items: center; gap: 1.25rem; margin-bottom: 1.25rem;">
                            <div class="avatar-current-preview" style="width: 72px; height: 72px; border-radius: 50%; overflow: hidden; border: 2px solid var(--executive-blue); flex-shrink: 0; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                                <img src="{{ $user->avatar_url ?? asset('assets/images/admin-khanh.jpg') }}" id="profileRowAvatar" alt="Ảnh đại diện Admin" style="width: 100%; height: 100%; object-fit: cover;" onerror="this.onerror=null; this.src='{{ asset('assets/images/admin-khanh.jpg') }}';">
                            </div>
                            <div class="avatar-current-info" style="flex: 1;">
                                <div class="avatar-current-title" style="font-size: 0.95rem; font-weight: 800; color: var(--text-title); margin-bottom: 0.25rem;">
                                    Ảnh đại diện tài khoản
                                </div>
                                <div class="avatar-current-note" style="font-size: 0.8rem; color: var(--text-muted); margin-bottom: 0.75rem;">
                                    Tải ảnh vuông hoặc chân dung chất lượng cao. Ảnh sẽ xuất hiện ở topbar, sidebar và tác giả các bài viết.
                                </div>
                                <input type="file" name="avatar_file" id="uploadNewAvatarInput" accept="image/*" style="display: none;" onchange="handleAvatarFileSelect(this)">
                                <button type="button" class="btn-upload-avatar-trigger" onclick="document.getElementById('uploadNewAvatarInput').click()" style="background-color: var(--executive-blue); color: #fff; border: none; padding: 0.55rem 1.15rem; border-radius: 6px; font-size: 0.84rem; font-weight: 700; cursor: pointer; display: inline-flex; align-items: center; gap: 0.45rem;">
                                    <i class="fa-solid fa-camera"></i> Tải ảnh mới từ thiết bị
                                </button>
                            </div>
                        </div>

                        <!-- CÁC ẢNH ĐẠI DIỆN CŨ ĐÃ DÙNG GẦN ĐÂY (LỊCH SỬ AVATAR) -->
                        <div class="avatar-quick-history-wrap" style="border-top: 1px solid #e2e8f0; padding-top: 1rem;">
                            <div class="avatar-history-label" style="font-size: 0.82rem; font-weight: 700; color: var(--text-title); margin-bottom: 0.65rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 0.35rem;">
                                <span style="display: flex; align-items: center; gap: 0.4rem;">
                                    <i class="fa-solid fa-clock-rotate-left" style="color: var(--executive-blue);"></i>
                                    <span>Ảnh đại diện cũ đã từng dùng (Bấm để đổi lại):</span>
                                </span>
                                <span style="font-size: 0.74rem; color: var(--text-muted); background: #f1f5f9; padding: 0.15rem 0.5rem; border-radius: 4px;">
                                    Tự động lưu khi bạn đổi ảnh
                                </span>
                            </div>

                            <div class="avatar-quick-grid" id="avatarHistoryGrid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(130px, 1fr)); gap: 0.85rem;">
                                @forelse($avatarHistory as $index => $oldImg)
                                    <div class="avatar-quick-card" onclick="selectAvatarPreset('{{ $oldImg }}', this)" style="border: 2px solid #cbd5e1; border-radius: 6px; padding: 0.75rem 0.5rem; text-align: center; cursor: pointer; background: #ffffff; transition: all 0.2s ease;">
                                        <div style="width: 56px; height: 56px; border-radius: 50%; overflow: hidden; margin: 0 auto 0.5rem; border: 1px solid #e2e8f0; box-shadow: 0 1px 4px rgba(0,0,0,0.06);">
                                            <img src="{{ asset($oldImg) }}" alt="Ảnh cũ #{{ $index + 1 }}" style="width: 100%; height: 100%; object-fit: cover;">
                                        </div>
                                        <div style="font-size: 0.76rem; font-weight: 700; color: var(--text-title); margin-bottom: 0.35rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                            Ảnh cũ #{{ $index + 1 }}
                                        </div>
                                        <span class="preset-status-pill" style="font-size: 0.7rem; font-weight: 700; padding: 0.2rem 0.55rem; border-radius: 4px; background: #f1f5f9; color: var(--text-muted); display: inline-block;">
                                            Dùng lại ảnh này
                                        </span>
                                    </div>
                                @empty
                                    <div style="grid-column: 1 / -1; padding: 0.85rem; font-size: 0.8rem; color: var(--text-muted); text-align: center; background: #ffffff; border-radius: 6px; border: 1px dashed #cbd5e1;">
                                        Chưa có ảnh đại diện cũ trong lịch sử. Khi bạn tải ảnh mới lên, ảnh đại diện cũ sẽ được lưu tại đây để đổi lại bất kỳ lúc nào!
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- CÁC TRƯỜNG NHẬP LIỆU THÔNG TIN CÁ NHÂN -->
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin-bottom: 1.25rem;">
                        <!-- Họ và tên -->
                        <div>
                            <label style="display: block; font-size: 0.84rem; font-weight: 700; color: var(--text-title); margin-bottom: 0.45rem;">
                                Họ và tên <span style="color: #ef4444;">*</span>
                            </label>
                            <input type="text" name="name" value="{{ old('name', $user->name ?? 'Admin Khánh') }}" required oninput="document.getElementById('leftDisplayName').textContent = this.value" placeholder="Họ và tên..." style="width: 100%; padding: 0.65rem 0.85rem; border-radius: 6px; border: 1px solid #cbd5e1; font-size: 0.88rem; outline: none; background: #ffffff;">
                        </div>

                        <!-- Email -->
                        <div>
                            <label style="display: block; font-size: 0.84rem; font-weight: 700; color: var(--text-title); margin-bottom: 0.45rem;">
                                Email đăng nhập & liên hệ <span style="color: #ef4444;">*</span>
                            </label>
                            <input type="email" name="email" value="{{ old('email', $user->email ?? 'admin@gmail.com') }}" required placeholder="admin@gmail.com" style="width: 100%; padding: 0.65rem 0.85rem; border-radius: 6px; border: 1px solid #cbd5e1; font-size: 0.88rem; outline: none; background: #ffffff;">
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin-bottom: 1.25rem;">
                        <!-- Số điện thoại -->
                        <div>
                            <label style="display: block; font-size: 0.84rem; font-weight: 700; color: var(--text-title); margin-bottom: 0.45rem;">
                                Số điện thoại:
                            </label>
                            <input type="tel" name="phone" value="{{ old('phone', $user->phone ?? '0900 123 456') }}" placeholder="0900 123 456" style="width: 100%; padding: 0.65rem 0.85rem; border-radius: 6px; border: 1px solid #cbd5e1; font-size: 0.88rem; outline: none; background: #ffffff;">
                        </div>

                        <!-- Chức danh / Vai trò -->
                        <div>
                            <label style="display: block; font-size: 0.84rem; font-weight: 700; color: var(--text-title); margin-bottom: 0.45rem;">
                                Chức danh / Vai trò:
                            </label>
                            <input type="text" name="role_title" value="{{ old('role_title', $user->role_title ?? 'Founder & Ban Quản trị') }}" oninput="document.getElementById('leftDisplayRole').textContent = this.value" placeholder="Ví dụ: Founder & Ban Quản trị" style="width: 100%; padding: 0.65rem 0.85rem; border-radius: 6px; border: 1px solid #cbd5e1; font-size: 0.88rem; outline: none; background: #ffffff;">
                        </div>
                    </div>

                    <!-- Giới thiệu tiểu sử (Bio) -->
                    <div style="margin-bottom: 1.25rem;">
                        <label style="display: block; font-size: 0.84rem; font-weight: 700; color: var(--text-title); margin-bottom: 0.45rem;">
                            Tiểu sử / Lời giới thiệu ngắn (Bio):
                        </label>
                        <textarea name="bio" rows="3" oninput="document.getElementById('leftDisplayBio').textContent = this.value" placeholder="Chia sẻ kinh nghiệm, chuyên môn hoặc triết lý quản trị..." style="width: 100%; padding: 0.65rem 0.85rem; border-radius: 6px; border: 1px solid #cbd5e1; font-size: 0.88rem; outline: none; background: #ffffff; resize: vertical;">{{ old('bio', $user->bio ?? 'Chia sẻ chiến lược kinh doanh, quản trị dòng tiền và đầu tư tài chính.') }}</textarea>
                    </div>

                    <!-- Địa chỉ trụ sở văn phòng -->
                    <div style="margin-bottom: 1.75rem;">
                        <label style="display: block; font-size: 0.84rem; font-weight: 700; color: var(--text-title); margin-bottom: 0.45rem;">
                            <i class="fa-solid fa-location-dot" style="color: var(--executive-blue);"></i> Địa chỉ trụ sở văn phòng:
                        </label>
                        <input type="text" name="contact_address" value="{{ $settings['contact_address'] ?? 'Tầng 18, Toà tháp Doanh nhân, Quận 1, TP. Hồ Chí Minh' }}" placeholder="Địa chỉ trụ sở..." style="width: 100%; padding: 0.65rem 0.85rem; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.88rem; outline: none; background: #ffffff;">
                    </div>

                    <!-- NÚT LƯU THAY ĐỔI VÀ HỦY -->
                    <div style="display: flex; align-items: center; justify-content: space-between; border-top: 1px solid #f1f5f9; padding-top: 1.5rem; flex-wrap: wrap; gap: 0.85rem;">
                        <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.82rem; color: var(--text-muted);">
                            <i class="fa-solid fa-circle-check" style="color: #16a34a;"></i>
                            <span>Dữ liệu được bảo vệ an toàn trong cơ sở dữ liệu hệ thống.</span>
                        </div>

                        <div style="display: flex; gap: 0.75rem;">
                            <a href="{{ route('home') }}" class="cat-pill" style="padding: 0.65rem 1.35rem; text-decoration: none; display: inline-flex; align-items: center;">
                                Hủy thay đổi
                            </a>
                            <button type="submit" class="btn-publish-trigger" style="background-color: var(--executive-blue); font-size: 0.9rem; padding: 0.65rem 1.75rem; border: none; border-radius: 6px; color: #fff; font-weight: 800; cursor: pointer; display: flex; align-items: center; gap: 0.5rem; box-shadow: 0 4px 10px rgba(37, 99, 235, 0.25);">
                                <i class="fa-solid fa-floppy-disk"></i> Lưu thông tin cá nhân
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- CARD 2: KHU VỰC ĐỔI MẬT KHẨU TÀI KHOẢN QUẢN TRỊ VIÊN -->
            <div class="widget-card" style="padding: 2.25rem 2rem; border: 1px solid var(--border-card); box-shadow: 0 4px 15px rgba(0,0,0,0.03);">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem; border-bottom: 1px solid #f1f5f9; padding-bottom: 1rem;">
                    <h3 style="font-size: 1.15rem; font-weight: 800; color: var(--text-title); margin: 0; display: flex; align-items: center; gap: 0.5rem;">
                        <i class="fa-solid fa-shield-halved" style="color: var(--executive-blue);"></i>
                        <span>Đổi mật khẩu tài khoản quản trị</span>
                    </h3>
                    <span style="font-size: 0.78rem; background: #fef3c7; color: #b45309; padding: 0.25rem 0.65rem; border-radius: 999px; font-weight: 700;">
                        <i class="fa-solid fa-lock"></i> Bảo mật hệ thống
                    </span>
                </div>

                @if(session('password_success'))
                <div style="background: #f0fdf4; border: 1px solid #bbf7d0; color: #15803d; padding: 0.85rem 1.15rem; border-radius: 8px; margin-bottom: 1.25rem; font-size: 0.88rem; display: flex; align-items: center; gap: 0.6rem;">
                    <i class="fa-solid fa-circle-check"></i>
                    <span>{{ session('password_success') }}</span>
                </div>
                @endif

                @if(session('password_error'))
                <div style="background: #fef2f2; border: 1px solid #fecaca; color: #b91c1c; padding: 0.85rem 1.15rem; border-radius: 8px; margin-bottom: 1.25rem; font-size: 0.88rem; display: flex; align-items: center; gap: 0.6rem;">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <span>{{ session('password_error') }}</span>
                </div>
                @endif

                <div id="passwordAlertBox" style="display: none; padding: 0.85rem 1.15rem; border-radius: 8px; margin-bottom: 1.25rem; font-size: 0.88rem; align-items: center; gap: 0.6rem;"></div>

                <form action="{{ route('admin.password.change') }}" method="POST" id="adminPasswordChangeForm">
                    @csrf
                    <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1.25rem; margin-bottom: 1.5rem;" class="password-fields-grid">
                        <!-- Mật khẩu hiện tại -->
                        <div>
                            <label style="display: block; font-size: 0.84rem; font-weight: 700; color: var(--text-title); margin-bottom: 0.45rem;">
                                Mật khẩu hiện tại <span style="color: #ef4444;">*</span>
                            </label>
                            <div style="position: relative;">
                                <input type="password" name="current_password" id="inputCurrentPassword" required placeholder="Nhập mật khẩu hiện tại..." style="width: 100%; padding: 0.65rem 2.4rem 0.65rem 0.85rem; border-radius: 6px; border: 1px solid #cbd5e1; font-size: 0.88rem; outline: none; background: #ffffff;">
                                <button type="button" onclick="togglePasswordVisibility('inputCurrentPassword', this)" style="position: absolute; right: 8px; top: 50%; transform: translateY(-50%); background: none; border: none; color: #94a3b8; cursor: pointer; padding: 4px; display: flex; align-items: center;">
                                    <i class="fa-solid fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Mật khẩu mới -->
                        <div>
                            <label style="display: block; font-size: 0.84rem; font-weight: 700; color: var(--text-title); margin-bottom: 0.45rem;">
                                Mật khẩu mới <span style="color: #ef4444;">*</span>
                            </label>
                            <div style="position: relative;">
                                <input type="password" name="new_password" id="inputNewPassword" required minlength="6" placeholder="Tối thiểu 6 ký tự..." style="width: 100%; padding: 0.65rem 2.4rem 0.65rem 0.85rem; border-radius: 6px; border: 1px solid #cbd5e1; font-size: 0.88rem; outline: none; background: #ffffff;">
                                <button type="button" onclick="togglePasswordVisibility('inputNewPassword', this)" style="position: absolute; right: 8px; top: 50%; transform: translateY(-50%); background: none; border: none; color: #94a3b8; cursor: pointer; padding: 4px; display: flex; align-items: center;">
                                    <i class="fa-solid fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Xác nhận mật khẩu mới -->
                        <div>
                            <label style="display: block; font-size: 0.84rem; font-weight: 700; color: var(--text-title); margin-bottom: 0.45rem;">
                                Xác nhận mật khẩu mới <span style="color: #ef4444;">*</span>
                            </label>
                            <div style="position: relative;">
                                <input type="password" name="new_password_confirmation" id="inputConfirmPassword" required minlength="6" placeholder="Nhập lại mật khẩu mới..." style="width: 100%; padding: 0.65rem 2.4rem 0.65rem 0.85rem; border-radius: 6px; border: 1px solid #cbd5e1; font-size: 0.88rem; outline: none; background: #ffffff;">
                                <button type="button" onclick="togglePasswordVisibility('inputConfirmPassword', this)" style="position: absolute; right: 8px; top: 50%; transform: translateY(-50%); background: none; border: none; color: #94a3b8; cursor: pointer; padding: 4px; display: flex; align-items: center;">
                                    <i class="fa-solid fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div style="display: flex; align-items: center; justify-content: space-between; border-top: 1px solid #f1f5f9; padding-top: 1.25rem; flex-wrap: wrap; gap: 0.85rem;">
                        <div style="font-size: 0.8rem; color: var(--text-muted); display: flex; align-items: center; gap: 0.45rem;">
                            <i class="fa-solid fa-shield-cat" style="color: var(--executive-blue);"></i>
                            <span>Mật khẩu mới yêu cầu tối thiểu 6 ký tự. Sau khi đổi thành công, hệ thống sẽ sử dụng mật khẩu mới này.</span>
                        </div>
                        <button type="submit" id="btnChangePasswordSubmit" style="background-color: #0f172a; font-size: 0.88rem; padding: 0.65rem 1.65rem; border: none; border-radius: 6px; color: #ffffff; font-weight: 800; cursor: pointer; display: flex; align-items: center; gap: 0.5rem; box-shadow: 0 4px 10px rgba(15, 23, 42, 0.25); transition: all 0.2s ease;">
                            <i class="fa-solid fa-key"></i>
                            <span id="btnChangePasswordText">Cập nhật mật khẩu</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</main>
@endsection

@push('scripts')
<script src="{{ asset('js/profile.js') }}"></script>
@endpush
