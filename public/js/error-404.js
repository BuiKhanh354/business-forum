/**
 * 404 Error Page Interaction Script (error-404.js)
 * Tách riêng toàn bộ logic tương tác và chuyển đổi giao diện Dark/Light Mode trên trang 404
 */

function updateThemeIcon() {
  const isDark = document.documentElement.getAttribute('data-theme') === 'dark' || 
                 (document.body && document.body.classList.contains('theme-dark'));
  const btnIcon = document.querySelector('#themeToggleBtn404 i');
  if (btnIcon) {
    if (isDark) {
      btnIcon.className = 'fa-solid fa-sun';
      btnIcon.style.color = '#f59e0b';
    } else {
      btnIcon.className = 'fa-solid fa-moon';
      btnIcon.style.color = '';
    }
  }
}

function toggleDarkMode404() {
  const isDark = document.documentElement.getAttribute('data-theme') === 'dark' || 
                 (document.body && document.body.classList.contains('theme-dark'));
  const target = isDark ? 'light' : 'dark';
  
  if (target === 'dark') {
    document.documentElement.setAttribute('data-theme', 'dark');
    if (document.body) document.body.classList.add('theme-dark');
    localStorage.setItem('site-mode', 'dark');
  } else {
    document.documentElement.removeAttribute('data-theme');
    if (document.body) document.body.classList.remove('theme-dark');
    localStorage.setItem('site-mode', 'light');
  }
  updateThemeIcon();
}

document.addEventListener('DOMContentLoaded', function () {
  updateThemeIcon();

  const toggleBtn = document.getElementById('themeToggleBtn404');
  if (toggleBtn) {
    toggleBtn.addEventListener('click', toggleDarkMode404);
  }
});
