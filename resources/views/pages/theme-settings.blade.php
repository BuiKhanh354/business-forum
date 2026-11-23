@extends('layouts.app')

@section('title', 'Cài đặt Theme & Giao diện toàn trang - KẾT NỐI Admin')
@section('breadcrumb', 'WORKSPACE / CÀI ĐẶT HỆ THỐNG / GIAO DIỆN & THƯƠNG HIỆU')

@section('content')
<main class="main-content-scroll">
    <!-- TIÊU ĐỀ TRANG VÀ CÁC NÚT THAO TÁC CHÍNH -->
    <div class="content-header-row" style="margin-bottom: 1.5rem; display: flex; align-items: flex-start; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h1 class="forum-main-title" style="font-size: 1.55rem; display: flex; align-items: center; gap: 0.65rem;">
                <i class="fa-solid fa-palette" style="color: var(--executive-blue);"></i>
                <span>Cài đặt Giao diện & Nhận diện Thương hiệu</span>
            </h1>
            <p class="forum-subtitle">
                Tùy chỉnh màu sắc, chế độ sáng/tối, ảnh bìa và thông tin liên hệ được lưu trữ trực tiếp vào cơ sở dữ liệu.
            </p>
        </div>
        <div style="display: flex; gap: 0.65rem; align-items: center; flex-wrap: wrap;">
            <!-- Form khôi phục mặc định -->
            <form action="{{ route('admin.theme.reset') }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn khôi phục toàn bộ giao diện về cài đặt Sapphire Executive mặc định của hệ thống?');" style="margin: 0;">
                @csrf
                <button type="submit" class="cat-pill" style="font-size: 0.84rem; padding: 0.6rem 1.15rem; background: #ffffff; border: 1px solid #cbd5e1; cursor: pointer; display: flex; align-items: center; gap: 0.45rem;">
                    <i class="fa-solid fa-rotate-left"></i> Khôi phục mặc định
                </button>
            </form>

            <!-- Nút submit lưu toàn trang -->
            <button type="button" onclick="document.getElementById('themeSettingsForm').submit();" class="btn-publish-trigger" style="background-color: var(--executive-blue); font-size: 0.86rem; padding: 0.6rem 1.4rem; display: flex; align-items: center; gap: 0.5rem; border: none; border-radius: 6px; color: #ffffff; font-weight: 700; cursor: pointer; box-shadow: 0 2px 6px rgba(37,99,235,0.3);">
                <i class="fa-solid fa-floppy-disk"></i> Lưu & Áp dụng toàn trang
            </button>
        </div>
    </div>

    <!-- BANNER THÔNG BÁO TÍNH NĂNG ĐỒNG BỘ REAL-TIME -->
    <div style="background: var(--executive-blue-light); border: 1px solid var(--executive-blue-border); border-radius: var(--radius-sm); padding: 0.85rem 1.15rem; margin-bottom: 1.75rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 0.75rem;">
        <div style="display: flex; align-items: center; gap: 0.65rem; font-size: 0.84rem; color: var(--executive-blue); font-weight: 600;">
            <i class="fa-solid fa-circle-check" style="font-size: 1.1rem;"></i>
            <span>
                Mọi thay đổi về màu sắc và chế độ hiển thị sẽ <strong>xem trước trực tiếp ngay lập tức</strong> trên trình duyệt và tự động lưu vào cơ sở dữ liệu khi bạn nhấn "Lưu & Áp dụng".
            </span>
        </div>
        <span style="font-size: 0.74rem; background: #ffffff; padding: 0.25rem 0.65rem; border-radius: 4px; font-weight: 800; color: var(--executive-blue); border: 1px solid var(--executive-blue-border);">
            Database Synced
        </span>
    </div>

    <!-- THANH TAB ĐIỀU HƯỚNG NHANH CÁC KHU VỰC CÀI ĐẶT -->
    <div style="display: flex; gap: 0.5rem; margin-bottom: 1.75rem; border-bottom: 2px solid var(--border-card); padding-bottom: 0.5rem; flex-wrap: wrap;">
        <button type="button" class="tab-nav-btn active" onclick="switchThemeTab('tab-appearance', this)" style="padding: 0.65rem 1.25rem; font-size: 0.86rem; font-weight: 800; border-radius: 6px; cursor: pointer; display: flex; align-items: center; gap: 0.45rem;">
            <i class="fa-solid fa-brush"></i> 1. Màu sắc & Giao diện
        </button>
        <button type="button" class="tab-nav-btn" onclick="switchThemeTab('tab-branding', this)" style="padding: 0.65rem 1.25rem; font-size: 0.86rem; font-weight: 700; border-radius: 6px; cursor: pointer; display: flex; align-items: center; gap: 0.45rem;">
            <i class="fa-solid fa-id-card"></i> 2. Thương hiệu & Tên Web
        </button>
        <button type="button" class="tab-nav-btn" onclick="switchThemeTab('tab-cover', this)" style="padding: 0.65rem 1.25rem; font-size: 0.86rem; font-weight: 700; border-radius: 6px; cursor: pointer; display: flex; align-items: center; gap: 0.45rem;">
            <i class="fa-regular fa-image"></i> 3. Ảnh bìa Blog
        </button>
    </div>

    <!-- FORM LƯU CÀI ĐẶT TOÀN DIỆN VÀO DATABASE -->
    <form action="{{ route('admin.theme.update') }}" method="POST" enctype="multipart/form-data" id="themeSettingsForm">
        @csrf

        <!-- CÁC TRƯỜNG DỮ LIỆU ẨN CHO TÙY CHỌN GIAO DIỆN -->
        <input type="hidden" name="theme_mode" id="inputThemeMode" value="{{ $settings['theme_mode'] ?? 'light' }}">
        <input type="hidden" name="theme_preset" id="inputThemePreset" value="{{ $settings['theme_preset'] ?? 'default' }}">
        <input type="hidden" name="border_radius" id="inputBorderRadius" value="{{ $settings['border_radius'] ?? 'default' }}">
        <input type="hidden" name="cover_desktop" id="inputCoverDesktop" value="{{ $settings['cover_desktop'] ?? 'assets/images/cover-banner.jpg' }}">
        <input type="hidden" name="cover_mobile" id="inputCoverMobile" value="{{ $settings['cover_mobile'] ?? 'assets/images/cover-night-skyline.jpg' }}">
        <input type="hidden" name="cover_height_desktop" id="inputCoverHeightDesktop" value="{{ $settings['cover_height_desktop'] ?? '350px' }}">
        <input type="hidden" name="cover_height_mobile" id="inputCoverHeightMobile" value="{{ $settings['cover_height_mobile'] ?? '220px' }}">

        <!-- ==================================================================
             TAB 1: MÀU SẮC & GIAO DIỆN (CHẾ ĐỘ SÁNG TỐI + PALETTE + DRAG COLOR)
             ================================================================== -->
        <div id="tab-appearance" class="theme-tab-pane">
            
            <!-- 1. CHẾ ĐỘ HIỂN THỊ TOÀN TRANG (LIGHT / DARK / AUTO) -->
            <div class="widget-card" style="padding: 1.5rem; margin-bottom: 1.5rem; border: 1px solid var(--border-card);">
                <div class="theme-section-title" style="display: flex; align-items: center; gap: 0.5rem; font-size: 1.1rem; font-weight: 800; color: var(--obsidian); margin-bottom: 0.35rem;">
                    <i class="fa-solid fa-circle-half-stroke" style="color: var(--executive-blue);"></i>
                    <span>Chế độ hiển thị toàn trang (System Theme Mode)</span>
                </div>
                <p style="font-size: 0.82rem; color: var(--text-muted); margin-bottom: 1rem;">
                    Chọn giao diện mặc định cho website của bạn: Chế độ Sáng (Light Mode) hoặc Chế độ Tối (Obsidian Dark), áp dụng đồng bộ cho toàn bộ độc giả và trang quản trị.
                </p>

                <div class="theme-mode-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1rem;">
                    <div class="theme-mode-option {{ ($settings['theme_mode'] ?? 'light') === 'light' ? 'active' : '' }}" id="modeLight" data-mode="light" style="padding: 1.15rem; border-radius: 8px; cursor: pointer; transition: all 0.2s ease;">
                        <div style="display: flex; align-items: center; gap: 0.65rem; margin-bottom: 0.45rem;">
                            <i class="fa-solid fa-sun" style="color: #f59e0b; font-size: 1.25rem;"></i>
                            <div style="font-size: 0.92rem; font-weight: 800;">Giao diện Sáng (Light Mode)</div>
                        </div>
                        <div class="text-muted" style="font-size: 0.76rem; line-height: 1.45;">Nền trắng ngà cao cấp, tương phản cao, tối ưu đọc tin tức và đối thoại doanh nghiệp ban ngày.</div>
                    </div>

                    <div class="theme-mode-option {{ ($settings['theme_mode'] ?? '') === 'dark' ? 'active' : '' }}" id="modeDark" data-mode="dark" style="padding: 1.15rem; border-radius: 8px; cursor: pointer; transition: all 0.2s ease;">
                        <div style="display: flex; align-items: center; gap: 0.65rem; margin-bottom: 0.45rem;">
                            <i class="fa-solid fa-moon" style="color: #93c5fd; font-size: 1.25rem;"></i>
                            <div style="font-size: 0.92rem; font-weight: 800;">Giao diện Tối (Obsidian Dark)</div>
                        </div>
                        <div class="text-muted" style="font-size: 0.76rem; line-height: 1.45;">Nền đen obsidian sang trọng, thẻ bài viết dark slate, giảm chói mắt khi đọc bài viết buổi tối.</div>
                    </div>
                </div>
            </div>

            <!-- 2. BẢNG MÀU CHỦ ĐẠO THƯƠNG HIỆU DOANH NGHIỆP (PRESETS) -->
            <div class="widget-card" style="padding: 1.5rem; margin-bottom: 1.5rem; border: 1px solid var(--border-card);">
                <div class="theme-section-title" style="display: flex; align-items: center; gap: 0.5rem; font-size: 1.1rem; font-weight: 800; color: var(--obsidian); margin-bottom: 0.35rem;">
                    <i class="fa-solid fa-swatchbook" style="color: var(--executive-blue);"></i>
                    <span>Bảng màu thương hiệu có sẵn (1-Click Presets)</span>
                </div>
                <p style="font-size: 0.82rem; color: var(--text-muted); margin-bottom: 1rem;">
                    Nhấp chọn bảng màu bên dưới để áp dụng trực tiếp cho toàn bộ menu, nút bấm, biểu tượng active và các điểm nhấn trên trang web:
                </p>

                <div class="theme-presets-row" id="palettePresetsRow" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(230px, 1fr)); gap: 0.85rem;">
                    <!-- Sapphire Blue -->
                    <button type="button" class="theme-preset-btn {{ ($settings['theme_preset'] ?? 'default') === 'default' ? 'active' : '' }}" data-theme-name="default" data-primary="#2563eb" style="display: flex; align-items: center; gap: 0.75rem; padding: 0.85rem 1rem; border-radius: 8px; cursor: pointer; text-align: left;">
                        <span class="preset-color-dot" style="width: 22px; height: 22px; border-radius: 50%; background-color: #2563eb; display: inline-block; flex-shrink: 0; box-shadow: 0 2px 4px rgba(37,99,235,0.4);"></span>
                        <div>
                            <div style="font-size: 0.84rem; font-weight: 800;">Sapphire Executive</div>
                            <div class="text-muted" style="font-size: 0.72rem;">Xanh hoàng gia mặc định</div>
                        </div>
                    </button>

                    <!-- Champagne Gold -->
                    <button type="button" class="theme-preset-btn {{ ($settings['theme_preset'] ?? '') === 'gold' ? 'active' : '' }}" data-theme-name="gold" data-primary="#d97706" style="display: flex; align-items: center; gap: 0.75rem; padding: 0.85rem 1rem; border-radius: 8px; cursor: pointer; text-align: left;">
                        <span class="preset-color-dot" style="width: 22px; height: 22px; border-radius: 50%; background-color: #d97706; display: inline-block; flex-shrink: 0; box-shadow: 0 2px 4px rgba(217,119,6,0.4);"></span>
                        <div>
                            <div style="font-size: 0.84rem; font-weight: 800;">Champagne Gold</div>
                            <div class="text-muted" style="font-size: 0.72rem;">Vàng ánh kim, Tài chính</div>
                        </div>
                    </button>

                    <!-- Emerald Green -->
                    <button type="button" class="theme-preset-btn {{ ($settings['theme_preset'] ?? '') === 'emerald' ? 'active' : '' }}" data-theme-name="emerald" data-primary="#059669" style="display: flex; align-items: center; gap: 0.75rem; padding: 0.85rem 1rem; border-radius: 8px; cursor: pointer; text-align: left;">
                        <span class="preset-color-dot" style="width: 22px; height: 22px; border-radius: 50%; background-color: #059669; display: inline-block; flex-shrink: 0; box-shadow: 0 2px 4px rgba(5,150,105,0.4);"></span>
                        <div>
                            <div style="font-size: 0.84rem; font-weight: 800;">Emerald ESG</div>
                            <div class="text-muted" style="font-size: 0.72rem;">Xanh ngọc lục bảo bền vững</div>
                        </div>
                    </button>

                    <!-- Obsidian Slate -->
                    <button type="button" class="theme-preset-btn {{ ($settings['theme_preset'] ?? '') === 'slate' ? 'active' : '' }}" data-theme-name="slate" data-primary="#1e293b" style="display: flex; align-items: center; gap: 0.75rem; padding: 0.85rem 1rem; border-radius: 8px; cursor: pointer; text-align: left;">
                        <span class="preset-color-dot" style="width: 22px; height: 22px; border-radius: 50%; background-color: #1e293b; display: inline-block; flex-shrink: 0; box-shadow: 0 2px 4px rgba(30,41,59,0.4);"></span>
                        <div>
                            <div style="font-size: 0.84rem; font-weight: 800;">Obsidian Slate</div>
                            <div class="text-muted" style="font-size: 0.72rem;">Đen xám tối giản & Quyền lực</div>
                        </div>
                    </button>

                    <!-- Imperial Purple -->
                    <button type="button" class="theme-preset-btn {{ ($settings['theme_preset'] ?? '') === 'purple' ? 'active' : '' }}" data-theme-name="purple" data-primary="#7c3aed" style="display: flex; align-items: center; gap: 0.75rem; padding: 0.85rem 1rem; border-radius: 8px; cursor: pointer; text-align: left;">
                        <span class="preset-color-dot" style="width: 22px; height: 22px; border-radius: 50%; background-color: #7c3aed; display: inline-block; flex-shrink: 0; box-shadow: 0 2px 4px rgba(124,58,237,0.4);"></span>
                        <div>
                            <div style="font-size: 0.84rem; font-weight: 800;">Imperial Purple</div>
                            <div class="text-muted" style="font-size: 0.72rem;">Tím hoàng gia & Đổi mới AI</div>
                        </div>
                    </button>

                    <!-- Crimson Executive -->
                    <button type="button" class="theme-preset-btn {{ ($settings['theme_preset'] ?? '') === 'crimson' ? 'active' : '' }}" data-theme-name="crimson" data-primary="#dc2626" style="display: flex; align-items: center; gap: 0.75rem; padding: 0.85rem 1rem; border-radius: 8px; cursor: pointer; text-align: left;">
                        <span class="preset-color-dot" style="width: 22px; height: 22px; border-radius: 50%; background-color: #dc2626; display: inline-block; flex-shrink: 0; box-shadow: 0 2px 4px rgba(220,38,38,0.4);"></span>
                        <div>
                            <div style="font-size: 0.84rem; font-weight: 800;">Crimson Executive</div>
                            <div class="text-muted" style="font-size: 0.72rem;">Đỏ đô lãnh đạo & Nhiệt huyết</div>
                        </div>
                    </button>
                </div>
            </div>

            <!-- 3. BẢNG MÀU KÉO 2D & CHỌN MÃ HEX TRỰC QUAN -->
            <div class="widget-card" style="padding: 1.5rem; margin-bottom: 1.5rem; border: 1px solid var(--border-card);">
                <div class="theme-section-title" style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.5rem;">
                    <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 1.1rem; font-weight: 800; color: var(--obsidian);">
                        <i class="fa-solid fa-eye-dropper" style="color: var(--executive-blue);"></i>
                        <span>Bảng màu kéo sắc độ 2D & Chọn mã HEX chính xác</span>
                    </div>
                    <span style="font-size: 0.74rem; background: var(--executive-blue-light); color: var(--executive-blue); padding: 0.25rem 0.65rem; border-radius: 999px; font-weight: 800;">
                        Interactive HSV Spectrum
                    </span>
                </div>
                <p style="font-size: 0.82rem; color: var(--text-muted); margin-bottom: 1.25rem;">
                    Kéo con trỏ trong khung 2D và thanh trượt dải màu bên dưới để pha màu bất kỳ theo chuẩn thương hiệu riêng của doanh nghiệp bạn.
                </p>

                <!-- BẢNG MÀU KÉO 2D -->
                <div style="margin-bottom: 1.15rem;">
                    <div id="dragSpectrumBox" class="spectrum-box-wrap" style="background-color: {{ $settings['primary_color'] ?? '#2563eb' }}; height: 180px; position: relative; border-radius: 8px; cursor: crosshair; overflow: hidden; box-shadow: inset 0 0 0 1px rgba(0,0,0,0.15);">
                        <div class="spectrum-sat-white"></div>
                        <div class="spectrum-val-black"></div>
                        <div id="dragSpectrumHandle" class="spectrum-handle" style="left: 85%; top: 20%; background-color: {{ $settings['primary_color'] ?? '#2563eb' }};"></div>
                    </div>
                    <div style="display: flex; justify-content: space-between; font-size: 0.74rem; color: var(--text-light-muted); margin-top: 0.45rem;">
                        <span>← Nhấp chuột & kéo rê con trỏ trong khung để chọn độ đậm/nhạt</span>
                        <span>Góc trên cùng bên phải là màu tươi nhất →</span>
                    </div>
                </div>

                <!-- THANH KÉO DẢI MÀU (HUE SLIDER) -->
                <div style="margin-bottom: 1.5rem;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.45rem;">
                        <span style="font-size: 0.82rem; font-weight: 800; color: var(--text-title);">Thanh dải màu cầu vồng:</span>
                        <span id="hueDegreeText" style="font-size: 0.78rem; font-family: monospace; color: var(--executive-blue); font-weight: 800; background: var(--executive-blue-light); padding: 0.15rem 0.5rem; border-radius: 4px;">221°</span>
                    </div>
                    <div class="hue-slider-track">
                        <input type="range" id="dragHueSlider" class="hue-slider-input" min="0" max="360" value="221">
                    </div>
                </div>

                <!-- TINH CHỈNH 4 MÃ MÀU HEX (CHỦ ĐẠO, ĐIỂM NHẤN, NỀN TRANG, NỀN THẺ) -->
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(210px, 1fr)); gap: 1rem; margin-top: 1.25rem; padding-top: 1.25rem; border-top: 1px solid var(--border-card);">
                    <!-- Màu chủ đạo -->
                    <div style="background: var(--bg-subtle); border: 1px solid var(--border-card); border-radius: 8px; padding: 0.85rem 1rem;">
                        <label style="display: block; font-size: 0.76rem; font-weight: 800; color: var(--text-title); margin-bottom: 0.4rem;">
                            Màu chủ đạo (Primary):
                        </label>
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <input type="color" id="pickerPrimary" value="{{ $settings['primary_color'] ?? '#2563eb' }}" style="width: 38px; height: 38px; border: none; border-radius: 6px; cursor: pointer; padding: 0; background: none;">
                            <input type="text" name="primary_color" id="inputPrimaryColor" value="{{ $settings['primary_color'] ?? '#2563eb' }}" style="flex: 1; font-family: monospace; font-size: 0.9rem; font-weight: 800; text-transform: uppercase; padding: 0.45rem 0.65rem; border: 1px solid var(--border-card); border-radius: 6px; outline: none; background: var(--bg-card); color: var(--text-title);">
                        </div>
                    </div>

                    <!-- Màu điểm nhấn Accent -->
                    <div style="background: var(--bg-subtle); border: 1px solid var(--border-card); border-radius: 8px; padding: 0.85rem 1rem;">
                        <label style="display: block; font-size: 0.76rem; font-weight: 800; color: var(--text-title); margin-bottom: 0.4rem;">
                            Màu điểm nhấn (Accent):
                        </label>
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <input type="color" id="pickerAccent" value="{{ $settings['accent_color'] ?? '#d97706' }}" style="width: 38px; height: 38px; border: none; border-radius: 6px; cursor: pointer; padding: 0; background: none;">
                            <input type="text" name="accent_color" id="inputAccentColor" value="{{ $settings['accent_color'] ?? '#d97706' }}" style="flex: 1; font-family: monospace; font-size: 0.9rem; font-weight: 800; text-transform: uppercase; padding: 0.45rem 0.65rem; border: 1px solid var(--border-card); border-radius: 6px; outline: none; background: var(--bg-card); color: var(--text-title);">
                        </div>
                    </div>

                    <!-- Màu nền trang web App Background -->
                    <div style="background: var(--bg-subtle); border: 1px solid var(--border-card); border-radius: 8px; padding: 0.85rem 1rem;">
                        <label style="display: block; font-size: 0.76rem; font-weight: 800; color: var(--text-title); margin-bottom: 0.4rem;">
                            Màu nền website (Background):
                        </label>
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <input type="color" id="pickerBg" value="{{ $settings['bg_color'] ?? '#f8fafc' }}" style="width: 38px; height: 38px; border: none; border-radius: 6px; cursor: pointer; padding: 0; background: none;">
                            <input type="text" name="bg_color" id="inputBgColor" value="{{ $settings['bg_color'] ?? '#f8fafc' }}" style="flex: 1; font-family: monospace; font-size: 0.9rem; font-weight: 800; text-transform: uppercase; padding: 0.45rem 0.65rem; border: 1px solid var(--border-card); border-radius: 6px; outline: none; background: var(--bg-card); color: var(--text-title);">
                        </div>
                    </div>

                    <!-- Màu nền thẻ bài viết Card Surface -->
                    <div style="background: var(--bg-subtle); border: 1px solid var(--border-card); border-radius: 8px; padding: 0.85rem 1rem;">
                        <label style="display: block; font-size: 0.76rem; font-weight: 800; color: var(--text-title); margin-bottom: 0.4rem;">
                            Màu nền thẻ (Card Surface):
                        </label>
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <input type="color" id="pickerCard" value="{{ $settings['card_bg_color'] ?? '#ffffff' }}" style="width: 38px; height: 38px; border: none; border-radius: 6px; cursor: pointer; padding: 0; background: none;">
                            <input type="text" name="card_bg_color" id="inputCardColor" value="{{ $settings['card_bg_color'] ?? '#ffffff' }}" style="flex: 1; font-family: monospace; font-size: 0.9rem; font-weight: 800; text-transform: uppercase; padding: 0.45rem 0.65rem; border: 1px solid var(--border-card); border-radius: 6px; outline: none; background: var(--bg-card); color: var(--text-title);">
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- ==================================================================
             TAB 2: THƯƠNG HIỆU & TÊN WEB (TÊN WEB, FAVICON)
             ================================================================== -->
        <div id="tab-branding" class="theme-tab-pane" style="display: none;">
            
            <div class="widget-card" style="padding: 1.75rem; margin-bottom: 1.5rem; border: 1px solid var(--border-card);">
                <div class="theme-section-title" style="display: flex; align-items: center; gap: 0.5rem; font-size: 1.15rem; font-weight: 800; color: var(--obsidian); margin-bottom: 0.35rem;">
                    <i class="fa-solid fa-building-columns" style="color: var(--executive-blue);"></i>
                    <span>Nhận diện Thương hiệu & Tên Diễn đàn</span>
                </div>
                <p style="font-size: 0.82rem; color: var(--text-muted); margin-bottom: 1.5rem;">
                    Cấu hình tiêu đề thương hiệu hiển thị trên thanh Sidebar, thanh Menu trên Topbar và thẻ tiêu đề trình duyệt (SEO).
                </p>

                <div style="margin-bottom: 1.25rem;">
                    <!-- Tên Website & Thương hiệu chính -->
                    <label style="display: block; font-size: 0.82rem; font-weight: 700; color: var(--text-title); margin-bottom: 0.45rem;">
                        Tên Website / Thương hiệu (Hiển thị trên Sidebar & Tiêu đề web) <span style="color: #ef4444;">*</span>
                    </label>
                    <input type="text" name="site_name" value="{{ $settings['site_name'] ?? 'KẾT NỐI' }}" required placeholder="Ví dụ: KẾT NỐI" style="width: 100%; padding: 0.65rem 0.85rem; border: 1px solid var(--border-card); border-radius: 6px; font-size: 0.88rem; outline: none; background: var(--bg-card); color: var(--text-title);">
                </div>

                <div style="padding-top: 1.25rem; border-top: 1px solid var(--border-card);">
                    <!-- Upload Favicon -->
                    <div>
                        <label style="display: block; font-size: 0.82rem; font-weight: 700; color: var(--text-title); margin-bottom: 0.35rem;">
                            Biểu tượng Tab trình duyệt (Favicon)
                        </label>
                        <p style="font-size: 0.74rem; color: var(--text-muted); margin-bottom: 0.65rem;">
                            Định dạng ICO, PNG, SVG (Kích thước chuẩn 32x32)
                        </p>

                        <div style="display: flex; align-items: center; gap: 1rem; background: var(--bg-subtle); padding: 1rem; border-radius: 8px; border: 1px solid var(--border-card); max-width: 480px;">
                            <div id="siteFaviconPreviewBox" style="width: 50px; height: 50px; background: var(--bg-card); border: 1px dashed var(--border-card); border-radius: 6px; display: flex; align-items: center; justify-content: center; overflow: hidden; padding: 4px;">
                                @if(!empty($settings['site_favicon']))
                                    <img src="{{ asset($settings['site_favicon']) }}" id="siteFaviconPreviewImg" alt="Favicon" style="max-height: 100%; max-width: 100%; object-fit: contain;">
                                @else
                                    <i id="siteFaviconPlaceholder" class="fa-solid fa-globe" style="font-size: 1.5rem; color: var(--text-light-muted);"></i>
                                @endif
                            </div>
                            <div style="flex: 1;">
                                <input type="file" name="site_favicon" id="siteFaviconFileInput" accept=".ico,.png,.jpg,.svg" style="display: none;" onchange="previewFaviconFile(this)">
                                <button type="button" onclick="document.getElementById('siteFaviconFileInput').click()" class="cat-pill" style="font-size: 0.8rem; padding: 0.45rem 0.85rem; cursor: pointer;">
                                    <i class="fa-solid fa-upload"></i> Chọn Favicon
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- KHỐI: TIÊU ĐỀ & MÔ TẢ KHỐI BÀI VIẾT TRANG CHỦ (CHUYỂN SANG ĐÂY) -->
            <div class="widget-card" style="padding: 1.75rem; margin-bottom: 1.5rem; border: 1px solid var(--border-card);">
                <div class="theme-section-title" style="display: flex; align-items: center; gap: 0.5rem; font-size: 1.15rem; font-weight: 800; color: var(--obsidian); margin-bottom: 0.35rem;">
                    <i class="fa-solid fa-newspaper" style="color: var(--executive-blue);"></i>
                    <span>Tiêu đề Khối Bài viết Trang chủ</span>
                </div>
                <p style="font-size: 0.82rem; color: var(--text-muted); margin-bottom: 1.5rem;">
                    Tùy chỉnh tiêu đề chính và mô tả ngắn phía trên danh sách bài viết trang chủ:
                </p>

                <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 1.25rem;">
                    <div>
                        <label style="display: block; font-size: 0.82rem; font-weight: 700; color: var(--text-title); margin-bottom: 0.45rem;">
                            Tiêu đề chính:
                        </label>
                        <input type="text" name="blog_title" value="{{ $settings['blog_title'] ?? 'Blog' }}" placeholder="Ví dụ: Blog hoặc Diễn đàn Doanh nghiệp" style="width: 100%; padding: 0.65rem 0.85rem; border: 1px solid var(--border-card); border-radius: 6px; font-size: 0.88rem; outline: none; background: var(--bg-card); color: var(--text-title);">
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.82rem; font-weight: 700; color: var(--text-title); margin-bottom: 0.45rem;">
                            Mô tả phụ:
                        </label>
                        <input type="text" name="blog_subtitle" value="{{ $settings['blog_subtitle'] ?? 'Cùng trao đổi góc nhìn chiến lược, cập nhật thị trường và chia sẻ kiến thức quản trị.' }}" placeholder="Mô tả dưới tiêu đề chính..." style="width: 100%; padding: 0.65rem 0.85rem; border: 1px solid var(--border-card); border-radius: 6px; font-size: 0.88rem; outline: none; background: var(--bg-card); color: var(--text-title);">
                    </div>
                </div>
            </div>

        </div>

        <!-- ==================================================================
             TAB 3: ẢNH BÌA BLOG (DESKTOP & MOBILE COVER + HEIGHT + TITLE)
             ================================================================== -->
        <div id="tab-cover" class="theme-tab-pane" style="display: none;">
            
            <div class="widget-card" style="padding: 1.75rem; margin-bottom: 1.5rem; border: 1px solid var(--border-card);">
                <div style="display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 1.25rem;">
                    <div>
                        <div class="theme-section-title" style="display: flex; align-items: center; gap: 0.5rem; font-size: 1.15rem; font-weight: 800; color: var(--obsidian); margin-bottom: 0.35rem;">
                            <i class="fa-regular fa-image" style="color: var(--executive-blue);"></i>
                            <span>Tùy chỉnh Ảnh bìa Hero Banner (Desktop & Mobile)</span>
                        </div>
                        <p style="font-size: 0.84rem; color: var(--text-muted); line-height: 1.5;">
                            Quản lý hình nền bìa xuất hiện ở đầu trang Blog. Bạn có thể tải ảnh riêng cho máy tính và ảnh tối ưu tỷ lệ dọc cho điện thoại.
                        </p>
                    </div>
                </div>

                <!-- NÚT CHUYỂN TAB THIẾT BỊ (DESKTOP / MOBILE) -->
                <div style="display: flex; gap: 0.75rem; margin-bottom: 1.5rem; border-bottom: 1px solid var(--border-card); padding-bottom: 1rem; flex-wrap: wrap;">
                    <button type="button" class="cover-tab-btn active" id="tabCoverDesktop" onclick="switchCoverDevice('desktop')" style="padding: 0.6rem 1.15rem; font-size: 0.84rem; font-weight: 800; border-radius: 6px; border: none; background: var(--executive-blue); color: #fff; cursor: pointer;">
                        <i class="fa-solid fa-desktop"></i> Ảnh bìa Máy tính (Desktop)
                    </button>
                    <button type="button" class="cover-tab-btn" id="tabCoverMobile" onclick="switchCoverDevice('mobile')" style="padding: 0.6rem 1.15rem; font-size: 0.84rem; font-weight: 700; border-radius: 6px; border: 1px solid #cbd5e1; background: #ffffff; color: var(--text-title); cursor: pointer;">
                        <i class="fa-solid fa-mobile-screen"></i> Ảnh bìa Điện thoại (Mobile)
                    </button>
                </div>

                <!-- ================= 1. KHUNG DESKTOP COVER ================= -->
                <div id="panelCoverDesktop">
                    <!-- KHUNG XEM TRƯỚC ẢNH BÌA DESKTOP -->
                    <div style="margin-bottom: 1.5rem;">
                        <div style="font-size: 0.82rem; font-weight: 800; color: var(--text-title); margin-bottom: 0.5rem; display: flex; justify-content: space-between; align-items: center;">
                            <span>Xem trước ảnh bìa Máy tính:</span>
                            <span id="coverCurrentHeightText" style="font-size: 0.78rem; color: var(--executive-blue); font-weight: 700; background: var(--executive-blue-light); padding: 0.2rem 0.6rem; border-radius: 4px;">
                                Chiều cao: {{ $settings['cover_height_desktop'] ?? '350px' }}
                            </span>
                        </div>
                        <div id="themeCoverPreviewBox" style="min-height: {{ $settings['cover_height_desktop'] ?? '350px' }}; height: {{ $settings['cover_height_desktop'] ?? '350px' }}; border-radius: 6px; position: relative; background: url('{{ asset($settings['cover_desktop'] ?? 'assets/images/cover-banner.jpg') }}'); background-size: cover; background-position: center; border: 2px solid rgba(37, 99, 235, 0.3); box-shadow: 0 4px 15px rgba(0,0,0,0.12); overflow: hidden; transition: height 0.3s cubic-bezier(0.4, 0, 0.2, 1), min-height 0.3s cubic-bezier(0.4, 0, 0.2, 1);">
                        </div>
                    </div>

                    <!-- NÚT TẢI ẢNH DESKTOP MỚI & THAO TÁC -->
                    <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 0.85rem; padding: 1rem 1.25rem; background: var(--bg-subtle); border-radius: 8px; border: 1px solid var(--border-card); margin-bottom: 1.5rem;">
                        <div style="display: flex; align-items: center; gap: 0.75rem; flex-wrap: wrap;">
                            <input type="file" name="cover_desktop_file" id="coverDesktopFileInput" accept="image/*" style="display: none;" onchange="handleCoverUpload(this, 'themeCoverPreviewBox', 'inputCoverDesktop', 'coverDesktopHistoryGrid')">
                            <button type="button" class="btn-publish-trigger" onclick="document.getElementById('coverDesktopFileInput').click()" style="background-color: var(--executive-blue); font-size: 0.86rem; padding: 0.6rem 1.25rem; display: flex; align-items: center; gap: 0.5rem; border: none; color: #fff; border-radius: 6px; cursor: pointer;">
                                <i class="fa-solid fa-camera"></i> Tải ảnh bìa Desktop từ máy tính
                            </button>
                            <button type="button" class="cat-pill" onclick="resetCoverToPreset('assets/images/cover-banner.jpg', 'themeCoverPreviewBox', 'inputCoverDesktop', 'coverDesktopHistoryGrid')" style="font-size: 0.82rem; padding: 0.6rem 1rem; border-radius: 6px; cursor: pointer;">
                                <i class="fa-solid fa-rotate-left"></i> Dùng ảnh mẫu gốc
                            </button>
                        </div>
                    </div>

                    <!-- LỊCH SỬ ẢNH BÌA DESKTOP ĐÃ DÙNG / ẢNH MẪU (CHỌN NHANH) -->
                    <div style="margin-bottom: 1.5rem;">
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.65rem;">
                            <label style="font-size: 0.82rem; font-weight: 800; color: var(--text-title); margin: 0;">
                                <i class="fa-solid fa-clock-rotate-left" style="color: var(--executive-blue);"></i> Hoặc chọn nhanh ảnh bìa Desktop cũ / ảnh mẫu đã dùng:
                            </label>
                            <span style="font-size: 0.74rem; color: var(--text-muted);">
                                Nhấp vào ảnh để áp dụng ngay
                            </span>
                        </div>
                        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(170px, 1fr)); gap: 0.85rem;" id="coverDesktopHistoryGrid">
                            @forelse($coverDesktopHistory as $index => $oldCover)
                                <div class="cover-quick-card" onclick="selectCoverPreset('{{ $oldCover }}', 'themeCoverPreviewBox', 'inputCoverDesktop', this, 'coverDesktopHistoryGrid')" style="border-radius: 6px; padding: 0.65rem 0.5rem; text-align: center; cursor: pointer; transition: all 0.2s ease;">
                                    <div style="height: 85px; border-radius: 4px; overflow: hidden; margin-bottom: 0.5rem; background: url('{{ asset($oldCover) }}') center/cover; border: 1px solid var(--border-card); box-shadow: 0 1px 4px rgba(0,0,0,0.06);">
                                    </div>
                                    <div style="font-size: 0.76rem; font-weight: 700; color: var(--text-title); margin-bottom: 0.35rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                        Ảnh bìa cũ #{{ $index + 1 }}
                                    </div>
                                    <span class="preset-status-pill" style="font-size: 0.7rem; font-weight: 700; padding: 0.2rem 0.55rem; border-radius: 4px; display: inline-block;">
                                        Dùng lại ảnh này
                                    </span>
                                </div>
                            @empty
                                <div style="grid-column: 1 / -1; padding: 1rem; text-align: center; color: var(--text-muted); font-size: 0.82rem; background: var(--bg-card); border-radius: 6px; border: 1px dashed var(--border-card);">
                                    Chưa có ảnh bìa cũ nào trong lịch sử
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- CHIỀU CAO ẢNH BÌA DESKTOP -->
                    <div>
                        <div style="font-size: 0.82rem; font-weight: 800; color: var(--text-title); margin-bottom: 0.5rem;">
                            Tùy chỉnh chiều cao ảnh bìa trên máy tính:
                        </div>
                        <div style="display: flex; gap: 0.85rem; flex-wrap: wrap;" id="coverHeightBtnGroup">
                            @php $hDesk = $settings['cover_height_desktop'] ?? '350px'; @endphp
                            <button type="button" class="theme-preset-btn {{ $hDesk == '280px' ? 'active' : '' }}" onclick="selectCoverHeight('280px', 'desktop', this)" data-height="280px" style="padding: 0.6rem 1.15rem; cursor: pointer;">
                                <i class="fa-solid fa-arrows-up-down"></i> Gọn gàng (280px)
                            </button>
                            <button type="button" class="theme-preset-btn {{ $hDesk == '350px' ? 'active' : '' }}" onclick="selectCoverHeight('350px', 'desktop', this)" data-height="350px" style="padding: 0.6rem 1.15rem; cursor: pointer;">
                                <i class="fa-solid fa-arrows-up-down"></i> Vừa vặn (350px - Tiêu chuẩn)
                            </button>
                            <button type="button" class="theme-preset-btn {{ $hDesk == '420px' ? 'active' : '' }}" onclick="selectCoverHeight('420px', 'desktop', this)" data-height="420px" style="padding: 0.6rem 1.15rem; cursor: pointer;">
                                <i class="fa-solid fa-arrows-up-down"></i> Hùng vĩ & Lớn (420px)
                            </button>
                        </div>
                    </div>
                </div>

                <!-- ================= 2. KHUNG MOBILE COVER ================= -->
                <div id="panelCoverMobile" style="display: none;">
                    <div style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 6px; padding: 0.85rem 1rem; margin-bottom: 1.5rem; display: flex; align-items: flex-start; gap: 0.6rem;">
                        <i class="fa-solid fa-mobile-screen-button" style="color: #16a34a; font-size: 1.1rem; margin-top: 0.15rem;"></i>
                        <div style="font-size: 0.82rem; color: #166534; line-height: 1.5;">
                            <strong>Tối ưu hóa hiển thị di động:</strong> Khi độc giả truy cập Blog bằng điện thoại hoặc màn hình nhỏ (&le; 768px), hệ thống sẽ hiển thị ảnh bìa riêng này giúp hình ảnh sắc nét, vừa vặn tỷ lệ màn hình dọc mà không bị méo mó hay cắt xén nội dung!
                        </div>
                    </div>

                    <!-- KHUNG MÔ PHỎNG SMARTPHONE MOCKUP -->
                    <div style="margin-bottom: 1.5rem; text-align: center;">
                        <div style="font-size: 0.82rem; font-weight: 800; color: var(--text-title); margin-bottom: 0.75rem; display: flex; justify-content: space-between; align-items: center; max-width: 420px; margin-left: auto; margin-right: auto;">
                            <span>Mô phỏng hiển thị trên Smartphone:</span>
                            <span id="coverCurrentMobileHeightText" style="font-size: 0.78rem; color: #16a34a; font-weight: 700; background: #dcfce7; padding: 0.2rem 0.6rem; border-radius: 4px;">
                                Chiều cao: {{ $settings['cover_height_mobile'] ?? '220px' }}
                            </span>
                        </div>

                        <div class="smartphone-mockup-frame" style="max-width: 320px; margin: 0 auto; background: #0f172a; padding: 12px; border-radius: 36px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.3);">
                            <div class="smartphone-screen-wrap" style="background: #fff; border-radius: 24px; overflow: hidden;">
                                <div style="background: #ffffff; padding: 8px 12px; display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #f1f5f9;">
                                    <span style="font-size: 0.75rem; font-weight: 800; color: #0f172a;">{{ $settings['site_name'] ?? 'KẾT NỐI' }}</span>
                                    <i class="fa-regular fa-moon" style="font-size: 0.75rem; color: #64748b;"></i>
                                </div>
                                <div id="themeCoverPreviewMobileBox" style="background: url('{{ asset($settings['cover_mobile'] ?? 'assets/images/cover-night-skyline.jpg') }}'); background-size: cover; background-position: center; min-height: {{ $settings['cover_height_mobile'] ?? '220px' }}; height: {{ $settings['cover_height_mobile'] ?? '220px' }}; border-radius: 20px; transition: height 0.3s cubic-bezier(0.4, 0, 0.2, 1), min-height 0.3s cubic-bezier(0.4, 0, 0.2, 1);">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TẢI ẢNH MOBILE MỚI & THAO TÁC -->
                    <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 0.85rem; padding: 1rem 1.25rem; background: var(--bg-subtle); border-radius: 8px; border: 1px solid var(--border-card); margin-bottom: 1.5rem;">
                        <div style="display: flex; align-items: center; gap: 0.75rem; flex-wrap: wrap;">
                            <input type="file" name="cover_mobile_file" id="coverMobileFileInput" accept="image/*" style="display: none;" onchange="handleCoverUpload(this, 'themeCoverPreviewMobileBox', 'inputCoverMobile', 'coverMobileHistoryGrid')">
                            <button type="button" class="btn-publish-trigger" onclick="document.getElementById('coverMobileFileInput').click()" style="background-color: #16a34a; font-size: 0.86rem; padding: 0.6rem 1.25rem; display: flex; align-items: center; gap: 0.5rem; border: none; color: #fff; border-radius: 6px; cursor: pointer;">
                                <i class="fa-solid fa-mobile-screen"></i> Tải ảnh bìa Mobile từ máy tính
                            </button>
                            <button type="button" class="cat-pill" onclick="resetCoverToPreset('assets/images/cover-night-skyline.jpg', 'themeCoverPreviewMobileBox', 'inputCoverMobile', 'coverMobileHistoryGrid')" style="font-size: 0.82rem; padding: 0.6rem 1rem; border-radius: 6px; cursor: pointer;">
                                <i class="fa-solid fa-rotate-left"></i> Khôi phục mặc định Mobile
                            </button>
                        </div>
                    </div>

                    <!-- LỊCH SỬ ẢNH BÌA MOBILE ĐÃ DÙNG / ẢNH MẪU (CHỌN NHANH) -->
                    <div style="margin-bottom: 1.5rem;">
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.65rem;">
                            <label style="font-size: 0.82rem; font-weight: 800; color: var(--text-title); margin: 0;">
                                <i class="fa-solid fa-clock-rotate-left" style="color: #16a34a;"></i> Hoặc chọn nhanh ảnh bìa Mobile cũ / ảnh mẫu đã dùng:
                            </label>
                            <span style="font-size: 0.74rem; color: var(--text-muted);">
                                Nhấp vào ảnh để áp dụng ngay
                            </span>
                        </div>
                        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(170px, 1fr)); gap: 0.85rem;" id="coverMobileHistoryGrid">
                            @forelse($coverMobileHistory as $index => $oldMobCover)
                                <div class="cover-quick-card" onclick="selectCoverPreset('{{ $oldMobCover }}', 'themeCoverPreviewMobileBox', 'inputCoverMobile', this, 'coverMobileHistoryGrid')" style="border-radius: 6px; padding: 0.65rem 0.5rem; text-align: center; cursor: pointer; transition: all 0.2s ease;">
                                    <div style="height: 85px; border-radius: 4px; overflow: hidden; margin-bottom: 0.5rem; background: url('{{ asset($oldMobCover) }}') center/cover; border: 1px solid var(--border-card); box-shadow: 0 1px 4px rgba(0,0,0,0.06);">
                                    </div>
                                    <div style="font-size: 0.76rem; font-weight: 700; color: var(--text-title); margin-bottom: 0.35rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                        Ảnh cũ #{{ $index + 1 }}
                                    </div>
                                    <span class="preset-status-pill" style="font-size: 0.7rem; font-weight: 700; padding: 0.2rem 0.55rem; border-radius: 4px; display: inline-block;">
                                        Dùng lại ảnh này
                                    </span>
                                </div>
                            @empty
                                <div style="grid-column: 1 / -1; padding: 1rem; text-align: center; color: var(--text-muted); font-size: 0.82rem; background: var(--bg-card); border-radius: 6px; border: 1px dashed var(--border-card);">
                                    Chưa có ảnh bìa Mobile cũ nào trong lịch sử
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- CHIỀU CAO ẢNH MOBILE -->
                    <div>
                        <div style="font-size: 0.82rem; font-weight: 800; color: var(--text-title); margin-bottom: 0.5rem;">
                            Tùy chỉnh chiều cao ảnh bìa trên điện thoại:
                        </div>
                        <div style="display: flex; gap: 0.85rem; flex-wrap: wrap;" id="coverMobileHeightBtnGroup">
                            @php $hMob = $settings['cover_height_mobile'] ?? '220px'; @endphp
                            <button type="button" class="theme-preset-btn {{ $hMob == '180px' ? 'active' : '' }}" onclick="selectCoverHeight('180px', 'mobile', this)" data-height="180px" style="padding: 0.6rem 1.15rem; cursor: pointer;">
                                <i class="fa-solid fa-arrows-up-down"></i> Gọn gàng (180px)
                            </button>
                            <button type="button" class="theme-preset-btn {{ $hMob == '220px' ? 'active' : '' }}" onclick="selectCoverHeight('220px', 'mobile', this)" data-height="220px" style="padding: 0.6rem 1.15rem; cursor: pointer;">
                                <i class="fa-solid fa-arrows-up-down"></i> Chuẩn Mobile (220px - Khuyên dùng)
                            </button>
                            <button type="button" class="theme-preset-btn {{ $hMob == '260px' ? 'active' : '' }}" onclick="selectCoverHeight('260px', 'mobile', this)" data-height="260px" style="padding: 0.6rem 1.15rem; cursor: pointer;">
                                <i class="fa-solid fa-arrows-up-down"></i> Rộng rãi (260px)
                            </button>
                        </div>
                    </div>
                </div>

            </div>

        </div>

        <!-- FOOTER TOOLBAR NÚT LƯU CỐ ĐỊNH PHÍA DƯỚI -->
        <div style="background: var(--bg-card); border: 1px solid var(--border-card); border-radius: 8px; padding: 1rem 1.5rem; display: flex; align-items: center; justify-content: space-between; margin-top: 1.5rem; box-shadow: 0 4px 12px rgba(0,0,0,0.05); flex-wrap: wrap; gap: 0.75rem;">
            <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.84rem; color: var(--text-muted);">
                <i class="fa-solid fa-shield-halved" style="color: var(--executive-blue);"></i>
                <span>Cấu hình này sẽ được áp dụng cho toàn bộ giao diện và người dùng của hệ thống.</span>
            </div>

            <div style="display: flex; align-items: center; gap: 0.75rem;">
                <button type="submit" class="btn-publish-trigger" style="background-color: var(--executive-blue); font-size: 0.9rem; padding: 0.65rem 1.75rem; border: none; border-radius: 6px; color: #ffffff; font-weight: 800; cursor: pointer; display: flex; align-items: center; gap: 0.5rem; box-shadow: 0 4px 10px rgba(37, 99, 235, 0.25);">
                    <i class="fa-solid fa-floppy-disk"></i> Lưu & Áp dụng ngay
                </button>
            </div>
        </div>

    </form>
</main>
@endsection

@push('scripts')
<script src="{{ asset('js/theme-settings.js') }}"></script>
@endpush
