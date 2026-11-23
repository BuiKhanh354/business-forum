// Hàm chọn preset ảnh đại diện có sẵn
window.selectAvatarPreset = function(filePath, cardEl) {
    document.getElementById('selectedAvatarInput').value = filePath;
    const fullUrl = window.resolveAssetUrl ? window.resolveAssetUrl(filePath) : ((window.assetBaseUrl || '/') + filePath.replace(/^\/+/, ''));
    
    const rowImg = document.getElementById('profileRowAvatar');
    const leftImg = document.getElementById('profileLeftAvatar');
    if (rowImg) rowImg.src = fullUrl;
    if (leftImg) leftImg.src = fullUrl;

    // Reset file upload input nếu đang chọn preset
    const fileInput = document.getElementById('uploadNewAvatarInput');
    if (fileInput) fileInput.value = '';

    // Cập nhật giao diện card
    const grid = document.getElementById('avatarHistoryGrid');
    if (grid) {
        grid.querySelectorAll('.avatar-quick-card').forEach(c => {
            c.classList.remove('active');
            c.style.borderColor = '#cbd5e1';
            const pill = c.querySelector('.preset-status-pill');
            if (pill) {
                pill.textContent = 'Chọn ảnh này';
                pill.style.background = '#f1f5f9';
                pill.style.color = 'var(--text-muted)';
            }
        });
        cardEl.classList.add('active');
        cardEl.style.borderColor = 'var(--executive-blue)';
        const pill = cardEl.querySelector('.preset-status-pill');
        if (pill) {
            pill.textContent = 'Đang chọn';
            pill.style.background = 'var(--executive-blue-light)';
            pill.style.color = 'var(--executive-blue)';
        }
    }
};

// Hàm xem trước ảnh đại diện khi tải lên từ thiết bị
window.handleAvatarFileSelect = function(input) {
    if (!input.files || !input.files[0]) return;
    const reader = new FileReader();
    reader.onload = function(e) {
        const rowImg = document.getElementById('profileRowAvatar');
        const leftImg = document.getElementById('profileLeftAvatar');
        if (rowImg) rowImg.src = e.target.result;
        if (leftImg) leftImg.src = e.target.result;

        // Bỏ chọn các preset
        const grid = document.getElementById('avatarHistoryGrid');
        if (grid) {
            grid.querySelectorAll('.avatar-quick-card').forEach(c => {
                c.classList.remove('active');
                c.style.borderColor = '#cbd5e1';
                const pill = c.querySelector('.preset-status-pill');
                if (pill) {
                    pill.textContent = 'Chọn ảnh này';
                    pill.style.background = '#f1f5f9';
                    pill.style.color = 'var(--text-muted)';
                }
            });
        }
    };
    reader.readAsDataURL(input.files[0]);
};

// ======================================================================
// QUẢN LÝ LIÊN KẾT MẠNG XÃ HỘI & NHẬN DIỆN ICON TAB TỰ ĐỘNG (FAVICON)
// ======================================================================

// Trích xuất domain từ URL bất kỳ
window.extractDomain = function(url) {
    try {
        let clean = (url || '').trim();
        if (!clean) return '';
        if (!/^https?:\/\//i.test(clean)) {
            clean = 'https://' + clean;
        }
        const u = new URL(clean);
        return u.hostname.replace(/^www\./, '');
    } catch(e) {
        return '';
    }
};

// Cập nhật icon favicon theo thời gian thực khi người dùng gõ/paste link
window.updateSocialFavicon = function(input) {
    const row = input.closest('.social-link-item');
    if (!row) return;
    const img = row.querySelector('.social-tab-favicon-img');
    const badge = row.querySelector('.social-domain-badge');
    const domain = window.extractDomain(input.value);

    if (domain && domain.includes('.')) {
        if (img) {
            img.src = `https://www.google.com/s2/favicons?domain=${domain}&sz=64`;
            img.style.display = 'block';
        }
        if (badge) {
            badge.textContent = domain;
            badge.style.display = 'inline-block';
        }
    } else {
        if (img) {
            img.src = `https://www.google.com/s2/favicons?domain=example.com&sz=64`;
        }
        if (badge) {
            badge.style.display = 'none';
        }
    }
};

