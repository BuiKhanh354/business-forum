window.openViewCommentModal = function(author, postTitle, content, time, postUrl) {
    document.getElementById('modalCommentAuthor').textContent = author;
    document.getElementById('modalCommentPost').textContent = postTitle;
    document.getElementById('modalCommentContent').textContent = content;
    document.getElementById('modalCommentTime').textContent = time;
    document.getElementById('modalPostLink').href = postUrl;

    const modal = document.getElementById('viewCommentModal');
    if (modal) {
      modal.style.display = 'flex';
      document.body.style.overflow = 'hidden';
    }
  };

  window.closeViewCommentModal = function() {
    const modal = document.getElementById('viewCommentModal');
    if (modal) {
      modal.style.display = 'none';
      document.body.style.overflow = '';
    }
  };

  // Hàm mở popup Xác nhận Xóa bình luận và đổ dữ liệu
  window.openDeleteCommentModal = function(btn) {
    if (!btn) return;
    const deleteUrl = btn.getAttribute('data-delete-url');
    const author = btn.getAttribute('data-author');
    const post = btn.getAttribute('data-post');
    const content = btn.getAttribute('data-content');

    const form = document.getElementById('deleteCommentForm');
    const authorTarget = document.getElementById('deleteCommentAuthorTarget');
    const postTarget = document.getElementById('deleteCommentPostTarget');
    const contentTarget = document.getElementById('deleteCommentContentTarget');
    const modal = document.getElementById('deleteCommentModal');

    if (form) form.action = deleteUrl;
    if (authorTarget) authorTarget.textContent = author || 'Độc giả';
    if (postTarget) postTarget.textContent = post || 'Bài viết';
    if (contentTarget) contentTarget.textContent = content ? `"${content}"` : '';

    if (modal) {
      modal.classList.add('show', 'active');
      modal.style.display = 'flex';
      document.body.style.overflow = 'hidden';
    }
  };

  // Hàm đóng popup Xác nhận Xóa bình luận
  window.closeDeleteCommentModal = function() {
    const modal = document.getElementById('deleteCommentModal');
    if (modal) {
      modal.classList.remove('show', 'active');
      modal.style.display = 'none';
      document.body.style.overflow = '';
    }
  };

  document.addEventListener('DOMContentLoaded', () => {
    // Live filter client-side cho bảng bình luận
    const filterInput = document.getElementById('commentFilterInput');
    const catButtons = document.querySelectorAll('.cat-pill[data-cat]');
    const rows = document.querySelectorAll('.comment-row');

    let currentCategory = 'all';

    function applyFilter() {
      const q = filterInput ? filterInput.value.toLowerCase().trim() : '';
      let visibleCount = 0;

      rows.forEach(row => {
        const rowText = row.textContent.toLowerCase();
        const rowCat = (row.getAttribute('data-category') || '').toLowerCase();
        const isLiked = row.getAttribute('data-is-liked') === 'true';

        const matchSearch = !q || rowText.includes(q);
        let matchCategory = false;

        if (currentCategory === 'all') {
          matchCategory = true;
        } else if (currentCategory === 'liked') {
          matchCategory = isLiked;
        } else {
          matchCategory = rowCat.includes(currentCategory);
        }

        const isVisible = matchSearch && matchCategory;
        row.style.display = isVisible ? '' : 'none';
        if (isVisible) visibleCount++;
      });

      const totalCountText = document.getElementById('totalCommentsCountText');
      if (totalCountText) {
        if (currentCategory === 'liked') {
          totalCountText.textContent = 'Hiển thị: ' + visibleCount + ' bình luận đã thích';
        } else if (currentCategory === 'all' && !q) {
          const total = totalCountText.getAttribute('data-total') || visibleCount;
          totalCountText.textContent = 'Tổng số: ' + total + ' bình luận';
        } else {
          totalCountText.textContent = 'Tìm thấy: ' + visibleCount + ' bình luận';
        }
      }
    }

    window.filterLikedComments = function() {
      const btnLiked = document.getElementById('btnFilterLiked');
      if (btnLiked) {
        catButtons.forEach(b => b.classList.remove('active'));
        btnLiked.classList.add('active');
        currentCategory = 'liked';
        applyFilter();
      }
    };

    window.toggleCommentLike = function(commentId, btn) {
      const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
      
      fetch('/admin/binh-luan/' + commentId + '/thich', {
        method: 'PATCH',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': token,
          'Accept': 'application/json'
        }
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          const row = btn.closest('tr');
          const isLiked = data.is_liked;
          row.setAttribute('data-is-liked', isLiked ? 'true' : 'false');

          const icon = btn.querySelector('i');
          const label = btn.querySelector('.like-btn-label');

          if (isLiked) {
            btn.classList.add('liked');
            btn.style.color = '#e11d48';
            btn.style.borderColor = '#fecdd3';
            btn.style.background = '#fff1f2';
            if (icon) icon.className = 'fa-solid fa-heart';
            if (label) label.textContent = 'Đã thích';
            btn.title = 'Bỏ thích bình luận';
          } else {
            btn.classList.remove('liked');
            btn.style.color = 'var(--text-muted)';
            btn.style.borderColor = '';
            btn.style.background = '#ffffff';
            if (icon) icon.className = 'fa-regular fa-heart';
            if (label) label.textContent = 'Thích';
            btn.title = 'Thích bình luận';
          }

          // Cập nhật số đếm trên thẻ thống kê & nút lọc
          const statLiked = document.getElementById('statLikedComments');
          const pillLiked = document.getElementById('pillLikedCount');
          if (statLiked) statLiked.textContent = data.total_liked;
          if (pillLiked) pillLiked.textContent = data.total_liked;

          if (currentCategory === 'liked' && !isLiked) {
            row.style.display = 'none';
          }
        }
      })
      .catch(err => {
        console.error('Lỗi toggle like bình luận:', err);
      });
    };

    if (filterInput) {
      filterInput.addEventListener('input', applyFilter);
    }

    catButtons.forEach(btn => {
      btn.addEventListener('click', () => {
        catButtons.forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        currentCategory = btn.getAttribute('data-cat') || 'all';
        applyFilter();
      });
    });

    // Đóng popup khi click ra ngoài vùng mờ hoặc nút close
    document.addEventListener('click', function(e) {
      if (e.target.id === 'deleteCommentModal' || e.target.id === 'viewCommentModal' || e.target.classList.contains('modal')) {
        closeDeleteCommentModal();
        closeViewCommentModal();
      }
      if (e.target.closest('[data-bs-dismiss="modal"]') || e.target.closest('.btn-close-modal')) {
        closeDeleteCommentModal();
        closeViewCommentModal();
      }
    });

    // Đóng popup khi bấm phím ESC
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape') {
        closeDeleteCommentModal();
        closeViewCommentModal();
      }
    });

    // Tự động mở modal chi tiết bình luận nếu có thẻ dữ liệu #autoOpenCommentData
    const autoOpenEl = document.getElementById('autoOpenCommentData');
    if (autoOpenEl) {
      setTimeout(() => {
        if (typeof openViewCommentModal === 'function') {
          openViewCommentModal(
            autoOpenEl.getAttribute('data-author') || '',
            autoOpenEl.getAttribute('data-post') || '',
            autoOpenEl.getAttribute('data-content') || '',
            autoOpenEl.getAttribute('data-time') || '',
            autoOpenEl.getAttribute('data-url') || '#'
          );
        }
      }, 150);
    }
  });