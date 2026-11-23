@extends('layouts.app')

@section('title', 'Quản lý Đề xuất Hợp tác - KẾT NỐI Admin')
@section('breadcrumb', 'WORKSPACE / QUẢN TRỊ KẾT NỐI / ĐỀ XUẤT HỢP TÁC')

@section('content')
<main class="main-content-scroll">
  <!-- TIÊU ĐỀ TRANG -->
  <div class="content-header-row" style="margin-bottom: 1.5rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
    <div>
      <h1 class="forum-main-title" style="font-size: 1.55rem; display: flex; align-items: center; gap: 0.65rem;">
        <i class="fa-solid fa-handshake" style="color: #f59e0b;"></i>
        <span>Quản lý Đề xuất & Thư ngỏ Hợp tác</span>
      </h1>
      <p class="forum-subtitle">Tiếp nhận hồ sơ năng lực, thư ngỏ doanh nghiệp, lời mời diễn giả và cơ hội kết nối chiến lược.</p>
    </div>
    <div style="display: flex; align-items: center; gap: 0.75rem;">
      <a href="{{ route('contact') }}" target="_blank" class="btn-median-read" style="background: var(--bg-card); color: var(--text-title); border: 1px solid var(--border-card); font-size: 0.84rem; padding: 0.55rem 1rem;">
        <i class="fa-solid fa-arrow-up-right-from-square"></i> Xem Form liên hệ ngoài Web
      </a>
    </div>
  </div>

  @if(session('success'))
    <div style="background-color: #dcfce7; border: 1px solid #86efac; color: #166534; padding: 0.85rem 1.25rem; border-radius: 8px; margin-bottom: 1.25rem; font-size: 0.88rem; font-weight: 600; display: flex; align-items: center; gap: 0.5rem;">
      <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
    </div>
  @endif

  <!-- 3 THẺ THỐNG KÊ (ĐỒNG BỘ 100% DATABASE) -->
  <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.25rem; margin-bottom: 1.75rem;">
    <!-- 1. Tổng đề xuất -->
    <div class="widget-card" style="padding: 1.25rem;">
      <div style="font-size: 0.75rem; color: var(--text-muted); font-weight: 700; text-transform: uppercase;">TỔNG THƯ NGỎ</div>
      <div style="font-size: 1.85rem; font-weight: 800; color: var(--text-title); margin: 0.35rem 0;">
        {{ number_format($totalProposals ?? 0) }}
      </div>
      <div style="font-size: 0.75rem; color: var(--text-muted); font-weight: 600;">Đã tiếp nhận toàn thời gian</div>
    </div>

    <!-- 2. Có tài liệu đính kèm -->
    <div class="widget-card" style="padding: 1.25rem; border-left: 3px solid #16a34a;">
      <div style="font-size: 0.75rem; color: #16a34a; font-weight: 700; text-transform: uppercase;">CÓ TÀI LIỆU ĐÍNH KÈM</div>
      <div style="font-size: 1.85rem; font-weight: 800; color: #16a34a; margin: 0.35rem 0;">
        {{ number_format($attachmentProposals ?? 0) }}
      </div>
      <div style="font-size: 0.75rem; color: #15803d; font-weight: 600;">Hồ sơ năng lực & tài liệu gửi kèm</div>
    </div>

    <!-- 3. Ưu tiên gắn sao -->
    <div class="widget-card" style="padding: 1.25rem; border-left: 3px solid #f59e0b;">
      <div style="font-size: 0.75rem; color: #f59e0b; font-weight: 700; text-transform: uppercase;">ƯU TIÊN GẮN SAO</div>
      <div style="font-size: 1.85rem; font-weight: 800; color: #f59e0b; margin: 0.35rem 0;">
        {{ number_format($starredProposals ?? 0) }}
      </div>
      <div style="font-size: 0.75rem; color: #b45309; font-weight: 600;">Thư ngỏ quan trọng cần ưu tiên</div>
    </div>
  </div>

  <!-- BẢNG DANH SÁCH ĐỀ XUẤT HỢP TÁC -->
  <div class="widget-card" style="padding: 1.5rem; margin-bottom: 1.5rem;">
    <!-- TIÊU ĐỀ KHỐI + TỔNG SỐ -->
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.25rem; flex-wrap: wrap; gap: 0.5rem;">
      <div>
        <h3 style="font-size: 1.05rem; font-weight: 800; color: var(--text-title);">Danh sách hồ sơ & Thư ngỏ hợp tác</h3>
        <p style="font-size: 0.78rem; color: var(--text-muted); margin-top: 0.15rem;">Thông tin được gửi từ form đối tác tại trang Liên hệ</p>
      </div>
      <span style="font-size: 0.8rem; color: var(--text-muted); font-weight: 700;">
        Hiển thị: {{ $proposals->total() }} hồ sơ đề xuất
      </span>
    </div>

    <!-- BỘ LỌC TÌM KIẾM & PHÂN LOẠI -->
    <form method="GET" action="{{ route('admin.proposals.index') }}" id="filterProposalsForm" style="display: flex; flex-direction: column; gap: 0.85rem; margin-bottom: 1.25rem;">
      <!-- Hàng 1: Tìm kiếm từ khóa + Lọc Mục đích -->
      <div style="display: grid; grid-template-columns: 1fr 280px auto; gap: 0.75rem; align-items: center;">
        <div style="position: relative; width: 100%;">
          <input type="text" name="keyword" value="{{ request('keyword') }}" placeholder="Tìm theo tên người gửi, email, số điện thoại, công ty, tiêu đề thư ngỏ..." style="width: 100%; padding: 0.55rem 0.85rem 0.5rem 2.2rem; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.86rem; outline: none; background: #ffffff;">
          <i class="fa-solid fa-magnifying-glass" style="position: absolute; left: 0.75rem; top: 50%; transform: translateY(-50%); color: var(--text-light-muted); font-size: 0.85rem;"></i>
        </div>

        <div>
          <select name="purpose" onchange="document.getElementById('filterProposalsForm').submit()" style="width: 100%; padding: 0.55rem 0.85rem; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.86rem; outline: none; background: #ffffff; cursor: pointer;">
            <option value="all">-- Tất cả mục đích kết nối --</option>
            <option value="Tin nhắn / Thư ngỏ riêng cho Founder" {{ request('purpose') === 'Tin nhắn / Thư ngỏ riêng cho Founder' ? 'selected' : '' }}>✉️ Tin nhắn riêng cho Founder</option>
            <option value="Hợp tác nội dung & Bài viết chuyên sâu" {{ request('purpose') === 'Hợp tác nội dung & Bài viết chuyên sâu' ? 'selected' : '' }}>Hợp tác nội dung chuyên sâu</option>
            <option value="Mời diễn giả / Cố vấn kinh tế" {{ request('purpose') === 'Mời diễn giả / Cố vấn kinh tế' ? 'selected' : '' }}>Mời Diễn giả / Cố vấn</option>
            <option value="Tài trợ Hội thảo / Sự kiện doanh nghiệp" {{ request('purpose') === 'Tài trợ Hội thảo / Sự kiện doanh nghiệp' ? 'selected' : '' }}>Tài trợ Hội thảo / Sự kiện</option>
            <option value="Giới thiệu cơ hội đầu tư & Dòng vốn" {{ request('purpose') === 'Giới thiệu cơ hội đầu tư & Dòng vốn' ? 'selected' : '' }}>Cơ hội Đầu tư & Dòng vốn</option>
            <option value="Góp ý xây dựng diễn đàn" {{ request('purpose') === 'Góp ý xây dựng diễn đàn' ? 'selected' : '' }}>Góp ý xây dựng diễn đàn</option>
            <option value="Khác" {{ request('purpose') === 'Khác' ? 'selected' : '' }}>Khác</option>
          </select>
        </div>

        <div style="display: flex; gap: 0.5rem; align-items: center;">
          <button type="submit" class="cat-pill active" style="font-size: 0.84rem; padding: 0.55rem 1rem; border-radius: 6px; cursor: pointer;">
            <i class="fa-solid fa-filter"></i> Lọc dữ liệu
          </button>
          <a href="{{ route('admin.proposals.index', array_merge(request()->except(['starred', 'page']), ['starred' => request('starred') === '1' ? null : '1'])) }}" class="cat-pill {{ request('starred') === '1' ? 'active' : '' }}" style="text-decoration: none; font-size: 0.84rem; display: inline-flex; align-items: center; gap: 0.35rem; padding: 0.55rem 0.85rem;">
            <i class="fa-solid fa-star" style="color: {{ request('starred') === '1' ? '#f59e0b' : '#94a3b8' }};"></i>
            <span>{{ request('starred') === '1' ? 'Đang lọc: Gắn sao' : 'Thư gắn sao' }}</span>
          </a>
          @if(request()->hasAny(['keyword', 'purpose', 'starred']))
            <a href="{{ route('admin.proposals.index') }}" class="cat-pill" style="font-size: 0.84rem; padding: 0.55rem 0.85rem; border-radius: 6px; text-decoration: none; color: var(--text-muted); background: #ffffff; display: flex; align-items: center; gap: 0.35rem;">
              <i class="fa-solid fa-rotate-left"></i> Xóa lọc
            </a>
          @endif
        </div>
      </div>
    </form>

    <!-- BẢNG DỮ LIỆU ĐỀ XUẤT -->
    <div style="overflow-x: auto;">
      <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.84rem;">
        <thead>
          <tr style="border-bottom: 2px solid #f1f5f9; color: var(--text-light-muted); font-size: 0.74rem; font-weight: 800; letter-spacing: 0.5px;">
            <th style="padding: 0.75rem 0.4rem; width: 35px; text-align: center;">⭐</th>
            <th style="padding: 0.75rem 0.5rem; width: 26%;">NGƯỜI GỬI & DOANH NGHIỆP</th>
            <th style="padding: 0.75rem 0.5rem; width: 40%;">MỤC ĐÍCH & TIÊU ĐỀ THƯ NGỎ</th>
            <th style="padding: 0.75rem 0.5rem; width: 18%;">ĐÍNH KÈM HỒ SƠ</th>
            <th style="padding: 0.75rem 0.5rem; width: 16%; text-align: right;">THAO TÁC</th>
          </tr>
        </thead>
        <tbody>
          @forelse($proposals as $item)
            <tr class="proposal-table-row" data-proposal-id="{{ $item->id }}">
              <!-- Đánh dấu sao -->
              <td style="padding: 0.85rem 0.4rem; text-align: center; vertical-align: top;">
                <form action="{{ route('admin.proposals.star', $item->id) }}" method="POST" style="margin: 0;">
                  @csrf
                  @method('PATCH')
                  <button type="submit" class="btn-star-trigger" title="{{ $item->is_starred ? 'Bỏ gắn sao' : 'Gắn sao ưu tiên' }}" style="background: none; border: none; cursor: pointer; padding: 4px; font-size: 1rem; color: {{ $item->is_starred ? '#f59e0b' : '#cbd5e1' }}; transition: transform 0.15s ease;">
                    <i class="{{ $item->is_starred ? 'fa-solid' : 'fa-regular' }} fa-star"></i>
                  </button>
                </form>
              </td>

              <!-- Người gửi & Doanh nghiệp -->
              <td style="padding: 0.85rem 0.5rem; vertical-align: top;">
                <div style="font-weight: 800; color: var(--text-title); font-size: 0.88rem; margin-bottom: 0.2rem;">
                  {{ $item->name }}
                </div>
                @if($item->company || $item->role)
                  <div style="font-size: 0.76rem; color: var(--text-muted); margin-bottom: 0.35rem; display: flex; align-items: center; gap: 0.35rem; flex-wrap: wrap;">
                    @if($item->role)
                      <span style="font-weight: 600; color: var(--obsidian);">{{ $item->role }}</span>
                    @endif
                    @if($item->role && $item->company) &bull; @endif
                    @if($item->company)
                      <span style="font-weight: 700; color: var(--executive-blue); background: #eff6ff; padding: 1px 6px; border-radius: 4px;">{{ $item->company }}</span>
                    @endif
                  </div>
                @endif
                <div style="font-size: 0.75rem; color: var(--text-muted); display: flex; flex-direction: column; gap: 0.15rem;">
                  <a href="mailto:{{ $item->email }}" style="color: var(--text-muted); text-decoration: none; display: flex; align-items: center; gap: 0.35rem;" title="Gửi email cho đối tác">
                    <i class="fa-regular fa-envelope" style="font-size: 0.72rem; color: var(--executive-blue);"></i> {{ $item->email }}
                  </a>
                  <a href="tel:{{ $item->phone }}" style="color: var(--text-muted); text-decoration: none; display: flex; align-items: center; gap: 0.35rem;" title="Gọi điện / Zalo">
                    <i class="fa-solid fa-phone" style="font-size: 0.72rem; color: #16a34a;"></i> {{ $item->phone }}
                  </a>
                </div>
              </td>

              <!-- Mục đích & Tiêu đề thư ngỏ -->
              <td style="padding: 0.85rem 0.5rem; vertical-align: top;">
                <div style="margin-bottom: 0.35rem;">
                  <span style="display: inline-block; font-size: 0.7rem; font-weight: 700; padding: 2px 8px; border-radius: 4px; background: #e0e7ff; color: #4338ca;">
                    <i class="fa-solid fa-tag" style="font-size: 0.65rem; margin-right: 3px;"></i> {{ $item->purpose }}
                  </span>
                  <span style="font-size: 0.72rem; color: var(--text-light-muted); margin-left: 0.35rem;">
                    {{ $item->created_at->diffForHumans() }}
                  </span>
                </div>
                <div style="font-weight: 700; color: var(--text-title); font-size: 0.86rem; margin-bottom: 0.25rem;">
                  {{ $item->subject }}
                </div>
                <div style="font-size: 0.78rem; color: var(--text-muted); line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                  {{ $item->message }}
                </div>
                @if($item->admin_notes)
                  <div class="proposal-admin-notes-badge" style="margin-top: 0.35rem; font-size: 0.74rem; border-left: 2px solid #ca8a04; padding: 2px 6px; border-radius: 2px;">
                    <strong>Ghi chú:</strong> <span>{{ Str::limit($item->admin_notes, 80) }}</span>
                  </div>
                @endif
              </td>

              <!-- Đính kèm hồ sơ -->
              <td style="padding: 0.85rem 0.5rem; vertical-align: top;">
                @if($item->attachment)
                  @php
                    $fileInfo = $item->file_icon_info;
                    $fileSize = $item->file_size_formatted;
                  @endphp
                  <a href="{{ route('admin.proposals.download', $item->id) }}" class="cat-pill" style="font-size: 0.74rem; padding: 0.35rem 0.65rem; display: inline-flex; align-items: center; gap: 0.4rem; text-decoration: none; color: {{ $fileInfo['color'] }}; background: {{ $fileInfo['bg'] }}; border: 1px solid {{ $fileInfo['color'] }}40; border-radius: 6px; font-weight: 700; transition: all 0.2s ease;" title="Bấm để tải về: {{ $item->attachment_name }}">
                    <i class="{{ $fileInfo['icon'] }}" style="font-size: 0.95rem;"></i>
                    <span style="max-width: 120px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $item->attachment_name ?? 'Tải file' }}</span>
                    @if($fileSize)
                      <span style="font-size: 0.68rem; opacity: 0.8; font-weight: normal;">({{ $fileSize }})</span>
                    @endif
                    <i class="fa-solid fa-download" style="font-size: 0.75rem; margin-left: 2px;"></i>
                  </a>
                @else
                  <span style="font-size: 0.75rem; color: var(--text-light-muted); font-style: italic;">Không có file</span>
                @endif
              </td>

              <!-- Thao tác -->
              <td style="padding: 0.85rem 0.5rem; vertical-align: top; text-align: right;">
                <div style="display: inline-flex; align-items: center; gap: 0.4rem;">
                  <!-- Nút xem chi tiết modal -->
                  <button type="button" class="cat-pill" onclick="openProposalModal({{ json_encode($item) }})" style="font-size: 0.78rem; padding: 0.35rem 0.65rem; background: var(--bg-card); cursor: pointer; border-radius: 5px; color: var(--executive-blue); display: inline-flex; align-items: center; gap: 0.3rem;" title="Xem toàn văn thư ngỏ">
                    <i class="fa-regular fa-eye"></i> Chi tiết
                  </button>

                  <!-- Nút xóa: Mở Popup Modal Xác nhận -->
                  <button type="button" class="cat-pill btn-delete-proposal-trigger"
                    onclick="openDeleteProposalModal(this)"
                    data-id="{{ $item->id }}"
                    data-sender="{{ $item->name }}"
                    data-company="{{ $item->company ?? '' }}"
                    data-subject="{{ $item->subject }}"
                    data-purpose="{{ $item->purpose }}"
                    data-delete-url="{{ route('admin.proposals.destroy', $item->id) }}"
                    style="font-size: 0.78rem; padding: 0.35rem 0.55rem; background: #fff; border-color: #fecaca; color: #ef4444; cursor: pointer; border-radius: 5px;"
                    title="Xóa đề xuất">
                    <i class="fa-regular fa-trash-can"></i>
                  </button>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="5" style="padding: 3rem 1rem; text-align: center; color: var(--text-muted);">
                <div style="font-size: 2.2rem; color: #cbd5e1; margin-bottom: 0.5rem;">
                  <i class="fa-solid fa-inbox"></i>
                </div>
                <div style="font-size: 0.95rem; font-weight: 700; color: var(--text-title); margin-bottom: 0.25rem;">Chưa có đề xuất hoặc thư ngỏ nào phù hợp</div>
                <p style="font-size: 0.82rem; margin: 0;">Khi đối tác gửi thư ngỏ qua trang Liên hệ, toàn bộ thông tin sẽ được hiển thị và cập nhật thời gian thực tại đây.</p>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <!-- PHÂN TRANG ĐỀ XUẤT CHUẨN MEDIAN UI -->
    <div style="display: flex; align-items: center; justify-content: space-between; padding-top: 1.25rem; border-top: 1px solid #f1f5f9; font-size: 0.82rem; color: var(--text-muted); margin-top: 0.5rem; flex-wrap: wrap; gap: 0.75rem;">
      <div style="display: flex; align-items: center; gap: 0.65rem;">
        <span>Hiển thị <strong>{{ $proposals->firstItem() ?? 0 }} - {{ $proposals->lastItem() ?? 0 }}</strong> trên tổng số <strong>{{ $proposals->total() }}</strong> đề xuất / thư ngỏ</span>
      </div>

      @if($proposals->hasPages())
      <nav style="display: flex; align-items: center; gap: 0.35rem;">
        {{-- Nút Về trang trước --}}
        @if($proposals->onFirstPage())
          <span style="display: inline-flex; align-items: center; justify-content: center; height: 32px; padding: 0 0.75rem; font-size: 0.8rem; font-weight: 700; color: #94a3b8; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; cursor: not-allowed; opacity: 0.6;">
            <i class="fa-solid fa-chevron-left" style="font-size: 0.7rem; margin-right: 4px;"></i> Trước
          </span>
        @else
          <a href="{{ request()->fullUrlWithQuery(['page' => $proposals->currentPage() - 1]) }}" style="display: inline-flex; align-items: center; justify-content: center; height: 32px; padding: 0 0.75rem; font-size: 0.8rem; font-weight: 700; color: var(--text-title); background: #ffffff; border: 1px solid #cbd5e1; border-radius: 6px; text-decoration: none; transition: all 0.2s ease;">
            <i class="fa-solid fa-chevron-left" style="font-size: 0.7rem; margin-right: 4px;"></i> Trước
          </a>
        @endif

        {{-- Các số trang (1, 2, 3...) --}}
        @for($p = 1; $p <= $proposals->lastPage(); $p++)
          @if($p == $proposals->currentPage())
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
        @if($proposals->hasMorePages())
          <a href="{{ request()->fullUrlWithQuery(['page' => $proposals->currentPage() + 1]) }}" style="display: inline-flex; align-items: center; justify-content: center; height: 32px; padding: 0 0.75rem; font-size: 0.8rem; font-weight: 700; color: var(--text-title); background: #ffffff; border: 1px solid #cbd5e1; border-radius: 6px; text-decoration: none; transition: all 0.2s ease;">
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

