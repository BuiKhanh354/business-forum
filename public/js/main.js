/**
 * KẾT NỐI BUSINESS COMMUNITY - CORE JAVASCRIPT (MAIN.JS)
 * Quản lý các tương tác giao diện dùng chung toàn hệ thống
 */

// Đường dẫn gốc tài nguyên (Asset Base URL)
window.assetBaseUrl = window.assetBaseUrl || document.querySelector('meta[name="asset-url"]')?.getAttribute('content') || '/';

window.resolveAssetUrl = function (path) {
  if (!path) return '';
  if (path.startsWith('http://') || path.startsWith('https://') || path.startsWith('//')) {
    return path;
  }
  const base = window.assetBaseUrl || '/';
  return base.replace(/\/+$/, '') + '/' + path.replace(/^\/+/, '');
};

// Hàm mở Modal Median UI toàn hệ thống
window.openModal = function (modalId) {
  const modal = document.getElementById(modalId);
  if (modal) {
    modal.classList.add('show', 'active');
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
  }
};

// Hàm đóng Modal Median UI
window.closeModal = function (modalId) {
  const modal = document.getElementById(modalId);
  if (modal) {
    modal.classList.remove('show', 'active');
    modal.style.display = 'none';
    document.body.style.overflow = '';
  }
};

// Đóng modal khi bấm vào nền mờ (Overlay)
document.addEventListener('click', function (e) {
  if (e.target.classList && e.target.classList.contains('modal-overlay')) {
    const activeModal = e.target.closest('.modal');
    if (activeModal && activeModal.id) {
      window.closeModal(activeModal.id);
    }
  }
});

// Hàm đổi theme giao diện Sáng / Tối toàn hệ thống
window.toggleSiteTheme = function () {
  const isCurrentlyDark = document.documentElement.getAttribute('data-theme') === 'dark' || (document.body && document.body.classList.contains('theme-dark'));
  const targetMode = isCurrentlyDark ? 'light' : 'dark';

  if (targetMode === 'light') {
    document.documentElement.removeAttribute('data-theme');
    if (document.body) document.body.classList.remove('theme-dark');
    localStorage.setItem('site-mode', 'light');
  } else {
    document.documentElement.setAttribute('data-theme', 'dark');
    if (document.body) document.body.classList.add('theme-dark');
    document.documentElement.style.removeProperty('--bg-app');
    document.documentElement.style.removeProperty('--bg-card');
    localStorage.setItem('site-mode', 'dark');
  }

  // Cập nhật icon trên tất cả nút bấm
  const isDarkNow = targetMode === 'dark';
  document.querySelectorAll('#themeToggleBtn, #mobileThemeToggleBtn').forEach(function (btn) {
    const icon = btn.querySelector('i');
    if (icon) {
      icon.className = isDarkNow ? 'fa-solid fa-sun' : 'fa-solid fa-moon';
    }
  });

  // Đồng bộ với session và database
  const csrfMeta = document.querySelector('meta[name="csrf-token"]');
  const token = csrfMeta ? csrfMeta.getAttribute('content') : '';

  fetch('/cai-dat-giao-dien/chuyen-giao-dien-nhanh', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': token,
      'Accept': 'application/json'
    },
    body: JSON.stringify({ mode: targetMode })
  }).catch(function () {});
};

