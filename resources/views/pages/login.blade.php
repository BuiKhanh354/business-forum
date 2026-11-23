<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Đăng nhập Quản trị viên - KẾT NỐI Business Community</title>
    
    <!-- Font Awesome Icons 6.5.1 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-navy: #0f172a;
            --primary-blue: #2563eb;
            --primary-blue-hover: #1d4ed8;
            --primary-blue-light: rgba(37, 99, 235, 0.1);
            --gold-accent: #d97706;
            --bg-page: #f8fafc;
            --card-bg: #ffffff;
            --border-color: #e2e8f0;
            --text-title: #0f172a;
            --text-body: #334155;
            --text-muted: #64748b;
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
                radial-gradient(at 0% 0%, rgba(37, 99, 235, 0.05) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(217, 119, 6, 0.05) 0px, transparent 50%),
                radial-gradient(at 50% 50%, rgba(15, 23, 42, 0.02) 0px, transparent 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            color: var(--text-body);
        }

        /* Top Navbar */
        .login-navbar {
            padding: 1.25rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border-color);
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .site-logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
        }

        .logo-badge {
            width: 38px;
            height: 38px;
            border-radius: 8px;
            background: #0f172a;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            box-shadow: 0 4px 10px rgba(15, 23, 42, 0.15);
        }

        .logo-text-wrap {
            display: flex;
            flex-direction: column;
            line-height: 1.15;
        }

        .logo-text-wrap .main-name {
            font-size: 1.15rem;
            font-weight: 800;
            color: var(--primary-navy);
            letter-spacing: -0.3px;
        }

        .logo-text-wrap .sub-name {
            font-size: 0.68rem;
            font-weight: 700;
            color: var(--gold-accent);
            letter-spacing: 0.8px;
        }

        .login-back-link {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            font-size: 0.86rem;
            font-weight: 700;
            color: var(--text-muted);
            text-decoration: none;
            padding: 0.5rem 0.85rem;
            border-radius: 6px;
            border: 1px solid var(--border-color);
            background: #ffffff;
            transition: all 0.2s ease;
        }

        .login-back-link:hover {
            color: var(--primary-blue);
            border-color: var(--primary-blue);
            background: var(--primary-blue-light);
        }

        /* Container & Card */
        .login-container {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2.5rem 1rem;
        }

        .login-card {
            width: 100%;
            max-width: 440px;
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 14px;
            padding: 2.5rem 2.25rem;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.06), 0 1px 3px rgba(15, 23, 42, 0.04);
            position: relative;
        }

        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .login-badge-wrap {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 0.75rem;
            font-weight: 700;
            color: var(--primary-blue);
            background: var(--primary-blue-light);
            padding: 0.3rem 0.75rem;
            border-radius: 999px;
            margin-bottom: 0.85rem;
        }

        .login-title {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--text-title);
            margin-bottom: 0.45rem;
            letter-spacing: -0.3px;
        }

        .login-subtitle {
            font-size: 0.84rem;
            color: var(--text-muted);
            line-height: 1.5;
        }

        /* Alert notifications */
        .login-alert {
            padding: 0.75rem 1rem;
            border-radius: 8px;
            font-size: 0.84rem;
            display: flex;
            align-items: center;
            gap: 0.65rem;
            margin-bottom: 1.5rem;
            transition: all 0.2s ease;
        }

        .login-alert-error {
            background-color: #fef2f2;
            border: 1px solid #fecaca;
            color: #b91c1c;
        }

        .login-alert-success {
            background-color: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #15803d;
        }

        /* Form elements */
        .form-group {
            margin-bottom: 1.35rem;
        }

        .form-label {
            display: block;
            font-size: 0.84rem;
            font-weight: 700;
            color: var(--text-title);
            margin-bottom: 0.5rem;
        }

        .link-input-container {
            position: relative;
            display: flex;
            align-items: center;
        }

        .link-icon {
            position: absolute;
            left: 0.85rem;
            color: #94a3b8;
            display: flex;
            align-items: center;
            justify-content: center;
            pointer-events: none;
            font-size: 0.95rem;
        }

        .form-input {
            width: 100%;
            height: 44px;
            padding: 0 1rem 0 2.4rem;
            border-radius: 8px;
            border: 1px solid #cbd5e1;
            font-size: 0.88rem;
            color: var(--text-title);
            background: #ffffff;
            outline: none;
            transition: all 0.2s ease;
        }

        .form-input:focus {
            border-color: var(--primary-blue);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
        }

        .password-input-wrap .form-input {
            padding-right: 2.75rem;
        }

        .btn-toggle-pwd {
            position: absolute;
            right: 0.75rem;
            background: none;
            border: none;
            color: #94a3b8;
            cursor: pointer;
            padding: 0.35rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 4px;
            transition: color 0.15s ease;
        }

        .btn-toggle-pwd:hover {
            color: var(--text-title);
        }

        .login-options-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
            font-size: 0.82rem;
        }

        .remember-label {
            display: flex;
            align-items: center;
            gap: 0.45rem;
            cursor: pointer;
            color: var(--text-body);
            user-select: none;
        }

        .remember-label input[type="checkbox"] {
            accent-color: var(--primary-blue);
            width: 16px;
            height: 16px;
            cursor: pointer;
        }

        .forgot-link {
            color: var(--primary-blue);
            text-decoration: none;
            font-weight: 600;
        }

        .forgot-link:hover {
            text-decoration: underline;
        }

        .btn-login-submit {
            width: 100%;
            height: 46px;
            background: var(--primary-navy);
            color: #ffffff;
            border: none;
            border-radius: 8px;
            font-size: 0.92rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.6rem;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.15);
        }

        .btn-login-submit:hover {
            background: #1e293b;
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(15, 23, 42, 0.2);
        }

        .btn-login-submit:active {
            transform: translateY(0);
        }

        .btn-login-submit:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }

        /* Demo hint */
        .demo-account-hint {
            margin-top: 1.75rem;
            padding: 1.1rem;
            background: #f8fafc;
            border: 1px dashed #cbd5e1;
            border-radius: 8px;
            font-size: 0.8rem;
            color: var(--text-muted);
            text-align: center;
        }

        .demo-account-hint strong {
            color: var(--text-title);
        }

        .demo-account-hint code {
            background: #e2e8f0;
            color: #0f172a;
            padding: 0.15rem 0.4rem;
            border-radius: 4px;
            font-weight: 600;
            font-size: 0.82rem;
        }

        .btn-fill-demo {
            margin-top: 0.75rem;
            width: 100%;
            padding: 0.5rem;
            background: #ffffff;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            font-size: 0.78rem;
            font-weight: 700;
            color: var(--primary-blue);
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.4rem;
        }

        .btn-fill-demo:hover {
            background: var(--primary-blue-light);
            border-color: var(--primary-blue);
        }

        /* Footer */
        .login-footer {
            padding: 1.5rem;
            text-align: center;
            font-size: 0.78rem;
            color: var(--text-muted);
        }
    </style>
