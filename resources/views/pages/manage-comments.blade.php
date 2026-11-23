@extends('layouts.app')

@section('title', 'Quản lý bình luận - KẾT NỐI Admin')
@section('breadcrumb', 'WORKSPACE / QUẢN TRỊ NỘI DUNG / BÌNH LUẬN')

@section('content')
<main class="main-content-scroll">
  <!-- TIÊU ĐỀ TRANG -->
  <div class="content-header-row" style="margin-bottom: 1.5rem;">
    <div>
      <h1 class="forum-main-title" style="font-size: 1.55rem;">Quản lý bình luận</h1>
      <p class="forum-subtitle">Theo dõi phản hồi của độc giả, kiểm duyệt và phân loại bình luận đồng bộ từ Database.</p>
    </div>
  </div>

  @if(session('success'))
    <div style="background-color: #dcfce7; border: 1px solid #86efac; color: #166534; padding: 0.85rem 1.25rem; border-radius: 8px; margin-bottom: 1.25rem; font-size: 0.88rem; font-weight: 600; display: flex; align-items: center; gap: 0.5rem;">
      <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
    </div>
  @endif

  <!-- 4 THẺ THỐNG KÊ (ĐỒNG BỘ 100% DATABASE) -->
  <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.25rem; margin-bottom: 1.75rem;">
    <!-- 1. Tổng bình luận -->
    <div class="widget-card" style="padding: 1.25rem;">
      <div style="font-size: 0.75rem; color: var(--text-muted); font-weight: 700; text-transform: uppercase;">TỔNG BÌNH LUẬN</div>
      <div style="font-size: 1.85rem; font-weight: 800; color: var(--text-title); margin: 0.35rem 0;" id="statTotalComments">
        {{ number_format($totalComments ?? 0) }}
      </div>
      <div style="font-size: 0.75rem; color: var(--text-muted); font-weight: 600;">Toàn bộ bài viết</div>
    </div>

    <!-- 2. Bình luận đã thích -->
    <div class="widget-card" style="padding: 1.25rem; cursor: pointer; transition: all 0.2s ease;" onclick="filterLikedComments()" title="Bấm để lọc các bình luận đã thích" onmouseover="this.style.borderColor='#fecdd3'; this.style.transform='translateY(-2px)'" onmouseout="this.style.borderColor='var(--border-card)'; this.style.transform='none'">
      <div style="font-size: 0.75rem; color: var(--text-muted); font-weight: 700; text-transform: uppercase; display: flex; align-items: center; justify-content: space-between;">
        <span>BÌNH LUẬN ĐÃ THÍCH</span>
        <i class="fa-solid fa-heart" style="color: #e11d48; font-size: 0.85rem;"></i>
      </div>
      <div style="font-size: 1.85rem; font-weight: 800; color: #e11d48; margin: 0.35rem 0;" id="statLikedComments">
        {{ number_format($likedComments ?? 0) }}
      </div>
      <div style="font-size: 0.75rem; color: #e11d48; font-weight: 600;">❤️ Bấm để lọc danh sách</div>
    </div>

    <!-- 3. Chuyên mục thảo luận nhiều nhất -->
    <div class="widget-card" style="padding: 1.25rem;">
      <div style="font-size: 0.75rem; color: var(--text-muted); font-weight: 700; text-transform: uppercase;">CHỦ ĐỀ SÔI NỔI NHẤT</div>
      <div style="font-size: 1.25rem; font-weight: 800; color: var(--text-title); margin: 0.55rem 0 0.45rem 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
        {{ $topCategoryName ?? 'Chiến lược' }}
      </div>
      <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $topCategoryComments ?? 0 }} lượt thảo luận</div>
    </div>

    <!-- 4. Thảo luận mới hôm nay -->
    <div class="widget-card" style="padding: 1.25rem;">
      <div style="font-size: 0.75rem; color: var(--text-muted); font-weight: 700; text-transform: uppercase;">BÌNH LUẬN HÔM NAY</div>
      <div style="font-size: 1.85rem; font-weight: 800; color: var(--executive-blue); margin: 0.35rem 0;">
        {{ number_format($todayComments ?? 0) }}
      </div>
      <div style="font-size: 0.75rem; color: var(--executive-blue); font-weight: 600;">Cập nhật thời gian thực</div>
    </div>
  </div>

  <!-- BẢNG DANH SÁCH BÌNH LUẬN -->
  <div class="widget-card" style="padding: 1.5rem; margin-bottom: 1.5rem;">
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.25rem; flex-wrap: wrap; gap: 0.5rem;">
      <div>
        <h3 style="font-size: 1.05rem; font-weight: 800; color: var(--text-title);">Danh sách bình luận của độc giả</h3>
        <p style="font-size: 0.78rem; color: var(--text-muted); margin-top: 0.15rem;">Dữ liệu phản hồi được đồng bộ trực tiếp từ Database</p>
      </div>
      <span style="font-size: 0.8rem; color: var(--text-muted); font-weight: 700;" id="totalCommentsCountText" data-total="{{ $comments->total() }}">
        Tổng số: {{ $comments->total() }} bình luận
      </span>
    </div>

    <!-- BỘ LỌC TÌM KIẾM NHANH TRỰC TIẾP -->
    <div style="display: flex; flex-direction: column; gap: 0.85rem; margin-bottom: 1.25rem;">
      <div style="position: relative; width: 100%;">
        <input type="text" id="commentFilterInput" placeholder="Tìm theo nội dung bình luận, tên thành viên hoặc bài viết..." style="width: 100%; padding: 0.55rem 0.85rem 0.5rem 2.2rem; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 0.86rem; outline: none; background: #ffffff;">
        <i class="fa-solid fa-magnifying-glass" style="position: absolute; left: 0.75rem; top: 50%; transform: translateY(-50%); color: var(--text-light-muted); font-size: 0.85rem;"></i>
      </div>

      <!-- BỘ LỌC DANH MỤC THỰC TẾ & BÌNH LUẬN ĐÃ THÍCH -->
      <div style="display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap;">
        <span style="font-size: 0.78rem; font-weight: 800; color: var(--text-title); margin-right: 0.25rem;">Lọc theo danh mục:</span>
        <button class="cat-pill active" data-cat="all">Tất cả danh mục</button>
        @if(isset($globalCategories) && count($globalCategories) > 0)
          @foreach($globalCategories as $cat)
            <button class="cat-pill" data-cat="{{ Str::lower($cat->slug) }}" data-name="{{ Str::lower($cat->name) }}">
              <span style="display: inline-block; width: 7px; height: 7px; border-radius: 50%; background-color: {{ $cat->color ?? '#2563eb' }}; margin-right: 4px;"></span>
              {{ $cat->name }}
            </button>
          @endforeach
        @endif
        <button class="cat-pill" id="btnFilterLiked" data-cat="liked" style="border-color: #fecdd3; color: #e11d48; background: #fff1f2; font-weight: 700; cursor: pointer; display: inline-flex; align-items: center; gap: 0.35rem;">
          <i class="fa-solid fa-heart" style="color: #e11d48;"></i>
          <span>Đã thích (<strong id="pillLikedCount">{{ $likedComments }}</strong>)</span>
        </button>
      </div>
    </div>

    <!-- BẢNG BÌNH LUẬN THỰC TẾ TỪ DATABASE -->
    <div style="overflow-x: auto;">
      <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.84rem;">
        <thead>
          <tr style="border-bottom: 2px solid #f1f5f9; color: var(--text-light-muted); font-size: 0.74rem; font-weight: 800; letter-spacing: 0.5px;">
            <th style="padding: 0.75rem 0.5rem; width: 18%;">THÀNH VIÊN</th>
            <th style="padding: 0.75rem 0.5rem; width: 36%;">NỘI DUNG BÌNH LUẬN</th>
            <th style="padding: 0.75rem 0.5rem; width: 22%;">BÀI VIẾT & DANH MỤC</th>
            <th style="padding: 0.75rem 0.5rem; width: 10%;">THỜI GIAN</th>
            <th style="padding: 0.75rem 0.5rem; text-align: right; width: 14%;">THAO TÁC</th>
          </tr>
        </thead>
        <tbody id="commentTableBody">
          @forelse($comments as $comment)
          @php
            $cPost = $comment->post;
            $cCatName = $cPost?->categoryItem?->name ?? $cPost?->category ?? 'Chưa phân loại';
            $cCatSlug = $cPost?->categoryItem?->slug ?? Str::slug($cCatName);
          @endphp
          <tr class="comment-row" style="border-bottom: 1px solid #f8fafc;" data-category="{{ Str::lower($cCatSlug) }} {{ Str::lower($cCatName) }}" data-is-liked="{{ ($comment->likes_count ?? 0) > 0 ? 'true' : 'false' }}">
            <td style="padding: 1rem 0.5rem;">
              <div style="font-weight: 700; color: var(--text-title);">{{ $comment->author_name }}</div>
              <div style="font-size: 0.74rem; color: var(--text-light-muted);">{{ $comment->author_email ?: 'Thành viên diễn đàn' }}</div>
            </td>
            <td style="padding: 1rem 0.5rem; color: var(--text-body);">
              <div class="comment-text-content" style="line-height: 1.45;">
                {{ $comment->content }}
              </div>
            </td>
            <td style="padding: 1rem 0.5rem;">
              @if($cPost)
                <a href="{{ route('post.detail', $cPost->id) }}" target="_blank" style="color: var(--text-title); font-weight: 700; font-size: 0.84rem; text-decoration: none; display: block; line-height: 1.35;">
                  {{ Str::limit($cPost->title, 40) }}
                </a>
                <span class="post-category-badge" style="background: rgba(37,99,235,0.08); color: var(--executive-blue); display: inline-block; margin-top: 0.25rem; font-size: 0.68rem; padding: 0.15rem 0.5rem;">
                  {{ $cCatName }}
                </span>
              @else
                <span style="color: var(--text-muted); font-size: 0.8rem;">Bài viết đã xóa</span>
              @endif
            </td>
            <td style="padding: 1rem 0.5rem; color: var(--text-muted); font-size: 0.78rem; white-space: nowrap;">
              {{ $comment->created_at->format('d/m/Y') }}<br>
              <span style="font-size: 0.72rem; color: var(--text-light-muted);">{{ $comment->created_at->format('H:i') }}</span>
            </td>
            <td style="padding: 1rem 0.5rem; text-align: right; white-space: nowrap;">
              <!-- XEM -->
              <button type="button" class="btn-action-view cat-pill" onclick="openViewCommentModal('{{ addslashes($comment->author_name) }}', '{{ addslashes($cPost?->title ?? 'Bài viết') }}', '{{ addslashes($comment->content) }}', '{{ $comment->created_at->format('d/m/Y H:i') }}', '{{ $cPost ? route('post.detail', $cPost->id) : '#' }}')" style="padding: 0.35rem 0.65rem; font-size: 0.78rem; font-weight: 700; cursor: pointer;" title="Xem chi tiết">
                <i class="fa-regular fa-eye"></i> Xem
              </button>
              <!-- THÍCH / BỎ THÍCH -->
              <button type="button" class="btn-action-like cat-pill {{ ($comment->likes_count ?? 0) > 0 ? 'liked' : '' }}" onclick="toggleCommentLike({{ $comment->id }}, this)" style="padding: 0.35rem 0.65rem; font-size: 0.78rem; font-weight: 700; cursor: pointer; {{ ($comment->likes_count ?? 0) > 0 ? 'color: #e11d48; border-color: #fecdd3; background: #fff1f2;' : 'color: var(--text-muted); background: #ffffff;' }}" title="{{ ($comment->likes_count ?? 0) > 0 ? 'Bỏ thích bình luận' : 'Thích bình luận' }}">
                <i class="{{ ($comment->likes_count ?? 0) > 0 ? 'fa-solid' : 'fa-regular' }} fa-heart" style="color: #e11d48; margin-right: 2px;"></i>
                <span class="like-btn-label">{{ ($comment->likes_count ?? 0) > 0 ? 'Đã thích' : 'Thích' }}</span>
              </button>
              <!-- XOÁ -->
              <form action="{{ route('admin.comments.destroy', $comment->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa bình luận này?');" style="display: inline; margin: 0;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-action-delete cat-pill" style="padding: 0.35rem 0.65rem; font-size: 0.78rem; font-weight: 700; color: #dc2626; border-color: #fecaca; background: none; cursor: pointer;" title="Xóa bình luận">
                  <i class="fa-solid fa-trash-can"></i> Xóa
                </button>
              </form>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="5" style="text-align: center; padding: 3rem 1rem; color: var(--text-muted);">
              <div style="font-size: 2rem; color: var(--text-light-muted); margin-bottom: 0.5rem;">
                <i class="fa-regular fa-comments"></i>
              </div>
              <div style="font-weight: 700; color: var(--text-title); font-size: 1rem;">Chưa có bình luận nào</div>
              <p style="font-size: 0.82rem; margin: 0.35rem 0 0 0;">Khi độc giả gửi bình luận trên các bài viết, dữ liệu sẽ tự động xuất hiện tại đây.</p>
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <!-- PHÂN TRANG CHUẨN MEDIAN UI -->
    <div style="display: flex; align-items: center; justify-content: space-between; padding-top: 1.25rem; border-top: 1px solid #f1f5f9; font-size: 0.82rem; color: var(--text-muted); margin-top: 0.5rem; flex-wrap: wrap; gap: 0.75rem;">
      <div style="display: flex; align-items: center; gap: 0.65rem;">
        <span>Hiển thị <strong>{{ $comments->firstItem() ?? 0 }} - {{ $comments->lastItem() ?? 0 }}</strong> trên tổng số <strong>{{ $comments->total() }}</strong> phản hồi</span>
      </div>

      @if($comments->hasPages())
      <nav style="display: flex; align-items: center; gap: 0.35rem;">
        {{-- Nút Về trang trước --}}
        @if($comments->onFirstPage())
          <span style="display: inline-flex; align-items: center; justify-content: center; height: 32px; padding: 0 0.75rem; font-size: 0.8rem; font-weight: 700; color: #94a3b8; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; cursor: not-allowed; opacity: 0.6;">
            <i class="fa-solid fa-chevron-left" style="font-size: 0.7rem; margin-right: 4px;"></i> Trước
          </span>
        @else
          <a href="{{ request()->fullUrlWithQuery(['page' => $comments->currentPage() - 1]) }}" style="display: inline-flex; align-items: center; justify-content: center; height: 32px; padding: 0 0.75rem; font-size: 0.8rem; font-weight: 700; color: var(--text-title); background: #ffffff; border: 1px solid #cbd5e1; border-radius: 6px; text-decoration: none; transition: all 0.2s ease;">
            <i class="fa-solid fa-chevron-left" style="font-size: 0.7rem; margin-right: 4px;"></i> Trước
          </a>
        @endif

        {{-- Các số trang (1, 2, 3...) --}}
        @for($p = 1; $p <= $comments->lastPage(); $p++)
          @if($p == $comments->currentPage())
            <span style="display: inline-flex; align-items: center; justify-content: center; min-width: 32px; height: 32px; padding: 0 0.65rem; font-size: 0.82rem; font-weight: 800; color: #ffffff; background: var(--executive-blue, #2563eb); border: 1px solid var(--executive-blue, #2563eb); border-radius: 6px; box-shadow: 0 2px 4px rgba(37, 99, 235, 0.25);">
              {{ $p }}
            </span>
          @else
            <a href="{{ request()->fullUrlWithQuery(['page' => $p]) }}" style="display: inline-flex; align-items: center; justify-content: center; min-width: 32px; height: 32px; padding: 0 0.65rem; font-size: 0.82rem; font-weight: 700; color: var(--text-title); background: #ffffff; border: 1px solid #cbd5e1; border-radius: 6px; text-decoration: none; transition: all 0.2s ease;">
              {{ $p }}
            </a>
          @endif
        @endfor

        {{-- Nút Sang trang sau --}}
        @if($comments->hasMorePages())
          <a href="{{ request()->fullUrlWithQuery(['page' => $comments->currentPage() + 1]) }}" style="display: inline-flex; align-items: center; justify-content: center; height: 32px; padding: 0 0.75rem; font-size: 0.8rem; font-weight: 700; color: var(--text-title); background: #ffffff; border: 1px solid #cbd5e1; border-radius: 6px; text-decoration: none; transition: all 0.2s ease;">
            Sau <i class="fa-solid fa-chevron-right" style="font-size: 0.7rem; margin-left: 4px;"></i>
          </a>
        @else
          <span style="display: inline-flex; align-items: center; justify-content: center; height: 32px; padding: 0 0.75rem; font-size: 0.8rem; font-weight: 700; color: #94a3b8; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; cursor: not-allowed; opacity: 0.6;">
            Sau <i class="fa-solid fa-chevron-right" style="font-size: 0.7rem; margin-left: 4px;"></i>
          </span>
        @endif
      </nav>
      @endif
    </div>
  </div>