// Khởi tạo các sự kiện giao diện khi DOM sẵn sàng
document.addEventListener('DOMContentLoaded', function () {
  // 0. Chuyển đổi giao diện Sáng / Tối (Đồng bộ LocalStorage & Database không bị quay lại Dark)
  const themeToggleBtn = document.getElementById('themeToggleBtn');
  const mobileThemeToggleBtn = document.getElementById('mobileThemeToggleBtn');

  const savedSiteMode = localStorage.getItem('site-mode');
  const isDarkInitial = savedSiteMode === 'dark' || (savedSiteMode !== 'light' && (document.documentElement.getAttribute('data-theme') === 'dark' || document.body.classList.contains('theme-dark')));
  
  // Cập nhật trạng thái icon ban đầu
  document.querySelectorAll('#themeToggleBtn, #mobileThemeToggleBtn').forEach(function (btn) {
    const icon = btn.querySelector('i');
    if (icon) {
      icon.className = isDarkInitial ? 'fa-solid fa-sun' : 'fa-solid fa-moon';
    }
  });

  if (themeToggleBtn) {
    themeToggleBtn.onclick = function (e) {
      e.preventDefault();
      window.toggleSiteTheme();
    };
  }
  if (mobileThemeToggleBtn) {
    mobileThemeToggleBtn.onclick = function (e) {
      e.preventDefault();
      window.toggleSiteTheme();
    };
  }

  // 1. Tự động gắn sự kiện nút đóng modal có data-dismiss="modal"
  document.querySelectorAll('[data-dismiss="modal"]').forEach(function (btn) {
    btn.addEventListener('click', function () {
      const modal = btn.closest('.modal');
      if (modal && modal.id) {
        window.closeModal(modal.id);
      }
    });
  });

  // 2. Xử lý bảng màu nhanh cho Modal tạo danh mục (components/modal-create-category.blade.php)
  const modalColorInput = document.getElementById('catModalColorInput');
  const colorChoices = document.querySelectorAll('.btn-cat-color-choice');
  if (colorChoices.length && modalColorInput) {
    colorChoices.forEach(function (btn) {
      btn.addEventListener('click', function () {
        const c = btn.getAttribute('data-color');
        if (c) {
          modalColorInput.value = c;
        }
      });
    });
  }

  // 3. Xử lý mở/thu gọn Submenu "Chủ đề bài viết" trong Sidebar
  const catToggle = document.getElementById('categoriesSubmenuToggle');
  const catMenu = document.getElementById('categoriesSubmenu');
  if (catToggle && catMenu) {
    catToggle.addEventListener('click', function (e) {
      e.preventDefault();
      const isExpanded = catMenu.style.display !== 'none';
      catMenu.style.display = isExpanded ? 'none' : 'block';
      const chevron = catToggle.querySelector('.fa-chevron-down, .fa-chevron-right');
      if (chevron) {
        chevron.classList.toggle('fa-chevron-down', !isExpanded);
        chevron.classList.toggle('fa-chevron-right', isExpanded);
      }
    });
  }

  // 3.1. Quản lý Drawer Sidebar trượt trên Mobile (Không nằm đè lên trên cùng)
  const btnMobileSidebarToggle = document.getElementById('btnMobileSidebarToggle');
  const btnCloseSidebarMobile = document.getElementById('btnCloseSidebarMobile');
  const sidebarBackdrop = document.getElementById('sidebarBackdrop');
  const mobileTopicsBtn = document.getElementById('mobileTopicsBtn');
  const appSidebar = document.querySelector('.app-sidebar');

  window.openMobileSidebar = function () {
    document.body.classList.add('sidebar-open');
    if (appSidebar) appSidebar.classList.add('show-mobile');
    if (sidebarBackdrop) sidebarBackdrop.classList.add('active');
  };

  window.closeMobileSidebar = function () {
    document.body.classList.remove('sidebar-open');
    if (appSidebar) appSidebar.classList.remove('show-mobile');
    if (sidebarBackdrop) sidebarBackdrop.classList.remove('active');
  };

  if (btnMobileSidebarToggle) {
    btnMobileSidebarToggle.addEventListener('click', function (e) {
      e.stopPropagation();
      window.openMobileSidebar();
    });
  }

  if (btnCloseSidebarMobile) {
    btnCloseSidebarMobile.addEventListener('click', function (e) {
      e.stopPropagation();
      window.closeMobileSidebar();
    });
  }

  if (sidebarBackdrop) {
    sidebarBackdrop.addEventListener('click', function () {
      window.closeMobileSidebar();
    });
  }

  if (mobileTopicsBtn) {
    mobileTopicsBtn.addEventListener('click', function (e) {
      e.preventDefault();
      e.stopPropagation();
      window.openMobileSidebar();
    });
  }

  // Tự động đóng sidebar drawer khi click vào liên kết điều hướng trên mobile
  if (appSidebar) {
    appSidebar.querySelectorAll('a').forEach(function (link) {
      link.addEventListener('click', function () {
        if (window.innerWidth <= 768) {
          window.closeMobileSidebar();
        }
      });
    });
  }

  // 4. Hỗ trợ phím Escape để đóng nhanh bất kỳ modal đang mở & Hotkey truy cập Quản trị (Ctrl + Shift + A)
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
      const openModals = document.querySelectorAll('.modal.show, .modal.active');
      openModals.forEach(function (m) {
        if (m.id) window.closeModal(m.id);
      });
    }

    // Phím tắt bí mật dành riêng cho Admin: Ctrl + Shift + A hoặc Ctrl + Alt + A để mở trang đăng nhập
    if ((e.ctrlKey && e.shiftKey && (e.key === 'A' || e.key === 'a')) ||
        (e.ctrlKey && e.altKey && (e.key === 'A' || e.key === 'a'))) {
      e.preventDefault();
      window.location.href = '/dang-nhap';
    }
  });

  // Lối vào bí mật dành riêng cho Admin: Nhấp đúp (Double-click) vào Logo thương hiệu để mở trang đăng nhập
  const brandLogos = document.querySelectorAll('.brand-link, .mobile-topbar-brand');
  brandLogos.forEach(function (logo) {
    logo.addEventListener('dblclick', function (e) {
      e.preventDefault();
      window.location.href = '/dang-nhap';
    });
  });

  // 5. Tính năng Sắp xếp bài viết (Feed Sort Dropdown)
  const sortBtn = document.getElementById('feedSortBtn');
  const sortDropdown = document.getElementById('feedSortDropdown');
  const sortLabel = document.getElementById('currentSortLabel');
  const sortHeading = document.getElementById('feedTitleHeading');
  const postsList = document.getElementById('postsList');

  if (sortBtn && sortDropdown && postsList) {
    sortBtn.addEventListener('click', function (e) {
      e.stopPropagation();
      const isOpen = sortDropdown.style.display === 'block';
      sortDropdown.style.display = isOpen ? 'none' : 'block';
      sortBtn.setAttribute('aria-expanded', !isOpen);
      const chevron = document.getElementById('feedSortChevron');
      if (chevron) chevron.style.transform = isOpen ? '' : 'rotate(180deg)';
    });

    document.addEventListener('click', function (e) {
      if (!sortDropdown.contains(e.target) && !sortBtn.contains(e.target)) {
        sortDropdown.style.display = 'none';
        sortBtn.setAttribute('aria-expanded', 'false');
        const chevron = document.getElementById('feedSortChevron');
        if (chevron) chevron.style.transform = '';
      }
    });

    const sortTitles = {
      latest: 'Bài viết chia sẻ mới nhất',
      views: 'Bài viết được xem nhiều nhất',
      comments: 'Bài viết nhiều bình luận nhất',
      featured: 'Bài viết nổi bật (Nhiều tim nhất)'
    };

    const sortButtons = sortDropdown.querySelectorAll('.sort-opt-btn');
    sortButtons.forEach(function (btn) {
      btn.addEventListener('click', function () {
        const sortType = btn.getAttribute('data-sort');
        const spanText = btn.querySelector('span');
        const sortText = spanText ? spanText.textContent.trim() : btn.textContent.trim();

        sortButtons.forEach(function (b) { b.classList.remove('active'); });
        btn.classList.add('active');

        if (sortLabel) sortLabel.textContent = sortText;
        if (sortHeading && sortTitles[sortType]) sortHeading.textContent = sortTitles[sortType];

        sortDropdown.style.display = 'none';
        sortBtn.setAttribute('aria-expanded', 'false');
        const chevron = document.getElementById('feedSortChevron');
        if (chevron) chevron.style.transform = '';

        // Thực hiện sắp xếp DOM bài viết
        const cards = Array.from(postsList.querySelectorAll('.post-card'));
        cards.sort(function (a, b) {
          if (sortType === 'latest') {
            const tA = parseInt(a.getAttribute('data-created') || '0', 10);
            const tB = parseInt(b.getAttribute('data-created') || '0', 10);
            if (tB !== tA) return tB - tA;
            return parseInt(b.getAttribute('data-id') || '0', 10) - parseInt(a.getAttribute('data-id') || '0', 10);
          } else if (sortType === 'views') {
            const vA = parseInt(a.getAttribute('data-views') || '0', 10);
            const vB = parseInt(b.getAttribute('data-views') || '0', 10);
            if (vB !== vA) return vB - vA;
            return parseInt(b.getAttribute('data-created') || '0', 10) - parseInt(a.getAttribute('data-created') || '0', 10);
          } else if (sortType === 'comments') {
            const cA = parseInt(a.getAttribute('data-comments') || '0', 10);
            const cB = parseInt(b.getAttribute('data-comments') || '0', 10);
            if (cB !== cA) return cB - cA;
            return parseInt(b.getAttribute('data-created') || '0', 10) - parseInt(a.getAttribute('data-created') || '0', 10);
          } else if (sortType === 'featured') {
            const lA = parseInt(a.getAttribute('data-likes') || '0', 10);
            const lB = parseInt(b.getAttribute('data-likes') || '0', 10);
            if (lB !== lA) return lB - lA;
            const fA = a.getAttribute('data-featured') === '1' ? 1 : 0;
            const fB = b.getAttribute('data-featured') === '1' ? 1 : 0;
            if (fB !== fA) return fB - fA;
            return parseInt(b.getAttribute('data-created') || '0', 10) - parseInt(a.getAttribute('data-created') || '0', 10);
          }
          return 0;
        });

        cards.forEach(function (card) {
          postsList.appendChild(card);
        });
      });
    });
  }

  // 6. Tính năng Danh mục thu gọn (+n chuyên mục)
  const btnCatMore = document.getElementById('btnCatMore');
  const catMoreDropdown = document.getElementById('catMoreDropdown');
  const catMoreLabel = document.getElementById('catMoreBtnLabel');

  if (btnCatMore && catMoreDropdown) {
    const defaultMoreText = catMoreLabel ? catMoreLabel.textContent.trim() : '+ Chuyên mục';

    btnCatMore.addEventListener('click', function (e) {
      e.stopPropagation();
      const isOpen = catMoreDropdown.style.display === 'block';
      catMoreDropdown.style.display = isOpen ? 'none' : 'block';
      const chevron = btnCatMore.querySelector('.fa-chevron-down');
      if (chevron) chevron.style.transform = isOpen ? '' : 'rotate(180deg)';
    });

    document.addEventListener('click', function (e) {
      if (!catMoreDropdown.contains(e.target) && !btnCatMore.contains(e.target)) {
        catMoreDropdown.style.display = 'none';
        const chevron = btnCatMore.querySelector('.fa-chevron-down');
        if (chevron) chevron.style.transform = '';
      }
    });

    catMoreDropdown.querySelectorAll('.cat-dropdown-item').forEach(function (item) {
      item.addEventListener('click', function () {
        catMoreDropdown.style.display = 'none';
        const chevron = btnCatMore.querySelector('.fa-chevron-down');
        if (chevron) chevron.style.transform = '';

        catMoreDropdown.querySelectorAll('.cat-dropdown-item').forEach(function (i) { i.classList.remove('active'); });
        item.classList.add('active');

        btnCatMore.classList.add('active');
        if (catMoreLabel) {
          const nameSpan = item.querySelector('span:nth-child(2)');
          catMoreLabel.textContent = nameSpan ? nameSpan.textContent.trim() : item.getAttribute('data-name');
        }
      });
    });

    document.querySelectorAll('#categoryPillsList > .cat-pill').forEach(function (pill) {
      pill.addEventListener('click', function () {
        btnCatMore.classList.remove('active');
        catMoreDropdown.querySelectorAll('.cat-dropdown-item').forEach(function (i) { i.classList.remove('active'); });
        if (catMoreLabel) catMoreLabel.textContent = defaultMoreText;
      });
    });
  }

  // 7. Đồng bộ trạng thái Thích bài viết từ localStorage (nếu có)
  try {
    const localLikes = JSON.parse(localStorage.getItem('business_forum_liked_posts') || localStorage.getItem('vlog_liked_posts') || '[]');
    if (Array.isArray(localLikes)) {
      localLikes.forEach(function (id) {
        document.querySelectorAll('.like-btn[data-post-id="' + id + '"]').forEach(function (btn) {
          if (!btn.classList.contains('liked')) {
            btn.classList.add('liked');
            const heart = btn.querySelector('i.fa-heart');
            if (heart) {
              heart.classList.remove('fa-regular');
              heart.classList.add('fa-solid');
              heart.style.color = '#ef4444';
            }
          }
        });
      });
    }
  } catch (e) {}
});