// Thêm hàng liên kết mạng xã hội mới
window.addSocialLinkRow = function(defaultUrl = '') {
    const container = document.getElementById('socialLinksListContainer');
    if (!container) return;

    const row = document.createElement('div');
    row.className = 'social-link-item';
    row.style = 'display: flex; align-items: center; gap: 0.5rem; background: var(--bg-subtle, #f8fafc); padding: 0.5rem 0.65rem; border-radius: 8px; border: 1px solid var(--border-card, #e2e8f0); transition: all 0.2s ease;';

    const domain = window.extractDomain(defaultUrl) || '';
    const hasDomain = Boolean(domain && domain.includes('.'));
    const favDomain = hasDomain ? domain : 'example.com';

    row.innerHTML = `
        <div class="social-tab-icon-box" style="width: 32px; height: 32px; background: #ffffff; border: 1px solid #cbd5e1; border-radius: 6px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; padding: 4px;">
            <img class="social-tab-favicon-img" src="https://www.google.com/s2/favicons?domain=${favDomain}&sz=64" alt="Tab Icon" style="width: 18px; height: 18px; object-fit: contain;" onerror="this.src='https://www.google.com/s2/favicons?domain=example.com&sz=64'">
        </div>
        <div style="flex: 1; min-width: 0;">
            <input type="url" name="social_links[]" form="profileUpdateForm" value="${defaultUrl}" placeholder="https://..." style="width: 100%; padding: 0.4rem 0.6rem; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.8rem; outline: none; background: #ffffff;" oninput="updateSocialFavicon(this)">
        </div>
        <button type="button" onclick="this.closest('.social-link-item').remove()" style="background: #ffffff; border: 1px solid #fecaca; color: #dc2626; border-radius: 6px; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; cursor: pointer; flex-shrink: 0;" title="Xóa">
            <i class="fa-solid fa-trash-can" style="font-size: 0.8rem;"></i>
        </button>
    `;

    container.appendChild(row);
    const newInp = row.querySelector('input');
    if (newInp && !defaultUrl) newInp.focus();
};