<!-- ======================================================================
     MODAL POPUP: CHI TIẾT ĐỀ XUẤT / THƯ NGỎ HỢP TÁC
     ====================================================================== -->
<div id="proposalDetailModal" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.65); z-index: 9999; align-items: center; justify-content: center; backdrop-filter: blur(4px); padding: 1.25rem;">
  <div style="background: var(--bg-card, #ffffff); color: var(--text-title); border-radius: 12px; max-width: 720px; width: 100%; max-height: 90vh; display: flex; flex-direction: column; overflow: hidden; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); border: 1px solid var(--border-card, #e2e8f0); animation: modalFadeIn 0.2s ease;">
    
    <!-- Modal Header -->
    <div style="padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--border-light, #f1f5f9); display: flex; align-items: center; justify-content: space-between; background: var(--bg-subtle, #f8fafc);">
      <div>
        <div style="font-size: 0.75rem; font-weight: 800; color: var(--executive-blue); text-transform: uppercase; letter-spacing: 0.5px;">
          HỒ SƠ THƯ NGỎ ĐỐI TÁC
        </div>
        <h3 id="modalSubjectText" style="font-size: 1.15rem; font-weight: 800; color: var(--text-title); margin: 0.15rem 0 0 0;">
          Tiêu đề thư ngỏ
        </h3>
      </div>
      <button type="button" onclick="closeProposalModal()" style="background: none; border: none; font-size: 1.25rem; color: #94a3b8; cursor: pointer; padding: 0.25rem; border-radius: 4px;">
        <i class="fa-solid fa-xmark"></i>
      </button>
    </div>

    <!-- Modal Body (Cuộn nội dung) -->
    <div style="padding: 1.5rem; overflow-y: auto; flex: 1; display: flex; flex-direction: column; gap: 1.25rem;">
      
      <!-- Hàng 1: Thẻ thông tin đối tác -->
      <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 1rem 1.25rem; display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
        <div>
          <div style="font-size: 0.74rem; color: var(--text-muted); font-weight: 700; text-transform: uppercase;">Người gửi:</div>
          <div id="modalSenderName" style="font-size: 0.95rem; font-weight: 800; color: var(--text-title); margin-top: 0.15rem;"></div>
          <div id="modalSenderRoleCompany" style="font-size: 0.8rem; color: var(--executive-blue); font-weight: 600; margin-top: 0.15rem;"></div>
        </div>

        <div>
          <div style="font-size: 0.74rem; color: var(--text-muted); font-weight: 700; text-transform: uppercase;">Kênh liên hệ trực tiếp:</div>
          <div style="margin-top: 0.2rem; font-size: 0.84rem; display: flex; flex-direction: column; gap: 0.2rem;">
            <div>
              <i class="fa-regular fa-envelope" style="color: var(--executive-blue); width: 16px;"></i> 
              <a id="modalSenderEmail" href="" style="color: inherit; text-decoration: none; font-weight: 600;"></a>
            </div>
            <div>
              <i class="fa-solid fa-phone" style="color: #16a34a; width: 16px;"></i> 
              <a id="modalSenderPhone" href="" style="color: inherit; text-decoration: none; font-weight: 600;"></a>
            </div>
          </div>
        </div>

        <div style="grid-column: span 2; display: flex; justify-content: space-between; align-items: center; border-top: 1px dashed #e2e8f0; padding-top: 0.65rem; margin-top: 0.25rem; font-size: 0.76rem; color: var(--text-muted);">
          <div>
            <strong>Mục đích:</strong> <span id="modalPurposeBadge" style="background: #e0e7ff; color: #4338ca; padding: 2px 7px; border-radius: 4px; font-weight: 700;"></span>
          </div>
          <div id="modalCreatedAtText"></div>
        </div>
      </div>

      <!-- Hàng 2: Toàn văn nội dung thư ngỏ -->
      <div>
        <div style="font-size: 0.78rem; font-weight: 800; color: var(--text-title); margin-bottom: 0.45rem; text-transform: uppercase; letter-spacing: 0.5px;">
          <i class="fa-solid fa-align-left" style="color: var(--executive-blue);"></i> Nội dung trao đổi chi tiết:
        </div>
        <div id="modalMessageContent" style="background: var(--bg-subtle, #ffffff); border: 1px solid var(--border-card, #cbd5e1); border-radius: 8px; padding: 1.15rem; font-size: 0.88rem; line-height: 1.6; color: var(--text-title); white-space: pre-wrap; max-height: 250px; overflow-y: auto;">
        </div>
      </div>

      <!-- Hàng 3: File tài liệu / Hồ sơ năng lực đính kèm -->
      <div id="modalAttachmentWrapper">
        <div style="font-size: 0.78rem; font-weight: 800; color: var(--text-title); margin-bottom: 0.45rem; text-transform: uppercase; letter-spacing: 0.5px;">
          <i class="fa-solid fa-paperclip" style="color: #16a34a;"></i> Hồ sơ năng lực / Tài liệu đính kèm:
        </div>
        <div style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px; padding: 0.85rem 1.15rem; display: flex; align-items: center; justify-content: space-between; gap: 1rem; flex-wrap: wrap;">
          <div style="display: flex; align-items: center; gap: 0.75rem;">
            <div id="modalAttachmentIconBox" style="width: 44px; height: 44px; border-radius: 8px; background: #ffffff; border: 1px solid #bbf7d0; display: flex; align-items: center; justify-content: center; font-size: 1.4rem;">
              <i id="modalAttachmentIcon" class="fa-solid fa-file-pdf" style="color: #16a34a;"></i>
            </div>
            <div>
              <div id="modalAttachmentFileName" style="font-size: 0.9rem; font-weight: 800; color: #166534; word-break: break-all;"></div>
              <div id="modalAttachmentSubText" style="font-size: 0.76rem; color: #15803d; margin-top: 2px;">Tài liệu gửi kèm từ đối tác &bull; Nhấp nút bên cạnh để tải về máy</div>
            </div>
          </div>
          <a id="modalAttachmentDownloadBtn" href="" class="cat-pill" style="font-size: 0.84rem; padding: 0.5rem 1.25rem; background: #16a34a; color: #ffffff; text-decoration: none; border-radius: 6px; display: inline-flex; align-items: center; gap: 0.45rem; font-weight: 700; box-shadow: 0 2px 4px rgba(22,163,74,0.25);">
            <i class="fa-solid fa-cloud-arrow-down"></i> Tải file về máy
          </a>
        </div>
      </div>

      <!-- Hàng 4: Ghi chú nội bộ của Admin & Đổi trạng thái -->
      <div class="modal-admin-notes-box" style="border-radius: 8px; padding: 1rem 1.25rem;">
        <form id="modalAdminNotesForm" method="POST" action="">
          @csrf
          @method('PATCH')

          <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.5rem;">
            <label for="modalAdminNotesInput" style="font-size: 0.8rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px;">
              <i class="fa-solid fa-note-sticky"></i> Ghi chú nội bộ Admin (Lịch hẹn / Đánh giá):
            </label>
            <span style="font-size: 0.72rem;">Chỉ Admin nhìn thấy</span>
          </div>

          <textarea name="admin_notes" id="modalAdminNotesInput" rows="3" placeholder="Nhập ghi chú cuộc gọi, kết quả gặp gỡ hoặc phân công xử lý..." style="width: 100%; padding: 0.6rem 0.8rem; border: 1px solid var(--border-card, #fde68a); border-radius: 6px; font-size: 0.85rem; outline: none; background: var(--bg-card, #ffffff); color: var(--text-title); resize: vertical; margin-bottom: 0.75rem;"></textarea>

          <div style="display: flex; justify-content: flex-end;">
            <button type="submit" class="cat-pill" style="font-size: 0.8rem; padding: 0.45rem 1rem; background: #d97706; color: #fff; border: none; border-radius: 5px; cursor: pointer;">
              <i class="fa-solid fa-floppy-disk"></i> Lưu ghi chú
            </button>
          </div>
        </form>
      </div>

    </div>

    <!-- Modal Footer -->
    <div style="padding: 1rem 1.5rem; border-top: 1px solid #f1f5f9; display: flex; align-items: center; justify-content: flex-end; background: #f8fafc;">
      <button type="button" onclick="closeProposalModal()" class="cat-pill" style="font-size: 0.84rem; padding: 0.5rem 1.35rem; background: #ffffff; cursor: pointer; border-radius: 6px; font-weight: 700;">
        Đóng cửa sổ
      </button>
    </div>

  </div>
