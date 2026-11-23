@extends('layouts.app')

@section('title', ($post->title ?? 'Chi tiết bài viết') . ' - KẾT NỐI Business Community')
@section('breadcrumb', 'WORKSPACE / BÀI VIẾT / CHI TIẾT')

@section('content')
<main class="main-content-scroll">
  <div style="margin-bottom: 1.25rem;">
    <a href="{{ route('home') }}" style="font-size: 0.86rem; color: var(--primary-blue); font-weight: 700; display: inline-flex; align-items: center; gap: 0.35rem; text-decoration: none;">
      <i class="fa-solid fa-arrow-left"></i> Quay lại Blog
    </a>
  </div>

  @if(session('success'))
    <div style="background-color: #dcfce7; border: 1px solid #86efac; color: #166534; padding: 0.85rem 1.25rem; border-radius: 8px; margin-bottom: 1.25rem; font-size: 0.88rem; font-weight: 600; display: flex; align-items: center; gap: 0.5rem;">
      <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
    </div>
  @endif

  @if(!$post)
    <div style="text-align: center; padding: 4rem 1rem; background: var(--bg-card); border-radius: 8px;">
      <h3 style="color: var(--text-title); font-weight: 800;">Bài viết không tồn tại</h3>
      <p style="color: var(--text-muted); font-size: 0.9rem;">Bài viết này có thể đã bị xóa hoặc tạm ẩn.</p>
      <a href="{{ route('home') }}" class="btn-median-read" style="display: inline-block; margin-top: 1rem;">Quay lại trang chủ</a>
    </div>
  @else
  <div class="forum-body-grid">

    <!-- CỘT CHI TIẾT BÀI ĐĂNG (ĐỒNG BỘ 100% DATABASE) -->
    <article class="post-card" style="padding: 2rem;">
      <div class="post-header-author">
        <div class="author-circle-avatar" style="overflow: hidden; border: 2px solid var(--gold-accent); flex-shrink: 0;">
          <img src="{{ $authorUser?->avatar_url ?? $currentUser?->avatar_url ?? asset('assets/images/admin-khanh.jpg') }}" alt="{{ $authorUser?->name ?? $currentUser?->name ?? 'Admin' }}" style="width: 100%; height: 100%; object-fit: cover; display: block;" onerror="this.onerror=null; this.src='{{ asset('assets/images/admin-khanh.jpg') }}';">
        </div>
        <div class="author-meta-info">
          <div class="author-name-row">
            <span class="author-name-text">{{ $authorUser?->name ?? $currentUser?->name ?? 'Admin' }}</span>
            <i class="fa-solid fa-circle-check median-verified-check" title="Quản trị viên đã xác minh"></i>
            <span class="badge-role-admin">{{ strtoupper($authorUser?->role_title ?? $currentUser?->role_title ?? 'FOUNDER & ADMIN') }}</span>
          </div>
          <span class="post-time-text">{{ $post->created_at->format('d/m/Y - H:i') }} &bull; {{ $post->reading_time }} phút đọc &bull; Báo cáo & Góc nhìn Quản trị</span>
        </div>
      </div>

      <h1 class="post-main-title" style="font-size: 1.6rem; margin-bottom: 0.75rem; line-height: 1.35;">
        {{ $post->title }}
      </h1>

      @php
        $pCatName = $post->categoryItem?->name ?? $post->category ?? 'Chiến lược kinh doanh';
        $pCatColor = $post->categoryItem?->color ?? '#2563eb';
      @endphp
      <div style="margin-bottom: 1.25rem;">
        <span class="post-category-badge" style="background-color: rgba(37, 99, 235, 0.08); color: {{ $pCatColor }}; border: 1px solid {{ $pCatColor }}33;">
          {{ mb_strtoupper($pCatName, 'UTF-8') }}
        </span>
      </div>

      @php
        $pImages = $post->all_images;
        $imgCount = count($pImages);
      @endphp

      <!-- Khung ảnh bài viết (Hỗ trợ 1 đến 5 ảnh đa dạng) -->
      @if($imgCount <= 1)
        <div class="post-image-placeholder" style="height: 420px; margin-bottom: 1.5rem; border-radius: 6px; overflow: hidden; position: relative; cursor: pointer;" onclick="openLightbox('{{ asset($pImages[0] ?? 'assets/images/post-tech-ai.jpg') }}')">
          <img src="{{ asset($pImages[0] ?? 'assets/images/post-tech-ai.jpg') }}" alt="{{ $post->title }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 6px;">
          <span class="img-tag" style="position: absolute; top: 12px; left: 12px; background: rgba(15, 23, 42, 0.85); color: #ffffff; padding: 0.35rem 0.75rem; border-radius: 4px; font-size: 0.72rem; font-weight: 700; letter-spacing: 0.5px;">ẢNH BÀI VIẾT DOANH NGHIỆP</span>
        </div>
      @elseif($imgCount == 2)
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px; height: 400px; border-radius: 6px; overflow: hidden; margin-bottom: 1.5rem; position: relative;">
          @foreach($pImages as $pImg)
            <div style="height: 100%; overflow: hidden; cursor: pointer;" onclick="openLightbox('{{ asset($pImg) }}')">
              <img src="{{ asset($pImg) }}" alt="{{ $post->title }}" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s ease;">
            </div>
          @endforeach
          <span style="position: absolute; top: 12px; right: 12px; background: rgba(15, 23, 42, 0.85); color: #fff; padding: 0.3rem 0.7rem; border-radius: 4px; font-size: 0.75rem; font-weight: 700;">
            <i class="fa-regular fa-images"></i> 2 ảnh (Bấm để xem lớn)
          </span>
        </div>
      @elseif($imgCount == 3)
        <div style="display: grid; grid-template-columns: 3fr 2fr; gap: 8px; height: 420px; border-radius: 6px; overflow: hidden; margin-bottom: 1.5rem; position: relative;">
          <div style="height: 100%; overflow: hidden; cursor: pointer;" onclick="openLightbox('{{ asset($pImages[0]) }}')">
            <img src="{{ asset($pImages[0]) }}" alt="{{ $post->title }}" style="width: 100%; height: 100%; object-fit: cover;">
          </div>
          <div style="display: grid; grid-template-rows: 1fr 1fr; gap: 8px; height: 100%;">
            <div style="overflow: hidden; cursor: pointer;" onclick="openLightbox('{{ asset($pImages[1]) }}')"><img src="{{ asset($pImages[1]) }}" alt="{{ $post->title }}" style="width: 100%; height: 100%; object-fit: cover;"></div>
            <div style="overflow: hidden; cursor: pointer;" onclick="openLightbox('{{ asset($pImages[2]) }}')"><img src="{{ asset($pImages[2]) }}" alt="{{ $post->title }}" style="width: 100%; height: 100%; object-fit: cover;"></div>
          </div>
          <span style="position: absolute; top: 12px; right: 12px; background: rgba(15, 23, 42, 0.85); color: #fff; padding: 0.3rem 0.7rem; border-radius: 4px; font-size: 0.75rem; font-weight: 700;">
            <i class="fa-regular fa-images"></i> 3 ảnh (Bấm để xem lớn)
          </span>
        </div>
      @elseif($imgCount == 4)
        <div style="display: grid; grid-template-columns: 1fr 1fr; grid-template-rows: 1fr 1fr; gap: 8px; height: 420px; border-radius: 6px; overflow: hidden; margin-bottom: 1.5rem; position: relative;">
          @foreach($pImages as $pImg)
            <div style="overflow: hidden; height: 100%; cursor: pointer;" onclick="openLightbox('{{ asset($pImg) }}')">
              <img src="{{ asset($pImg) }}" alt="{{ $post->title }}" style="width: 100%; height: 100%; object-fit: cover;">
            </div>
          @endforeach
          <span style="position: absolute; top: 12px; right: 12px; background: rgba(15, 23, 42, 0.85); color: #fff; padding: 0.3rem 0.7rem; border-radius: 4px; font-size: 0.75rem; font-weight: 700;">
            <i class="fa-regular fa-images"></i> 4 ảnh (Bấm để xem lớn)
          </span>
        </div>
      @else
        <div style="display: flex; flex-direction: column; gap: 8px; height: 440px; border-radius: 6px; overflow: hidden; margin-bottom: 1.5rem; position: relative;">
          <div style="display: flex; gap: 8px; height: 55%;">
            <div style="flex: 1; overflow: hidden; cursor: pointer;" onclick="openLightbox('{{ asset($pImages[0]) }}')"><img src="{{ asset($pImages[0]) }}" alt="{{ $post->title }}" style="width: 100%; height: 100%; object-fit: cover;"></div>
            <div style="flex: 1; overflow: hidden; cursor: pointer;" onclick="openLightbox('{{ asset($pImages[1]) }}')"><img src="{{ asset($pImages[1]) }}" alt="{{ $post->title }}" style="width: 100%; height: 100%; object-fit: cover;"></div>
          </div>
          <div style="display: flex; gap: 8px; height: 45%;">
            <div style="flex: 1; overflow: hidden; cursor: pointer;" onclick="openLightbox('{{ asset($pImages[2]) }}')"><img src="{{ asset($pImages[2]) }}" alt="{{ $post->title }}" style="width: 100%; height: 100%; object-fit: cover;"></div>
            <div style="flex: 1; overflow: hidden; cursor: pointer;" onclick="openLightbox('{{ asset($pImages[3]) }}')"><img src="{{ asset($pImages[3]) }}" alt="{{ $post->title }}" style="width: 100%; height: 100%; object-fit: cover;"></div>
            <div style="flex: 1; overflow: hidden; cursor: pointer;" onclick="openLightbox('{{ asset($pImages[4]) }}')"><img src="{{ asset($pImages[4]) }}" alt="{{ $post->title }}" style="width: 100%; height: 100%; object-fit: cover;"></div>
          </div>
          <span style="position: absolute; top: 12px; right: 12px; background: rgba(15, 23, 42, 0.85); color: #fff; padding: 0.3rem 0.7rem; border-radius: 4px; font-size: 0.75rem; font-weight: 700;">
            <i class="fa-regular fa-images"></i> 5 ảnh (Bấm để xem lớn)
          </span>
        </div>
      @endif

      <!-- Nội dung tóm tắt & chi tiết từ Database -->
      @if($post->excerpt)
      <div style="font-size: 1.05rem; font-weight: 600; color: var(--text-title); line-height: 1.7; margin-bottom: 1.5rem; padding: 1rem 1.25rem; background: var(--bg-subtle, #f8fafc); border-left: 4px solid var(--executive-blue); border-radius: 0 6px 6px 0;">
        {{ $post->excerpt }}
      </div>
      @endif

      <div style="font-size: 0.96rem; line-height: 1.85; color: var(--text-body); margin-bottom: 2rem;">
        {!! nl2br(e($post->content ?: $post->excerpt)) !!}
      </div>

      <!-- THẺ LINK BÀI BÁO ĐÍNH KÈM THAM KHẢO (CHỈ HIỂN THỊ KHI CÓ LINK THỰC TẾ TRONG DATABASE) -->
      @if(!empty($post->external_link))
      @php
        $rawLink = trim($post->external_link);
        $fullUrl = \Illuminate\Support\Str::startsWith($rawLink, ['http://', 'https://']) ? $rawLink : 'https://' . $rawLink;
        $parsedDomain = parse_url($fullUrl, PHP_URL_HOST) ?? 'link';
        $cleanDomain = preg_replace('/^www\./i', '', strtolower($parsedDomain));
        $favUrl = "https://www.google.com/s2/favicons?domain=" . ($cleanDomain ?: 'example.com') . "&sz=64";
        $linkTitle = !empty($post->external_link_title) ? $post->external_link_title : ($cleanDomain ?: 'Xem liên kết');
      @endphp
      <div style="margin: 2rem 0;">
        <div style="font-size: 0.8rem; font-weight: 800; color: var(--executive-blue); margin-bottom: 0.65rem; text-transform: uppercase; letter-spacing: 0.5px;">
          <i class="fa-solid fa-link"></i> TÀI LIỆU & BÀI BÁO THAM KHẢO ĐÍNH KÈM:
        </div>
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
              <span class="preview-title-text" style="font-size: 1.05rem; font-weight: 800; color: var(--text-title); line-height: 1.35;">
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
      </div>
      @endif

      <!-- THANH TƯƠNG TÁC THÍCH, CHIA SẺ VÀ BÌNH LUẬN TRONG CHI TIẾT BÀI VIẾT -->
      @php
        $isDetailLiked = in_array($post->id, session('liked_posts', []));
      @endphp
      <div class="post-footer-row" style="display: flex; align-items: center; justify-content: space-between; padding: 1.1rem 0; border-top: 1px solid var(--border-light); border-bottom: 1px solid var(--border-light); margin-top: 2rem;">
        <div style="display: flex; align-items: center; gap: 0.85rem;">
          <button type="button" class="post-meta-btn like-btn {{ $isDetailLiked ? 'liked' : '' }}" data-post-id="{{ $post->id }}" onclick="togglePostLike({{ $post->id }}, this)" title="Thích bài viết" style="padding: 0.45rem 1rem; font-size: 0.92rem; border-radius: 6px; cursor: pointer; display: inline-flex; align-items: center; gap: 0.45rem;">
            <i class="{{ $isDetailLiked ? 'fa-solid' : 'fa-regular' }} fa-heart" style="{{ $isDetailLiked ? 'color: #ef4444;' : '' }}"></i>
            <span class="likes-count">{{ $post->likes_count ?? 0 }}</span> Thích
          </button>
          <a href="#comments" class="post-meta-btn" title="Bình luận" style="padding: 0.45rem 1rem; font-size: 0.92rem; border-radius: 6px; text-decoration: none; display: inline-flex; align-items: center; gap: 0.45rem;">
            <i class="fa-regular fa-comment"></i>
            <span>{{ $post->comments->count() }}</span> Thảo luận
          </a>
        </div>
        <div style="display: flex; align-items: center; gap: 0.75rem;">
          <button type="button" class="post-meta-btn" onclick="copyPostShareLink(window.location.href)" title="Chia sẻ bài viết" style="padding: 0.45rem 1rem; font-size: 0.92rem; border-radius: 6px; cursor: pointer; display: inline-flex; align-items: center; gap: 0.45rem;">
            <i class="fa-solid fa-share-nodes"></i> Chia sẻ
          </button>
        </div>
      </div>

      <!-- PHẦN BÌNH LUẬN CỦA THÀNH VIÊN (ĐỒNG BỘ 100% DATABASE) -->
      <section id="comments" style="margin-top: 2.5rem; padding-top: 1.5rem; border-top: 2px solid #f1f5f9;">
        <h3 style="font-size: 1.15rem; font-weight: 800; color: var(--text-title); margin-bottom: 1rem;">
          Bình luận & Thảo luận ({{ $post->comments->count() }})
        </h3>

        <!-- Form gửi bình luận thực tế -->
        <form action="{{ route('post.comment', $post->id) }}" method="POST" style="margin-bottom: 1.75rem;">
          @csrf
          <div style="margin-bottom: 0.65rem;">
            <input type="text" name="author_name" placeholder="Họ tên của bạn (hoặc tên doanh nghiệp)..." style="width: 100%; padding: 0.6rem 0.85rem; border-radius: 6px; border: 1px solid #cbd5e1; font-size: 0.88rem; outline: none;">
          </div>
          <div style="margin-bottom: 0.65rem;">
            <textarea name="content" rows="3" required style="width: 100%; padding: 0.75rem; border-radius: 8px; border: 1px solid #cbd5e1; font-family: inherit; font-size: 0.9rem; outline: none;" placeholder="Viết phản hồi hoặc câu hỏi của bạn cho Ban quản trị..."></textarea>
          </div>
          <div style="display: flex; justify-content: flex-end;">
            <button type="submit" class="btn-publish-trigger" style="padding: 0.5rem 1.25rem; font-size: 0.85rem; border: none; background: var(--executive-blue); color: #fff; border-radius: 6px; font-weight: 700; cursor: pointer;">
              <i class="fa-solid fa-paper-plane"></i> Gửi bình luận
            </button>
          </div>
        </form>

        <!-- Danh sách bình luận thực tế từ Database -->
        <div id="commentsList" style="display: flex; flex-direction: column; gap: 1rem;">
          @php
            $sessionLikedComments = session('liked_comments', []);
          @endphp
          @forelse($post->comments as $comment)
          @php
            $isCommentLiked = in_array($comment->id, $sessionLikedComments) || (($comment->likes_count ?? 0) > 0);
            $isAuthorLiked = ($comment->likes_count ?? 0) > 0;
          @endphp
          <div class="comment-item-card" id="comment-item-{{ $comment->id }}" style="display: flex; gap: 0.75rem; background: var(--bg-subtle); padding: 1rem; border-radius: 8px; border: 1px solid var(--border-light); transition: all 0.2s ease;">
            <div class="author-circle-avatar" style="width: 36px; height: 36px; font-size: 0.78rem; background: #e2e8f0; color: #475569; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; flex-shrink: 0;">
              {{ mb_strtoupper(mb_substr($comment->author_name, 0, 2, 'UTF-8'), 'UTF-8') }}
            </div>
            <div style="flex: 1;">
              <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.2rem;">
                <div style="font-size: 0.88rem; font-weight: 700; color: var(--text-title);">{{ $comment->author_name }}</div>
                <div style="font-size: 0.74rem; color: var(--text-light-muted);">{{ $comment->created_at->diffForHumans() }}</div>
              </div>
              <p style="font-size: 0.88rem; color: var(--text-body); line-height: 1.5; margin: 0 0 0.5rem 0;">{{ $comment->content }}</p>

              <!-- THANH TƯƠNG TÁC: NÚT THẢ TIM & HUY HIỆU TÁC GIẢ ĐÃ THÍCH -->
              <div class="comment-actions-bar" style="display: flex; align-items: center; gap: 0.65rem; flex-wrap: wrap;">
                {{-- Nút Thích bình luận --}}
                <button type="button" 
                        class="btn-comment-like {{ $isCommentLiked ? 'liked' : '' }}" 
                        data-comment-id="{{ $comment->id }}"
                        onclick="toggleCommentLikeFront({{ $comment->id }}, this)" 
                        title="{{ $isCommentLiked ? 'Bỏ thích bình luận' : 'Thích bình luận' }}">
                  <i class="{{ $isCommentLiked ? 'fa-solid' : 'fa-regular' }} fa-heart"></i>
                  <span class="comment-like-count">{{ ($comment->likes_count ?? 0) > 0 ? $comment->likes_count : '' }}</span>
                </button>

                {{-- Huy hiệu Tác giả đã thích --}}
                <div class="author-liked-badge" 
                     id="authorHeartBadge-{{ $comment->id }}" 
                     style="{{ $isAuthorLiked ? 'display: inline-flex;' : 'display: none;' }}" 
                     title="Bình luận này đã được tác giả ({{ $authorUser?->name ?? 'Admin' }}) thả tim">
                  <div class="author-liked-avatar-wrap">
                    <img src="{{ $authorUser?->avatar_url ?? $currentUser?->avatar_url ?? asset('assets/images/admin-khanh.jpg') }}" alt="Tác giả" onerror="this.onerror=null; this.src='{{ asset('assets/images/admin-khanh.jpg') }}';">
                    <i class="fa-solid fa-heart badge-heart-icon"></i>
                  </div>
                  <span>Tác giả đã thích</span>
                </div>
              </div>
            </div>
          </div>
          @empty
          <div style="text-align: center; padding: 2rem 1rem; color: var(--text-muted); background: #f8fafc; border-radius: 8px; font-size: 0.86rem;">
            Chưa có bình luận nào cho bài viết này. Hãy là người đầu tiên để lại phản hồi!
          </div>
          @endforelse
        </div>
      </section>
    </article>

    <!-- SIDEBAR PHẢI (ĐỒNG BỘ 100% DATABASE) -->
    <aside class="right-sidebar-panel">

      <!-- WIDGET 1: THẺ ẢNH TÁC GIẢ & FOUNDER ADMIN (NẰM NGAY TRÊN BÀI VIẾT NỔI BẬT) -->
      <div class="median-author-widget">
        <div class="median-author-cover" style="background-image: url('{{ asset($siteSettings['cover_desktop'] ?? 'assets/images/cover-banner.jpg') }}'); background-size: cover; background-position: center; height: 54px; margin: -1.15rem -1.15rem 0 -1.15rem;"></div>
        <div class="median-author-avatar-wrap">
          <img src="{{ $authorUser?->avatar_url ?? $currentUser?->avatar_url ?? asset('assets/images/admin-khanh.jpg') }}" alt="{{ $authorUser?->name ?? 'Admin Khánh' }}" onerror="this.onerror=null; this.src='{{ asset('assets/images/admin-khanh.jpg') }}';">
        </div>
        <div class="median-author-name">
          <span>{{ $authorUser?->name ?? 'Bùi Viết Khánh' }}</span>
          <i class="fa-solid fa-circle-check median-verified-check" title="Quản trị viên đã xác minh"></i>
        </div>
        <div class="median-author-role">{{ strtoupper($authorUser?->role_title ?? 'FOUNDER & QUẢN TRỊ VIÊN') }}</div>
        <div class="median-author-bio">
          {{ \Illuminate\Support\Str::limit($authorUser?->bio ?? 'Chia sẻ chiến lược quản trị, tài chính và góc nhìn điều hành thực chiến.', 115) }}
        </div>

        <!-- Thống kê năng lực -->
        <div class="median-author-stats">
          <div>
            <div class="median-stat-val">{{ \App\Models\Post::where('is_published', true)->count() }}</div>
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

      <!-- BÀI VIẾT NỔI BẬT KHÁC (DO ADMIN ĐÁNH DẤU is_featured = 1) -->
      @php
        $rightFeatured = $featuredPosts ?? ($trendingPosts ?? []);
      @endphp
      @if(isset($rightFeatured) && count($rightFeatured) > 0)
      <div class="median-trending-card" style="margin-bottom: 1.5rem;">
        <div class="median-widget-header">
          <div class="median-widget-title">
            <i class="fa-solid fa-heart" style="color: #ef4444;"></i>
            <span>Bài viết nổi bật</span>
          </div>
          <span style="font-size: 0.7rem; font-weight: 700; color: #ef4444; background: rgba(239, 68, 68, 0.12); padding: 0.15rem 0.5rem; border-radius: 4px; text-transform: uppercase;">Top yêu thích</span>
        </div>
        <div class="median-trending-list">
          @foreach($rightFeatured as $index => $tPost)
          <a href="{{ route('post.detail', $tPost->id) }}" class="median-trending-item">
            <span class="median-rank-num top-{{ $index + 1 }}">0{{ $index + 1 }}</span>
            <div class="median-trending-thumb">
              <img src="{{ asset($tPost->cover_image ?: 'assets/images/post-tech-ai.jpg') }}" alt="{{ $tPost->title }}">
            </div>
            <div class="median-trending-info">
              <div class="median-trending-title">{{ $tPost->title }}</div>
              <div class="median-trending-meta" style="display: flex; align-items: center; gap: 0.45rem;">
                <span style="color: #ef4444; font-weight: 700; display: inline-flex; align-items: center; gap: 0.25rem;">
                  <i class="fa-solid fa-heart" style="font-size: 0.68rem;"></i> {{ number_format($tPost->likes_count ?? 0) }}
                </span>
                <span style="color: var(--text-muted);">&bull;</span>
                <span>{{ $tPost->created_at ? $tPost->created_at->format('d/m') : '' }}</span>
              </div>
            </div>
          </a>
          @endforeach
        </div>
      </div>
      @endif

      <!-- CHỦ ĐỀ PHỔ BIẾN TỪ DATABASE -->
      <div class="widget-card">
        <h4 class="widget-card-title">Chuyên mục bài viết</h4>
        <div class="popular-tags-list">
          @if(isset($globalCategories) && count($globalCategories) > 0)
            @foreach($globalCategories as $gCat)
              <a href="{{ route('home') }}?cat={{ $gCat->slug }}" class="tag-badge" style="text-decoration: none; display: inline-flex; align-items: center; gap: 0.35rem; margin-bottom: 0.4rem; padding: 0.35rem 0.65rem; border-radius: 9999px; background: rgba(37,99,235,0.08); color: {{ $gCat->color ?? '#2563eb' }}; border: 1px solid {{ $gCat->color ?? '#2563eb' }}33; font-weight: 700; font-size: 0.78rem;">
                <span>{{ $gCat->name }}</span>
                <span style="font-size: 0.72rem; opacity: 0.85;">{{ $gCat->posts_count }}</span>
              </a>
            @endforeach
          @endif
        </div>
      </div>
    </aside>

  </div>
  @endif
</main>

<!-- Lightbox xem ảnh kích thước lớn (Fullscreen Zoom) -->
<div id="imageLightboxModal" style="display: none; position: fixed; inset: 0; background: rgba(0, 0, 0, 0.88); z-index: 99999; align-items: center; justify-content: center; backdrop-filter: blur(6px); cursor: pointer;" onclick="this.style.display='none'; document.body.style.overflow=''">
  <div style="position: relative; max-width: 90%; max-height: 90%; text-align: center;" onclick="event.stopPropagation()">
    <img id="lightboxImg" src="" alt="Ảnh kích thước lớn" style="max-width: 100%; max-height: 86vh; border-radius: 8px; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.6); object-fit: contain;">
    <button type="button" onclick="document.getElementById('imageLightboxModal').style.display='none'; document.body.style.overflow=''" style="position: absolute; top: -16px; right: -16px; background: #ef4444; color: #fff; border: 2px solid #fff; width: 36px; height: 36px; border-radius: 50%; font-size: 1.1rem; cursor: pointer; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 6px rgba(0,0,0,0.2);">
      <i class="fa-solid fa-xmark"></i>
    </button>
  </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/post-detail.js') }}"></script>
@endpush
