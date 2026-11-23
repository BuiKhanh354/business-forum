<!-- ======================================================================
     MODAL TẠO DANH MỤC MỚI (CHO PHÉP NGƯỜI DÙNG & ADMIN TẠO CHUYÊN MỤC)
     CHẤT LƯỢNG CHUẨN MEDIAN UI - EXECUTIVE BUSINESS THEME
     ====================================================================== -->
<div class="modal fade" id="createCategoryModal" tabindex="-1" aria-labelledby="createCategoryModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="border-radius: 12px; border: 1px solid var(--border-light, #e2e8f0); box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); background-color: var(--bg-card, #ffffff);">
      
      <div class="modal-header" style="border-bottom: 1px solid var(--border-light, #f1f5f9); padding: 1.25rem 1.5rem; display: flex; align-items: center; justify-content: space-between;">
        <div style="display: flex; align-items: center; gap: 0.65rem;">
          <div style="width: 38px; height: 38px; border-radius: 8px; background: rgba(37, 99, 235, 0.1); color: var(--executive-blue, #2563eb); display: flex; align-items: center; justify-content: center; font-size: 1.1rem;">
            <i class="fa-solid fa-folder-plus"></i>
          </div>
          <div>
            <h5 class="modal-title" id="createCategoryModalLabel" style="font-size: 1.05rem; font-weight: 800; color: var(--text-title, #0f172a); margin: 0;">
              Tạo danh mục chuyên đề
            </h5>
            <p style="font-size: 0.75rem; color: var(--text-muted, #64748b); margin: 0;">
              Thêm chủ đề thảo luận mới cho cộng đồng doanh nghiệp
            </p>
          </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng" style="font-size: 0.8rem;"></button>
      </div>

      <form action="{{ Route::has('categories.store') ? route('categories.store') : url('/danh-muc') }}" method="POST" id="createCategoryModalForm">
        @csrf
        <div class="modal-body" style="padding: 1.5rem;">
          <!-- Tên danh mục -->
          <div style="margin-bottom: 1.25rem;">
            <label for="catModalNameInput" style="display: block; font-size: 0.82rem; font-weight: 700; color: var(--text-title, #0f172a); margin-bottom: 0.45rem;">
              Tên danh mục <span style="color: #ef4444;">*</span>
            </label>
            <input type="text" id="catModalNameInput" name="name" required placeholder="Ví dụ: Chuyển đổi số & AI, Bất động sản công nghiệp..." style="width: 100%; padding: 0.6rem 0.85rem; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.88rem; outline: none; background: var(--bg-card, #ffffff); color: var(--text-title, #0f172a); transition: border-color 0.2s;">
          </div>

          <!-- Màu sắc nhận diện -->
          <div style="margin-bottom: 1.25rem;">
            <label style="display: block; font-size: 0.82rem; font-weight: 700; color: var(--text-title, #0f172a); margin-bottom: 0.45rem;">
              Màu nhận diện danh mục
            </label>
            <div style="display: flex; align-items: center; gap: 0.75rem;">
              <input type="color" id="catModalColorInput" name="color" value="#2563eb" style="width: 44px; height: 38px; border: 1px solid #cbd5e1; border-radius: 6px; padding: 2px; cursor: pointer; background: #fff;">
              <div style="display: flex; gap: 0.45rem; flex-wrap: wrap;">
                <button type="button" class="btn-cat-color-choice" data-color="#2563eb" style="width: 24px; height: 24px; border-radius: 50%; background: #2563eb; border: 2px solid #fff; box-shadow: 0 0 0 1px #cbd5e1; cursor: pointer;" title="Executive Blue"></button>
                <button type="button" class="btn-cat-color-choice" data-color="#16a34a" style="width: 24px; height: 24px; border-radius: 50%; background: #16a34a; border: 2px solid #fff; box-shadow: 0 0 0 1px #cbd5e1; cursor: pointer;" title="Emerald Green"></button>
                <button type="button" class="btn-cat-color-choice" data-color="#d97706" style="width: 24px; height: 24px; border-radius: 50%; background: #d97706; border: 2px solid #fff; box-shadow: 0 0 0 1px #cbd5e1; cursor: pointer;" title="Amber Gold"></button>
                <button type="button" class="btn-cat-color-choice" data-color="#7c3aed" style="width: 24px; height: 24px; border-radius: 50%; background: #7c3aed; border: 2px solid #fff; box-shadow: 0 0 0 1px #cbd5e1; cursor: pointer;" title="Tech Purple"></button>
                <button type="button" class="btn-cat-color-choice" data-color="#0d9488" style="width: 24px; height: 24px; border-radius: 50%; background: #0d9488; border: 2px solid #fff; box-shadow: 0 0 0 1px #cbd5e1; cursor: pointer;" title="Teal ESG"></button>
                <button type="button" class="btn-cat-color-choice" data-color="#e11d48" style="width: 24px; height: 24px; border-radius: 50%; background: #e11d48; border: 2px solid #fff; box-shadow: 0 0 0 1px #cbd5e1; cursor: pointer;" title="Rose Red"></button>
                <button type="button" class="btn-cat-color-choice" data-color="#0284c7" style="width: 24px; height: 24px; border-radius: 50%; background: #0284c7; border: 2px solid #fff; box-shadow: 0 0 0 1px #cbd5e1; cursor: pointer;" title="Sky Blue"></button>
              </div>
            </div>
          </div>

          <!-- Mô tả định hướng -->
          <div>
            <label for="catModalDescInput" style="display: block; font-size: 0.82rem; font-weight: 700; color: var(--text-title, #0f172a); margin-bottom: 0.45rem;">
              Mô tả ngắn chuyên mục (Không bắt buộc)
            </label>
            <textarea id="catModalDescInput" name="description" rows="2" placeholder="Tóm tắt nội dung và định hướng bài viết trong danh mục..." style="width: 100%; padding: 0.6rem 0.85rem; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.85rem; outline: none; background: var(--bg-card, #ffffff); color: var(--text-title, #0f172a); transition: border-color 0.2s;"></textarea>
          </div>
        </div>

        <div class="modal-footer" style="border-top: 1px solid var(--border-light, #f1f5f9); padding: 1rem 1.5rem; display: flex; justify-content: flex-end; gap: 0.75rem;">
          <button type="button" class="btn btn-light" data-bs-dismiss="modal" style="font-size: 0.85rem; font-weight: 600; padding: 0.5rem 1rem; border-radius: 6px; color: var(--text-muted, #64748b);">
            Hủy bỏ
          </button>
          <button type="submit" class="btn btn-primary" style="background-color: var(--executive-blue, #2563eb); border: none; font-size: 0.85rem; font-weight: 700; padding: 0.5rem 1.35rem; border-radius: 6px; display: flex; align-items: center; gap: 0.45rem;">
            <i class="fa-solid fa-plus"></i> Tạo danh mục
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- JS moved to public/js/main.js --}}