</head>
<body>

  <!-- TOP NAVBAR -->
  <header class="login-navbar">
    <a href="{{ route('home') }}" class="site-logo">
      <div class="logo-badge">
        <i class="fa-brands fa-discourse"></i>
      </div>
      <div class="logo-text-wrap">
        <span class="main-name">{{ $siteSettings['site_name'] ?? 'KẾT NỐI' }}</span>
        <span class="sub-name">BUSINESS COMMUNITY</span>
      </div>
    </a>

    <a href="{{ route('home') }}" class="login-back-link">
      <i class="fa-solid fa-arrow-left"></i>
      <span>Quay lại Blog</span>
    </a>
  </header>

  <!-- FORM CONTAINER -->
  <main class="login-container">
    <div class="login-card">

      <div class="login-header">
        <div class="login-badge-wrap">
          <i class="fa-solid fa-lock"></i>
          <span>Admin Portal</span>
        </div>
        <h1 class="login-title">Đăng nhập Quản trị viên</h1>
        <p class="login-subtitle">Hệ thống quản lý diễn đàn, duyệt bài báo &amp; đề xuất hợp tác</p>
      </div>

      <!-- Thông báo lỗi hoặc thành công từ Session -->
      <div class="login-alert login-alert-error" id="loginAlert" style="{{ (session('error') || $errors->any()) ? 'display: flex;' : 'display: none;' }}">
        <i class="fa-solid fa-circle-exclamation"></i>
        <span id="loginAlertText">
            @if(session('error'))
                {{ session('error') }}
            @elseif($errors->any())
                {{ $errors->first() }}
            @else
                Email hoặc mật khẩu không chính xác!
            @endif
        </span>
      </div>

      @if(session('success'))
      <div class="login-alert login-alert-success" style="display: flex;">
        <i class="fa-solid fa-circle-check"></i>
        <span>{{ session('success') }}</span>
      </div>
      @endif

      <form action="{{ route('login.submit') }}" method="POST" id="adminLoginForm">
        @csrf

        <!-- Email / Tên đăng nhập -->
        <div class="form-group">
          <label class="form-label" for="adminLoginInput">Email hoặc Tên đăng nhập Admin</label>
          <div class="link-input-container">
            <span class="link-icon">
              <i class="fa-regular fa-user"></i>
            </span>
            <input 
              type="text" 
              name="login" 
              id="adminLoginInput" 
              class="form-input" 
              placeholder="admin@gmail.com hoặc Tên tài khoản" 
              value="{{ old('login') }}" 
              required
              autocomplete="username"
            >
          </div>
        </div>

        <!-- Mật khẩu -->
        <div class="form-group">
          <label class="form-label" for="adminPasswordInput">Mật khẩu</label>
          <div class="link-input-container password-input-wrap">
            <span class="link-icon">
              <i class="fa-solid fa-key"></i>
            </span>
            <input 
              type="password" 
              name="password" 
              id="adminPasswordInput" 
              class="form-input" 
              placeholder="Nhập mật khẩu..." 
              required
              autocomplete="current-password"
            >
            <button type="button" class="btn-toggle-pwd" id="btnTogglePassword" title="Hiện/Ẩn mật khẩu">
              <i class="fa-solid fa-eye" id="eyeIcon"></i>
            </button>
          </div>
        </div>

        <!-- Ghi nhớ & Quên mật khẩu -->
        <div class="login-options-row">
          <label class="remember-label">
            <input type="checkbox" name="remember" id="rememberMe" value="1" checked>
            <span>Ghi nhớ đăng nhập</span>
          </label>
          <a href="#" class="forgot-link" onclick="alert('Vui lòng liên hệ quản trị viên cơ sở dữ liệu để cấp lại mật khẩu.'); return false;">Quên mật khẩu?</a>
        </div>

        <!-- Nút Đăng nhập -->
        <button type="submit" class="btn-login-submit" id="btnLoginSubmit">
          <span id="btnSubmitText">Đăng nhập Quản trị</span>
          <i class="fa-solid fa-arrow-right" id="submitArrowIcon"></i>
        </button>
      </form>

      <!-- Hộp gợi ý tài khoản mẫu -->
      <div class="demo-account-hint">
        <div><strong>Tài khoản Quản trị Demo:</strong></div>
        <div style="margin-top: 5px;">
          Tài khoản: <code>adminnnnn@gmail.com</code> hoặc <code>admin</code>
        </div>
        <div style="margin-top: 3px;">
          Mật khẩu: <code>admin123</code>
        </div>
        <button type="button" class="btn-fill-demo" id="btnAutoFillDemo">
          <i class="fa-solid fa-bolt"></i> Điền nhanh tài khoản demo
        </button>
      </div>

    </div>
  </main>

  <footer class="login-footer">
    &copy; {{ date('Y') }} {{ $siteSettings['site_name'] ?? 'KẾT NỐI' }} Business Community. Bảo mật toàn diện.
  </footer>

  <!-- JAVASCRIPT XỬ LÝ ĐĂNG NHẬP RIÊNG BIỆT -->
  <script src="{{ asset('js/login.js') }}"></script>
</body>
</html>