// Xử lý lưu hồ sơ cá nhân & kênh truyền thông bằng AJAX (Không reload trang)
document.addEventListener('DOMContentLoaded', function () {
    const profileForm = document.getElementById('profileUpdateForm');
    if (profileForm) {
        profileForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const submitBtn = profileForm.querySelector('button[type="submit"]');
            const origHtml = submitBtn ? submitBtn.innerHTML : '';
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Đang lưu thông tin...';
            }

            const formData = new FormData(profileForm);

            // Bổ sung đầy đủ các liên kết mạng xã hội nằm ở cột widget-card bên trái
            formData.delete('social_links[]');
            document.querySelectorAll('input[name="social_links[]"]').forEach(inp => {
                const val = (inp.value || '').trim();
                if (val) {
                    formData.append('social_links[]', val);
                }
            });

            fetch(profileForm.action, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(async res => {
                const data = await res.json().catch(() => ({}));
                if (!res.ok) throw new Error(data.message || 'Lưu thất bại: ' + res.status);
                return data;
            })
            .then(data => {
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.style.backgroundColor = '#16a34a';
                    submitBtn.innerHTML = '<i class="fa-solid fa-check"></i> Đã lưu thành công!';
                    setTimeout(() => {
                        submitBtn.style.backgroundColor = 'var(--executive-blue)';
                        submitBtn.innerHTML = origHtml;
                    }, 2500);
                }
                if (typeof showThemeToast === 'function') {
                    showThemeToast(data.message || 'Đã cập nhật thông tin cá nhân thành công!', 'success');
                }
            })
            .catch(err => {
                console.error(err);
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = origHtml;
                }
                alert('Có lỗi xảy ra: ' + (err.message || 'Vui lòng thử lại!'));
            });
        });
    }

    // ======================================================================
    // XỬ LÝ ĐỔI MẬT KHẨU TÀI KHOẢN QUẢN TRỊ
    // ======================================================================
    window.togglePasswordVisibility = function(inputId, btn) {
        const input = document.getElementById(inputId);
        if (!input) return;
        const icon = btn.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            if (icon) {
                icon.className = 'fa-solid fa-eye-slash';
            }
        } else {
            input.type = 'password';
            if (icon) {
                icon.className = 'fa-solid fa-eye';
            }
        }
    };

    const pwdForm = document.getElementById('adminPasswordChangeForm');
    if (pwdForm) {
        pwdForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const alertBox = document.getElementById('passwordAlertBox');
            const submitBtn = document.getElementById('btnChangePasswordSubmit');
            const submitText = document.getElementById('btnChangePasswordText');
            const currentPwd = document.getElementById('inputCurrentPassword').value;
            const newPwd = document.getElementById('inputNewPassword').value;
            const confirmPwd = document.getElementById('inputConfirmPassword').value;

            if (alertBox) alertBox.style.display = 'none';

            if (newPwd.length < 6) {
                if (alertBox) {
                    alertBox.style.display = 'flex';
                    alertBox.style.backgroundColor = '#fef2f2';
                    alertBox.style.borderColor = '#fecaca';
                    alertBox.style.color = '#b91c1c';
                    alertBox.innerHTML = '<i class="fa-solid fa-circle-exclamation"></i> <span>Mật khẩu mới phải có tối thiểu 6 ký tự!</span>';
                }
                return;
            }

            if (newPwd !== confirmPwd) {
                if (alertBox) {
                    alertBox.style.display = 'flex';
                    alertBox.style.backgroundColor = '#fef2f2';
                    alertBox.style.borderColor = '#fecaca';
                    alertBox.style.color = '#b91c1c';
                    alertBox.innerHTML = '<i class="fa-solid fa-circle-exclamation"></i> <span>Xác nhận mật khẩu mới không trùng khớp!</span>';
                }
                return;
            }

            if (submitBtn) {
                submitBtn.disabled = true;
                if (submitText) submitText.textContent = 'Đang xác thực...';
            }

            const formData = new FormData(pwdForm);

            fetch(pwdForm.action, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(async res => {
                const data = await res.json().catch(() => ({}));
                if (!res.ok) throw new Error(data.message || 'Đổi mật khẩu thất bại!');
                return data;
            })
            .then(data => {
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.style.backgroundColor = '#16a34a';
                    if (submitText) submitText.textContent = 'Đổi thành công!';
                    setTimeout(() => {
                        submitBtn.style.backgroundColor = '#0f172a';
                        if (submitText) submitText.textContent = 'Cập nhật mật khẩu';
                    }, 2500);
                }

                if (alertBox) {
                    alertBox.style.display = 'flex';
                    alertBox.style.backgroundColor = '#f0fdf4';
                    alertBox.style.borderColor = '#bbf7d0';
                    alertBox.style.color = '#15803d';
                    alertBox.innerHTML = '<i class="fa-solid fa-circle-check"></i> <span>' + (data.message || 'Đổi mật khẩu thành công!') + '</span>';
                }

                pwdForm.reset();

                if (typeof showThemeToast === 'function') {
                    showThemeToast(data.message || 'Đổi mật khẩu thành công!', 'success');
                }
            })
            .catch(err => {
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.style.backgroundColor = '#0f172a';
                    if (submitText) submitText.textContent = 'Cập nhật mật khẩu';
                }
                if (alertBox) {
                    alertBox.style.display = 'flex';
                    alertBox.style.backgroundColor = '#fef2f2';
                    alertBox.style.borderColor = '#fecaca';
                    alertBox.style.color = '#b91c1c';
                    alertBox.innerHTML = '<i class="fa-solid fa-circle-exclamation"></i> <span>' + (err.message || 'Đổi mật khẩu thất bại!') + '</span>';
                }
            });
        });
    }
});
