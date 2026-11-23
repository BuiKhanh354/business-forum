@extends('layouts.app')

@section('title', ($siteSettings['site_name'] ?? 'KẾT NỐI') . ' - Business Community | Blog Diễn Đàn Doanh Nghiệp')
@section('breadcrumb', 'WORKSPACE / DIỄN ĐÀN DOANH NGHIỆP')

@section('content')
<main class="main-content-scroll">
<!-- ====================================================================
             KHU VỰC ẢNH BÌA BLOG: ĐỒNG BỘ 100% TỪ CÀI ĐẶT GIAO DIỆN HỆ THỐNG
             ==================================================================== -->
        <section class="hero-cover-card" id="ve-toi" 
          data-desktop-image="{{ asset($siteSettings['cover_desktop'] ?? 'assets/images/cover-banner.jpg') }}"
          data-mobile-image="{{ asset($siteSettings['cover_mobile'] ?? 'assets/images/cover-night-skyline.jpg') }}"
          data-desktop-height="{{ $siteSettings['cover_height_desktop'] ?? '350px' }}"
          data-mobile-height="{{ $siteSettings['cover_height_mobile'] ?? '220px' }}"
          style="border-radius: 5px; position: relative; margin-bottom: 1.5rem; display: flex; align-items: center; justify-content: center; padding: 0;">
          <input type="file" id="coverImageInput" accept="image/*" style="display: none;">

          <div id="coverImageLayer" style="position: absolute; inset: 0; background-size: cover; background-position: center; pointer-events: none; border-radius: 5px;"></div>
        </section>

        <!-- TIÊU ĐỀ CHÍNH: ĐỒNG BỘ TỪ CÀI ĐẶT GIAO DIỆN -->
        <div class="content-header-row" style="margin-bottom: 1.25rem;">
          <div>
            <h1 class="forum-main-title">{{ $siteSettings['blog_title'] ?? 'Blog' }}</h1>
            <p class="forum-subtitle">{{ $siteSettings['blog_subtitle'] ?? 'Cùng trao đổi góc nhìn chiến lược, cập nhật thị trường và chia sẻ kiến thức quản trị.' }}</p>
          </div>
        </div>

        <!-- BỐ CỤC 2 CỘT DOANH NGHIỆP: CỘT BÀI VIẾT (70%) + SIDEBAR (30%) -->
        <div class="forum-body-grid">

          <!-- ==================================================================
               CỘT TRÁI: BÀI VIẾT MỚI NHẤT & BỘ LỌC CHUYÊN MỤC DOANH NGHIỆP
               ================================================================== -->
          <section>
            <!-- Tiêu đề & Sắp xếp -->
            <div class="feed-header-row" style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.25rem;">
              <h3 class="feed-title" id="feedTitleHeading" style="margin: 0;">Bài viết chia sẻ mới nhất</h3>
              
              <!-- DROPDOWN SẮP XẾP BÀI VIẾT (MEDIAN UI FEED SORT) -->
              <div class="feed-sort-wrapper" style="position: relative;">
                <button type="button" class="sort-dropdown-trigger" id="feedSortBtn" aria-expanded="false" title="Sắp xếp bài viết">
                  <i class="fa-solid fa-arrow-down-wide-short" style="font-size: 0.78rem; color: var(--executive-blue);"></i>
                  <span id="currentSortLabel">Mới nhất</span>
                  <i class="fa-solid fa-chevron-down" id="feedSortChevron" style="font-size: 0.65rem; transition: transform 0.2s ease;"></i>
                </button>

                <div class="feed-sort-dropdown" id="feedSortDropdown" style="display: none; position: absolute; right: 0; top: calc(100% + 6px); background: var(--bg-card); border: 1px solid var(--border-card); border-radius: 8px; box-shadow: 0 10px 25px rgba(0,0,0,0.12); min-width: 195px; z-index: 100; overflow: hidden; padding: 0.35rem 0;">
                  <button type="button" class="sort-opt-btn active" data-sort="latest">
                    <i class="fa-regular fa-clock"></i>
                    <span>Mới nhất</span>
                  </button>
                  <button type="button" class="sort-opt-btn" data-sort="views">
                    <i class="fa-solid fa-fire" style="color: #f59e0b;"></i>
                    <span>Xem nhiều nhất</span>
                  </button>
                  <button type="button" class="sort-opt-btn" data-sort="comments">
                    <i class="fa-regular fa-comment-dots" style="color: #06b6d4;"></i>
                    <span>Nhiều bình luận</span>
                  </button>
                  <button type="button" class="sort-opt-btn" data-sort="featured">
                    <i class="fa-solid fa-heart" style="color: #ef4444;"></i>
                    <span>Bài viết nổi bật (Nhiều tim nhất)</span>
                  </button>
                </div>
              </div>
            </div>

            <!-- Bộ lọc chuyên mục Doanh nghiệp (Tối đa 10 danh mục + Nút hiển thị +n số còn lại) -->
            <div class="category-filter-bar" id="chu-de-bai-viet">
              <div class="category-filter-label">Chuyên mục trọng tâm</div>
              <div class="category-pills-list" id="categoryPillsList">
                <button class="cat-pill active" data-cat="all">Tất cả bài viết</button>
                @php
                  $maxVisibleCats = 10;
                  $allCats = isset($globalCategories) ? $globalCategories : collect([]);
                  $visibleCats = $allCats->take($maxVisibleCats);
                  $remainingCats = $allCats->slice($maxVisibleCats);
                  $remainingCount = $remainingCats->count();
                @endphp

                @foreach($visibleCats as $cat)
                  <button class="cat-pill" data-cat="{{ Str::lower($cat->slug) }}" data-name="{{ Str::lower($cat->name) }}">
                    <span style="display: inline-block; width: 7px; height: 7px; border-radius: 50%; background-color: {{ $cat->color ?? '#2563eb' }}; margin-right: 5px;"></span>
                    {{ $cat->name }}
                  </button>
                @endforeach

                @if($remainingCount > 0)
                <div class="cat-more-wrapper" style="position: relative; display: inline-block;">
                  <button type="button" class="cat-pill cat-more-btn" id="btnCatMore" style="background: var(--bg-subtle); border: 1px dashed var(--border-card); font-weight: 700; color: var(--executive-blue); display: inline-flex; align-items: center; gap: 0.35rem; cursor: pointer;">
                    <span id="catMoreBtnLabel">+{{ $remainingCount }} chuyên mục</span>
                    <i class="fa-solid fa-chevron-down" style="font-size: 0.65rem; transition: transform 0.2s ease;"></i>
                  </button>

                  <div class="cat-more-dropdown" id="catMoreDropdown" style="display: none; position: absolute; left: 0; top: calc(100% + 6px); background: var(--bg-card); border: 1px solid var(--border-card); border-radius: 8px; box-shadow: 0 10px 25px rgba(0,0,0,0.15); min-width: 210px; max-height: 280px; overflow-y: auto; z-index: 100; padding: 0.45rem;">
                    @foreach($remainingCats as $cat)
                      <button type="button" class="cat-pill cat-dropdown-item" data-cat="{{ Str::lower($cat->slug) }}" data-name="{{ Str::lower($cat->name) }}" style="width: 100%; text-align: left; justify-content: space-between; margin-bottom: 0.35rem; display: flex; align-items: center; padding: 0.45rem 0.65rem; border-radius: 6px; border: 1px solid transparent; background: transparent;">
                        <span style="display: flex; align-items: center; gap: 0.45rem;">
                          <span style="display: inline-block; width: 7px; height: 7px; border-radius: 50%; background-color: {{ $cat->color ?? '#2563eb' }};"></span>
                          <span style="font-size: 0.82rem; font-weight: 600; color: var(--text-title);">{{ $cat->name }}</span>
                        </span>
                        @if(($cat->posts_count ?? 0) > 0)
                        <span style="font-size: 0.72rem; color: var(--text-muted); background: var(--bg-subtle); padding: 1px 6px; border-radius: 999px;">{{ $cat->posts_count }}</span>
                        @endif
                      </button>
                    @endforeach
                  </div>
                </div>
                @endif
              </div>
            </div>

            <!-- DANH SÁCH BÀI VIẾT DOANH NGHIỆP (ĐỒNG BỘ 100% TỪ DATABASE) -->
            <div id="postsList">
              @forelse($posts as $post)
              <article class="post-card"
                data-id="{{ $post->id }}"
                data-created="{{ $post->created_at ? $post->created_at->timestamp : 0 }}"
                data-views="{{ $post->views_count ?? 0 }}"
                data-comments="{{ $post->comments ? $post->comments->count() : 0 }}"
                data-featured="{{ $post->is_featured ? '1' : '0' }}"
                data-likes="{{ $post->likes_count ?? 0 }}"
                data-category="{{ Str::lower($post->categoryItem->slug ?? '') }} {{ Str::lower($post->categoryItem->name ?? '') }}">
                <!-- Header: Tác giả Admin chuẩn Blog (Đồng bộ theo hồ sơ cá nhân) -->
                <div class="post-header-author">
                  <div class="topbar-avatar" style="width: 38px; height: 38px; border-radius: 5px; overflow: hidden;">
                    <img src="{{ asset(($authorUser->avatar ?? $currentUser->avatar) ?? 'assets/images/admin-khanh.jpg') }}" alt="{{ $authorUser->name ?? $currentUser->name ?? 'Admin' }}" style="width: 100%; height: 100%; object-fit: cover;">
                  </div>
                  <div class="author-meta-info">
                    <div class="author-name-row">
                      <span class="author-name-text">{{ $authorUser->name ?? $currentUser->name ?? 'Admin' }}</span>
                      <i class="fa-solid fa-circle-check median-verified-check" title="Quản trị viên đã xác minh"></i>
                      <span class="badge-role-admin">{{ strtoupper($authorUser->role_title ?? $currentUser->role_title ?? 'FOUNDER') }}</span>
                    </div>
                    <span class="post-time-text">{{ $post->created_at->format('d/m/Y') }} &bull; {{ $post->reading_time }} phút đọc &bull; Góc nhìn Quản trị</span>
                  </div>
                </div>

                @php
                  $pImages = $post->all_images;
                  $imgCount = count($pImages);
                @endphp

                <!-- Khung ảnh bài viết doanh nghiệp (Hỗ trợ 1 đến 5 ảnh đa dạng) -->
                @if($imgCount <= 1)
                  <a href="{{ route('post.detail', $post->id) }}" class="post-image-placeholder" style="display: block; border-radius: 6px; height: 360px; overflow: hidden; position: relative;">
                    <img src="{{ asset($pImages[0] ?? 'assets/images/post-tech-ai.jpg') }}" alt="{{ $post->title }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 6px; transition: transform 0.3s ease;">
                    <span class="img-tag" style="position: absolute; top: 12px; left: 12px; background: rgba(15, 23, 42, 0.85); color: #ffffff; padding: 0.35rem 0.75rem; border-radius: 4px; font-size: 0.72rem; font-weight: 700; letter-spacing: 0.5px;">ẢNH BÀI VIẾT DOANH NGHIỆP</span>
                  </a>
                @elseif($imgCount == 2)
                  <a href="{{ route('post.detail', $post->id) }}" style="display: grid; grid-template-columns: 1fr 1fr; gap: 6px; height: 350px; border-radius: 6px; overflow: hidden; position: relative; margin-bottom: 0.75rem; text-decoration: none;">
                    @foreach($pImages as $pImg)
                      <div style="height: 100%; overflow: hidden;">
                        <img src="{{ asset($pImg) }}" alt="{{ $post->title }}" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s ease;">
                      </div>
                    @endforeach
                    <span style="position: absolute; top: 10px; right: 10px; background: rgba(15, 23, 42, 0.85); color: #fff; padding: 0.25rem 0.65rem; border-radius: 4px; font-size: 0.72rem; font-weight: 700;">
                      <i class="fa-regular fa-images"></i> 2 ảnh
                    </span>
                  </a>
                @elseif($imgCount == 3)
                  <a href="{{ route('post.detail', $post->id) }}" style="display: grid; grid-template-columns: 3fr 2fr; gap: 6px; height: 350px; border-radius: 6px; overflow: hidden; position: relative; margin-bottom: 0.75rem; text-decoration: none;">
                    <div style="height: 100%; overflow: hidden;">
                      <img src="{{ asset($pImages[0]) }}" alt="{{ $post->title }}" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                    <div style="display: grid; grid-template-rows: 1fr 1fr; gap: 6px; height: 100%;">
                      <div style="overflow: hidden;"><img src="{{ asset($pImages[1]) }}" alt="{{ $post->title }}" style="width: 100%; height: 100%; object-fit: cover;"></div>
                      <div style="overflow: hidden;"><img src="{{ asset($pImages[2]) }}" alt="{{ $post->title }}" style="width: 100%; height: 100%; object-fit: cover;"></div>
                    </div>
                    <span style="position: absolute; top: 10px; right: 10px; background: rgba(15, 23, 42, 0.85); color: #fff; padding: 0.25rem 0.65rem; border-radius: 4px; font-size: 0.72rem; font-weight: 700;">
                      <i class="fa-regular fa-images"></i> 3 ảnh
                    </span>
                  </a>
                @elseif($imgCount == 4)
                  <a href="{{ route('post.detail', $post->id) }}" style="display: grid; grid-template-columns: 1fr 1fr; grid-template-rows: 1fr 1fr; gap: 6px; height: 360px; border-radius: 6px; overflow: hidden; position: relative; margin-bottom: 0.75rem; text-decoration: none;">
                    @foreach($pImages as $pImg)
                      <div style="overflow: hidden; height: 100%;">
                        <img src="{{ asset($pImg) }}" alt="{{ $post->title }}" style="width: 100%; height: 100%; object-fit: cover;">
                      </div>
                    @endforeach
                    <span style="position: absolute; top: 10px; right: 10px; background: rgba(15, 23, 42, 0.85); color: #fff; padding: 0.25rem 0.65rem; border-radius: 4px; font-size: 0.72rem; font-weight: 700;">
                      <i class="fa-regular fa-images"></i> 4 ảnh
                    </span>
                  </a>
                @else
                  <a href="{{ route('post.detail', $post->id) }}" style="display: flex; flex-direction: column; gap: 6px; height: 380px; border-radius: 6px; overflow: hidden; position: relative; margin-bottom: 0.75rem; text-decoration: none;">
                    <div style="display: flex; gap: 6px; height: 55%;">
                      <div style="flex: 1; overflow: hidden;"><img src="{{ asset($pImages[0]) }}" alt="{{ $post->title }}" style="width: 100%; height: 100%; object-fit: cover;"></div>
                      <div style="flex: 1; overflow: hidden;"><img src="{{ asset($pImages[1]) }}" alt="{{ $post->title }}" style="width: 100%; height: 100%; object-fit: cover;"></div>
                    </div>
                    <div style="display: flex; gap: 6px; height: 45%;">
                      <div style="flex: 1; overflow: hidden;"><img src="{{ asset($pImages[2]) }}" alt="{{ $post->title }}" style="width: 100%; height: 100%; object-fit: cover;"></div>
                      <div style="flex: 1; overflow: hidden;"><img src="{{ asset($pImages[3]) }}" alt="{{ $post->title }}" style="width: 100%; height: 100%; object-fit: cover;"></div>
                      <div style="flex: 1; overflow: hidden;"><img src="{{ asset($pImages[4]) }}" alt="{{ $post->title }}" style="width: 100%; height: 100%; object-fit: cover;"></div>
                    </div>
                    <span style="position: absolute; top: 10px; right: 10px; background: rgba(15, 23, 42, 0.85); color: #fff; padding: 0.25rem 0.65rem; border-radius: 4px; font-size: 0.72rem; font-weight: 700;">
                      <i class="fa-regular fa-images"></i> 5 ảnh
                    </span>
                  </a>
                @endif

                <!-- Huy hiệu bài viết phong cách Tạp chí Doanh nghiệp -->
                <div style="display: flex; align-items: center; gap: 0.6rem; margin-bottom: 0.5rem; flex-wrap: wrap;">
                  @if($post->is_featured)
                  <span class="corp-article-badge featured">
                    <i class="fa-solid fa-star"></i> TIÊU ĐIỂM DOANH NGHIỆP
                  </span>
                  @endif
                  <span style="font-size: 0.76rem; color: var(--text-muted); font-weight: 600;">
                    <i class="fa-regular fa-clock"></i> {{ $post->reading_time }} phút đọc &bull; Thẩm định bởi Founder Admin
                  </span>
                </div>

                <!-- Tiêu đề bài viết -->
                <h4 class="post-main-title">
                  <a href="{{ route('post.detail', $post->id) }}" style="text-decoration: none; color: inherit;">
                    {{ $post->title }}
                  </a>
                </h4>

                <!-- Tag chuyên mục -->
                @php
                  $pCatName = $post->categoryItem?->name ?? $post->category ?? 'Chiến lược kinh doanh';
                  $pCatColor = $post->categoryItem?->color ?? '#2563eb';
                @endphp
                <div>
                  <span class="post-category-badge" style="background-color: rgba(37, 99, 235, 0.08); color: {{ $pCatColor }}; border: 1px solid {{ $pCatColor }}33;">
                    {{ mb_strtoupper($pCatName, 'UTF-8') }}
                  </span>
                </div>

                <!-- Nội dung tóm tắt -->
                <p class="post-excerpt-text">
                  {{ $post->excerpt ?: 'Nội dung phân tích thực chiến và các giải pháp chiến lược dành cho cộng đồng lãnh đạo doanh nghiệp.' }}
                </p>

                <!-- Khối Preview Liên kết ngoài đính kèm (nếu có) -->
                @if(!empty($post->external_link))
                @php
                  $rawLink = trim($post->external_link);
                  $fullUrl = \Illuminate\Support\Str::startsWith($rawLink, ['http://', 'https://']) ? $rawLink : 'https://' . $rawLink;
                  $parsedDomain = parse_url($fullUrl, PHP_URL_HOST) ?? 'link';
                  $cleanDomain = preg_replace('/^www\./i', '', strtolower($parsedDomain));
                  $favUrl = "https://www.google.com/s2/favicons?domain=" . ($cleanDomain ?: 'example.com') . "&sz=64";
                  $linkTitle = !empty($post->external_link_title) ? $post->external_link_title : ($cleanDomain ?: 'Xem liên kết');
                @endphp
                <div class="attached-preview-box">
                  <a href="{{ $fullUrl }}" target="_blank" rel="noopener noreferrer" class="preview-thumb-img-wrap" style="display: block; text-decoration: none;" title="{{ $linkTitle }}">
                    @php
                      $extArticleImg = $post->external_article_image;
                    @endphp
                    @if(!empty($extArticleImg))
                      <img src="{{ $extArticleImg }}" alt="{{ $linkTitle }}" onerror="this.onerror=null; this.style.display='none'; this.nextElementSibling.style.display='flex';">
                      <div class="preview-fallback-icon" style="display: none;">
                        <i class="fa-solid fa-newspaper"></i>
                        <span>{{ strtoupper($cleanDomain) }}</span>
                      </div>
                    @else
                      <div class="preview-fallback-icon">
                        <i class="fa-solid fa-newspaper"></i>
                        <span>{{ strtoupper($cleanDomain) }}</span>
                      </div>
                    @endif
                  </a>
                  <div class="preview-text-col">
                    <!-- TIÊU ĐỀ DUY NHẤT: TÊN ĐÃ ĐẶT KÈM ICON TAB WEB ĐÓ (KHÔNG HIỆN LINK THÔ) -->
                    <a href="{{ $fullUrl }}" target="_blank" rel="noopener noreferrer" class="preview-title-row" style="text-decoration: none; display: flex; align-items: center; gap: 0.65rem; margin-bottom: 0.5rem;" title="{{ $linkTitle }}">
                      <span class="preview-tab-icon-box" style="width: 28px; height: 28px; background: #ffffff; border: 1px solid #cbd5e1; border-radius: 6px; display: inline-flex; align-items: center; justify-content: center; flex-shrink: 0; box-shadow: 0 1px 3px rgba(0,0,0,0.06); padding: 3px;">
                        <img src="{{ $favUrl }}" alt="Tab Icon" style="width: 18px; height: 18px; object-fit: contain; border-radius: 2px;" onerror="this.src='https://www.google.com/s2/favicons?domain=example.com&sz=64'">
                      </span>
                      <span class="preview-title-text" style="font-size: 1.02rem; font-weight: 800; color: var(--text-title); line-height: 1.35;">
                        {{ $linkTitle }}
                      </span>
                    </a>
                    <div class="preview-desc-row">
                      Tài liệu và bài báo tham khảo bên ngoài đính kèm cùng bài viết. Nhấn để đọc bài gốc.
                    </div>
                    <div class="preview-footer-action" style="justify-content: flex-end;">
                      <a href="{{ $fullUrl }}" target="_blank" rel="noopener noreferrer" class="btn-read-orig-link">
                        Đọc bài gốc <i class="fa-solid fa-arrow-up-right-from-square"></i>
                      </a>
                    </div>
                  </div>
                </div>
                @endif

                <!-- Footer bài: Thích, Bình luận, Chia sẻ, Đọc tiếp chuẩn Blog -->
                @php
                  $isPostLiked = in_array($post->id, session('liked_posts', []));
                @endphp
                <div class="post-footer-row" style="display: flex; align-items: center; justify-content: space-between; padding-top: 1rem; border-top: 1px solid var(--border-light); margin-top: 1.25rem;">
                  <div style="display: flex; align-items: center; gap: 0.65rem;">
                    <button type="button" class="post-meta-btn like-btn {{ $isPostLiked ? 'liked' : '' }}" data-post-id="{{ $post->id }}" onclick="togglePostLike({{ $post->id }}, this)" title="Thích bài viết">
                      <i class="{{ $isPostLiked ? 'fa-solid' : 'fa-regular' }} fa-heart" style="{{ $isPostLiked ? 'color: #ef4444;' : '' }}"></i>
                      <span class="likes-count">{{ $post->likes_count ?? 0 }}</span>
                    </button>
                    <a href="{{ route('post.detail', $post->id) }}#comments" class="post-meta-btn" title="Xem bình luận">
                      <i class="fa-regular fa-comment"></i>
                      <span>{{ $post->comments->count() }}</span>
                    </a>
                    <button type="button" class="post-meta-btn" onclick="copyPostShareLink(window.location.origin + '{{ route('post.detail', $post->id, false) }}')" title="Chia sẻ bài viết">
                      <i class="fa-solid fa-share-nodes"></i>
                    </button>
                  </div>
                  <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <a href="{{ route('post.detail', $post->id) }}" class="btn-median-read">
                      Đọc tiếp <i class="fa-solid fa-arrow-right"></i>
                    </a>
                  </div>
                </div>
              </article>
              @empty
              <div style="text-align: center; padding: 3rem 1rem; color: var(--text-muted); background: var(--bg-card); border-radius: 8px;">
                <p>Chưa có bài viết nào được xuất bản.</p>
              </div>
              @endforelse
            </div>
          </section>

          <!-- ==================================================================
               SIDEBAR PHẢI: CHUẨN Blog (AUTHOR BIO, TRENDING, NEWSLETTER, TAGS)
               ================================================================== -->
          <aside class="right-sidebar-panel">

            <!-- WIDGET 1: THẺ ẢNH TÁC GIẢ & FOUNDER ADMIN (NẰM NGAY TRÊN BÀI VIẾT NỔI BẬT) -->
            <div class="median-author-widget">
              <div class="median-author-cover" style="background-image: url('{{ asset($siteSettings['cover_desktop'] ?? 'assets/images/cover-banner.jpg') }}'); background-size: cover; background-position: center; height: 54px; margin: -1.15rem -1.15rem 0 -1.15rem;"></div>
              <div class="median-author-avatar-wrap">
                <img src="{{ asset(($authorUser->avatar ?? $currentUser->avatar) ?? 'assets/images/admin-khanh.jpg') }}" alt="{{ $authorUser->name ?? 'Admin Khánh' }}">
              </div>
              <div class="median-author-name">
                <span>{{ $authorUser->name ?? 'Bùi Viết Khánh' }}</span>
                <i class="fa-solid fa-circle-check median-verified-check" title="Quản trị viên đã xác minh"></i>
              </div>
              <div class="median-author-role">{{ strtoupper($authorUser->role_title ?? 'FOUNDER & QUẢN TRỊ VIÊN') }}</div>
              <div class="median-author-bio">
                {{ \Illuminate\Support\Str::limit($authorUser->bio ?? 'Chia sẻ chiến lược quản trị, tài chính và góc nhìn điều hành thực chiến.', 115) }}
              </div>

              <!-- Thống kê năng lực -->
              <div class="median-author-stats">
                <div>
                  <div class="median-stat-val">{{ $posts->count() }}</div>
                  <div class="median-stat-lbl">Bài viết</div>
                </div>
                <div>
                  <div class="median-stat-val">10+</div>
                  <div class="median-stat-lbl">Năm KN</div>
                </div>
                <div>
                  <div class="median-stat-val">150+</div>
                  <div class="median-stat-lbl">Đối tác</div>
                </div>
              </div>

              <!-- Nút Kết nối / Về tôi -->
              <a href="{{ route('about') }}" class="btn-median-connect" style="text-decoration: none;">
                <i class="fa-solid fa-user"></i> Khám phá Về tôi
              </a>
            </div>

            <!-- WIDGET 2: BÀI VIẾT NỔI BẬT (THEO LƯỢT THẢ TIM NHIỀU NHẤT - TỐI ĐA 5 BÀI) -->
            <div class="median-trending-card">
              <div class="median-widget-header">
                <div class="median-widget-title">
                  <i class="fa-solid fa-heart" style="color: #ef4444;"></i>
                  <span>Bài viết nổi bật</span>
                </div>
                <span style="font-size: 0.7rem; font-weight: 700; color: #ef4444; background: rgba(239, 68, 68, 0.12); padding: 0.15rem 0.5rem; border-radius: 4px; text-transform: uppercase;">Top yêu thích</span>
              </div>

              <div class="median-trending-list">
                @forelse($featuredPosts ?? ($trendingPosts ?? []) as $index => $fPost)
                <a href="{{ route('post.detail', $fPost->id) }}" class="median-trending-item">
                  <span class="median-rank-num top-{{ $index + 1 }}">0{{ $index + 1 }}</span>
                  <div class="median-trending-thumb">
                    <img src="{{ asset($fPost->cover_image ?: 'assets/images/post-tech-ai.jpg') }}" alt="{{ $fPost->title }}">
                  </div>
                  <div class="median-trending-info">
                    <div class="median-trending-title">{{ $fPost->title }}</div>
                    <div class="median-trending-meta" style="display: flex; align-items: center; gap: 0.45rem;">
                      <span style="color: #ef4444; font-weight: 700; display: inline-flex; align-items: center; gap: 0.25rem;">
                        <i class="fa-solid fa-heart" style="font-size: 0.68rem;"></i> {{ number_format($fPost->likes_count ?? 0) }}
                      </span>
                      <span style="color: var(--text-muted);">&bull;</span>
                      <span>{{ $fPost->created_at ? $fPost->created_at->format('d/m') : '' }}</span>
                    </div>
                  </div>
                </a>
                @empty
                <div style="font-size: 0.8rem; color: var(--text-muted); padding: 0.75rem 0;">Chưa có bài viết nổi bật nào.</div>
                @endforelse
              </div>
            </div>

          </aside>

        </div>
</main>
@endsection

@section('modals')

@endsection