// Hàm Thích / Bỏ thích bài viết dùng chung toàn hệ thống (Lưu DB & Session & LocalStorage)
window.togglePostLike = function (postId, btn) {
  if (!postId || !btn) return;
  if (btn.dataset.loading === 'true') return;
  btn.dataset.loading = 'true';

  const csrfMeta = document.querySelector('meta[name="csrf-token"]');
  const token = csrfMeta ? csrfMeta.getAttribute('content') : '';

  fetch('/bai-viet/' + postId + '/thich', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': token,
      'Accept': 'application/json'
    }
  })
  .then(function (res) {
    return res.json();
  })
  .then(function (data) {
    btn.dataset.loading = 'false';
    if (data && data.success) {
      const allBtns = document.querySelectorAll('.like-btn[data-post-id="' + postId + '"]');
      const targets = allBtns.length > 0 ? allBtns : [btn];

      let localLikes = [];
      try {
        localLikes = JSON.parse(localStorage.getItem('business_forum_liked_posts') || localStorage.getItem('vlog_liked_posts') || '[]');
      } catch (e) {
        localLikes = [];
      }

      targets.forEach(function (b) {
        const countSpan = b.querySelector('.likes-count') || b.querySelector('span');
        if (countSpan) {
          countSpan.textContent = data.likes_count;
        }

        const heartIcon = b.querySelector('i.fa-heart');
        if (data.is_liked) {
          b.classList.add('liked');
          if (heartIcon) {
            heartIcon.classList.remove('fa-regular');
            heartIcon.classList.add('fa-solid');
            heartIcon.style.color = '#ef4444';
          }
        } else {
          b.classList.remove('liked');
          if (heartIcon) {
            heartIcon.classList.remove('fa-solid');
            heartIcon.classList.add('fa-regular');
            heartIcon.style.color = '';
          }
        }
      });

      if (data.is_liked) {
        if (!localLikes.includes(postId)) localLikes.push(postId);
      } else {
        localLikes = localLikes.filter(function (id) { return id != postId; });
      }
      try {
        localStorage.setItem('business_forum_liked_posts', JSON.stringify(localLikes));
      } catch (e) {}
    }
  })
  .catch(function (err) {
    console.error('Lỗi khi thích bài viết:', err);
    btn.dataset.loading = 'false';
  });
};

