@php
  $currentThemeMode = session('theme_mode', $siteSettings['theme_mode'] ?? 'light');
@endphp
<!DOCTYPE html>
<html lang="vi" @if($currentThemeMode === 'dark') data-theme="dark" @elseif(!empty($siteSettings['theme_preset']) && $siteSettings['theme_preset'] !== 'default' && $siteSettings['theme_preset'] !== 'custom') data-theme="{{ $siteSettings['theme_preset'] }}" @endif>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @if(app()->environment('production'))
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    @endif
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="asset-url" content="{{ asset('') }}">
    <script src="{{ asset('js/theme-init.js') }}"></script>
    <title>@yield('title', $siteSettings['site_name'] ?? 'KẾT NỐI')</title>

    @if(!empty($siteSettings['site_favicon']))
    <link rel="icon" href="{{ asset($siteSettings['site_favicon']) }}">
    @endif

    <!-- Font Awesome Icons 6.5.1 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Direct Core CSS & Laravel Vite Bundled Assets -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Cấu hình giao diện động từ Database -->
    <style>
        :root {
            @if(!empty($siteSettings['primary_color']))
            --executive-blue: {{ $siteSettings['primary_color'] }};
            --primary-blue: {{ $siteSettings['primary_color'] }};
            --executive-blue-light: {{ $siteSettings['primary_color'] }}18;
            --executive-blue-border: {{ $siteSettings['primary_color'] }}40;
            @endif
            @if(!empty($siteSettings['accent_color']))
            --gold-accent: {{ $siteSettings['accent_color'] }};
            @endif
            @if(!empty($siteSettings['bg_color']) && ($siteSettings['theme_mode'] ?? '') !== 'dark')
            --bg-app: {{ $siteSettings['bg_color'] }};
            @endif
            @if(!empty($siteSettings['card_bg_color']) && ($siteSettings['theme_mode'] ?? '') !== 'dark')
            --bg-card: {{ $siteSettings['card_bg_color'] }};
            @endif
            @if(($siteSettings['border_radius'] ?? '') === 'sharp')
            --radius-sm: 3px;
            --radius-md: 4px;
            @elseif(($siteSettings['border_radius'] ?? '') === 'round')
            --radius-sm: 14px;
            --radius-md: 20px;
            @endif
        }

        /* Giao diện Tối Obsidian Dark Mode: Màu đen sang trọng, độ tương phản cao, chữ rõ nét */
        [data-theme="dark"], body.theme-dark {
            --obsidian: #f8fafc !important;
            --obsidian-surface: #0b1120 !important;
            --bg-app: #080c16 !important;
            --bg-card: #111a2e !important;
            --bg-subtle: #19243d !important;
            --bg-hover: #223254 !important;
            --text-title: #f8fafc !important;
            --text-body: #cbd5e1 !important;
            --text-muted: #94a3b8 !important;
            --text-light-muted: #64748b !important;
            --border-card: #202d48 !important;
            --border-light: #162035 !important;
            --executive-blue-light: rgba(37, 99, 235, 0.22) !important;
            --executive-blue-border: rgba(37, 99, 235, 0.45) !important;
            --gold-light: rgba(217, 119, 6, 0.22) !important;
        }

        /* ẢNH BÌA HERO BANNER THÍCH ỨNG ĐỘC LẬP CHO DESKTOP VÀ MOBILE */
        #ve-toi {
            background-image: url('{{ asset($siteSettings['cover_desktop'] ?? 'assets/images/cover-banner.jpg') }}') !important;
            min-height: {{ $siteSettings['cover_height_desktop'] ?? '350px' }} !important;
            height: {{ $siteSettings['cover_height_desktop'] ?? '350px' }} !important;
            background-size: cover !important;
            background-position: center !important;
        }

        @media (max-width: 768px) {
            #ve-toi {
                background-image: url('{{ asset($siteSettings['cover_mobile'] ?? 'assets/images/cover-night-skyline.jpg') }}') !important;
                min-height: {{ $siteSettings['cover_height_mobile'] ?? '220px' }} !important;
                height: {{ $siteSettings['cover_height_mobile'] ?? '220px' }} !important;
            }
        }
    </style>

    @stack('styles')
</head>
<body class="@yield('body-class', '') {{ (($siteSettings['theme_mode'] ?? '') === 'dark') ? 'theme-dark' : '' }}">

    <div class="app-container">
        <!-- SIDEBAR MEDIAN UI -->
        @include('components.sidebar')

        <!-- BACKDROP MỜ CHO SIDEBAR TRÊN MOBILE -->
        <div class="sidebar-backdrop" id="sidebarBackdrop"></div>

        <!-- NỘI DUNG CHÍNH (APP MAIN) -->
        <div class="app-main">
            <!-- TOPBAR CHUẨN MEDIAN UI -->
            @include('components.topbar')

            <!-- THÔNG BÁO FLASH MESSAGE TOÀN HỆ THỐNG -->
            @if(session('success'))
            <div style="margin: 1.25rem 1.75rem 0 1.75rem; background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; padding: 0.85rem 1.25rem; border-radius: 8px; font-size: 0.88rem; font-weight: 600; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 1px 3px rgba(0,0,0,0.05); animation: fadeInAlert 0.3s ease;">
                <div style="display: flex; align-items: center; gap: 0.65rem;">
                    <i class="fa-solid fa-circle-check" style="color: #10b981; font-size: 1.15rem;"></i>
                    <span>{{ session('success') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" style="background: none; border: none; color: #065f46; font-size: 1.25rem; cursor: pointer; line-height: 1; padding: 0 0.25rem;">&times;</button>
            </div>
            @endif

            @if(session('error'))
            <div style="margin: 1.25rem 1.75rem 0 1.75rem; background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; padding: 0.85rem 1.25rem; border-radius: 8px; font-size: 0.88rem; font-weight: 600; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                <div style="display: flex; align-items: center; gap: 0.65rem;">
                    <i class="fa-solid fa-triangle-exclamation" style="color: #ef4444; font-size: 1.15rem;"></i>
                    <span>{{ session('error') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" style="background: none; border: none; color: #991b1b; font-size: 1.25rem; cursor: pointer; line-height: 1; padding: 0 0.25rem;">&times;</button>
            </div>
            @endif

            <!-- NỘI DUNG TRANG CUỘN -->
            @yield('content')
        </div>
    </div>

    <!-- MEDIAN UI MOBILE BOTTOM NAVIGATION -->
    @include('components.mobile-nav')

    <!-- KHU VỰC MODAL RIÊNG CỦA TỪNG TRANG -->
    @yield('modals')

    <!-- CHUNG JAVASCRIPT TOÀN HỆ THỐNG -->
    <script src="{{ asset('js/main.js') }}"></script>

    @stack('scripts')
</body>
</html>