</div>

<!-- ======================================================================
     MODAL POPUP: XÁC NHẬN XÓA ĐỀ XUẤT HỢP TÁC (GIAO DIỆN CHUYÊN NGHIỆP)
     ====================================================================== -->
<div class="modal fade" id="deleteProposalModal" tabindex="-1" aria-labelledby="deleteProposalModalLabel" aria-hidden="true" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.65); z-index: 10000; align-items: center; justify-content: center; backdrop-filter: blur(4px);">
  <div class="modal-dialog modal-dialog-centered" style="max-width: 480px; width: 92%;">
    <div class="modal-content" style="border-radius: 14px; border: 1px solid var(--border-card, #e2e8f0); box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); background-color: var(--bg-card, #ffffff); overflow: hidden;">
      
      <!-- Modal Header -->
      <div class="modal-header" style="border-bottom: 1px solid var(--border-card, #f1f5f9); padding: 1.25rem 1.5rem; display: flex; align-items: center; justify-content: space-between;">
        <div style="display: flex; align-items: center; gap: 0.75rem;">
          <div style="width: 42px; height: 42px; border-radius: 10px; background: rgba(239, 68, 68, 0.12); color: #ef4444; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; flex-shrink: 0; border: 1px solid rgba(239, 68, 68, 0.2);">
            <i class="fa-solid fa-triangle-exclamation"></i>
          </div>
          <div>
            <h5 class="modal-title" id="deleteProposalModalLabel" style="font-size: 1.05rem; font-weight: 800; color: var(--text-title, #0f172a); margin: 0;">
              Xác nhận xóa đề xuất
            </h5>
            <p style="font-size: 0.75rem; color: var(--text-muted, #64748b); margin: 0;">
              Thao tác này sẽ gỡ thư ngỏ khỏi hệ thống
            </p>
          </div>
        </div>
        <button type="button" class="btn-close-modal" data-bs-dismiss="modal" onclick="closeDeleteProposalModal()" aria-label="Đóng" style="background: none; border: none; color: var(--text-muted); font-size: 1.15rem; cursor: pointer; padding: 0.35rem; border-radius: 6px; display: flex; align-items: center; justify-content: center; line-height: 1;">
          <i class="fa-solid fa-xmark"></i>
        </button>
      </div>

      <!-- Form Xác nhận Xóa -->
      <form id="deleteProposalForm" action="" method="POST">
        @csrf
        @method('DELETE')
        <div class="modal-body" style="padding: 1.5rem;">
          <p style="font-size: 0.92rem; color: var(--text-body, #1e293b); line-height: 1.6; margin: 0 0 0.85rem 0;">
            Bạn có chắc chắn muốn xóa hồ sơ đề xuất của <strong id="deleteProposalSenderTarget" style="color: #ef4444;"></strong>?
          </p>

          <div style="background: var(--bg-subtle, #f8fafc); border: 1px solid var(--border-card, #e2e8f0); border-radius: 8px; padding: 0.85rem 1rem; margin-bottom: 1rem;">
            <div id="deleteProposalSubjectTarget" style="font-size: 0.9rem; font-weight: 800; color: var(--text-title); margin-bottom: 0.35rem; line-height: 1.4;"></div>
            <div id="deleteProposalMetaTarget" style="font-size: 0.76rem; color: var(--text-muted); display: flex; gap: 0.5rem; flex-wrap: wrap;"></div>
          </div>

          <div style="background: rgba(239, 68, 68, 0.05); border: 1px dashed rgba(239, 68, 68, 0.25); border-radius: 8px; padding: 0.75rem 1rem; display: flex; align-items: flex-start; gap: 0.65rem;">
            <i class="fa-solid fa-circle-exclamation" style="color: #ef4444; font-size: 0.95rem; margin-top: 0.15rem; flex-shrink: 0;"></i>
            <span style="font-size: 0.78rem; color: var(--text-muted, #64748b); line-height: 1.45;">
              Hồ sơ đề xuất cùng tài liệu đính kèm sẽ bị xóa vĩnh viễn và <strong>không thể khôi phục lại</strong>.
            </span>
          </div>
        </div>

        <!-- Modal Footer -->
        <div class="modal-footer" style="border-top: 1px solid var(--border-card, #f1f5f9); padding: 1rem 1.5rem; display: flex; justify-content: flex-end; gap: 0.75rem; background: var(--bg-subtle, #f8fafc);">
          <button type="button" class="btn" onclick="closeDeleteProposalModal()" data-bs-dismiss="modal" style="font-size: 0.85rem; font-weight: 600; padding: 0.55rem 1.15rem; border-radius: 6px; color: var(--text-muted, #64748b); background: var(--bg-card, #ffffff); border: 1px solid var(--border-card, #cbd5e1); cursor: pointer; transition: all 0.2s;">
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

@if(isset($autoOpenProposal) && $autoOpenProposal)
<div id="autoOpenProposalData" style="display:none;" data-proposal='@json($autoOpenProposal)'></div>
@endif

<script src="{{ asset('js/manage-proposals.js') }}"></script>

<style>
@keyframes modalFadeIn {
  from { opacity: 0; transform: scale(0.97); }
  to { opacity: 1; transform: scale(1); }
}
</style>
@endsection