// ==========================================================================
// TRUNG TÂM THÔNG BÁO CHO ADMIN (NOTIFICATIONS DROPDOWN & REAL-TIME POLLING)
// ==========================================================================
document.addEventListener('DOMContentLoaded', function () {
  const notifBtn = document.getElementById('topbarNotifBtn');
  const notifDropdown = document.getElementById('topbarNotifDropdown');
  const markAllBtn = document.getElementById('btnMarkAllNotifsRead');

  if (!notifBtn || !notifDropdown) return;

  // 1. Mở / Đóng dropdown thông báo khi bấm nút chuông
  notifBtn.addEventListener('click', function (e) {
    e.preventDefault();
    e.stopPropagation();
    const isShown = notifDropdown.style.display === 'block';
    if (isShown) {
      notifDropdown.style.display = 'none';
      notifBtn.setAttribute('aria-expanded', 'false');
    } else {
      notifDropdown.style.display = 'block';
      notifBtn.setAttribute('aria-expanded', 'true');
    }
  });

  // Ngăn chặn sự kiện click bên trong dropdown làm đóng menu
  notifDropdown.addEventListener('click', function (e) {
    e.stopPropagation();
  });

  // Đóng khi click ngoài
  document.addEventListener('click', function (e) {
    if (!notifDropdown.contains(e.target) && !notifBtn.contains(e.target)) {
      notifDropdown.style.display = 'none';
      notifBtn.setAttribute('aria-expanded', 'false');
    }
  });

  // Đóng khi nhấn phím Escape
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && notifDropdown.style.display === 'block') {
      notifDropdown.style.display = 'none';
      notifBtn.setAttribute('aria-expanded', 'false');
    }
  });

  // 2. Chuyển đổi tab bộ lọc (Tất cả / Đề xuất / Bình luận)
  const tabs = notifDropdown.querySelectorAll('.notif-tab');
  const items = notifDropdown.querySelectorAll('.notif-item');

  tabs.forEach(function (tab) {
    tab.addEventListener('click', function (e) {
      e.stopPropagation();
      tabs.forEach(function (t) { t.classList.remove('active'); });
      tab.classList.add('active');

      const filter = tab.getAttribute('data-filter');
      items.forEach(function (item) {
        if (filter === 'all') {
          item.style.display = 'flex';
        } else if (filter === 'proposal') {
          item.style.display = item.classList.contains('notif-item-proposal') ? 'flex' : 'none';
        } else if (filter === 'comment') {
          item.style.display = item.classList.contains('notif-item-comment') ? 'flex' : 'none';
        }
      });
    });
  });

  // 3. Đánh dấu tất cả thông báo là đã đọc
  if (markAllBtn) {
    markAllBtn.addEventListener('click', function (e) {
      e.stopPropagation();

      const csrfMeta = document.querySelector('meta[name="csrf-token"]');
      const token = csrfMeta ? csrfMeta.getAttribute('content') : '';

      fetch('/admin/thong-bao/danh-dau-da-doc', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': token,
          'Accept': 'application/json'
        }
      })
      .then(function (res) { return res.json(); })
      .then(function (data) {
        window.updateBellNotificationBadge(0, 0, 0);

        items.forEach(function (item) {
          item.classList.remove('unread');
          item.setAttribute('data-is-read', '1');
        });

        const tabBadges = notifDropdown.querySelectorAll('.notif-tab-badge');
        tabBadges.forEach(function (b) { b.style.display = 'none'; });
      })
      .catch(function (err) {
        console.error('Lỗi khi đánh dấu thông báo đã đọc:', err);
      });
    });
  }

  // 4. Bấm vào xem 1 thông báo cụ thể -> giảm số trên chuông ngay và cập nhật database
  notifDropdown.querySelectorAll('.notif-item').forEach(function (itemLink) {
    itemLink.addEventListener('click', function (e) {
      const isRead = itemLink.getAttribute('data-is-read') === '1';
      const type = itemLink.getAttribute('data-notif-type');
      const id = itemLink.getAttribute('data-notif-id');
      const destUrl = itemLink.href;

      if (!isRead && id && type) {
        e.preventDefault();
        itemLink.classList.remove('unread');
        itemLink.setAttribute('data-is-read', '1');

        // Giảm số hiển thị trên chuông ngay lập tức
        const counter = notifBtn.querySelector('.notif-badge-counter');
        let currentNum = counter ? parseInt(counter.textContent.replace('+', ''), 10) || 0 : 0;
        let newNum = Math.max(0, currentNum - 1);
        window.updateBellNotificationBadge(newNum);

        const csrfMeta = document.querySelector('meta[name="csrf-token"]');
        const token = csrfMeta ? csrfMeta.getAttribute('content') : '';

        fetch('/admin/thong-bao/doc-muc', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json'
          },
          body: JSON.stringify({ type: type, id: id })
        })
        .finally(function () {
          window.location.href = destUrl;
        });
      }
    });
  });

  // 5. Real-time Polling: Tự động kiểm tra thông báo mới mỗi 20 giây
  let lastKnownCount = parseInt(notifBtn.querySelector('.notif-badge-counter')?.textContent || '0', 10);

  function pollAdminNotifications() {
    fetch('/admin/thong-bao', {
      headers: { 'Accept': 'application/json' }
    })
    .then(function (res) { return res.json(); })
    .then(function (data) {
      if (data && data.success) {
        const count = data.unread_count || 0;
        window.updateBellNotificationBadge(count, data.proposals_count, data.comments_count);

        // Nếu có thông báo mới phát sinh trong lúc Admin đang online, hiện popup thông báo nổi (Toast)
        if (count > lastKnownCount && lastKnownCount >= 0) {
          showAdminToastNotification('🔔 Bạn có ' + (count - lastKnownCount) + ' thông báo mới từ hệ thống!');
        }
        lastKnownCount = count;
      }
    })
    .catch(function () {});
  }

  // Khởi động kiểm tra thông báo định kỳ
  setInterval(pollAdminNotifications, 20000);

  function showAdminToastNotification(msg) {
    const toast = document.createElement('div');
    toast.className = 'admin-notif-toast';
    toast.innerHTML = '<span>' + msg + '</span>';
    document.body.appendChild(toast);
    setTimeout(function () {
      toast.style.opacity = '0';
      toast.style.transform = 'translateY(-10px)';
      toast.style.transition = 'all 0.3s ease';
      setTimeout(function () { toast.remove(); }, 300);
    }, 4500);
  }
});

