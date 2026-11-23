@extends('layouts.app')

@section('title', 'Quản lý danh mục - KẾT NỐI Admin')
@section('breadcrumb', 'WORKSPACE / QUẢN TRỊ NỘI DUNG / DANH MỤC')

@section('content')
<main class="main-content-scroll">
    <!-- TIÊU ĐỀ & ĐIỀU HƯỚNG -->
    <div class="content-header-row" style="margin-bottom: 1.5rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h1 class="forum-main-title" style="font-size: 1.55rem; font-weight: 800; color: var(--obsidian); margin: 0;">Quản lý danh mục chuyên đề</h1>
            <p class="forum-subtitle" style="font-size: 0.85rem; color: var(--text-muted); margin-top: 0.25rem;">
                Tạo mới, chỉnh sửa và quản lý các chủ đề thảo luận cho cộng đồng doanh nghiệp.
            </p>
        </div>
        <div style="display: flex; gap: 0.65rem;">
            <a href="{{ route('admin.posts') }}" class="cat-pill" style="font-size: 0.85rem; padding: 0.55rem 1.15rem; text-decoration: none;">
                <i class="fa-solid fa-arrow-left"></i> Quay lại bài viết
            </a>
        </div>
    </div>

    <!-- BỐ CỤC 2 CỘT: FORM TẠO DANH MỤC (TRÁI) + DANH SÁCH DANH MỤC (PHẢI) -->
    <div style="display: grid; grid-template-columns: 340px 1fr; gap: 1.5rem; align-items: start;">
        
        <!-- ================= CỘT 1: FORM TẠO DANH MỤC MỚI ================= -->
        <div class="widget-card" style="padding: 1.5rem;">
            <div style="font-size: 1.05rem; font-weight: 800; color: var(--obsidian); margin-bottom: 0.35rem; display: flex; align-items: center; gap: 0.5rem;">
                <i class="fa-solid fa-folder-plus" style="color: var(--executive-blue);"></i>
                <span>Tạo danh mục mới</span>
            </div>
            <p style="font-size: 0.8rem; color: var(--text-muted); margin-bottom: 1.25rem;">
                Chuyên mục mới sẽ hiển thị tại thanh lọc và menu Sidebar toàn hệ thống.
            </p>

            <form action="{{ route('admin.categories.store') }}" method="POST">
                @csrf
                <!-- Tên danh mục -->
                <div style="margin-bottom: 1.15rem;">
                    <label style="display: block; font-size: 0.82rem; font-weight: 700; color: var(--text-title); margin-bottom: 0.4rem;">
                        Tên danh mục <span style="color: #ef4444;">*</span>
                    </label>
                    <input type="text" name="name" required placeholder="Ví dụ: Chuyển đổi số & AI..." style="width: 100%; background: var(--bg-subtle); border: 1px solid var(--border-card); border-radius: 6px; padding: 0.6rem 0.85rem; font-size: 0.88rem; outline: none; font-family: var(--font-family); color: var(--text-title);">
                </div>

                <!-- Màu sắc nhận diện -->
                <div style="margin-bottom: 1.15rem;">
                    <label style="display: block; font-size: 0.82rem; font-weight: 700; color: var(--text-title); margin-bottom: 0.4rem;">
                        Màu nhận diện
                    </label>
                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                        <input type="color" id="categoryColorPicker" name="color" value="#2563eb" style="width: 44px; height: 38px; border: 1px solid var(--border-card); border-radius: 6px; cursor: pointer; padding: 2px; background: #fff;">
                        <span id="categoryColorHexText" style="font-family: monospace; font-size: 0.85rem; font-weight: 700; color: var(--text-muted);">#2563EB</span>
                    </div>

                    <!-- Bảng màu doanh nghiệp gợi ý nhanh -->
                    <div style="display: flex; gap: 0.45rem; margin-top: 0.6rem; flex-wrap: wrap;">
                        <button type="button" class="cat-color-choice" data-color="#2563eb" style="width: 24px; height: 24px; border-radius: 50%; background: #2563eb; border: 2px solid #fff; box-shadow: 0 0 0 1px #cbd5e1; cursor: pointer;" title="Executive Blue"></button>
                        <button type="button" class="cat-color-choice" data-color="#16a34a" style="width: 24px; height: 24px; border-radius: 50%; background: #16a34a; border: 2px solid #fff; box-shadow: 0 0 0 1px #cbd5e1; cursor: pointer;" title="Emerald Green"></button>
                        <button type="button" class="cat-color-choice" data-color="#d97706" style="width: 24px; height: 24px; border-radius: 50%; background: #d97706; border: 2px solid #fff; box-shadow: 0 0 0 1px #cbd5e1; cursor: pointer;" title="Amber Gold"></button>
                        <button type="button" class="cat-color-choice" data-color="#7c3aed" style="width: 24px; height: 24px; border-radius: 50%; background: #7c3aed; border: 2px solid #fff; box-shadow: 0 0 0 1px #cbd5e1; cursor: pointer;" title="Tech Purple"></button>
                        <button type="button" class="cat-color-choice" data-color="#0d9488" style="width: 24px; height: 24px; border-radius: 50%; background: #0d9488; border: 2px solid #fff; box-shadow: 0 0 0 1px #cbd5e1; cursor: pointer;" title="Teal ESG"></button>
                        <button type="button" class="cat-color-choice" data-color="#e11d48" style="width: 24px; height: 24px; border-radius: 50%; background: #e11d48; border: 2px solid #fff; box-shadow: 0 0 0 1px #cbd5e1; cursor: pointer;" title="Rose Red"></button>
                        <button type="button" class="cat-color-choice" data-color="#0891b2" style="width: 24px; height: 24px; border-radius: 50%; background: #0891b2; border: 2px solid #fff; box-shadow: 0 0 0 1px #cbd5e1; cursor: pointer;" title="Cyan Deep"></button>
                    </div>
                </div>

                <!-- Nút lưu -->
                <button type="submit" class="btn-publish-trigger" style="width: 100%; background-color: var(--executive-blue); justify-content: center; height: 42px; font-size: 0.9rem; font-weight: 700; border-radius: 6px; cursor: pointer; display: flex; align-items: center; gap: 0.5rem; border: none; color: #fff;">
                    <i class="fa-solid fa-plus"></i> Tạo danh mục ngay
                </button>
            </form>
        </div>

        <!-- ================= CỘT 2: BẢNG DANH MỤC HIỆN CÓ ================= -->
        <div class="widget-card" style="padding: 1.75rem;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.25rem;">
                <div>
                    <div style="font-size: 1.05rem; font-weight: 800; color: var(--obsidian); display: flex; align-items: center; gap: 0.5rem;">
                        <i class="fa-solid fa-list-check" style="color: var(--executive-blue);"></i>
                        <span>Danh sách danh mục ({{ $categories->total() }})</span>
                    </div>
                    <p style="font-size: 0.82rem; color: var(--text-muted); margin-top: 0.2rem;">
                        Các chuyên đề hiển thị trực tiếp trên thanh Sidebar và bộ lọc trang chủ.
                    </p>
                </div>
            </div>

            <!-- BẢNG DANH MỤC -->
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.86rem;">
                    <thead>
                        <tr style="border-bottom: 2px solid var(--border-card); color: var(--text-muted); font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.5px;">
                            <th style="padding: 0.75rem 0.5rem;">Tên danh mục</th>
                            <th style="padding: 0.75rem 0.5rem; text-align: center;">Số bài viết</th>
                            <th style="padding: 0.75rem 0.5rem; text-align: right;">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $cat)
                            <tr style="border-bottom: 1px solid var(--border-card); transition: background 0.15s ease;">
                                <td style="padding: 0.85rem 0.5rem;">
                                    <div style="display: flex; align-items: center; gap: 0.65rem;">
                                        <span style="width: 12px; height: 12px; border-radius: 50%; background-color: {{ $cat->color }}; flex-shrink: 0; box-shadow: 0 0 0 1px rgba(0,0,0,0.1);"></span>
                                        <div>
                                            <div style="font-weight: 700; color: var(--text-title);">{{ $cat->name }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td style="padding: 0.85rem 0.5rem; text-align: center;">
                                    <span style="font-size: 0.78rem; font-weight: 800; background: var(--bg-subtle); padding: 0.2rem 0.6rem; border-radius: 999px; color: var(--obsidian);">
                                        {{ $cat->posts_count ?? 0 }} bài
                                    </span>
                                </td>
                                <td style="padding: 0.85rem 0.5rem; text-align: right; white-space: nowrap;">
                                    <!-- NÚT SỬA: MỞ MODAL SỬA DANH MỤC -->
                                    <button type="button" class="cat-pill btn-edit-category-trigger"
                                        onclick="openEditCategoryModal(this)"
                                        data-id="{{ $cat->id }}"
                                        data-name="{{ $cat->name }}"
                                        data-color="{{ $cat->color }}"
                                        data-description="{{ $cat->description }}"
                                        data-update-url="{{ route('admin.categories.update', $cat->id) }}"
                                        style="font-size: 0.76rem; padding: 0.35rem 0.65rem; color: var(--executive-blue); border-color: #bfdbfe; background: #eff6ff; cursor: pointer; margin-right: 0.35rem;"
                                        title="Chỉnh sửa danh mục">
                                        <i class="fa-solid fa-pen"></i> Sửa
                                    </button>

                                    <!-- NÚT XÓA DANH MỤC -->
                                    <form action="{{ route('admin.categories.destroy', $cat->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa danh mục \'{{ $cat->name }}\'?');" style="display: inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="cat-pill" style="font-size: 0.76rem; padding: 0.35rem 0.65rem; color: #ef4444; border-color: #fecaca; background: #fff5f5; cursor: pointer;" title="Xóa danh mục">
                                            <i class="fa-solid fa-trash-can"></i> Xóa
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="text-align: center; padding: 2.5rem 1rem; color: var(--text-muted);">
                                    <i class="fa-solid fa-folder-open" style="font-size: 2rem; color: var(--text-light-muted); margin-bottom: 0.5rem; display: block;"></i>
                                    Chưa có danh mục nào. Hãy dùng form bên trái để tạo danh mục đầu tiên!
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- PHÂN TRANG DANH MỤC CHUẨN MEDIAN UI -->
            <div style="display: flex; align-items: center; justify-content: space-between; padding-top: 1.25rem; border-top: 1px solid var(--border-card); font-size: 0.82rem; color: var(--text-muted); margin-top: 0.75rem; flex-wrap: wrap; gap: 0.75rem;">
                <div style="display: flex; align-items: center; gap: 0.65rem;">
                    <span>Hiển thị <strong>{{ $categories->firstItem() ?? 0 }} - {{ $categories->lastItem() ?? 0 }}</strong> trên tổng số <strong>{{ $categories->total() }}</strong> danh mục</span>
                </div>

                @if($categories->hasPages())
                <nav style="display: flex; align-items: center; gap: 0.35rem;">
                    {{-- Nút Về trang trước --}}
                    @if($categories->onFirstPage())
                        <span style="display: inline-flex; align-items: center; justify-content: center; height: 32px; padding: 0 0.75rem; font-size: 0.8rem; font-weight: 700; color: #94a3b8; background: var(--bg-subtle); border: 1px solid var(--border-card); border-radius: 6px; cursor: not-allowed; opacity: 0.6;">
                            <i class="fa-solid fa-chevron-left" style="font-size: 0.7rem; margin-right: 4px;"></i> Trước
                        </span>
                    @else
                        <a href="{{ request()->fullUrlWithQuery(['page' => $categories->currentPage() - 1]) }}" style="display: inline-flex; align-items: center; justify-content: center; height: 32px; padding: 0 0.75rem; font-size: 0.8rem; font-weight: 700; color: var(--text-title); background: var(--bg-card); border: 1px solid var(--border-card); border-radius: 6px; text-decoration: none; transition: all 0.2s ease;">
                            <i class="fa-solid fa-chevron-left" style="font-size: 0.7rem; margin-right: 4px;"></i> Trước
                        </a>
                    @endif

                    {{-- Các số trang (1, 2, 3...) --}}
                    @for($p = 1; $p <= $categories->lastPage(); $p++)
                        @if($p == $categories->currentPage())
                            <span style="display: inline-flex; align-items: center; justify-content: center; min-width: 32px; height: 32px; padding: 0 0.65rem; font-size: 0.82rem; font-weight: 800; color: #ffffff; background: var(--executive-blue, #2563eb); border: 1px solid var(--executive-blue, #2563eb); border-radius: 6px; box-shadow: 0 2px 4px rgba(37, 99, 235, 0.25);">
                                {{ $p }}
                            </span>
                        @else
                            <a href="{{ request()->fullUrlWithQuery(['page' => $p]) }}" style="display: inline-flex; align-items: center; justify-content: center; min-width: 32px; height: 32px; padding: 0 0.65rem; font-size: 0.82rem; font-weight: 700; color: var(--text-title); background: var(--bg-card); border: 1px solid var(--border-card); border-radius: 6px; text-decoration: none; transition: all 0.2s ease;">
                                {{ $p }}
                            </a>
                        @endif
                    @endfor

                    {{-- Nút Sang trang sau --}}
                    @if($categories->hasMorePages())
                        <a href="{{ request()->fullUrlWithQuery(['page' => $categories->currentPage() + 1]) }}" style="display: inline-flex; align-items: center; justify-content: center; height: 32px; padding: 0 0.75rem; font-size: 0.8rem; font-weight: 700; color: var(--text-title); background: var(--bg-card); border: 1px solid var(--border-card); border-radius: 6px; text-decoration: none; transition: all 0.2s ease;">
                            Sau <i class="fa-solid fa-chevron-right" style="font-size: 0.7rem; margin-left: 4px;"></i>
                        </a>
                    @else
                        <span style="display: inline-flex; align-items: center; justify-content: center; height: 32px; padding: 0 0.75rem; font-size: 0.8rem; font-weight: 700; color: #94a3b8; background: var(--bg-subtle); border: 1px solid var(--border-card); border-radius: 6px; cursor: not-allowed; opacity: 0.6;">
                            Sau <i class="fa-solid fa-chevron-right" style="font-size: 0.7rem; margin-left: 4px;"></i>
                        </span>
                    @endif
                </nav>
                @endif
            </div>
        </div>

    </div>

</main>
@endsection

@section('modals')
<!-- ======================================================================
     MODAL POPUP: CHỈNH SỬA DANH MỤC CHUYÊN ĐỀ
     ====================================================================== -->
<div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="border-radius: 12px; border: 1px solid var(--border-light, #e2e8f0); box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1); background-color: #ffffff;">
      
      <div class="modal-header" style="border-bottom: 1px solid #f1f5f9; padding: 1.25rem 1.5rem; display: flex; align-items: center; justify-content: space-between;">
        <div style="display: flex; align-items: center; gap: 0.65rem;">
          <div style="width: 38px; height: 38px; border-radius: 8px; background: rgba(37, 99, 235, 0.1); color: var(--executive-blue, #2563eb); display: flex; align-items: center; justify-content: center; font-size: 1.1rem;">
            <i class="fa-solid fa-pen-to-square"></i>
          </div>
          <div>
            <h5 class="modal-title" id="editCategoryModalLabel" style="font-size: 1.1rem; font-weight: 800; color: var(--text-title, #0f172a); margin: 0;">
              Chỉnh sửa danh mục
            </h5>
            <p style="font-size: 0.75rem; color: var(--text-muted, #64748b); margin: 0;">
              Cập nhật tên, màu sắc nhận diện và tóm tắt định hướng chuyên mục
            </p>
          </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng" style="font-size: 0.8rem;"></button>
      </div>

      <form id="editCategoryForm" action="" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-body" style="padding: 1.5rem;">
          
          <!-- Tên danh mục -->
          <div style="margin-bottom: 1.25rem;">
            <label style="display: block; font-size: 0.82rem; font-weight: 700; color: var(--text-title); margin-bottom: 0.45rem;">
              Tên danh mục <span style="color: #ef4444;">*</span>
            </label>
            <input type="text" id="editCategoryName" name="name" required placeholder="Nhập tên danh mục..." style="width: 100%; padding: 0.65rem 0.85rem; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.88rem; outline: none;">
          </div>

          <!-- Màu nhận diện -->
          <div style="margin-bottom: 1.25rem;">
            <label style="display: block; font-size: 0.82rem; font-weight: 700; color: var(--text-title); margin-bottom: 0.45rem;">
              Màu nhận diện danh mục
            </label>
            <div style="display: flex; align-items: center; gap: 0.75rem;">
              <input type="color" id="editCategoryColor" name="color" value="#2563eb" style="width: 44px; height: 38px; border: 1px solid #cbd5e1; border-radius: 6px; padding: 2px; cursor: pointer; background: #fff;">
              <span id="editCategoryColorHex" style="font-family: monospace; font-size: 0.85rem; font-weight: 700; color: var(--text-muted);">#2563EB</span>
            </div>
            <!-- Bảng màu gợi ý -->
            <div style="display: flex; gap: 0.45rem; margin-top: 0.6rem; flex-wrap: wrap;">
              <button type="button" class="edit-cat-color-choice" data-color="#2563eb" style="width: 24px; height: 24px; border-radius: 50%; background: #2563eb; border: 2px solid #fff; box-shadow: 0 0 0 1px #cbd5e1; cursor: pointer;" title="Executive Blue"></button>
              <button type="button" class="edit-cat-color-choice" data-color="#16a34a" style="width: 24px; height: 24px; border-radius: 50%; background: #16a34a; border: 2px solid #fff; box-shadow: 0 0 0 1px #cbd5e1; cursor: pointer;" title="Emerald Green"></button>
              <button type="button" class="edit-cat-color-choice" data-color="#d97706" style="width: 24px; height: 24px; border-radius: 50%; background: #d97706; border: 2px solid #fff; box-shadow: 0 0 0 1px #cbd5e1; cursor: pointer;" title="Amber Gold"></button>
              <button type="button" class="edit-cat-color-choice" data-color="#7c3aed" style="width: 24px; height: 24px; border-radius: 50%; background: #7c3aed; border: 2px solid #fff; box-shadow: 0 0 0 1px #cbd5e1; cursor: pointer;" title="Tech Purple"></button>
              <button type="button" class="edit-cat-color-choice" data-color="#0d9488" style="width: 24px; height: 24px; border-radius: 50%; background: #0d9488; border: 2px solid #fff; box-shadow: 0 0 0 1px #cbd5e1; cursor: pointer;" title="Teal ESG"></button>
              <button type="button" class="edit-cat-color-choice" data-color="#e11d48" style="width: 24px; height: 24px; border-radius: 50%; background: #e11d48; border: 2px solid #fff; box-shadow: 0 0 0 1px #cbd5e1; cursor: pointer;" title="Rose Red"></button>
              <button type="button" class="edit-cat-color-choice" data-color="#0891b2" style="width: 24px; height: 24px; border-radius: 50%; background: #0891b2; border: 2px solid #fff; box-shadow: 0 0 0 1px #cbd5e1; cursor: pointer;" title="Cyan Deep"></button>
            </div>
          </div>

        </div>

        <div class="modal-footer" style="border-top: 1px solid #f1f5f9; padding: 1rem 1.5rem; display: flex; justify-content: flex-end; gap: 0.75rem;">
          <button type="button" class="btn btn-light" data-bs-dismiss="modal" style="font-size: 0.85rem; font-weight: 600; padding: 0.55rem 1.15rem; border-radius: 6px; color: #64748b;">
            Hủy bỏ
          </button>
          <button type="submit" class="btn btn-primary" style="background-color: var(--executive-blue); border: none; font-size: 0.85rem; font-weight: 700; padding: 0.55rem 1.35rem; border-radius: 6px; display: flex; align-items: center; gap: 0.45rem;">
            <i class="fa-solid fa-check"></i> Lưu thay đổi
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/manage-categories.js') }}"></script>
@endpush
