@extends('layouts.app')

@section('title', 'Liên hệ hợp tác - KẾT NỐI Business Community')
@section('breadcrumb', 'WORKSPACE / LIÊN HỆ & KẾT NỐI DOANH NGHIỆP')

@section('content')
<main class="main-content-scroll">
<!-- BREADCRUMB -->
        <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.82rem; color: var(--text-muted); margin-bottom: 1rem;">
          <a href="{{ route('home') }}" style="color: var(--text-muted); text-decoration: none;">Trang chủ</a>
          <span>/</span>
          <span style="color: var(--text-title); font-weight: 600;">Liên hệ & Kết nối Hợp tác</span>
        </div>

        <!-- ====================================================================
             HEADER TRANG LIÊN HỆ & KẾT NỐI HỢP TÁC
             ==================================================================== -->
        <div class="content-header-row" style="margin-bottom: 2rem;">
          <div>
            <h1 class="forum-main-title" style="display: flex; align-items: center; gap: 0.65rem;">
              <i class="fa-solid fa-handshake" style="color: var(--executive-blue);"></i>
              <span>Liên hệ & Kết nối Hợp tác</span>
            </h1>
            <p class="forum-subtitle">
              Kết nối trực tiếp cùng Ban Quản trị KẾT NỐI • Tiếp nhận đề xuất hợp tác, tài trợ và phát triển kinh doanh.
            </p>
          </div>
          <div style="display: flex; align-items: center; gap: 0.75rem;">
            <a href="{{ route('about') }}" class="btn-median-read" style="background: var(--bg-card); color: var(--text-title); border: 1px solid var(--border-card);">
              <i class="fa-solid fa-user"></i> Về nhà sáng lập
            </a>
          </div>
        </div>

        <!-- DẢI ĐỐI TÁC DOANH NGHIỆP & CƠ QUAN TRUYỀN THÔNG KINH TẾ (CORP PARTNERS) -->
        <div class="corp-partners-strip" style="margin-bottom: 2rem;">
          <div class="corp-partners-heading">
            Mạng lưới đối tác doanh nghiệp & cơ quan truyền thông kinh tế
          </div>
          <div class="corp-partners-grid">
            <div class="corp-partner-item" title="Forbes Vietnam">
              <i class="fa-solid fa-newspaper"></i>
              <span>FORBES VIETNAM</span>
            </div>
            <div class="corp-partner-item" title="Bloomberg Markets">
              <i class="fa-solid fa-chart-line"></i>
              <span>BLOOMBERG</span>
            </div>
            <div class="corp-partner-item" title="VnExpress Kinh Doanh">
              <i class="fa-solid fa-globe"></i>
              <span>VNEXPRESS KINH DOANH</span>
            </div>
            <div class="corp-partner-item" title="Techcombank Priority">
              <i class="fa-solid fa-building-columns"></i>
              <span>TECHCOMBANK PRIORITY</span>
            </div>
            <div class="corp-partner-item" title="Vingroup Capital">
              <i class="fa-solid fa-city"></i>
              <span>VINGROUP CAPITAL</span>
            </div>
            <div class="corp-partner-item" title="FPT Enterprise">
              <i class="fa-solid fa-microchip"></i>
              <span>FPT ENTERPRISE</span>
            </div>
          </div>
        </div>

        <!-- ====================================================================
             4 THẺ CHỈ SỐ CAM KẾT & MẠNG LƯỚI
             ==================================================================== -->
        <div class="contact-stats-grid">
          <div class="contact-stat-card">
            <div class="contact-stat-icon blue">
              <i class="fa-solid fa-bolt-lightning"></i>
            </div>
            <div>
              <div class="contact-stat-value">&lt; 24 Giờ</div>
              <div class="contact-stat-label">Thời gian phản hồi thư ngỏ</div>
            </div>
          </div>

          <div class="contact-stat-card">
            <div class="contact-stat-icon amber">
              <i class="fa-solid fa-building-user"></i>
            </div>
            <div>
              <div class="contact-stat-value">150+ Đối tác</div>
              <div class="contact-stat-label">Doanh nghiệp, Quỹ & Chuyên gia</div>
            </div>
          </div>

          <div class="contact-stat-card">
            <div class="contact-stat-icon emerald">
              <i class="fa-solid fa-shield-halved"></i>
            </div>
            <div>
              <div class="contact-stat-value">100% Bảo mật</div>
              <div class="contact-stat-label">Thông tin & Đề xuất hợp tác</div>
            </div>
          </div>

          <div class="contact-stat-card">
            <div class="contact-stat-icon purple">
              <i class="fa-solid fa-location-dot"></i>
            </div>
            <div>
              <div class="contact-stat-value">TP.HCM & HN</div>
              <div class="contact-stat-label">Văn phòng tiếp đón đối tác</div>
            </div>
          </div>
        </div>

        <!-- ====================================================================
             BỐ CỤC CHÍNH 2 CỘT: FORM THƯ NGỎ (TRÁI) + THÔNG TIN TRỰC TIẾP (PHẢI)
             ==================================================================== -->
        <div class="contact-layout-grid" id="formContactSection">

          <!-- ================================================================
               CỘT TRÁI: FORM GỬI THƯ NGỎ / ĐỀ XUẤT HỢP TÁC DOANH NGHIỆP
               ================================================================ -->
          <div class="contact-card-box">
            <div class="contact-box-header">
              <h2 class="contact-box-title">
                <i class="fa-solid fa-paper-plane" style="color: var(--executive-blue);"></i>
                Gửi Đề xuất / Thư ngỏ Hợp tác
              </h2>
              <p class="contact-box-subtitle">
                Vui lòng điền thông tin của Quý Doanh nghiệp hoặc cá nhân. Thư ngỏ sẽ được gửi trực tiếp đến Founder Admin để phản hồi nhanh chóng.
              </p>
            </div>

            @if(session('success'))
              <div style="background-color: #dcfce7; border: 1px solid #86efac; color: #166534; padding: 1rem 1.25rem; border-radius: 8px; margin-bottom: 1.5rem; font-size: 0.9rem; font-weight: 600; display: flex; align-items: center; gap: 0.65rem;">
                <i class="fa-solid fa-circle-check" style="font-size: 1.25rem; color: #16a34a; flex-shrink: 0;"></i>
                <div>{{ session('success') }}</div>
              </div>
            @endif

            @if($errors->any())
              <div style="background-color: #fef2f2; border: 1px solid #fecaca; color: #991b1b; padding: 1rem 1.25rem; border-radius: 8px; margin-bottom: 1.5rem; font-size: 0.88rem;">
                <div style="font-weight: 700; margin-bottom: 0.35rem; display: flex; align-items: center; gap: 0.5rem;">
                  <i class="fa-solid fa-triangle-exclamation"></i> Có thông tin chưa hợp lệ:
                </div>
                <ul style="margin: 0; padding-left: 1.25rem;">
                  @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                  @endforeach
                </ul>
              </div>
            @endif

            <form id="contactForm" action="{{ route('contact.submit') }}" method="POST" enctype="multipart/form-data">
              @csrf

              <!-- Hàng 1: Họ tên + Email -->
              <div class="contact-form-row">
                <div class="contact-form-group" style="margin-bottom: 0;">
                  <label class="contact-form-label" for="senderName">
                    Họ và tên người gửi <span class="req">*</span>
                  </label>
                  <input type="text" name="name" id="senderName" class="contact-form-input" placeholder="Ví dụ: Nguyễn Văn An" value="{{ old('name') }}" required>
                </div>

                <div class="contact-form-group" style="margin-bottom: 0;">
                  <label class="contact-form-label" for="senderEmail">
                    Email công việc / cá nhân <span class="req">*</span>
                  </label>
                  <input type="email" name="email" id="senderEmail" class="contact-form-input" placeholder="an.nguyen@company.vn" value="{{ old('email') }}" required>
                </div>
              </div>

              <!-- Hàng 2: Số điện thoại + Tên công ty -->
              <div class="contact-form-row">
                <div class="contact-form-group" style="margin-bottom: 0;">
                  <label class="contact-form-label" for="senderPhone">
                    Số điện thoại / Zalo <span class="req">*</span>
                  </label>
                  <input type="tel" name="phone" id="senderPhone" class="contact-form-input" placeholder="Ví dụ: 0912 345 678" value="{{ old('phone') }}" required>
                </div>

                <div class="contact-form-group" style="margin-bottom: 0;">
                  <label class="contact-form-label" for="senderCompany">
                    Tên Doanh nghiệp / Tổ chức
                  </label>
                  <input type="text" name="company" id="senderCompany" class="contact-form-input" placeholder="Ví dụ: Công ty CP Đầu tư Á Châu" value="{{ old('company') }}">
                </div>
              </div>

              <!-- Hàng 3: Chức vụ + Mục đích kết nối -->
              <div class="contact-form-row">
                <div class="contact-form-group" style="margin-bottom: 0;">
                  <label class="contact-form-label" for="senderRole">
                    Chức vụ / Vai trò
                  </label>
                  <input type="text" name="role" id="senderRole" class="contact-form-input" placeholder="Ví dụ: CEO, Giám đốc Chiến lược, Trợ lý..." value="{{ old('role') }}">
                </div>

                <div class="contact-form-group" style="margin-bottom: 0;">
                  <label class="contact-form-label" for="contactPurpose">
                    Mục đích kết nối <span class="req">*</span>
                  </label>
                  <select name="purpose" id="contactPurpose" class="contact-form-select" required>
                    <option value="">-- Chọn mục đích phù hợp --</option>
                    <option value="Hợp tác nội dung & Bài viết chuyên sâu" {{ old('purpose') === 'Hợp tác nội dung & Bài viết chuyên sâu' ? 'selected' : '' }}>Hợp tác nội dung & Bài viết chuyên sâu</option>
                    <option value="Mời diễn giả / Cố vấn kinh tế" {{ old('purpose') === 'Mời diễn giả / Cố vấn kinh tế' ? 'selected' : '' }}>Mời Diễn giả / Cố vấn Chiến lược & Quản trị</option>
                    <option value="Tài trợ Hội thảo / Sự kiện doanh nghiệp" {{ old('purpose') === 'Tài trợ Hội thảo / Sự kiện doanh nghiệp' ? 'selected' : '' }}>Tài trợ Hội thảo / Sự kiện Business Community</option>
                    <option value="Giới thiệu cơ hội đầu tư & Dòng vốn" {{ old('purpose') === 'Giới thiệu cơ hội đầu tư & Dòng vốn' ? 'selected' : '' }}>Giới thiệu cơ hội Đầu tư & Dòng vốn</option>
                    <option value="Góp ý xây dựng diễn đàn" {{ old('purpose') === 'Góp ý xây dựng diễn đàn' ? 'selected' : '' }}>Góp ý & Đóng góp ý kiến cho diễn đàn</option>
                    <option value="Khác" {{ old('purpose') === 'Khác' ? 'selected' : '' }}>Nội dung hợp tác khác</option>
                  </select>
                </div>
              </div>

              <!-- Tiêu đề liên hệ -->
              <div class="contact-form-group">
                <label class="contact-form-label" for="contactSubject">
                  Tiêu đề thư ngỏ <span class="req">*</span>
                </label>
                <input type="text" name="subject" id="contactSubject" class="contact-form-input" placeholder="Ví dụ: Thư ngỏ hợp tác chuỗi bài phân tích Kinh tế vĩ mô Quý 1/2026" value="{{ old('subject') }}" required>
              </div>

              <!-- Nội dung chi tiết -->
              <div class="contact-form-group">
                <label class="contact-form-label" for="contactMessage">
                  Nội dung trao đổi chi tiết <span class="req">*</span>
                </label>
                <textarea name="message" id="contactMessage" class="contact-form-textarea" placeholder="Kính gửi Ban Quản trị KẾT NỐI, tôi đại diện cho doanh nghiệp muốn trao đổi về..." required>{{ old('message') }}</textarea>
              </div>

              <!-- Upload tài liệu mô phỏng -->
              <div class="contact-form-group">
                <label class="contact-form-label">
                  <span>Hồ sơ năng lực / Tài liệu đính kèm (nếu có)</span>
                  <span style="font-size: 0.75rem; color: var(--text-muted); font-weight: normal;">PDF, DOCX, PPTX &le; 10MB</span>
                </label>
                <div class="contact-upload-zone" id="uploadZoneTrigger" style="cursor: pointer; transition: all 0.2s ease;">
                  <input type="file" name="file_attachment" id="fileAttachment" style="display: none;" accept=".pdf,.doc,.docx,.ppt,.pptx,.zip,image/*">
                  <div class="contact-upload-icon" id="uploadIconWrapper">
                    <i class="fa-solid fa-cloud-arrow-up"></i>
                  </div>
                  <div class="contact-upload-text" id="uploadStatusText">Nhấp để chọn file hoặc kéo thả tài liệu vào đây</div>
                  <div class="contact-upload-hint">Hỗ trợ định dạng PDF, DOCX, Presentation (Tối đa 10MB)</div>
                </div>
              </div>

              <!-- Checkbox cam kết -->
              <div class="contact-consent-row">
                <input type="checkbox" id="consentCheck" required checked>
                <label for="consentCheck">
                  Tôi cam kết thông tin cung cấp là chính xác và đồng ý để Founder KẾT NỐI liên hệ phản hồi qua Email hoặc Số điện thoại.
                </label>
              </div>

              <!-- Nút hành động -->
              <div class="contact-form-actions">
                <button type="submit" class="btn-contact-submit">
                  <i class="fa-solid fa-paper-plane"></i>
                  <span>Gửi yêu cầu kết nối ngay</span>
                </button>
                <button type="reset" class="btn-contact-reset" id="btnResetForm">
                  <i class="fa-solid fa-rotate-left"></i> Làm mới
                </button>
              </div>
            </form>
          </div>

          <!-- ================================================================
               CỘT PHẢI: THÔNG TIN TRỰC TIẾP, VĂN PHÒNG, FAQ & BẢN ĐỒ
               ================================================================ -->
          <div style="display: flex; flex-direction: column; gap: 1.5rem;">

            <!-- CARD 1: THÔNG TIN TRỰC TIẾP & VĂN PHÒNG TIẾP ĐÓN -->
            <div class="contact-card-box">
              <div class="contact-box-header" style="margin-bottom: 1.15rem; padding-bottom: 0.75rem;">
                <h3 class="contact-box-title" style="font-size: 1.05rem;">
                  <i class="fa-solid fa-address-card" style="color: var(--executive-blue);"></i>
                  Kênh liên hệ trực tiếp
                </h3>
              </div>

              <div class="contact-info-list">
                <!-- Địa chỉ TP.HCM -->
                <div class="contact-info-item">
                  <div class="contact-info-icon-badge">
                    <i class="fa-solid fa-building"></i>
                  </div>
                  <div>
                    <div class="contact-info-label">Trụ sở tiếp đón (TP. Hồ Chí Minh)</div>
                    <div class="contact-info-value">Tầng 18, Bitexco Financial Tower, Số 2 Hải Triều, P. Bến Nghé, Quận 1</div>
                  </div>
                </div>

                <!-- Địa chỉ Hà Nội -->
                <div class="contact-info-item">
                  <div class="contact-info-icon-badge">
                    <i class="fa-solid fa-landmark"></i>
                  </div>
                  <div>
                    <div class="contact-info-label">Văn phòng kết nối (Hà Nội)</div>
                    <div class="contact-info-value">Tầng 12, Keangnam Landmark 72, Đường Phạm Hùng, Nam Từ Liêm</div>
                  </div>
                </div>

                <!-- Email -->
                <div class="contact-info-item">
                  <div class="contact-info-icon-badge">
                    <i class="fa-solid fa-envelope"></i>
                  </div>
                  <div>
                    <div class="contact-info-label">Email chính thức</div>
                    <div class="contact-info-value">
                      <a href="mailto:ketnoi@gmail.vn">ketnoi@gmail.vn</a> &bull; 
                      <a href="mailto:banbientap@ketnoi.vn">banbientap@ketnoi.vn</a>
                    </div>
                  </div>
                </div>

                <!-- Hotline -->
                <div class="contact-info-item">
                  <div class="contact-info-icon-badge">
                    <i class="fa-solid fa-phone"></i>
                  </div>
                  <div>
                    <div class="contact-info-label">Hotline & Zalo Doanh nghiệp</div>
                    <div class="contact-info-value">
                      <a href="tel:0900123456" style="color: var(--executive-blue); font-weight: 700;">0900 123 456</a> 
                      <span style="font-size: 0.78rem; color: #16a34a; font-weight: 600; margin-left: 0.35rem;">(Hỗ trợ 24/7)</span>
                    </div>
                  </div>
                </div>

                <!-- Thời gian làm việc -->
                <div class="contact-info-item">
                  <div class="contact-info-icon-badge">
                    <i class="fa-regular fa-clock"></i>
                  </div>
                  <div>
                    <div class="contact-info-label">Lịch làm việc & Tiếp khách</div>
                    <div class="contact-info-value" style="font-size: 0.85rem;">
                      Thứ Hai - Thứ Sáu: 08:30 - 18:00<br>
                      Thứ Bảy: 09:00 - 12:00 (Hẹn trước)
                    </div>
                  </div>
                </div>
              </div>

              <!-- Mạng xã hội doanh nghiệp (Tự động nhận diện Icon Tab của mọi website) -->
              @php
                $contactSocials = !empty($siteSettings['social_links']) ? json_decode($siteSettings['social_links'], true) : [];
                if (!is_array($contactSocials) || empty($contactSocials)) {
                    $contactSocials = array_values(array_filter([
                        $siteSettings['social_linkedin'] ?? 'https://linkedin.com',
                        $siteSettings['social_facebook'] ?? 'https://facebook.com',
                        $siteSettings['social_youtube'] ?? 'https://youtube.com',
                        $siteSettings['social_telegram'] ?? 'https://telegram.org',
                    ]));
                }
              @endphp
              <div style="margin-top: 1.25rem; padding-top: 1rem; border-top: 1px solid var(--border-card);">
                <div class="contact-info-label" style="margin-bottom: 0.5rem;">Mạng lưới & Kênh truyền thông</div>
                <div class="contact-social-grid">
                  @foreach($contactSocials as $sUrl)
                    @php
                        $sHost = parse_url($sUrl, PHP_URL_HOST) ?? '';
                        $cleanHost = preg_replace('/^www\./', '', strtolower($sHost));
                        $faviconUrl = "https://www.google.com/s2/favicons?domain=" . ($cleanHost ?: 'example.com') . "&sz=64";
                        $siteName = ucfirst(explode('.', $cleanHost)[0] ?? 'Website');
                    @endphp
                    <a href="{{ $sUrl }}" target="_blank" rel="noopener noreferrer" class="contact-social-btn" style="display: inline-flex; align-items: center; gap: 0.5rem;">
                      <img src="{{ $faviconUrl }}" alt="{{ $cleanHost }}" style="width: 18px; height: 18px; object-fit: contain; border-radius: 3px;" onerror="this.src='https://www.google.com/s2/favicons?domain=example.com&sz=64'">
                      <span>{{ $siteName }}</span>
                    </a>
                  @endforeach
                </div>
              </div>
            </div>

            <!-- CARD 2: BẢN ĐỒ VỊ TRÍ MÔ PHỎNG SANG TRỌNG -->
            <div class="contact-card-box" style="padding: 1.25rem;">
              <div class="contact-map-card">
                <div class="contact-map-mock">
                  <div class="contact-map-pin">
                    <i class="fa-solid fa-location-dot"></i>
                  </div>
                  <div class="contact-map-title">Bitexco Financial Tower</div>
                  <div class="contact-map-subtitle">Số 2 Hải Triều, P. Bến Nghé, Quận 1, TP. Hồ Chí Minh</div>
                  <a href="https://maps.google.com" target="_blank" class="btn-open-map">
                    <i class="fa-solid fa-arrow-up-right-from-square"></i>
                    <span>Xem bản đồ vệ tinh</span>
                  </a>
                </div>
              </div>
            </div>

            <!-- CARD 3: CÂU HỎI THƯỜNG GẶP (FAQ MINI) -->
            <div class="contact-card-box">
              <div class="contact-box-header" style="margin-bottom: 1rem; padding-bottom: 0.65rem;">
                <h3 class="contact-box-title" style="font-size: 1.05rem;">
                  <i class="fa-solid fa-circle-question" style="color: var(--executive-blue);"></i>
                  Câu hỏi thường gặp
                </h3>
              </div>

              <div class="contact-faq-list" id="contactFaqAccordion">
                <!-- FAQ 1 -->
                <div class="contact-faq-item active">
                  <div class="contact-faq-question">
                    <span>Tôi có thể gửi bài nghiên cứu để đăng trên diễn đàn không?</span>
                    <i class="fa-solid fa-chevron-down contact-faq-arrow"></i>
                  </div>
                  <div class="contact-faq-answer">
                    Diễn đàn KẾT NỐI hoạt động theo mô hình 1 Founder đăng bài độc quyền nhằm đảm bảo tính thẩm định cao nhất. Tuy nhiên, các chuyên gia có thể gửi đề tài hoặc báo cáo qua form liên hệ; nếu nội dung xuất sắc, Founder sẽ trích dẫn và ghi nhận nguồn của Quý đối tác.
                  </div>
                </div>

                <!-- FAQ 2 -->
                <div class="contact-faq-item">
                  <div class="contact-faq-question">
                    <span>Thời gian phản hồi thông thường là bao lâu?</span>
                    <i class="fa-solid fa-chevron-down contact-faq-arrow"></i>
                  </div>
                  <div class="contact-faq-answer">
                    Chúng tôi cam kết phản hồi tất cả thư ngỏ hợp tác và liên hệ công việc trong vòng 24 giờ làm việc kể từ thời điểm tiếp nhận.
                  </div>
                </div>

                <!-- FAQ 3 -->
                <div class="contact-faq-item">
                  <div class="contact-faq-question">
                    <span>Quy trình sắp xếp lịch gặp trực tiếp như thế nào?</span>
                    <i class="fa-solid fa-chevron-down contact-faq-arrow"></i>
                  </div>
                  <div class="contact-faq-answer">
                    Sau khi tiếp nhận đề xuất qua biểu mẫu, trợ lý Ban Quản trị sẽ gửi email xác nhận và gửi kèm đường link chọn lịch làm việc (Online qua Google Meet hoặc Trực tiếp tại văn phòng Bitexco Q.1).
                  </div>
                </div>
              </div>
            </div>

          </div>
        </div>
</main>

<script src="{{ asset('js/contact.js') }}"></script>
@endsection