// Hàm cập nhật huy hiệu số trên chuông thông báo toàn hệ thống
window.updateBellNotificationBadge = function (count, proposalsCount, commentsCount) {
  const notifBtn = document.getElementById('topbarNotifBtn');
  if (!notifBtn) return;

  const dot = notifBtn.querySelector('.notif-badge-dot, .median-badge-dot');
  let counter = notifBtn.querySelector('.notif-badge-counter');
  const pill = document.getElementById('notifPillCount');
  const num = parseInt(count, 10) || 0;

  if (num > 0) {
    if (dot) dot.style.display = 'block';
    if (!counter) {
      counter = document.createElement('span');
      counter.className = 'notif-badge-counter';
      notifBtn.appendChild(counter);
    }
    counter.textContent = num > 9 ? '9+' : num;
    if (pill) pill.textContent = num + ' mới';
  } else {
    if (dot) dot.style.display = 'none';
    if (counter) counter.remove();
    if (pill) pill.textContent = '0 mới';
  }

  // Cập nhật số trên tab Đề xuất
  if (typeof proposalsCount !== 'undefined') {
    const propBadge = document.getElementById('notifTabProposalBadge');
    if (propBadge) {
      if (proposalsCount > 0) {
        propBadge.textContent = proposalsCount;
        propBadge.style.display = 'inline-block';
      } else {
        propBadge.style.display = 'none';
      }
    }
  }

  // Cập nhật số trên tab Bình luận
  if (typeof commentsCount !== 'undefined') {
    const comBadge = document.getElementById('notifTabCommentBadge');
    if (comBadge) {
      if (commentsCount > 0) {
        comBadge.textContent = commentsCount;
        comBadge.style.display = 'inline-block';
      } else {
        comBadge.style.display = 'none';
      }
    }
  }
};

