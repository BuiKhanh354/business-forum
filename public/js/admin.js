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

    // Hàm mở popup Chỉnh sửa bài viết và đổ dữ liệu
    window.openEditPostModal = function(btn) {
        if (!btn) return;

        const updateUrl = btn.getAttribute('data-update-url');
        const title = btn.getAttribute('data-title');
        const categoryId = btn.getAttribute('data-category-id');
        const excerpt = btn.getAttribute('data-excerpt');
        const content = btn.getAttribute('data-content');
        const cover = btn.getAttribute('data-cover');
        const readingTime = btn.getAttribute('data-reading-time');
        const isPublished = btn.getAttribute('data-published') === '1';
        const isFeatured = btn.getAttribute('data-featured') === '1';
        const link = btn.getAttribute('data-link');
        const linkTitle = btn.getAttribute('data-link-title');
        const imagesData = btn.getAttribute('data-images');

        const editForm = document.getElementById('editPostForm');
        const editTitle = document.getElementById('editPostTitle');
        const editCategory = document.getElementById('editPostCategory');
        const editExcerpt = document.getElementById('editPostExcerpt');
        const editContent = document.getElementById('editPostContent');
        const editReadingTime = document.getElementById('editPostReadingTime');
        const editPublished = document.getElementById('editPostPublished');
        const editFeatured = document.getElementById('editPostFeatured');
        const editLinkTitle = document.getElementById('editPostLinkTitle');
        const editLink = document.getElementById('editPostLink');

        if (editForm) editForm.action = updateUrl;
        if (editTitle) editTitle.value = title || '';
        if (editCategory) editCategory.value = categoryId || '';
        if (editExcerpt) editExcerpt.value = excerpt || '';
        if (editContent) editContent.value = content || '';
        if (editReadingTime) editReadingTime.value = readingTime || 5;
        if (editPublished) editPublished.checked = isPublished;
        if (editFeatured) editFeatured.checked = isFeatured;
        if (editLinkTitle) editLinkTitle.value = linkTitle || '';
        if (editLink) editLink.value = link || '';

        // Reset file input và hiển thị danh sách ảnh hiện tại (Tối đa 5 ảnh)
        const editCoverFileInput = document.getElementById('editCoverFileInput');
        if (editCoverFileInput) editCoverFileInput.value = '';

        const editCoverPreviewBox = document.getElementById('editCoverPreviewBox');
        const editCoverDropContent = document.getElementById('editCoverDropContent');
        const editImagesGrid = document.getElementById('editImagesGrid');
        const editBadge = document.getElementById('editCoverCountBadge');

        let images = [];
        try {
            images = JSON.parse(imagesData || '[]');
        } catch(e) {}
        if (!images.length && cover) {
            images = [cover];
        }

        if (images.length > 0 && editImagesGrid) {
            editImagesGrid.innerHTML = '';
            images.slice(0, 5).forEach((imgSrc, idx) => {
                const formattedSrc = (imgSrc.startsWith('http') || imgSrc.startsWith('/')) ? imgSrc : ('/' + imgSrc);
                const item = document.createElement('div');
                item.style.cssText = 'position: relative; border-radius: 6px; overflow: hidden; height: 105px; border: 1px solid #cbd5e1; background: #000; box-shadow: 0 2px 4px rgba(0,0,0,0.06);';
                item.innerHTML = `
                    <img src="${formattedSrc}" style="width: 100%; height: 100%; object-fit: cover;">
                    <span style="position: absolute; top: 4px; left: 4px; background: rgba(15, 23, 42, 0.85); color: #fff; font-size: 0.62rem; font-weight: 800; padding: 1px 5px; border-radius: 3px;">
                        #${idx + 1}
                    </span>
                    ${idx === 0 ? '<span style="position: absolute; bottom: 4px; left: 4px; background: #2563eb; color: #fff; font-size: 0.62rem; font-weight: 700; padding: 2px 6px; border-radius: 3px;"><i class="fa-solid fa-star"></i> Bìa</span>' : ''}
                `;
                editImagesGrid.appendChild(item);
            });
            if (editBadge) editBadge.textContent = images.length + ' / 5 ảnh';
            if (editCoverPreviewBox) editCoverPreviewBox.style.display = 'block';
            if (editCoverDropContent) editCoverDropContent.style.display = 'none';
        } else {
            if (editBadge) editBadge.textContent = '0 / 5 ảnh';
            if (editCoverPreviewBox) editCoverPreviewBox.style.display = 'none';
            if (editCoverDropContent) editCoverDropContent.style.display = 'block';
        }

        openModal('editPostModal');
    };

    // Hàm mở popup Xác nhận Xóa bài viết và đổ dữ liệu
    window.openDeletePostModal = function(btn) {
        if (!btn) return;
        const deleteUrl = btn.getAttribute('data-delete-url');
        const title = btn.getAttribute('data-title');
        const category = btn.getAttribute('data-category');
        const views = btn.getAttribute('data-views') || '0';
        const commentsCount = btn.getAttribute('data-comments-count') || '0';

        const form = document.getElementById('deletePostForm');
        const titleTarget = document.getElementById('deletePostTitleTarget');
        const metaTarget = document.getElementById('deletePostMetaTarget');

        if (form) form.action = deleteUrl;
        if (titleTarget) titleTarget.textContent = title || 'Bài viết';
        if (metaTarget) {
            metaTarget.innerHTML = `
                <span style="display: inline-flex; align-items: center; gap: 0.35rem;"><i class="fa-solid fa-folder" style="color: var(--executive-blue);"></i> ${category || 'Chưa phân loại'}</span>
                <span style="display: inline-flex; align-items: center; gap: 0.35rem;"><i class="fa-regular fa-eye"></i> ${views} lượt xem</span>
                <span style="display: inline-flex; align-items: center; gap: 0.35rem;"><i class="fa-regular fa-comment"></i> ${commentsCount} bình luận</span>
            `;
        }

        openModal('deletePostModal');
    };

    // Hàm hiển thị xem trước danh sách nhiều ảnh tải lên từ máy tính (Tối đa 5 ảnh)
    window.handleMultiSelect = function(input, previewBoxId, dropContentId, gridId, countBadgeId) {
        if (!input.files || input.files.length === 0) return;

        let files = Array.from(input.files);
        if (files.length > 5) {
            alert('Bạn chỉ được đăng tối đa 5 ảnh. Hệ thống đã tự động lấy 5 ảnh đầu tiên.');
            files = files.slice(0, 5);
            const dt = new DataTransfer();
            files.forEach(f => dt.items.add(f));
            input.files = dt.files;
        }

        const previewBox = document.getElementById(previewBoxId);
        const dropContent = document.getElementById(dropContentId);
        const grid = document.getElementById(gridId);
        const badge = document.getElementById(countBadgeId);

        if (grid) grid.innerHTML = '';
        if (badge) badge.textContent = files.length + ' / 5 ảnh';

        files.forEach((file, idx) => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const item = document.createElement('div');
                item.style.cssText = 'position: relative; border-radius: 6px; overflow: hidden; height: 105px; border: 1px solid #cbd5e1; background: #000; box-shadow: 0 2px 4px rgba(0,0,0,0.06);';
                item.innerHTML = `
                    <img src="${e.target.result}" style="width: 100%; height: 100%; object-fit: cover;">
                    <span style="position: absolute; top: 4px; left: 4px; background: rgba(15, 23, 42, 0.85); color: #fff; font-size: 0.62rem; font-weight: 800; padding: 1px 5px; border-radius: 3px;">
                        #${idx + 1}
                    </span>
                    ${idx === 0 ? '<span style="position: absolute; bottom: 4px; left: 4px; background: #2563eb; color: #fff; font-size: 0.62rem; font-weight: 700; padding: 2px 6px; border-radius: 3px;"><i class="fa-solid fa-star"></i> Bìa</span>' : ''}
                `;
                grid.appendChild(item);
            };
            reader.readAsDataURL(file);
        });

        if (previewBox) previewBox.style.display = 'block';
        if (dropContent) dropContent.style.display = 'none';
    };

    // Hàm hỗ trợ kéo thả nhiều ảnh từ máy tính
    window.handleMultiDrop = function(e, inputId, previewBoxId, dropContentId, gridId, countBadgeId) {
        e.preventDefault();
        e.stopPropagation();
        const input = document.getElementById(inputId);
        if (e.dataTransfer && e.dataTransfer.files.length > 0 && input) {
            let files = Array.from(e.dataTransfer.files);
            if (files.length > 5) {
                alert('Bạn chỉ được đăng tối đa 5 ảnh. Hệ thống đã tự động lấy 5 ảnh đầu tiên.');
                files = files.slice(0, 5);
            }
            const dt = new DataTransfer();
            files.forEach(f => dt.items.add(f));
            input.files = dt.files;
            handleMultiSelect(input, previewBoxId, dropContentId, gridId, countBadgeId);
        }
    };

    document.addEventListener('DOMContentLoaded', () => {
        // Đóng modal khi click ra vùng nền mờ bên ngoài hoặc nút đóng
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

        // Lọc nhanh bảng bài viết trực tiếp trên giao diện (Client-side live filter)
        const searchInput = document.getElementById('adminPostSearchInput');
        const rows = document.querySelectorAll('.admin-post-row');

        if (searchInput && rows.length > 0) {
            searchInput.addEventListener('input', (e) => {
                const q = e.target.value.toLowerCase().trim();
                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    row.style.display = (!q || text.includes(q)) ? '' : 'none';
                });
            });
        }
    });