/**
 * Theme Pre-Initialization Script (theme-init.js)
 * Chạy ngay trong <head> để ngăn chặn hiện tượng nhấp nháy giao diện (FOUC)
 */
(function () {
  try {
    var m = localStorage.getItem("site-mode");
    if (m === "light") {
      document.documentElement.removeAttribute("data-theme");
      document.documentElement.classList.remove("theme-dark");
      if (document.body) document.body.classList.remove("theme-dark");
    } else if (m === "dark") {
      document.documentElement.setAttribute("data-theme", "dark");
      document.documentElement.classList.add("theme-dark");
      if (document.body) document.body.classList.add("theme-dark");
    }
  } catch (e) {
    // LocalStorage không khả dụng (Private browsing)
  }
})();
