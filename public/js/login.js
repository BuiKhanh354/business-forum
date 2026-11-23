/**
 * Login Page Script (login.js)
 * Tách riêng toàn bộ logic xác thực, chuyển đổi mật khẩu và đăng nhập AJAX mượt mà
 */

document.addEventListener('DOMContentLoaded', function () {
  const toggleBtn = document.getElementById('btnTogglePassword');
  const pwdInput = document.getElementById('adminPasswordInput');
  const eyeIcon = document.getElementById('eyeIcon');
  const loginInput = document.getElementById('adminLoginInput');
  const loginForm = document.getElementById('adminLoginForm');
  const alertBox = document.getElementById('loginAlert');
  const alertText = document.getElementById('loginAlertText');
  const submitBtn = document.getElementById('btnLoginSubmit');
  const submitText = document.getElementById('btnSubmitText');
  const submitIcon = document.getElementById('submitArrowIcon');

  // Hiện/Ẩn mật khẩu
  if (toggleBtn && pwdInput) {
    toggleBtn.addEventListener('click', function () {
      if (pwdInput.type === 'password') {
        pwdInput.type = 'text';
        if (eyeIcon) eyeIcon.className = 'fa-solid fa-eye-slash';
      } else {
        pwdInput.type = 'password';
        if (eyeIcon) eyeIcon.className = 'fa-solid fa-eye';
      }
    });
  }

  // Xử lý đăng nhập thông minh qua AJAX (mượt mà, không bị chớp trang)
  if (loginForm) {
    loginForm.addEventListener('submit', function (e) {
      e.preventDefault();

      if (alertBox) alertBox.style.display = 'none';

      if (submitBtn) submitBtn.disabled = true;
      if (submitText) submitText.textContent = 'Đang xác thực...';
      if (submitIcon) submitIcon.className = 'fa-solid fa-spinner fa-spin';

      const formData = new FormData(loginForm);

      fetch(loginForm.action, {
        method: 'POST',
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'Accept': 'application/json'
        },
        body: formData
      })
      .then(async (response) => {
        const data = await response.json().catch(() => ({}));
        if (!response.ok) {
          throw new Error(data.message || 'Email hoặc mật khẩu không chính xác!');
        }
        return data;
      })
      .then((data) => {
        if (submitText) submitText.textContent = 'Đang chuyển hướng...';
        if (submitIcon) submitIcon.className = 'fa-solid fa-check';
        if (submitBtn) submitBtn.style.backgroundColor = '#16a34a';

        // Chuyển hướng vào trang quản trị
        setTimeout(() => {
          window.location.href = data.redirect || '/admin/bai-viet';
        }, 400);
      })
      .catch((err) => {
        if (submitBtn) {
          submitBtn.disabled = false;
          submitBtn.style.backgroundColor = '';
        }
        if (submitText) submitText.textContent = 'Đăng nhập Quản trị';
        if (submitIcon) submitIcon.className = 'fa-solid fa-arrow-right';

        if (alertBox && alertText) {
          alertText.textContent = err.message || 'Đăng nhập thất bại. Vui lòng kiểm tra lại!';
          alertBox.style.display = 'flex';
        }
      });
    });
  }
});