</main>
@endsection

@section('modals')
<!-- MODAL XEM CHI TIẾT BÌNH LUẬN -->
<div id="viewCommentModal" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.65); z-index: 10000; align-items: center; justify-content: center; backdrop-filter: blur(4px);">
  <div style="background: white; width: 540px; max-width: 92%; border-radius: 12px; padding: 1.75rem; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.25);">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; border-bottom: 1px solid #f1f5f9; padding-bottom: 0.75rem;">
      <div>
        <h3 style="font-size: 1.15rem; font-weight: 800; color: var(--obsidian); margin: 0;">Chi tiết phản hồi của độc giả</h3>
        <span id="modalCommentTime" style="font-size: 0.74rem; color: var(--text-light-muted);"></span>
      </div>
      <button type="button" onclick="closeViewCommentModal()" style="background: none; border: none; font-size: 1.5rem; cursor: pointer; color: var(--text-muted); line-height: 1;">&times;</button>
    </div>

    <div style="margin-bottom: 1rem;">
      <div style="font-size: 0.74rem; font-weight: 800; color: var(--text-light-muted); text-transform: uppercase;">Người bình luận:</div>
      <div id="modalCommentAuthor" style="font-size: 0.95rem; font-weight: 800; color: var(--text-title); margin-top: 0.15rem;"></div>
    </div>

    <div style="margin-bottom: 1rem;">
      <div style="font-size: 0.74rem; font-weight: 800; color: var(--text-light-muted); text-transform: uppercase;">Bài viết thuộc danh mục:</div>
      <div id="modalCommentPost" style="font-size: 0.9rem; font-weight: 700; color: var(--executive-blue); margin-top: 0.15rem;"></div>
    </div>

    <div style="background: #f8fafc; padding: 1rem; border-radius: 8px; border: 1px solid #e2e8f0; margin-bottom: 1.25rem;">
      <div style="font-size: 0.74rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; margin-bottom: 0.35rem;">Toàn văn nội dung:</div>
      <p id="modalCommentContent" style="font-size: 0.92rem; color: var(--text-body); line-height: 1.55; font-style: italic; margin: 0;"></p>
    </div>

    <div style="display: flex; justify-content: space-between; align-items: center;">
      <a id="modalPostLink" href="#" target="_blank" style="font-size: 0.84rem; font-weight: 700; color: var(--executive-blue); display: flex; align-items: center; gap: 0.35rem; text-decoration: none;">
        <span>Xem bài viết gốc trên diễn đàn</span>
        <i class="fa-solid fa-arrow-up-right-from-square"></i>
      </a>
      <button type="button" onclick="closeViewCommentModal()" class="btn-publish-trigger" style="background-color: var(--executive-blue); padding: 0.5rem 1.25rem; font-size: 0.84rem; border: none; color: #fff; border-radius: 6px; cursor: pointer;">
        Đóng
      </button>
    </div>
  </div>
</div>
@endsection

@if(isset($autoOpenComment) && $autoOpenComment)
<div id="autoOpenCommentData" style="display:none;"
    data-author="{{ $autoOpenComment->author_name }}"
    data-post="{{ $autoOpenComment->post?->title ?? 'Bài viết' }}"
    data-content="{{ $autoOpenComment->content }}"
    data-time="{{ $autoOpenComment->created_at->format('d/m/Y H:i') }}"
    data-url="{{ $autoOpenComment->post ? route('post.detail', $autoOpenComment->post->id) : '#' }}">
</div>
@endif

@push('scripts')
<script src="{{ asset('js/manage-comments.js') }}"></script>
@endpush
