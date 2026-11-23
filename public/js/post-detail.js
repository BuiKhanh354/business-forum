window.openLightbox = function(src) {
  const modal = document.getElementById('imageLightboxModal');
  const img = document.getElementById('lightboxImg');
  if (modal && img) {
    img.src = src;
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
  }
};

// Hàm Thích / Bỏ thích bình luận ngoài trang bài viết
window.toggleCommentLikeFront = function(commentId, btn) {
  if (!commentId || !btn) return;
  if (btn.dataset.loading === 'true') return;
  btn.dataset.loading = 'true';

  const csrfMeta = document.querySelector('meta[name="csrf-token"]');
  const token = csrfMeta ? csrfMeta.getAttribute('content') : '';

  fetch('/binh-luan/' + commentId + '/thich', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': token,
      'Accept': 'application/json'
    }
  })
  .then(res => res.json())
  .then(data => {
    btn.dataset.loading = 'false';
    if (data.success) {
      const icon = btn.querySelector('i');
      const countSpan = btn.querySelector('.comment-like-count');
      const badge = document.getElementById('authorHeartBadge-' + commentId);

      if (data.is_liked) {
        btn.classList.add('liked');
        if (icon) {
          icon.className = 'fa-solid fa-heart';
        }
      } else {
        btn.classList.remove('liked');
        if (icon) {
          icon.className = 'fa-regular fa-heart';
        }
      }

      if (countSpan) {
        countSpan.textContent = data.likes_count > 0 ? data.likes_count : '';
      }

      if (badge) {
        badge.style.display = data.author_liked ? 'inline-flex' : 'none';
      }
    }
  })
  .catch(err => {
    console.error('Error toggling comment like:', err);
    btn.dataset.loading = 'false';
  });
};
