// Hàm mở popup modal trực tiếp
    window.openModal = function(modalId) {
        const m = document.getElementById(modalId);
        if (m) {
            m.classList.add('show', 'active');
            m.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }
    };

    // Hàm đóng popup modal
    window.closeModal = function(modalId) {
        const m = document.getElementById(modalId);
        if (m) {
            m.classList.remove('show', 'active');
            m.style.display = 'none';
            document.body.style.overflow = '';
        }
    };

    // Hàm mở popup Chỉnh sửa danh mục và đổ dữ liệu
    window.openEditCategoryModal = function(btn) {
        if (!btn) return;
        const updateUrl = btn.getAttribute('data-update-url');
        const name = btn.getAttribute('data-name');
        const color = btn.getAttribute('data-color');
        const description = btn.getAttribute('data-description');

        const form = document.getElementById('editCategoryForm');
        const nameInput = document.getElementById('editCategoryName');
        const descInput = document.getElementById('editCategoryDescription');
        const editCatColorInput = document.getElementById('editCategoryColor');
        const editCatHexText = document.getElementById('editCategoryColorHex');

        if (form) form.action = updateUrl;
        if (nameInput) nameInput.value = name || '';
        if (descInput) descInput.value = description || '';
        if (editCatColorInput && color) {
            editCatColorInput.value = color;
            if (editCatHexText) editCatHexText.textContent = color.toUpperCase();
        }

        openModal('editCategoryModal');
    };

    // Hàm mở popup Xác nhận Xóa danh mục và đổ dữ liệu
    window.openDeleteCategoryModal = function(btn) {
        if (!btn) return;
        const deleteUrl = btn.getAttribute('data-delete-url');
        const name = btn.getAttribute('data-name');
        const postsCount = btn.getAttribute('data-posts-count') || '0';

        const form = document.getElementById('deleteCategoryForm');
        const nameTarget = document.getElementById('deleteCategoryNameTarget');
        const warningBox = document.getElementById('deleteCategoryPostWarning');

        if (form) form.action = deleteUrl;
        if (nameTarget) nameTarget.textContent = name || 'này';

        if (warningBox) {
            const count = parseInt(postsCount) || 0;
            if (count > 0) {
                warningBox.innerHTML = '<i class="fa-solid fa-triangle-exclamation" style="color: #d97706; margin-right: 6px;"></i> Danh mục này hiện đang liên kết với <strong>' + count + ' bài viết</strong>. Sau khi xóa, các bài viết sẽ được chuyển về trạng thái <em>Chưa phân loại</em>.';
                warningBox.style.display = 'block';
            } else {
                warningBox.style.display = 'none';
            }
        }

        openModal('deleteCategoryModal');
    };

    document.addEventListener('DOMContentLoaded', () => {
        // 1. Color picker ở form tạo mới
        const picker = document.getElementById('categoryColorPicker');
        const hexText = document.getElementById('categoryColorHexText');
        const colorChoices = document.querySelectorAll('.cat-color-choice');

        if (picker && hexText) {
            picker.addEventListener('input', (e) => {
                hexText.textContent = e.target.value.toUpperCase();
            });
        }

        colorChoices.forEach(choice => {
            choice.addEventListener('click', () => {
                const color = choice.getAttribute('data-color');
                if (picker && color) {
                    picker.value = color;
                    if (hexText) hexText.textContent = color.toUpperCase();
                }
            });
        });

        // 2. Color picker ở popup sửa
        const editCatColorInput = document.getElementById('editCategoryColor');
        const editCatHexText = document.getElementById('editCategoryColorHex');
        const editColorChoices = document.querySelectorAll('.edit-cat-color-choice');

        if (editCatColorInput && editCatHexText) {
            editCatColorInput.addEventListener('input', (e) => {
                editCatHexText.textContent = e.target.value.toUpperCase();
            });
        }

        editColorChoices.forEach(choice => {
            choice.addEventListener('click', () => {
                const c = choice.getAttribute('data-color');
                if (editCatColorInput && c) {
                    editCatColorInput.value = c;
                    if (editCatHexText) editCatHexText.textContent = c.toUpperCase();
                }
            });
        });

        // Đóng modal khi bấm ra ngoài nền hoặc nút close
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('modal') || e.target.classList.contains('modal-overlay')) {
                e.target.classList.remove('show', 'active');
                e.target.style.display = 'none';
                document.body.style.overflow = '';
            }
            if (e.target.closest('[data-bs-dismiss="modal"]') || e.target.closest('.btn-close-modal')) {
                const modal = e.target.closest('.modal') || e.target.closest('.modal-overlay');
                if (modal) {
                    modal.classList.remove('show', 'active');
                    modal.style.display = 'none';
                    document.body.style.overflow = '';
                }
            }
        });

        // Đóng modal khi bấm phím ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                document.querySelectorAll('.modal.show, .modal-overlay.show').forEach(m => {
                    m.classList.remove('show', 'active');
                    m.style.display = 'none';
                    document.body.style.overflow = '';
                });
            }
        });
    });