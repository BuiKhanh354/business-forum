/**
 * KẾT NỐI | BUSINESS COMMUNITY
 * Client-side Interactive Prototype Scripts
 */

document.addEventListener("DOMContentLoaded", () => {
  // -1. Đồng bộ Theme toàn trang từ localStorage
  const savedTheme = localStorage.getItem("site-theme");
  const savedMode = localStorage.getItem("site-mode");
  const customPrimary = localStorage.getItem("custom-primary");
  const customAccent = localStorage.getItem("custom-accent");
  const customBg = localStorage.getItem("custom-bg");
  const customCard = localStorage.getItem("custom-card");

  if (savedMode === "dark") {
    document.documentElement.setAttribute("data-theme", "dark");
    document.body.classList.add("theme-dark");
  } else if (savedMode === "light") {
    document.documentElement.removeAttribute("data-theme");
    document.body.classList.remove("theme-dark");
    if (savedTheme && savedTheme !== "default" && savedTheme !== "dark") {
      document.documentElement.setAttribute("data-theme", savedTheme);
    }
  } else if (savedTheme && savedTheme !== "default") {
    document.documentElement.setAttribute("data-theme", savedTheme);
  }

  if (customPrimary) document.documentElement.style.setProperty("--executive-blue", customPrimary);
  if (customAccent) document.documentElement.style.setProperty("--gold-accent", customAccent);
  if (customBg) document.documentElement.style.setProperty("--bg-app", customBg);
  if (customCard) document.documentElement.style.setProperty("--bg-card", customCard);

  // Xoá cache avatar cũ trong localStorage nếu có để luôn ưu tiên dữ liệu từ Database
  if (localStorage.getItem("admin-avatar-image")) {
    localStorage.removeItem("admin-avatar-image");
  }

  // Đồng bộ ảnh bìa trang chủ và chiều cao (Hỗ trợ độc lập Desktop & Mobile)
  function syncResponsiveCover() {
    const heroCover = document.getElementById("ve-toi");
    if (!heroCover) return;
    const isMobile = window.innerWidth <= 768;

    const desktopImg = heroCover.dataset.desktopImage || localStorage.getItem("site-cover-image");
    const mobileImg = heroCover.dataset.mobileImage || localStorage.getItem("site-cover-image-mobile");
    const desktopHeight = heroCover.dataset.desktopHeight || localStorage.getItem("site-cover-height") || "350px";
    const mobileHeight = heroCover.dataset.mobileHeight || localStorage.getItem("site-cover-height-mobile") || "220px";

    const activeImg = isMobile ? (mobileImg || desktopImg) : desktopImg;
    const activeHeight = isMobile ? mobileHeight : desktopHeight;

    if (activeImg) {
      heroCover.style.backgroundImage = `url('${activeImg}')`;
    }

    heroCover.style.minHeight = activeHeight;
    heroCover.style.height = activeHeight;
  }

  syncResponsiveCover();
  window.addEventListener("resize", syncResponsiveCover);

  // Phím tắt Ctrl + K mở ô tìm kiếm nhanh chuẩn Blog
  window.addEventListener("keydown", (e) => {
    if ((e.ctrlKey || e.metaKey) && (e.key === "k" || e.key === "K")) {
      e.preventDefault();
      const searchInput = document.getElementById("topSearchInput");
      if (searchInput) searchInput.focus();
    }
  });

  
  // 0.1. Bộ lọc tìm kiếm thời gian thực trên thanh Topbar (Real-time Search Filter)
  const topSearchInput = document.getElementById("topSearchInput");
  if (topSearchInput) {
    topSearchInput.addEventListener("input", (e) => {
      const query = e.target.value.trim().toLowerCase();

      // 1. Lọc danh sách bài viết ở trang chủ (index.html / home.blade.php)
      const postCards = document.querySelectorAll("#postsList .post-card");
      let matchedCount = 0;

      if (postCards.length > 0) {
        postCards.forEach((card) => {
          const title = card.querySelector(".post-main-title")?.textContent.toLowerCase() || "";
          const excerpt = card.querySelector(".post-excerpt-text")?.textContent.toLowerCase() || "";
          const category = card.querySelector(".post-category-badge")?.textContent.toLowerCase() || "";

          const matches = !query || title.includes(query) || excerpt.includes(query) || category.includes(query);
          card.style.display = matches ? "" : "none";
          if (matches) matchedCount++;
        });

        // Hiển thị thông báo khi không tìm thấy kết quả
        let noResultMsg = document.getElementById("noSearchResultMsg");
        if (query && matchedCount === 0) {
          if (!noResultMsg) {
            noResultMsg = document.createElement("div");
            noResultMsg.id = "noSearchResultMsg";
            noResultMsg.style.cssText = "padding: 2.5rem 1.5rem; text-align: center; background: var(--bg-card); border-radius: 8px; border: 1px dashed var(--border-card); margin-top: 1rem;";
            noResultMsg.innerHTML = `
              <i class="fa-solid fa-magnifying-glass" style="font-size: 2rem; color: var(--text-light-muted); margin-bottom: 0.75rem;"></i>
              <div style="font-size: 1rem; font-weight: 700; color: var(--text-title); margin-bottom: 0.25rem;">Không tìm thấy kết quả phù hợp</div>
              <p style="font-size: 0.84rem; color: var(--text-muted); margin-bottom: 1rem;">Không có bài viết nào khớp với từ khóa "${query}".</p>
              <button type="button" class="cat-pill" id="btnResetSearchQuery" style="font-size: 0.8rem; padding: 0.45rem 1rem;">Xóa bộ lọc tìm kiếm</button>
            `;
            document.getElementById("postsList")?.appendChild(noResultMsg);
            document.getElementById("btnResetSearchQuery")?.addEventListener("click", () => {
              topSearchInput.value = "";
              topSearchInput.dispatchEvent(new Event("input"));
              topSearchInput.focus();
            });
          } else {
            noResultMsg.style.display = "block";
          }
        } else if (noResultMsg) {
          noResultMsg.style.display = "none";
        }
      }

      // 2. Lọc bảng bài viết ở trang Admin (admin.html / admin.blade.php)
      const adminRows = document.querySelectorAll(".post-manage-table tbody tr, .posts-table tbody tr");
      if (adminRows.length > 0) {
        adminRows.forEach((row) => {
          const text = row.textContent.toLowerCase();
          row.style.display = !query || text.includes(query) ? "" : "none";
        });
      }

      // 3. Lọc danh sách bình luận (manage-comments.html)
      const commentCards = document.querySelectorAll(".comment-manage-card");
      if (commentCards.length > 0) {
        commentCards.forEach((card) => {
          const text = card.textContent.toLowerCase();
          card.style.display = !query || text.includes(query) ? "" : "none";
        });
      }
    });
  }

  // 1. Accordion sổ xuống / thu gọn danh sách "Chủ đề bài viết" trong sidebar
  const topicDropdown = document.getElementById("menuTopicDropdown");
  const topicToggleBtn = document.getElementById("topicToggleBtn");
  const topicSubmenu = document.getElementById("sidebarTopicsSubmenu");

  if (topicToggleBtn && topicDropdown) {
    topicToggleBtn.onclick = function (e) {
      e.preventDefault();
      e.stopPropagation();
      const isOpen = topicDropdown.classList.toggle("open");
      if (topicSubmenu) {
        topicSubmenu.style.display = isOpen ? "flex" : "none";
      }
    };
  }

  // 2. Chuyển đổi bộ lọc danh mục bài viết trên trang chủ (index.html)
  const catPills = document.querySelectorAll(".cat-pill");
  const submenuLinks = document.querySelectorAll(".submenu-link");

  const filterCategory = (categorySlug, categoryName = "") => {
    // Đồng bộ nút pill ngang
    catPills.forEach((pill) => {
      const match = pill.getAttribute("data-cat") === categorySlug;
      pill.classList.toggle("active", match);
    });

    // Đồng bộ menu con trong sidebar
    submenuLinks.forEach((link) => {
      const match = link.getAttribute("data-category") === categorySlug;
      link.classList.toggle("active", match);
    });

    // Lọc bài viết
    const postCards = document.querySelectorAll("#postsList .post-card");
    postCards.forEach((card) => {
      if (categorySlug === "all") {
        card.style.display = "";
      } else {
        const badge = card.querySelector(".post-category-badge");
        const cardCatAttr = card.getAttribute("data-category") || "";
        const badgeText = badge ? badge.textContent.toLowerCase() : "";
        const targetSlug = categorySlug.toLowerCase().replace(/-/g, " ");
        const targetName = categoryName ? categoryName.toLowerCase() : "";
        const isMatch = badgeText.includes(categorySlug.toLowerCase()) || 
                        badgeText.includes(targetSlug) || 
                        (targetName && badgeText.includes(targetName)) ||
                        cardCatAttr.includes(categorySlug.toLowerCase()) ||
                        (targetName && cardCatAttr.includes(targetName));
        card.style.display = isMatch ? "" : "none";
      }
    });
  };

  catPills.forEach((pill) => {
    if (pill.classList.contains("btn-create-category-trigger")) return;
    pill.addEventListener("click", () => {
      const cat = pill.getAttribute("data-cat") || "all";
      const name = pill.getAttribute("data-name") || "";
      filterCategory(cat, name);
    });
  });

  submenuLinks.forEach((link) => {
    link.addEventListener("click", (e) => {
      const cat = link.getAttribute("data-category");
      if (!cat) return; // nút mở modal
      const name = link.getAttribute("data-name") || "";
      filterCategory(cat, name);
    });
  });

  // 3. Chuyển đổi bộ lọc bình luận trên trang Quản lý bình luận (manage-comments.html)
  const commentFilterPills = document.querySelectorAll(".comment-filter-pill");
  commentFilterPills.forEach((pill) => {
    pill.addEventListener("click", () => {
      commentFilterPills.forEach((p) => p.classList.remove("active"));
      pill.classList.add("active");
    });
  });

  // 4. Scroll nhanh tới khung chỉnh sửa trên trang admin.html
  const btnScrollToEdit = document.getElementById("btnScrollToEdit");
  const editSection = document.getElementById("editSection");
  if (btnScrollToEdit && editSection) {
    btnScrollToEdit.addEventListener("click", () => {
      editSection.scrollIntoView({ behavior: "smooth" });
    });
  }

  // 5. Modal tạo bài viết mới (index.html)
  const openModalBtn = document.getElementById("btnOpenNewPostModal");
  const closeModalBtn = document.getElementById("btnCloseModal");
  const cancelModalBtn = document.getElementById("btnCancelModal");
  const postModal = document.getElementById("postModal");

  if (openModalBtn && postModal) {
    openModalBtn.addEventListener("click", () => {
      postModal.style.display = "flex";
    });
  }

  const closeModal = () => {
    if (postModal) postModal.style.display = "none";
  };

  if (closeModalBtn) closeModalBtn.addEventListener("click", closeModal);
  if (cancelModalBtn) cancelModalBtn.addEventListener("click", closeModal);
  if (postModal) {
    postModal.addEventListener("click", (e) => {
      if (e.target === postModal) closeModal();
    });
  }

  // Toggle thu gọn/mở rộng Sidebar theo phong cách Blog
  const btnToggleSidebar = document.getElementById("btnToggleSidebar");
  if (btnToggleSidebar) {
    btnToggleSidebar.addEventListener("click", () => {
      const sidebar = document.querySelector(".app-sidebar");
      if (sidebar) {
        sidebar.classList.toggle("collapsed");
      }
    });
  }

  // Blog Mobile Bottom Navigation & Topbar Search Handlers
  const mobileSearchToggleBtn = document.getElementById("btnMobileSearchToggle");
  const mobileBottomSearchBtn = document.getElementById("mobileSearchBtn");
  const mobileSearchBar = document.getElementById("mobileSearchBar");
  const mobileSearchInput = document.getElementById("mobileSearchInput");
  const clearMobileSearchBtn = document.getElementById("btnClearMobileSearch");
  const closeMobileSearchBtn = document.getElementById("btnCloseMobileSearch");

  function openMobileSearchBox() {
    if (!mobileSearchBar) return;
    mobileSearchBar.classList.add("active");
    mobileSearchBar.style.display = "block";
    if (mobileSearchInput) {
      if (topSearchInput && topSearchInput.value) {
        mobileSearchInput.value = topSearchInput.value;
      }
      setTimeout(() => mobileSearchInput.focus(), 120);
      if (clearMobileSearchBtn) {
        clearMobileSearchBtn.style.display = mobileSearchInput.value ? "inline-flex" : "none";
      }
    }
  }

  function closeMobileSearchBox() {
    if (!mobileSearchBar) return;
    mobileSearchBar.classList.remove("active");
    mobileSearchBar.style.display = "none";
  }

  if (mobileSearchToggleBtn) {
    mobileSearchToggleBtn.addEventListener("click", (e) => {
      e.stopPropagation();
      if (mobileSearchBar && mobileSearchBar.classList.contains("active")) {
        closeMobileSearchBox();
      } else {
        openMobileSearchBox();
      }
    });
  }

  if (mobileBottomSearchBtn) {
    mobileBottomSearchBtn.addEventListener("click", (e) => {
      e.preventDefault();
      e.stopPropagation();
      openMobileSearchBox();
      window.scrollTo({ top: 0, behavior: "smooth" });
    });
  }

  if (closeMobileSearchBtn) {
    closeMobileSearchBtn.addEventListener("click", (e) => {
      e.stopPropagation();
      closeMobileSearchBox();
    });
  }

  if (clearMobileSearchBtn && mobileSearchInput) {
    clearMobileSearchBtn.addEventListener("click", () => {
      mobileSearchInput.value = "";
      clearMobileSearchBtn.style.display = "none";
      if (topSearchInput) {
        topSearchInput.value = "";
        topSearchInput.dispatchEvent(new Event("input"));
      }
      mobileSearchInput.dispatchEvent(new Event("input"));
      mobileSearchInput.focus();
    });
  }

  if (mobileSearchInput) {
    mobileSearchInput.addEventListener("input", (e) => {
      const val = e.target.value;
      if (clearMobileSearchBtn) {
        clearMobileSearchBtn.style.display = val.length > 0 ? "inline-flex" : "none";
      }
      // Đồng bộ trực tiếp với topSearchInput để kích hoạt bộ lọc bài viết thời gian thực
      if (topSearchInput) {
        topSearchInput.value = val;
        topSearchInput.dispatchEvent(new Event("input"));
      }
    });
  }

  const mobileTopicsBtn = document.getElementById("mobileTopicsBtn");
  if (mobileTopicsBtn) {
    mobileTopicsBtn.addEventListener("click", () => {
      const filterSection = document.getElementById("postsFilterSection") || document.querySelector(".category-filter-nav");
      if (filterSection) {
        filterSection.scrollIntoView({ behavior: "smooth" });
      }
    });
  }

});