// ==========================================================================
// THÔNG BÁO SAO CHÉP LIÊN KẾT BÀI VIẾT (TOAST THAY THẾ CHO ALERT BROWSER)
// ==========================================================================
window.copyPostShareLink = function (url) {
  const targetUrl = url || window.location.href;

  function onCopied() {
    window.showToastNotification('Đã sao chép liên kết bài viết!');
  }

  function onFailed() {
    window.showToastNotification('Không thể sao chép liên kết, vui lòng thử lại.', 'error');
  }

  if (navigator.clipboard && navigator.clipboard.writeText) {
    navigator.clipboard.writeText(targetUrl).then(onCopied).catch(function () {
      fallbackCopy(targetUrl);
    });
  } else {
    fallbackCopy(targetUrl);
  }

  function fallbackCopy(text) {
    try {
      const tempInput = document.createElement('textarea');
      tempInput.value = text;
      tempInput.style.position = 'fixed';
      tempInput.style.top = '-9999px';
      tempInput.style.opacity = '0';
      document.body.appendChild(tempInput);
      tempInput.select();
      const success = document.execCommand('copy');
      document.body.removeChild(tempInput);
      if (success) {
        onCopied();
      } else {
        onFailed();
      }
    } catch (e) {
      onFailed();
    }
  }
};

