@extends('layouts.app')

@section('title', 'Quản lý bài viết - KẾT NỐI Admin')
@section('breadcrumb', 'WORKSPACE / QUẢN TRỊ NỘI DUNG / BÀI VIẾT')

@section('content')
<main class="main-content-scroll">
    <!-- TIÊU ĐỀ & NÚT + TẠO BÀI VIẾT MỚI -->
    <div class="content-header-row" style="margin-bottom: 1.5rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h1 class="forum-main-title" style="font-size: 1.55rem; font-weight: 800; color: var(--obsidian); margin: 0;">Quản lý bài viết</h1>
            <p class="forum-subtitle" style="font-size: 0.85rem; color: var(--text-muted); margin-top: 0.25rem;">Tạo mới, chỉnh sửa và quản lý nội dung diễn đàn doanh nghiệp.</p>
        </div>
        <button type="button" class="btn-publish-trigger" onclick="openModal('createPostModal')" style="background-color: var(--executive-blue); font-size: 0.88rem; padding: 0.6rem 1.25rem; font-weight: 700; border-radius: 6px; display: flex; align-items: center; gap: 0.45rem; cursor: pointer; border: none; color: #ffffff;">
            <i class="fa-solid fa-plus"></i> Tạo bài viết mới
        </button>
    </div>

    <!-- 3 THẺ THỐNG KÊ DOANH NGHIỆP -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.25rem; margin-bottom: 1.75rem;">
        <!-- Thẻ 1: Tổng bài viết -->
        <div class="widget-card" style="padding: 1.4rem;">
            <div style="font-size: 0.82rem; color: var(--text-muted); font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">Tổng bài viết</div>
            <div style="font-size: 2rem; font-weight: 800; color: var(--text-title); margin: 0.35rem 0;">{{ $totalPosts }}</div>
            <div style="font-size: 0.78rem; color: #16a34a; font-weight: 700; display: flex; align-items: center; gap: 0.35rem;">
                <i class="fa-solid fa-circle-check"></i> {{ $totalPublished }} bài đã xuất bản
            </div>
        </div>

        <!-- Thẻ 2: Tổng bình luận -->
        <div class="widget-card" style="padding: 1.4rem;">
            <div style="font-size: 0.82rem; color: var(--text-muted); font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">Bình luận</div>
            <div style="font-size: 2rem; font-weight: 800; color: var(--text-title); margin: 0.35rem 0;">{{ $totalComments }}</div>
            <div style="font-size: 0.78rem; color: var(--text-muted); font-weight: 600;">Trên tất cả bài viết</div>
        </div>

        <!-- Thẻ 3: Bản nháp -->
        <div class="widget-card" style="padding: 1.4rem;">
            <div style="font-size: 0.82rem; color: var(--text-muted); font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">Bản nháp</div>
            <div style="font-size: 2rem; font-weight: 800; color: var(--text-title); margin: 0.35rem 0;">{{ $totalDrafts }}</div>
            <div style="font-size: 0.78rem; color: #d97706; font-weight: 700; display: flex; align-items: center; gap: 0.35rem;">
                <i class="fa-solid fa-pen-ruler"></i> Chưa công khai ngoài blog
            </div>
        </div>
    </div>

    <!-- KHU VỰC: DANH SÁCH BÀI VIẾT (TABLE VIEW) -->
    <div class="widget-card" style="padding: 1.5rem; margin-bottom: 2rem;">
        
        <!-- BỘ LỌC & TÌM KIẾM BÀI VIẾT -->
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.25rem; flex-wrap: wrap; gap: 0.75rem;">
            <div style="display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap;">
                <a href="{{ route('admin.posts') }}" class="cat-pill {{ empty(request('status')) && empty(request('category_id')) ? 'active' : '' }}" style="text-decoration: none; font-size: 0.8rem; padding: 0.4rem 0.85rem;">
                    Tất cả ({{ $totalPosts }})
                </a>
                <a href="{{ route('admin.posts', ['status' => 'published']) }}" class="cat-pill {{ request('status') === 'published' ? 'active' : '' }}" style="text-decoration: none; font-size: 0.8rem; padding: 0.4rem 0.85rem; color: #15803d; border-color: #bbf7d0;">
                    <i class="fa-solid fa-circle" style="font-size: 0.45rem; margin-right: 3px;"></i> Đã đăng ({{ $totalPublished }})
                </a>
                <a href="{{ route('admin.posts', ['status' => 'draft']) }}" class="cat-pill {{ request('status') === 'draft' ? 'active' : '' }}" style="text-decoration: none; font-size: 0.8rem; padding: 0.4rem 0.85rem; color: #c2410c; border-color: #fed7aa;">
                    <i class="fa-solid fa-circle" style="font-size: 0.45rem; margin-right: 3px;"></i> Bản nháp ({{ $totalDrafts }})
                </a>
            </div>

            <!-- Ô TÌM KIẾM BÀI VIẾT -->
            <form action="{{ route('admin.posts') }}" method="GET" style="display: flex; align-items: center; gap: 0.5rem;">
                @if(request('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif
                <div style="position: relative; width: 260px;">
                    <input type="text" id="adminPostSearchInput" name="search" value="{{ request('search') }}" placeholder="Tìm theo tiêu đề, chuyên mục..." style="width: 100%; padding: 0.45rem 0.75rem 0.45rem 2rem; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.82rem; outline: none; background: #ffffff;">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="position: absolute; left: 0.65rem; top: 50%; transform: translateY(-50%); color: var(--text-light-muted);"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                </div>
                @if(request('search'))
                    <a href="{{ route('admin.posts') }}" class="cat-pill" style="font-size: 0.78rem; padding: 0.4rem 0.65rem; text-decoration: none; color: #ef4444;" title="Xóa tìm kiếm">&times;</a>
                @endif
            </form>
        </div>

        <!-- BẢNG DANH SÁCH BÀI VIẾT -->
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.86rem;" id="adminPostsTable">
                <thead>
                    <tr style="border-bottom: 2px solid var(--border-light, #f1f5f9); color: var(--text-muted); font-size: 0.74rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px;">
                        <th style="padding: 0.75rem 0.5rem; width: 45%;">Tiêu đề bài viết</th>
                        <th style="padding: 0.75rem 0.5rem;">Chuyên mục</th>
                        <th style="padding: 0.75rem 0.5rem; text-align: center;">Trạng thái</th>
                        <th style="padding: 0.75rem 0.5rem; text-align: center;">Bình luận</th>
                        <th style="padding: 0.75rem 0.5rem; text-align: center;">Lượt đọc</th>
                        <th style="padding: 0.75rem 0.5rem; text-align: right;">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($posts as $post)
                    <tr style="border-bottom: 1px solid #f8fafc; transition: background 0.15s ease;" class="admin-post-row">
                        <!-- Cột tiêu đề -->
                        <td style="padding: 0.9rem 0.5rem;">
                            <div style="display: flex; align-items: center; gap: 0.75rem;">
                                <div style="width: 48px; height: 48px; border-radius: 6px; overflow: hidden; flex-shrink: 0; background: #e2e8f0;">
                                    <img src="{{ asset($post->cover_image ?: 'assets/images/post-tech-ai.jpg') }}" alt="{{ $post->title }}" style="width: 100%; height: 100%; object-fit: cover;">
                                </div>
                                <div>
                                    <div style="font-weight: 700; color: var(--text-title); font-size: 0.88rem; line-height: 1.35; margin-bottom: 0.2rem;">
                                        {{ $post->title }}
                                    </div>
                                    <div style="font-size: 0.75rem; color: var(--text-light-muted); display: flex; align-items: center; gap: 0.5rem;">
                                        <span><i class="fa-regular fa-calendar"></i> {{ $post->created_at->format('d/m/Y') }}</span>
                                        <span>&bull;</span>
                                        <span><i class="fa-regular fa-clock"></i> {{ $post->reading_time }} phút đọc</span>
                                        @if($post->is_featured)
                                            <span style="background: #fef3c7; color: #b45309; padding: 0.1rem 0.4rem; border-radius: 4px; font-weight: 700; font-size: 0.68rem;">TIÊU ĐIỂM</span>
                                        @endif
                                        @if(count($post->all_images) > 1)
                                            <span style="background: #f0fdf4; color: #166534; padding: 0.1rem 0.45rem; border-radius: 4px; font-weight: 700; font-size: 0.68rem; display: inline-flex; align-items: center; gap: 0.2rem;">
                                                <i class="fa-regular fa-images"></i> {{ count($post->all_images) }} ảnh
                                            </span>
                                        @endif
                                        @if(!empty($post->external_link))
                                        @php
                                            $admLink = trim($post->external_link);
                                            $admUrl = Str::startsWith($admLink, ['http://', 'https://']) ? $admLink : 'https://' . $admLink;
                                            $admHost = preg_replace('/^www\./i', '', parse_url($admUrl, PHP_URL_HOST) ?? '');
                                            $admFav = "https://www.google.com/s2/favicons?domain=" . ($admHost ?: 'example.com') . "&sz=32";
                                            $admTitle = !empty($post->external_link_title) ? $post->external_link_title : ($admHost ?: 'Liên kết');
                                        @endphp
                                            <a href="{{ $admUrl }}" target="_blank" rel="noopener noreferrer" class="post-external-link-badge" title="{{ $admLink }}">
                                                <img src="{{ $admFav }}" alt="Favicon" style="width: 13px; height: 13px; object-fit: contain; border-radius: 2px;" onerror="this.style.display='none'">
                                                <span>{{ Str::limit($admTitle, 22) }}</span>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>

                        <!-- Cột chuyên mục -->
                        <td style="padding: 0.9rem 0.5rem;">
                            @php
                                $catName = $post->categoryItem?->name ?? $post->category ?? 'Chưa phân loại';
                                $catColor = $post->categoryItem?->color ?? '#2563eb';
                            @endphp
                            <span style="display: inline-flex; align-items: center; gap: 0.35rem; font-size: 0.76rem; font-weight: 700; color: var(--text-title); background: var(--bg-subtle, #f8fafc); border: 1px solid var(--border-light, #e2e8f0); padding: 0.2rem 0.6rem; border-radius: 9999px;">
                                <span style="width: 7px; height: 7px; border-radius: 50%; background-color: {{ $catColor }};"></span>
                                {{ $catName }}
                            </span>
                        </td>

                        <!-- Cột trạng thái (Bấm đổi nhanh trạng thái Đã đăng / Bản nháp) -->
                        <td style="padding: 0.9rem 0.5rem; text-align: center;">
                            <form action="{{ route('admin.posts.toggle', $post->id) }}" method="POST" style="margin: 0;">
                                @csrf
                                @method('PATCH')
                                <button type="submit" style="background: none; border: none; cursor: pointer; padding: 0;" title="Nhấn để chuyển trạng thái">
                                    @if($post->is_published)
                                        <span style="background-color: #dcfce7; color: #15803d; font-size: 0.72rem; font-weight: 700; padding: 0.25rem 0.75rem; border-radius: 9999px; display: inline-flex; align-items: center; gap: 0.3rem;">
                                            <i class="fa-solid fa-check"></i> Đã đăng
                                        </span>
                                    @else
                                        <span style="background-color: #ffedd5; color: #c2410c; font-size: 0.72rem; font-weight: 700; padding: 0.25rem 0.75rem; border-radius: 9999px; display: inline-flex; align-items: center; gap: 0.3rem;">
                                            <i class="fa-solid fa-pen-ruler"></i> Bản nháp
                                        </span>
                                    @endif
                                </button>
                            </form>
                        </td>

                        <!-- Cột bình luận -->
                        <td style="padding: 0.9rem 0.5rem; text-align: center; color: var(--text-muted); font-weight: 700;">
                            {{ $post->comments_count }}
                        </td>

                        <!-- Cột lượt đọc -->
                        <td style="padding: 0.9rem 0.5rem; text-align: center; color: var(--text-muted); font-weight: 600;">
                            {{ number_format($post->views_count) }}
                        </td>

                        <!-- Cột thao tác (Xem, Sửa, Xóa) -->
                        <td style="padding: 0.9rem 0.5rem; text-align: right; white-space: nowrap;">
                            <div style="display: inline-flex; align-items: center; gap: 0.5rem;">
                                <!-- Nút Xem -->
                                <a href="{{ route('post.detail', $post->id) }}" target="_blank" style="color: var(--text-muted); font-size: 0.82rem; text-decoration: none; padding: 0.25rem 0.45rem; border-radius: 4px; font-weight: 600;" title="Xem bài viết">
                                    <i class="fa-regular fa-eye"></i> Xem
                                </a>

                                <!-- Nút Sửa: Mở Popup Modal Chỉnh sửa bài viết -->
                                <button type="button" class="btn-edit-post-trigger" onclick="openEditPostModal(this)"
                                    data-id="{{ $post->id }}"
                                    data-title="{{ $post->title }}"
                                    data-category-id="{{ $post->category_id }}"
                                    data-excerpt="{{ $post->excerpt }}"
                                    data-content="{{ $post->content }}"
                                    data-cover="{{ $post->cover_image }}"
                                    data-images="{{ json_encode($post->all_images) }}"
                                    data-reading-time="{{ $post->reading_time }}"
                                    data-published="{{ $post->is_published ? '1' : '0' }}"
                                    data-featured="{{ $post->is_featured ? '1' : '0' }}"
                                    data-link="{{ $post->external_link }}"
                                    data-link-title="{{ $post->external_link_title }}"
                                    data-update-url="{{ route('admin.posts.update', $post->id) }}"
                                    style="background: none; border: none; color: var(--primary-blue); font-weight: 700; font-size: 0.82rem; cursor: pointer; padding: 0.25rem 0.45rem;">
                                    <i class="fa-solid fa-pen"></i> Sửa
                                </button>

                                <!-- Nút Xóa: Mở Popup Modal Xác nhận Xóa bài viết -->
                                <button type="button" class="btn-delete-post-trigger" onclick="openDeletePostModal(this)"
                                    data-id="{{ $post->id }}"
                                    data-title="{{ $post->title }}"
                                    data-category="{{ $post->category ?? 'Chưa phân loại' }}"
                                    data-views="{{ $post->views_count ?? 0 }}"
                                    data-comments-count="{{ $post->comments_count ?? 0 }}"
                                    data-delete-url="{{ route('admin.posts.destroy', $post->id) }}"
                                    style="background: none; border: none; color: #ef4444; font-weight: 600; font-size: 0.82rem; cursor: pointer; padding: 0.25rem 0.45rem;"
                                    title="Xóa bài viết">
                                    <i class="fa-solid fa-trash-can"></i> Xóa
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 3rem 1rem; color: var(--text-muted);">
                            <div style="font-size: 2.5rem; color: var(--text-light-muted); margin-bottom: 0.5rem;">
                                <i class="fa-regular fa-newspaper"></i>
                            </div>
                            <div style="font-size: 1rem; font-weight: 700; color: var(--text-title); margin-bottom: 0.25rem;">Không tìm thấy bài viết nào</div>
                            <p style="font-size: 0.82rem; margin-bottom: 1rem;">Hãy nhấn nút bên dưới để tạo bài viết đầu tiên cho diễn đàn doanh nghiệp.</p>
                            <button type="button" class="btn-publish-trigger" data-bs-toggle="modal" data-bs-target="#createPostModal" style="background-color: var(--executive-blue); font-size: 0.85rem; padding: 0.5rem 1.25rem; display: inline-flex; align-items: center; gap: 0.4rem; border: none; color: #fff; border-radius: 6px; cursor: pointer;">
                                <i class="fa-solid fa-plus"></i> Tạo bài viết ngay
                            </button>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- PHÂN TRANG CHUẨN MEDIAN UI (TỐI ĐA 10 BÀI / TRANG) -->
        <div style="display: flex; align-items: center; justify-content: space-between; padding-top: 1.25rem; border-top: 1px solid #f1f5f9; font-size: 0.82rem; color: var(--text-muted); margin-top: 0.75rem; flex-wrap: wrap; gap: 0.75rem;">
            <div style="display: flex; align-items: center; gap: 0.65rem;">
                <span>Hiển thị <strong>{{ $posts->firstItem() ?? 0 }} - {{ $posts->lastItem() ?? 0 }}</strong> trên tổng số <strong>{{ $posts->total() }}</strong> bài viết</span>
                <span style="background: var(--bg-subtle, #f1f5f9); border: 1px solid #e2e8f0; color: var(--text-muted); padding: 0.15rem 0.55rem; border-radius: 4px; font-weight: 700; font-size: 0.72rem;">
                    <i class="fa-solid fa-layer-group"></i> 10 bài / trang
                </span>
            </div>

            @if($posts->hasPages())
            <nav style="display: flex; align-items: center; gap: 0.35rem;">
                {{-- Nút Về trang trước --}}
                @if($posts->onFirstPage())
                    <span style="display: inline-flex; align-items: center; justify-content: center; height: 34px; padding: 0 0.75rem; font-size: 0.8rem; font-weight: 700; color: #94a3b8; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; cursor: not-allowed; opacity: 0.6;">
                        <i class="fa-solid fa-chevron-left" style="font-size: 0.7rem; margin-right: 4px;"></i> Trước
                    </span>
                @else
                    <a href="{{ request()->fullUrlWithQuery(['page' => $posts->currentPage() - 1]) }}" style="display: inline-flex; align-items: center; justify-content: center; height: 34px; padding: 0 0.75rem; font-size: 0.8rem; font-weight: 700; color: var(--text-title); background: #ffffff; border: 1px solid #cbd5e1; border-radius: 6px; text-decoration: none; transition: all 0.2s ease;">
                        <i class="fa-solid fa-chevron-left" style="font-size: 0.7rem; margin-right: 4px;"></i> Trước
                    </a>
                @endif

                {{-- Các số trang (1, 2, 3...) --}}
                @for($p = 1; $p <= $posts->lastPage(); $p++)
                    @if($p == $posts->currentPage())
                        <span style="display: inline-flex; align-items: center; justify-content: center; min-width: 34px; height: 34px; padding: 0 0.65rem; font-size: 0.82rem; font-weight: 800; color: #ffffff; background: var(--executive-blue, #2563eb); border: 1px solid var(--executive-blue, #2563eb); border-radius: 6px; box-shadow: 0 2px 4px rgba(37, 99, 235, 0.25);">
                            {{ $p }}
                        </span>
                    @else
                        <a href="{{ request()->fullUrlWithQuery(['page' => $p]) }}" style="display: inline-flex; align-items: center; justify-content: center; min-width: 34px; height: 34px; padding: 0 0.65rem; font-size: 0.82rem; font-weight: 700; color: var(--text-title); background: #ffffff; border: 1px solid #cbd5e1; border-radius: 6px; text-decoration: none; transition: all 0.2s ease;">
                            {{ $p }}
                        </a>
                    @endif
                @endfor

                {{-- Nút Sang trang sau --}}
                @if($posts->hasMorePages())
                    <a href="{{ request()->fullUrlWithQuery(['page' => $posts->currentPage() + 1]) }}" style="display: inline-flex; align-items: center; justify-content: center; height: 34px; padding: 0 0.75rem; font-size: 0.8rem; font-weight: 700; color: var(--text-title); background: #ffffff; border: 1px solid #cbd5e1; border-radius: 6px; text-decoration: none; transition: all 0.2s ease;">
                        Sau <i class="fa-solid fa-chevron-right" style="font-size: 0.7rem; margin-left: 4px;"></i>
                    </a>
                @else
                    <span style="display: inline-flex; align-items: center; justify-content: center; height: 34px; padding: 0 0.75rem; font-size: 0.8rem; font-weight: 700; color: #94a3b8; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; cursor: not-allowed; opacity: 0.6;">
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
<!-- ======================================================================
     MODAL 1: TẠO BÀI VIẾT MỚI (CREATE POST MODAL)
     ====================================================================== -->
<div class="modal fade" id="createPostModal" tabindex="-1" aria-labelledby="createPostModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content" style="border-radius: 12px; border: 1px solid var(--border-card, #e2e8f0); box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1); background-color: var(--bg-card, #ffffff); color: var(--text-title);">
      
      <div class="modal-header" style="border-bottom: 1px solid #f1f5f9; padding: 1.25rem 1.5rem; display: flex; align-items: center; justify-content: space-between;">
        <div style="display: flex; align-items: center; gap: 0.65rem;">
          <div style="width: 38px; height: 38px; border-radius: 8px; background: rgba(37, 99, 235, 0.1); color: var(--executive-blue, #2563eb); display: flex; align-items: center; justify-content: center; font-size: 1.1rem;">
            <i class="fa-solid fa-file-pen"></i>
          </div>
          <div>
            <h5 class="modal-title" id="createPostModalLabel" style="font-size: 1.1rem; font-weight: 800; color: var(--text-title, #0f172a); margin: 0;">
              Tạo bài viết mới
            </h5>
            <p style="font-size: 0.75rem; color: var(--text-muted, #64748b); margin: 0;">
              Chia sẻ góc nhìn phân tích, chiến lược và kiến thức quản trị kinh doanh
            </p>
          </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng" style="font-size: 0.8rem;"></button>
      </div>

      <form action="{{ route('admin.posts.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-body" style="padding: 1.5rem; max-height: 75vh; overflow-y: auto;">
          
          <!-- Tiêu đề bài viết -->
          <div style="margin-bottom: 1.25rem;">
            <label style="display: block; font-size: 0.82rem; font-weight: 700; color: var(--text-title); margin-bottom: 0.45rem;">
              Tiêu đề bài viết <span style="color: #ef4444;">*</span>
            </label>
            <input type="text" name="title" required placeholder="Ví dụ: Chiến lược thích ứng của doanh nghiệp trước chu kỳ công nghệ 2026..." style="width: 100%; padding: 0.65rem 0.85rem; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.88rem; outline: none;">
          </div>

          <!-- Chuyên mục & Thời gian đọc -->
          <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1rem; margin-bottom: 1.25rem;">
            <div>
              <label style="display: block; font-size: 0.82rem; font-weight: 700; color: var(--text-title); margin-bottom: 0.45rem;">
                Chuyên mục bài viết
              </label>
              <select name="category_id" style="width: 100%; padding: 0.65rem 0.85rem; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.88rem; outline: none; background: #ffffff;">
                <option value="">-- Chọn chuyên mục --</option>
                @foreach($categories as $cat)
                  <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
              </select>
            </div>
            <div>
              <label style="display: block; font-size: 0.82rem; font-weight: 700; color: var(--text-title); margin-bottom: 0.45rem;">
                Thời gian đọc (phút)
              </label>
              <input type="number" name="reading_time" value="5" min="1" max="60" style="width: 100%; padding: 0.65rem 0.85rem; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.88rem; outline: none;">
            </div>
          </div>

          <!-- Tải ảnh bài viết từ máy tính (Tối đa 5 ảnh) -->
          <div style="margin-bottom: 1.25rem;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.45rem;">
              <label style="font-size: 0.82rem; font-weight: 700; color: var(--text-title); margin: 0;">
                Tải ảnh bài viết từ máy tính <span style="font-size: 0.75rem; font-weight: 400; color: var(--text-muted);">(Tối đa 5 ảnh, định dạng JPG, PNG, WEBP)</span>
              </label>
              <span id="createCoverCountBadge" style="background: #eff6ff; color: #2563eb; padding: 0.15rem 0.55rem; border-radius: 9999px; font-size: 0.72rem; font-weight: 800;">
                0 / 5 ảnh
              </span>
            </div>
            <div style="border: 2px dashed #cbd5e1; border-radius: 8px; padding: 1.25rem 1rem; text-align: center; background: #f8fafc; cursor: pointer; transition: all 0.2s ease;" onclick="document.getElementById('createCoverFileInput').click();" ondragover="event.preventDefault(); this.style.borderColor='var(--executive-blue)';" ondragleave="this.style.borderColor='#cbd5e1';" ondrop="handleMultiDrop(event, 'createCoverFileInput', 'createCoverPreviewBox', 'createCoverDropContent', 'createImagesGrid', 'createCoverCountBadge');">
              <input type="file" id="createCoverFileInput" name="images[]" multiple accept="image/*" style="display: none;" onchange="handleMultiSelect(this, 'createCoverPreviewBox', 'createCoverDropContent', 'createImagesGrid', 'createCoverCountBadge');">
              
              <div id="createCoverDropContent">
                <div style="font-size: 2rem; color: var(--executive-blue); margin-bottom: 0.35rem;">
                  <i class="fa-solid fa-images"></i>
                </div>
                <div style="font-size: 0.88rem; font-weight: 700; color: var(--text-title);">
                  Nhấn vào đây để chọn tối đa 5 ảnh từ máy tính
                </div>
                <div style="font-size: 0.74rem; color: var(--text-muted); margin-top: 0.25rem;">
                  Có thể giữ phím Ctrl/Shift để chọn cùng lúc nhiều ảnh (Ảnh đầu tiên sẽ làm ảnh bìa chính)
                </div>
              </div>

              <div id="createCoverPreviewBox" style="display: none; text-align: left;">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.65rem;">
                  <span style="font-size: 0.78rem; font-weight: 700; color: var(--text-title);">Ảnh đã chọn để đăng:</span>
                  <span style="font-size: 0.72rem; color: #f59e0b; font-weight: 700;"><i class="fa-solid fa-star"></i> Ảnh số 1 là ảnh bìa chính</span>
                </div>
                <div id="createImagesGrid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(110px, 1fr)); gap: 0.65rem;"></div>
                <div style="margin-top: 0.75rem; text-align: center;">
                  <span style="display: inline-flex; align-items: center; gap: 0.35rem; font-size: 0.78rem; font-weight: 700; color: var(--executive-blue);">
                    <i class="fa-solid fa-arrows-rotate"></i> Bấm để chọn lại danh sách ảnh khác
                  </span>
                </div>
              </div>
            </div>
          </div>

          <!-- Tóm tắt bài viết -->
          <div style="margin-bottom: 1.25rem;">
            <label style="display: block; font-size: 0.82rem; font-weight: 700; color: var(--text-title); margin-bottom: 0.45rem;">
              Tóm tắt nội dung (Excerpt) <span style="color: #ef4444;">*</span>
            </label>
            <textarea name="excerpt" rows="2" required placeholder="Tóm tắt ngắn gọn những luận điểm quan trọng nhất của bài viết..." style="width: 100%; padding: 0.65rem 0.85rem; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.86rem; outline: none;"></textarea>
          </div>

          <!-- Nội dung chi tiết -->
          <div style="margin-bottom: 1.25rem;">
            <label style="display: block; font-size: 0.82rem; font-weight: 700; color: var(--text-title); margin-bottom: 0.45rem;">
              Nội dung chi tiết bài viết
            </label>
            <textarea name="content" rows="6" placeholder="Nội dung bài viết, phân tích số liệu, góc nhìn quản trị..." style="width: 100%; padding: 0.65rem 0.85rem; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.86rem; outline: none;"></textarea>
          </div>

          <!-- Gắn liên kết ngoài (External Link) -->
          <div style="background: #f8fafc; border: 1px dashed #cbd5e1; border-radius: 8px; padding: 1rem; margin-bottom: 1.25rem;">
            <div style="font-size: 0.84rem; font-weight: 700; color: var(--text-title); margin-bottom: 0.65rem; display: flex; align-items: center; gap: 0.45rem;">
              <i class="fa-solid fa-link" style="color: var(--executive-blue);"></i> Gắn liên kết đính kèm (Báo chí, tài liệu, website tham khảo)
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.85rem;">
              <div>
                <label style="display: block; font-size: 0.78rem; font-weight: 700; color: var(--text-title); margin-bottom: 0.35rem;">
                  Tên hiển thị liên kết
                </label>
                <input type="text" name="external_link_title" placeholder="Ví dụ: Bài báo về tôi, Báo Đầu Tư..." style="width: 100%; padding: 0.6rem 0.8rem; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.84rem; outline: none; background: #ffffff;">
              </div>
              <div>
                <label style="display: block; font-size: 0.78rem; font-weight: 700; color: var(--text-title); margin-bottom: 0.35rem;">
                  Đường dẫn liên kết (URL)
                </label>
                <input type="text" name="external_link" placeholder="Ví dụ: abc-link.com hoặc https://..." style="width: 100%; padding: 0.6rem 0.8rem; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.84rem; outline: none; background: #ffffff;">
              </div>
            </div>
            <div style="font-size: 0.72rem; color: var(--text-muted); margin-top: 0.45rem;">
              Khi nhập liên kết, bài viết ngoài trang chủ sẽ hiển thị một khối xem trước liên kết (Link Preview Card) phong cách doanh nghiệp.
            </div>
          </div>

          <!-- Thiết lập trạng thái & Tiêu điểm -->
          <div style="display: flex; gap: 1.5rem; align-items: center; padding: 0.85rem 1rem; background: #f8fafc; border-radius: 8px;">
            <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.85rem; font-weight: 700; cursor: pointer; color: var(--text-title);">
              <input type="checkbox" name="is_published" value="1" checked style="width: 16px; height: 16px; cursor: pointer;">
              Xuất bản công khai ngay
            </label>
            <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.85rem; font-weight: 700; cursor: pointer; color: var(--text-title);">
              <input type="checkbox" name="is_featured" value="1" style="width: 16px; height: 16px; cursor: pointer;">
              Đánh dấu bài viết TIÊU ĐIỂM
            </label>
          </div>

        </div>

        <div class="modal-footer" style="border-top: 1px solid #f1f5f9; padding: 1rem 1.5rem; display: flex; justify-content: flex-end; gap: 0.75rem;">
          <button type="button" class="btn btn-light" data-bs-dismiss="modal" style="font-size: 0.85rem; font-weight: 600; padding: 0.55rem 1.15rem; border-radius: 6px; color: #64748b;">
            Hủy bỏ
          </button>
          <button type="submit" class="btn btn-primary" style="background-color: var(--executive-blue); border: none; font-size: 0.85rem; font-weight: 700; padding: 0.55rem 1.35rem; border-radius: 6px; display: flex; align-items: center; gap: 0.45rem;">
            <i class="fa-solid fa-paper-plane"></i> Lưu & Xuất bản bài viết
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- ======================================================================
     MODAL 2: CHỈNH SỬA BÀI VIẾT (EDIT POST MODAL)
     ====================================================================== -->
<div class="modal fade" id="editPostModal" tabindex="-1" aria-labelledby="editPostModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content" style="border-radius: 12px; border: 1px solid var(--border-card, #e2e8f0); box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1); background-color: var(--bg-card, #ffffff); color: var(--text-title);">
      
      <div class="modal-header" style="border-bottom: 1px solid #f1f5f9; padding: 1.25rem 1.5rem; display: flex; align-items: center; justify-content: space-between;">
        <div style="display: flex; align-items: center; gap: 0.65rem;">
          <div style="width: 38px; height: 38px; border-radius: 8px; background: rgba(37, 99, 235, 0.1); color: var(--executive-blue, #2563eb); display: flex; align-items: center; justify-content: center; font-size: 1.1rem;">
            <i class="fa-solid fa-pen-to-square"></i>
          </div>
          <div>
            <h5 class="modal-title" id="editPostModalLabel" style="font-size: 1.1rem; font-weight: 800; color: var(--text-title, #0f172a); margin: 0;">
              Chỉnh sửa bài viết
            </h5>
            <p style="font-size: 0.75rem; color: var(--text-muted, #64748b); margin: 0;">
              Cập nhật nội dung, tiêu đề và chuyên mục bài viết
            </p>
          </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng" style="font-size: 0.8rem;"></button>
      </div>

      <form id="editPostForm" action="" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="modal-body" style="padding: 1.5rem; max-height: 75vh; overflow-y: auto;">
          
          <!-- Tiêu đề bài viết -->
          <div style="margin-bottom: 1.25rem;">
            <label style="display: block; font-size: 0.82rem; font-weight: 700; color: var(--text-title); margin-bottom: 0.45rem;">
              Tiêu đề bài viết <span style="color: #ef4444;">*</span>
            </label>
            <input type="text" id="editPostTitle" name="title" required placeholder="Nhập tiêu đề..." style="width: 100%; padding: 0.65rem 0.85rem; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.88rem; outline: none;">
          </div>

          <!-- Chuyên mục & Thời gian đọc -->
          <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1rem; margin-bottom: 1.25rem;">
            <div>
              <label style="display: block; font-size: 0.82rem; font-weight: 700; color: var(--text-title); margin-bottom: 0.45rem;">
                Chuyên mục bài viết
              </label>
              <select id="editPostCategory" name="category_id" style="width: 100%; padding: 0.65rem 0.85rem; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.88rem; outline: none; background: #ffffff;">
                <option value="">-- Chọn chuyên mục --</option>
                @foreach($categories as $cat)
                  <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
              </select>
            </div>
            <div>
              <label style="display: block; font-size: 0.82rem; font-weight: 700; color: var(--text-title); margin-bottom: 0.45rem;">
                Thời gian đọc (phút)
              </label>
              <input type="number" id="editPostReadingTime" name="reading_time" min="1" max="60" style="width: 100%; padding: 0.65rem 0.85rem; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.88rem; outline: none;">
            </div>
          </div>

          <!-- Tải ảnh bài viết từ máy tính (Chỉnh sửa tối đa 5 ảnh) -->
          <div style="margin-bottom: 1.25rem;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.45rem;">
              <label style="font-size: 0.82rem; font-weight: 700; color: var(--text-title); margin: 0;">
                Ảnh bài viết <span style="font-size: 0.75rem; font-weight: 400; color: var(--text-muted);">(Tối đa 5 ảnh - Tải bộ ảnh mới từ máy tính nếu muốn thay đổi)</span>
              </label>
              <span id="editCoverCountBadge" style="background: #eff6ff; color: #2563eb; padding: 0.15rem 0.55rem; border-radius: 9999px; font-size: 0.72rem; font-weight: 800;">
                0 / 5 ảnh
              </span>
            </div>
            <div style="border: 2px dashed #cbd5e1; border-radius: 8px; padding: 1.25rem 1rem; text-align: center; background: #f8fafc; cursor: pointer; transition: all 0.2s ease;" onclick="document.getElementById('editCoverFileInput').click();" ondragover="event.preventDefault(); this.style.borderColor='var(--executive-blue)';" ondragleave="this.style.borderColor='#cbd5e1';" ondrop="handleMultiDrop(event, 'editCoverFileInput', 'editCoverPreviewBox', 'editCoverDropContent', 'editImagesGrid', 'editCoverCountBadge');">
              <input type="file" id="editCoverFileInput" name="images[]" multiple accept="image/*" style="display: none;" onchange="handleMultiSelect(this, 'editCoverPreviewBox', 'editCoverDropContent', 'editImagesGrid', 'editCoverCountBadge');">
              
              <div id="editCoverPreviewBox" style="text-align: left;">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.65rem;">
                  <span style="font-size: 0.78rem; font-weight: 700; color: var(--text-title);">Ảnh hiện tại của bài viết:</span>
                  <span style="font-size: 0.72rem; color: #f59e0b; font-weight: 700;"><i class="fa-solid fa-star"></i> Ảnh số 1 là ảnh bìa</span>
                </div>
                <div id="editImagesGrid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(110px, 1fr)); gap: 0.65rem;"></div>
                <div style="margin-top: 0.75rem; text-align: center;">
                  <span style="display: inline-flex; align-items: center; gap: 0.35rem; font-size: 0.78rem; font-weight: 700; color: var(--executive-blue);">
                    <i class="fa-solid fa-cloud-arrow-up"></i> Bấm để tải bộ ảnh mới từ máy tính thay thế (Tối đa 5 ảnh)
                  </span>
                </div>
              </div>

              <div id="editCoverDropContent" style="display: none;">
                <div style="font-size: 2rem; color: var(--executive-blue); margin-bottom: 0.35rem;">
                  <i class="fa-solid fa-images"></i>
                </div>
                <div style="font-size: 0.88rem; font-weight: 700; color: var(--text-title);">
                  Nhấn vào đây để chọn tối đa 5 ảnh từ máy tính
                </div>
                <div style="font-size: 0.74rem; color: var(--text-muted); margin-top: 0.25rem;">
                  Hỗ trợ JPG, PNG, WEBP, GIF (Tối đa 10MB/ảnh)
                </div>
              </div>
            </div>
          </div>

          <!-- Tóm tắt bài viết -->
          <div style="margin-bottom: 1.25rem;">
            <label style="display: block; font-size: 0.82rem; font-weight: 700; color: var(--text-title); margin-bottom: 0.45rem;">
              Tóm tắt nội dung (Excerpt) <span style="color: #ef4444;">*</span>
            </label>
            <textarea id="editPostExcerpt" name="excerpt" rows="2" required placeholder="Tóm tắt ngắn gọn..." style="width: 100%; padding: 0.65rem 0.85rem; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.86rem; outline: none;"></textarea>
          </div>

          <!-- Nội dung chi tiết -->
          <div style="margin-bottom: 1.25rem;">
            <label style="display: block; font-size: 0.82rem; font-weight: 700; color: var(--text-title); margin-bottom: 0.45rem;">
              Nội dung chi tiết bài viết
            </label>
            <textarea id="editPostContent" name="content" rows="6" placeholder="Nội dung bài viết..." style="width: 100%; padding: 0.65rem 0.85rem; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.86rem; outline: none;"></textarea>
          </div>

          <!-- Gắn liên kết ngoài (External Link) -->
          <div style="background: #f8fafc; border: 1px dashed #cbd5e1; border-radius: 8px; padding: 1rem; margin-bottom: 1.25rem;">
            <div style="font-size: 0.84rem; font-weight: 700; color: var(--text-title); margin-bottom: 0.65rem; display: flex; align-items: center; gap: 0.45rem;">
              <i class="fa-solid fa-link" style="color: var(--executive-blue);"></i> Gắn liên kết đính kèm (Báo chí, tài liệu, website tham khảo)
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.85rem;">
              <div>
                <label style="display: block; font-size: 0.78rem; font-weight: 700; color: var(--text-title); margin-bottom: 0.35rem;">
                  Tên hiển thị liên kết
                </label>
                <input type="text" id="editPostLinkTitle" name="external_link_title" placeholder="Ví dụ: Bài báo về tôi, Báo Đầu Tư..." style="width: 100%; padding: 0.6rem 0.8rem; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.84rem; outline: none; background: #ffffff;">
              </div>
              <div>
                <label style="display: block; font-size: 0.78rem; font-weight: 700; color: var(--text-title); margin-bottom: 0.35rem;">
                  Đường dẫn liên kết (URL)
                </label>
                <input type="text" id="editPostLink" name="external_link" placeholder="Ví dụ: abc-link.com hoặc https://..." style="width: 100%; padding: 0.6rem 0.8rem; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.84rem; outline: none; background: #ffffff;">
              </div>
            </div>
            <div style="font-size: 0.72rem; color: var(--text-muted); margin-top: 0.45rem;">
              Khi nhập liên kết, bài viết ngoài trang chủ sẽ hiển thị một khối xem trước liên kết (Link Preview Card) phong cách doanh nghiệp.
            </div>
          </div>

          <!-- Thiết lập trạng thái & Tiêu điểm -->
          <div style="display: flex; gap: 1.5rem; align-items: center; padding: 0.85rem 1rem; background: #f8fafc; border-radius: 8px;">
            <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.85rem; font-weight: 700; cursor: pointer; color: var(--text-title);">
              <input type="checkbox" id="editPostPublished" name="is_published" value="1" style="width: 16px; height: 16px; cursor: pointer;">
              Xuất bản công khai
            </label>
            <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.85rem; font-weight: 700; cursor: pointer; color: var(--text-title);">
              <input type="checkbox" id="editPostFeatured" name="is_featured" value="1" style="width: 16px; height: 16px; cursor: pointer;">
              Đánh dấu bài viết TIÊU ĐIỂM
            </label>
          </div>

        </div>

        <div class="modal-footer" style="border-top: 1px solid #f1f5f9; padding: 1rem 1.5rem; display: flex; justify-content: flex-end; gap: 0.75rem;">
          <button type="button" class="btn btn-light" data-bs-dismiss="modal" style="font-size: 0.85rem; font-weight: 600; padding: 0.55rem 1.15rem; border-radius: 6px; color: #64748b;">
            Hủy bỏ
          </button>
          <button type="submit" class="btn btn-primary" style="background-color: var(--executive-blue); border: none; font-size: 0.85rem; font-weight: 700; padding: 0.55rem 1.35rem; border-radius: 6px; display: flex; align-items: center; gap: 0.45rem;">
            <i class="fa-solid fa-check"></i> Cập nhật thay đổi
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- ======================================================================
     MODAL POPUP: XÁC NHẬN XÓA BÀI VIẾT (GIAO DIỆN CHUYÊN NGHIỆP)
     ====================================================================== -->
<div class="modal fade" id="deletePostModal" tabindex="-1" aria-labelledby="deletePostModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="max-width: 480px; width: 92%;">
    <div class="modal-content" style="border-radius: 14px; border: 1px solid var(--border-card, #e2e8f0); box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); background-color: var(--bg-card, #ffffff); overflow: hidden;">
      
      <!-- Modal Header -->
      <div class="modal-header" style="border-bottom: 1px solid var(--border-card, #f1f5f9); padding: 1.25rem 1.5rem; display: flex; align-items: center; justify-content: space-between;">
        <div style="display: flex; align-items: center; gap: 0.75rem;">
          <div style="width: 42px; height: 42px; border-radius: 10px; background: rgba(239, 68, 68, 0.12); color: #ef4444; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; flex-shrink: 0; border: 1px solid rgba(239, 68, 68, 0.2);">
            <i class="fa-solid fa-triangle-exclamation"></i>
          </div>
          <div>
            <h5 class="modal-title" id="deletePostModalLabel" style="font-size: 1.05rem; font-weight: 800; color: var(--text-title, #0f172a); margin: 0;">
              Xác nhận xóa bài viết
            </h5>
            <p style="font-size: 0.75rem; color: var(--text-muted, #64748b); margin: 0;">
              Thao tác này sẽ gỡ bài viết vĩnh viễn khỏi diễn đàn
            </p>
          </div>
        </div>
        <button type="button" class="btn-close-modal" data-bs-dismiss="modal" onclick="closeModal('deletePostModal')" aria-label="Đóng" style="background: none; border: none; color: var(--text-muted); font-size: 1.15rem; cursor: pointer; padding: 0.35rem; border-radius: 6px; display: flex; align-items: center; justify-content: center; line-height: 1;">
          <i class="fa-solid fa-xmark"></i>
        </button>
      </div>

      <!-- Form Xác nhận Xóa -->
      <form id="deletePostForm" action="" method="POST">
        @csrf
        @method('DELETE')
        <div class="modal-body" style="padding: 1.5rem;">
          <p style="font-size: 0.92rem; color: var(--text-body, #1e293b); line-height: 1.6; margin: 0 0 0.85rem 0;">
            Bạn có chắc chắn muốn xóa bài viết sau?
          </p>

          <div style="background: var(--bg-subtle, #f8fafc); border: 1px solid var(--border-card, #e2e8f0); border-radius: 8px; padding: 0.85rem 1rem; margin-bottom: 1rem;">
            <div id="deletePostTitleTarget" style="font-size: 0.92rem; font-weight: 800; color: #ef4444; line-height: 1.5; margin-bottom: 0.45rem;"></div>
            <div id="deletePostMetaTarget" style="display: flex; gap: 0.85rem; font-size: 0.76rem; color: var(--text-muted); flex-wrap: wrap;"></div>
          </div>

          <div style="background: rgba(239, 68, 68, 0.05); border: 1px dashed rgba(239, 68, 68, 0.25); border-radius: 8px; padding: 0.75rem 1rem; display: flex; align-items: flex-start; gap: 0.65rem;">
            <i class="fa-solid fa-circle-exclamation" style="color: #ef4444; font-size: 0.95rem; margin-top: 0.15rem; flex-shrink: 0;"></i>
            <span style="font-size: 0.78rem; color: var(--text-muted, #64748b); line-height: 1.45;">
              Bài viết cùng toàn bộ bình luận liên quan sẽ bị xóa khỏi hệ thống và <strong>không thể hoàn tác</strong>.
            </span>
          </div>
        </div>

        <!-- Modal Footer -->
        <div class="modal-footer" style="border-top: 1px solid var(--border-card, #f1f5f9); padding: 1rem 1.5rem; display: flex; justify-content: flex-end; gap: 0.75rem; background: var(--bg-subtle, #f8fafc);">
          <button type="button" class="btn" onclick="closeModal('deletePostModal')" data-bs-dismiss="modal" style="font-size: 0.85rem; font-weight: 600; padding: 0.55rem 1.15rem; border-radius: 6px; color: var(--text-muted, #64748b); background: var(--bg-card, #ffffff); border: 1px solid var(--border-card, #cbd5e1); cursor: pointer; transition: all 0.2s;">
            Hủy bỏ
          </button>
          <button type="submit" class="btn" style="background-color: #ef4444; border: none; color: #ffffff; font-size: 0.85rem; font-weight: 700; padding: 0.55rem 1.35rem; border-radius: 6px; display: inline-flex; align-items: center; gap: 0.45rem; cursor: pointer; box-shadow: 0 4px 14px rgba(239, 68, 68, 0.3); transition: background-color 0.2s;">
            <i class="fa-solid fa-trash-can"></i> Xác nhận xóa
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/admin.js') }}"></script>
@endpush
