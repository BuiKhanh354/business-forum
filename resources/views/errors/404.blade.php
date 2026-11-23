@php
    $currentThemeMode = session('theme_mode', $siteSettings['theme_mode'] ?? 'light');
@endphp
<!DOCTYPE html>
<html lang="vi" @if($currentThemeMode === 'dark') data-theme="dark" @elseif(!empty($siteSettings['theme_preset']) && $siteSettings['theme_preset'] !== 'default' && $siteSettings['theme_preset'] !== 'custom') data-theme="{{ $siteSettings['theme_preset'] }}" @endif>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="{{ asset('js/theme-init.js') }}"></script>
    <title>404 - Không tìm thấy trang | {{ $siteSettings['site_name'] ?? 'KẾT NỐI' }}</title>

    @if(!empty($siteSettings['site_favicon']))
    <link rel="icon" href="{{ asset($siteSettings['site_favicon']) }}">
    @endif

    <!-- Font Awesome Icons 6.5.1 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Direct Core CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <style>
        :root {
            --primary-navy: #0f172a;
            --executive-blue: {{ $siteSettings['primary_color'] ?? '#1e3a8a' }};
            --primary-blue: {{ $siteSettings['primary_color'] ?? '#2563eb' }};
            --gold-accent: {{ $siteSettings['accent_color'] ?? '#d97706' }};
            --bg-page: #f8fafc;
            --bg-card: #ffffff;
            --border-card: #e2e8f0;
            --text-title: #0f172a;
            --text-body: #334155;
            --text-muted: #64748b;
        }

        [data-theme="dark"], body.theme-dark {
            --bg-page: #080c16;
            --bg-card: #111a2e;
            --border-card: #202d48;
            --text-title: #f8fafc;
            --text-body: #cbd5e1;
            --text-muted: #94a3b8;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        }

        body {
            background-color: var(--bg-page);
            background-image: 
                radial-gradient(at 0% 0%, rgba(37, 99, 235, 0.06) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(217, 119, 6, 0.05) 0px, transparent 50%),
                radial-gradient(at 50% 50%, rgba(15, 23, 42, 0.02) 0px, transparent 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 2rem 1.25rem;
            color: var(--text-body);
            transition: background-color 0.3s ease, color 0.3s ease;
            position: relative;
        }

        /* Nút đổi theme góc phải */
        .corner-actions {
            position: absolute;
            top: 1.5rem;
            right: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            z-index: 20;
        }

        .theme-btn {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            border: 1px solid var(--border-card);
            background: var(--bg-card);
            color: var(--text-muted);
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.05rem;
            transition: all 0.2s ease;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .theme-btn:hover {
            color: var(--gold-accent);
            transform: scale(1.05);
            border-color: var(--gold-accent);
        }

        /* Thẻ 404 trung tâm */
        .error-404-card {
            max-width: 680px;
            width: 100%;
            text-align: center;
            background: var(--bg-card);
            border: 1px solid var(--border-card);
            border-radius: 20px;
            padding: 3.5rem 2.5rem;
            box-shadow: 0 20px 45px rgba(0, 0, 0, 0.06);
            position: relative;
            z-index: 10;
            transition: background-color 0.3s ease, border-color 0.3s ease, box-shadow 0.3s ease;
        }

        @media (max-width: 640px) {
            .error-404-card {
                padding: 2.5rem 1.5rem;
            }
            .corner-actions {
                top: 1rem;
                right: 1rem;
            }
        }

        .brand-link-404 {
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            text-decoration: none;
            margin-bottom: 1.5rem;
        }

        .brand-name-404 {
            font-size: 1.35rem;
            font-weight: 800;
            letter-spacing: -0.5px;
            background: linear-gradient(135deg, var(--executive-blue), var(--gold-accent));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .error-num {
            font-size: clamp(5.5rem, 15vw, 8.5rem);
            font-weight: 900;
            line-height: 1;
            letter-spacing: -3px;
            background: linear-gradient(135deg, var(--executive-blue) 0%, var(--gold-accent) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 0.5rem;
        }

        .error-icon-badge {
            font-size: 2.2rem;
            color: var(--gold-accent);
            margin-top: -1.75rem;
            margin-bottom: 1.25rem;
            filter: drop-shadow(0 2px 10px rgba(217, 119, 6, 0.35));
        }

        .error-title {
            font-size: clamp(1.4rem, 4vw, 1.85rem);
            font-weight: 800;
            color: var(--text-title);
            margin-bottom: 0.85rem;
            line-height: 1.3;
        }

        .error-desc {
            font-size: 0.96rem;
            color: var(--text-muted);
            line-height: 1.65;
            max-width: 520px;
            margin: 0 auto 2rem auto;
        }

        /* Khung tìm kiếm */
        .search-404-form {
            max-width: 480px;
            margin: 0 auto 2.25rem auto;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(125, 125, 125, 0.06);
            border: 1px solid var(--border-card);
            border-radius: 12px;
            padding: 0.35rem 0.5rem;
            box-shadow: inset 0 1px 3px rgba(0,0,0,0.02);
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .search-404-form:focus-within {
            border-color: var(--executive-blue);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
        }

        .search-404-input {
            flex: 1;
            border: none;
            background: transparent;
            padding: 0.6rem 0.6rem;
            font-size: 0.92rem;
            color: var(--text-title);
            outline: none;
        }

        .search-404-btn {
            padding: 0.6rem 1.15rem;
            border: none;
            border-radius: 8px;
            background: var(--executive-blue);
            color: #ffffff;
            font-size: 0.86rem;
            font-weight: 700;
            cursor: pointer;
            transition: opacity 0.2s;
        }

        .search-404-btn:hover {
            opacity: 0.9;
        }

        /* Nút thao tác */
        .btn-action-primary {
            display: inline-flex;
            align-items: center;
            gap: 0.55rem;
            padding: 0.85rem 1.85rem;
            border-radius: 10px;
            font-size: 0.92rem;
            font-weight: 700;
            background: var(--executive-blue);
            color: #ffffff;
            text-decoration: none;
            box-shadow: 0 4px 15px rgba(30, 58, 138, 0.25);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .btn-action-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(30, 58, 138, 0.35);
        }

        .btn-action-outline {
            display: inline-flex;
            align-items: center;
            gap: 0.55rem;
            padding: 0.85rem 1.85rem;
            border-radius: 10px;
            font-size: 0.92rem;
            font-weight: 700;
            background: var(--bg-card);
            color: var(--text-title);
            border: 1px solid var(--border-card);
            cursor: pointer;
            transition: transform 0.2s ease, background-color 0.2s ease, border-color 0.2s ease;
        }

        .btn-action-outline:hover {
            transform: translateY(-2px);
            border-color: var(--executive-blue);
            color: var(--executive-blue);
        }

        /* Gợi ý chuyên mục */
        .category-pills-row {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 1rem;
        }

        .cat-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.45rem 0.95rem;
            border-radius: 20px;
            font-size: 0.82rem;
            font-weight: 600;
            background: rgba(125, 125, 125, 0.06);
            color: var(--text-body);
            border: 1px solid var(--border-card);
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .cat-pill:hover {
            border-color: var(--executive-blue);
            color: var(--executive-blue);
            transform: translateY(-1px);
        }
    </style>
</head>
<body class="{{ (($siteSettings['theme_mode'] ?? '') === 'dark') ? 'theme-dark' : '' }}">

    <!-- NÚT GÓC PHẢI: ĐỔI SÁNG / TỐI & VỀ TRANG CHỦ -->
    <div class="corner-actions">
        <a href="{{ route('home') }}" class="theme-btn" title="Về trang chủ">
            <i class="fa-solid fa-house"></i>
        </a>
        <button type="button" class="theme-btn" id="themeToggleBtn404" title="Đổi chế độ Sáng / Tối" onclick="toggleDarkMode404()">
            <i class="fa-solid fa-moon"></i>
        </button>
    </div>

    <!-- KHỐI CARD NỘI DUNG 404 TRUNG TÂM -->
    <div class="error-404-card">
        
        <!-- LOGO BRAND WEBSITE -->
        <a href="{{ route('home') }}" class="brand-link-404">
            @if(!empty($siteSettings['site_logo']))
            <img src="{{ asset($siteSettings['site_logo']) }}" alt="{{ $siteSettings['site_name'] ?? 'KẾT NỐI' }}" style="height: 36px; object-fit: contain;">
            @endif
            <span class="brand-name-404">{{ $siteSettings['site_name'] ?? 'KẾT NỐI' }}</span>
        </a>

        <!-- BADGE THÔNG BÁO LỖI -->
        <div style="margin-bottom: 0.75rem;">
            <span style="display: inline-flex; align-items: center; gap: 0.45rem; padding: 0.4rem 1rem; border-radius: 999px; background: rgba(239, 68, 68, 0.1); color: #ef4444; font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.75px;">
                <i class="fa-solid fa-triangle-exclamation"></i> Lỗi 404 - Not Found
            </span>
        </div>

        <!-- SỐ 404 GRADIENT -->
        <div class="error-num">404</div>

        <!-- TIÊU ĐỀ & MÔ TẢ -->
        <h1 class="error-title">Không tìm thấy trang bạn yêu cầu</h1>
        <p class="error-desc">
            Trang web hoặc bài viết bạn đang tìm kiếm có thể đã được gỡ bỏ, đổi tên đường dẫn hoặc tạm thời không khả dụng.
        </p>

        <!-- THANH TÌM KIẾM BÀI VIẾT NHANH -->
        <form action="{{ route('home') }}" method="GET" class="search-404-form">
            <i class="fa-solid fa-magnifying-glass" style="margin-left: 0.65rem; color: var(--text-muted); font-size: 0.95rem;"></i>
            <input type="text" name="search" class="search-404-input" placeholder="Tìm kiếm bài viết, tài liệu, chiến lược...">
            <button type="submit" class="search-404-btn">Tìm kiếm</button>
        </form>

        <!-- NÚT ĐIỀU HƯỚNG CHÍNH -->
        <div style="display: flex; align-items: center; justify-content: center; gap: 0.85rem; flex-wrap: wrap; margin-bottom: 2.25rem;">
            <a href="{{ route('home') }}" class="btn-action-primary">
                <i class="fa-solid fa-house"></i> Về trang chủ
            </a>
            <button type="button" onclick="if(window.history.length > 1) { window.history.back(); } else { window.location.href='{{ route('home') }}'; }" class="btn-action-outline">
                <i class="fa-solid fa-arrow-left"></i> Quay lại trang trước
            </button>
        </div>

        <!-- GỢI Ý CHUYÊN MỤC PHỔ BIẾN -->
        @if(!empty($globalCategories) && $globalCategories->count() > 0)
        <div style="border-top: 1px dashed var(--border-card); padding-top: 1.5rem;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.5rem;">
                <span style="font-size: 0.82rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">
                    <i class="fa-solid fa-layer-group" style="margin-right: 0.35rem; color: var(--gold-accent);"></i> Khám phá chuyên mục
                </span>
                <a href="{{ route('home') }}" style="font-size: 0.8rem; font-weight: 600; color: var(--executive-blue); text-decoration: none;">Xem tất cả &rarr;</a>
            </div>
            <div class="category-pills-row">
                @foreach($globalCategories->take(6) as $cat)
                <a href="{{ route('home', ['category' => $cat->slug]) }}" class="cat-pill">
                    <span style="width: 7px; height: 7px; border-radius: 50%; background-color: {{ $cat->color ?? '#2563eb' }}; display: inline-block;"></span>
                    <span>{{ $cat->name }}</span>
                    @if(isset($cat->posts_count))
                    <span style="opacity: 0.65; font-size: 0.75rem;">({{ $cat->posts_count }})</span>
                    @endif
                </a>
                @endforeach
            </div>
        </div>
        @endif

    </div>

    <!-- JAVASCRIPT HỖ TRỢ ĐỔI DARK MODE & TƯƠNG TÁC ĐỘC LẬP TRÊN 404 -->
    <script src="{{ asset('js/error-404.js') }}"></script>
</body>
</html>