window.showToastNotification = function (message, type) {
  const existing = document.getElementById('globalSystemToast');
  if (existing) existing.remove();

  const toast = document.createElement('div');
  toast.id = 'globalSystemToast';
  toast.className = 'global-system-toast ' + (type === 'error' ? 'toast-error' : 'toast-success');
  toast.innerHTML = `
    <div class="toast-inner">
      <i class="fa-solid ${type === 'error' ? 'fa-circle-xmark' : 'fa-circle-check'}"></i>
      <span>${message}</span>
    </div>
  `;
  document.body.appendChild(toast);

  setTimeout(function () {
    toast.classList.add('hide');
    setTimeout(function () { toast.remove(); }, 300);
  }, 2500);
};

// ==========================================================================
// TÌM KIẾM TOÀN HỆ THỐNG & MOBILE SEARCH BAR
// ==========================================================================
window.performGlobalSearch = function (customQuery) {
  const topInput = document.getElementById('topSearchInput');
  const mobInput = document.getElementById('mobileSearchInput');
  let query = '';
  if (customQuery !== undefined) {
    query = String(customQuery).trim().toLowerCase();
  } else if (mobInput && mobInput.value.trim()) {
    query = mobInput.value.trim().toLowerCase();
  } else if (topInput && topInput.value.trim()) {
    query = topInput.value.trim().toLowerCase();
  }

  // 1. Nếu đang ở trang chủ (home)
  const postsList = document.getElementById('postsList');
  if (postsList) {
    const postCards = postsList.querySelectorAll('.post-card');
    let matchedCount = 0;
    postCards.forEach(card => {
      const title = card.querySelector('.post-main-title')?.textContent.toLowerCase() || '';
      const excerpt = card.querySelector('.post-excerpt-text')?.textContent.toLowerCase() || '';
      const category = card.querySelector('.post-category-badge')?.textContent.toLowerCase() || '';
      const matches = !query || title.includes(query) || excerpt.includes(query) || category.includes(query);
      card.style.display = matches ? '' : 'none';
      if (matches) matchedCount++;
    });

    let noResultMsg = document.getElementById('noSearchResultMsg');
    if (query && matchedCount === 0) {
      if (!noResultMsg) {
        noResultMsg = document.createElement('div');
        noResultMsg.id = 'noSearchResultMsg';
        noResultMsg.style.cssText = 'padding: 2.5rem 1.5rem; text-align: center; background: var(--bg-card); border-radius: 8px; border: 1px dashed var(--border-card); margin-top: 1rem;';
        noResultMsg.innerHTML = `
          <i class="fa-solid fa-magnifying-glass" style="font-size: 2rem; color: var(--text-light-muted); margin-bottom: 0.75rem;"></i>
          <div style="font-size: 1rem; font-weight: 700; color: var(--text-title); margin-bottom: 0.25rem;">Không tìm thấy kết quả phù hợp</div>
          <p style="font-size: 0.84rem; color: var(--text-muted); margin-bottom: 1rem;">Không có bài viết nào khớp với từ khóa "${query}".</p>
          <button type="button" class="cat-pill" onclick="window.clearGlobalSearch()" style="font-size: 0.8rem; padding: 0.45rem 1rem;">Xóa bộ lọc tìm kiếm</button>
        `;
        postsList.appendChild(noResultMsg);
      } else {
        noResultMsg.style.display = 'block';
      }
    } else if (noResultMsg) {
      noResultMsg.style.display = 'none';
    }

    if (query && window.innerWidth <= 768) {
      postsList.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
    return;
  }

  // 2. Nếu đang ở trang Quản lý bài viết Admin
  const adminRows = document.querySelectorAll('.admin-post-row');
  if (adminRows.length > 0) {
    adminRows.forEach(row => {
      const text = row.textContent.toLowerCase();
      row.style.display = (!query || text.includes(query)) ? '' : 'none';
    });
    return;
  }

  // 3. Nếu đang ở trang khác và người dùng nhấn submit
  if (query) {
    window.location.href = '/?q=' + encodeURIComponent(query);
  }
};

window.clearGlobalSearch = function () {
  const topInput = document.getElementById('topSearchInput');
  const mobInput = document.getElementById('mobileSearchInput');
  const clearBtn = document.getElementById('btnClearMobileSearch');
  if (topInput) topInput.value = '';
  if (mobInput) mobInput.value = '';
  if (clearBtn) clearBtn.style.display = 'none';
  window.performGlobalSearch('');
};

window.toggleMobileSearch = function () {
  const bar = document.getElementById('mobileSearchBar');
  if (!bar) return;
  const isCurrentlyOpen = bar.classList.contains('active') || bar.style.display === 'block';
  if (isCurrentlyOpen) {
    bar.classList.remove('active');
    bar.style.display = 'none';
  } else {
    bar.classList.add('active');
    bar.style.display = 'block';
    const topInput = document.getElementById('topSearchInput');
    const mobInput = document.getElementById('mobileSearchInput');
    const clearBtn = document.getElementById('btnClearMobileSearch');
    if (mobInput) {
      if (topInput && topInput.value) {
        mobInput.value = topInput.value;
      }
      if (clearBtn) {
        clearBtn.style.display = mobInput.value ? 'inline-flex' : 'none';
      }
      setTimeout(() => mobInput.focus(), 80);
    }
  }
};

function initSearchListeners() {
  const topInput = document.getElementById('topSearchInput');
  const mobInput = document.getElementById('mobileSearchInput');
  const clearBtn = document.getElementById('btnClearMobileSearch');

  if (topInput) {
    topInput.addEventListener('input', function (e) {
      const val = e.target.value;
      if (mobInput && mobInput.value !== val) {
        mobInput.value = val;
      }
      window.performGlobalSearch(val);
    });
    topInput.addEventListener('keydown', function (e) {
      if (e.key === 'Enter') {
        e.preventDefault();
        window.performGlobalSearch(topInput.value);
      }
    });
  }

  if (mobInput) {
    mobInput.addEventListener('input', function (e) {
      const val = e.target.value;
      if (clearBtn) clearBtn.style.display = val ? 'inline-flex' : 'none';
      if (topInput && topInput.value !== val) {
        topInput.value = val;
      }
      window.performGlobalSearch(val);
    });
    mobInput.addEventListener('keydown', function (e) {
      if (e.key === 'Enter') {
        e.preventDefault();
        window.performGlobalSearch(mobInput.value);
      }
    });
  }
}

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initSearchListeners);
} else {
  initSearchListeners();
}
