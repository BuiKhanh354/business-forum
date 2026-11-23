// Hàm chuyển Tab cài đặt
    window.switchThemeTab = function(tabId, btn) {
        document.querySelectorAll('.theme-tab-pane').forEach(el => el.style.display = 'none');
        const target = document.getElementById(tabId);
        if (target) target.style.display = 'block';

        document.querySelectorAll('.tab-nav-btn').forEach(b => {
            b.classList.remove('active');
            b.style.background = '';
            b.style.color = '';
            b.style.borderColor = '';
        });

        const activeBtn = btn || document.querySelector(`.tab-nav-btn[onclick*="${tabId}"]`);
        if (activeBtn) {
            activeBtn.classList.add('active');
            activeBtn.style.background = 'var(--executive-blue)';
            activeBtn.style.color = '#ffffff';
            activeBtn.style.borderColor = 'var(--executive-blue)';
        }

        try {
            localStorage.setItem('active_theme_tab', tabId);
        } catch(e) {}
    };

    // Hàm hiển thị thông báo Toast nổi đẹp mắt không cần tải lại trang
    window.showThemeToast = function(message, type = 'success') {
        let container = document.getElementById('themeToastContainer');
        if (!container) {
            container = document.createElement('div');
            container.id = 'themeToastContainer';
            container.style = 'position: fixed; top: 24px; right: 24px; z-index: 99999; display: flex; flex-direction: column; gap: 10px; pointer-events: none;';
            document.body.appendChild(container);
        }

        const toast = document.createElement('div');
        const isSuccess = type === 'success';
        toast.style = `
            pointer-events: auto;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 20px;
            background: ${isSuccess ? '#065f46' : '#991b1b'};
            color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            font-size: 0.9rem;
            font-weight: 700;
            opacity: 0;
            transform: translateY(-10px);
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        `;

        toast.innerHTML = `
            <i class="fa-solid ${isSuccess ? 'fa-circle-check' : 'fa-circle-exclamation'}" style="font-size: 1.25rem; color: ${isSuccess ? '#34d399' : '#f87171'}; flex-shrink: 0;"></i>
            <span>${message}</span>
            <button type="button" style="background: none; border: none; color: #ffffff; opacity: 0.7; font-size: 1.1rem; cursor: pointer; margin-left: 8px; padding: 0 4px;" onclick="this.parentElement.remove()">&times;</button>
        `;

        container.appendChild(toast);
        requestAnimationFrame(() => {
            toast.style.opacity = '1';
            toast.style.transform = 'translateY(0)';
        });

        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateY(-10px)';
            setTimeout(() => toast.remove(), 350);
        }, 4000);
    };

    // Hàm chuyển đổi chế độ xem Cover Máy tính / Điện thoại
    window.switchCoverDevice = function(device) {
        const deskBtn = document.getElementById('tabCoverDesktop');
        const mobBtn = document.getElementById('tabCoverMobile');
        const deskPanel = document.getElementById('panelCoverDesktop');
        const mobPanel = document.getElementById('panelCoverMobile');

        if (device === 'desktop') {
            deskBtn.classList.add('active');
            deskBtn.style.background = 'var(--executive-blue)';
            deskBtn.style.color = '#fff';
            mobBtn.classList.remove('active');
            mobBtn.style.background = '';
            mobBtn.style.color = '';

            deskPanel.style.display = 'block';
            mobPanel.style.display = 'none';
        } else {
            mobBtn.classList.add('active');
            mobBtn.style.background = '#16a34a';
            mobBtn.style.color = '#fff';
            deskBtn.classList.remove('active');
            deskBtn.style.background = '';
            deskBtn.style.color = '';

            deskPanel.style.display = 'none';
            mobPanel.style.display = 'block';
        }
    };

    // Hàm chọn chiều cao Cover
    window.selectCoverHeight = function(height, device, btn) {
        if (device === 'desktop') {
            document.getElementById('inputCoverHeightDesktop').value = height;
            localStorage.setItem('site-cover-height', height);

            const preview = document.getElementById('themeCoverPreviewBox');
            if (preview) {
                preview.style.height = height;
                preview.style.minHeight = height;
            }
            const text = document.getElementById('coverCurrentHeightText');
            if (text) text.textContent = `Chiều cao: ${height}`;

            document.querySelectorAll('#coverHeightBtnGroup button').forEach(b => {
                b.classList.remove('active');
            });
            btn.classList.add('active');
        } else {
            document.getElementById('inputCoverHeightMobile').value = height;
            localStorage.setItem('site-cover-height-mobile', height);

            const preview = document.getElementById('themeCoverPreviewMobileBox');
            if (preview) {
                preview.style.height = height;
                preview.style.minHeight = height;
            }
            const text = document.getElementById('coverCurrentMobileHeightText');
            if (text) text.textContent = `Chiều cao: ${height}`;

            document.querySelectorAll('#coverMobileHeightBtnGroup button').forEach(b => {
                b.classList.remove('active');
            });
            btn.classList.add('active');
        }
    };

    // Hàm chọn ảnh bìa từ danh sách có sẵn / ảnh cũ
    window.selectCoverPreset = function(filePath, previewBoxId, inputTargetId, cardElement, gridId) {
        const preview = document.getElementById(previewBoxId);
        const fullUrl = window.resolveAssetUrl ? window.resolveAssetUrl(filePath) : ((window.assetBaseUrl || '/') + filePath.replace(/^\/+/, ''));
        if (preview) {
            preview.style.backgroundImage = `url('${fullUrl}')`;
        }
        document.getElementById(inputTargetId).value = filePath;

        // Reset file upload input tương ứng nếu đang chọn ảnh cũ/preset
        if (inputTargetId === 'inputCoverDesktop') {
            const fileInp = document.getElementById('coverDesktopFileInput');
            if (fileInp) fileInp.value = '';
        } else if (inputTargetId === 'inputCoverMobile') {
            const fileInp = document.getElementById('coverMobileFileInput');
            if (fileInp) fileInp.value = '';
        }

        const grid = document.getElementById(gridId);
        if (grid) {
            const isDesk = (previewBoxId === 'themeCoverPreviewBox');
            const activeColor = isDesk ? 'var(--executive-blue)' : '#16a34a';
            const activeBg = isDesk ? 'var(--executive-blue-light)' : '#dcfce7';

            grid.querySelectorAll('.cover-quick-card, .cover-choice-card').forEach(c => {
                c.style.borderColor = '#cbd5e1';
                c.classList.remove('active');
                const pill = c.querySelector('.preset-status-pill');
                if (pill) {
                    pill.textContent = 'Dùng lại ảnh này';
                    pill.style.background = '#f1f5f9';
                    pill.style.color = 'var(--text-muted)';
                }
            });

            if (cardElement) {
                cardElement.style.borderColor = activeColor;
                cardElement.classList.add('active');
                const pill = cardElement.querySelector('.preset-status-pill');
                if (pill) {
                    pill.textContent = 'Đang chọn';
                    pill.style.background = activeBg;
                    pill.style.color = activeColor;
                }
            }
        }
    };

    // Khôi phục ảnh mẫu gốc
    window.resetCoverToPreset = function(filePath, previewBoxId, inputTargetId, gridId) {
        const grid = document.getElementById(gridId);
        const firstCard = grid ? grid.querySelector('.cover-quick-card, .cover-choice-card') : null;
        window.selectCoverPreset(filePath, previewBoxId, inputTargetId, firstCard, gridId);
    };

    // Xem trước file ảnh bìa tải lên từ máy tính
    window.handleCoverUpload = function(input, previewBoxId, inputTargetId, gridId) {
        if (!input.files || !input.files[0]) return;
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById(previewBoxId);
            if (preview) {
                preview.style.backgroundImage = `url('${e.target.result}')`;
            }
            
            // Bỏ chọn các card ảnh cũ khi tải file mới lên
            const targetGridId = gridId || (inputTargetId === 'inputCoverMobile' ? 'coverMobileHistoryGrid' : 'coverDesktopHistoryGrid');
            const grid = document.getElementById(targetGridId);
            if (grid) {
                grid.querySelectorAll('.cover-quick-card, .cover-choice-card').forEach(c => {
                    c.style.borderColor = '#cbd5e1';
                    c.classList.remove('active');
                    const pill = c.querySelector('.preset-status-pill');
                    if (pill) {
                        pill.textContent = 'Dùng lại ảnh này';
                        pill.style.background = '#f1f5f9';
                        pill.style.color = 'var(--text-muted)';
                    }
                });
            }
        };
        reader.readAsDataURL(input.files[0]);
    };

    // Xem trước file Favicon
    window.previewFaviconFile = function(input) {
        if (!input.files || !input.files[0]) return;
        const reader = new FileReader();
        reader.onload = function(e) {
            const box = document.getElementById('siteFaviconPreviewBox');
            box.innerHTML = `<img src="${e.target.result}" style="max-height: 100%; max-width: 100%; object-fit: contain;">`;
        };
        reader.readAsDataURL(input.files[0]);
    };

    // Lựa chọn độ bo góc
    window.selectBorderRadius = function(radiusType, btn) {
        document.getElementById('inputBorderRadius').value = radiusType;
        document.querySelectorAll('.radius-option-btn').forEach(b => {
            b.style.borderColor = '#cbd5e1';
            b.classList.remove('active');
        });
        btn.style.borderColor = 'var(--executive-blue)';
        btn.classList.add('active');

        if (radiusType === 'sharp') {
            document.documentElement.style.setProperty('--radius-sm', '3px');
            document.documentElement.style.setProperty('--radius-md', '4px');
        } else if (radiusType === 'round') {
            document.documentElement.style.setProperty('--radius-sm', '14px');
            document.documentElement.style.setProperty('--radius-md', '20px');
        } else {
            document.documentElement.style.setProperty('--radius-sm', '8px');
            document.documentElement.style.setProperty('--radius-md', '12px');
        }
    };

    // Khởi tạo tương tác màu sắc và HSV Drag Spectrum
    document.addEventListener('DOMContentLoaded', () => {
        // Mode options
        document.querySelectorAll('.theme-mode-option').forEach(opt => {
            opt.addEventListener('click', () => {
                const mode = opt.dataset.mode;
                document.getElementById('inputThemeMode').value = mode;
                localStorage.setItem('site-mode', mode);

                document.querySelectorAll('.theme-mode-option').forEach(o => {
                    o.classList.toggle('active', o === opt);
                    o.style.borderColor = o === opt ? 'var(--executive-blue)' : '';
                });

                if (mode === 'dark') {
                    document.documentElement.setAttribute('data-theme', 'dark');
                    document.body.classList.add('theme-dark');
                } else {
                    document.body.classList.remove('theme-dark');
                    const preset = document.getElementById('inputThemePreset').value;
                    if (preset && preset !== 'default' && preset !== 'custom') {
                        document.documentElement.setAttribute('data-theme', preset);
                    } else {
                        document.documentElement.removeAttribute('data-theme');
                    }
                }
            });
        });

        // Theme Preset options
        document.querySelectorAll('.theme-preset-btn[data-theme-name]').forEach(btn => {
            btn.addEventListener('click', () => {
                const name = btn.dataset.themeName;
                const primary = btn.dataset.primary;
                document.getElementById('inputThemePreset').value = name;

                document.querySelectorAll('.theme-preset-btn[data-theme-name]').forEach(b => {
                    b.classList.toggle('active', b === btn);
                    b.style.borderColor = b === btn ? 'var(--executive-blue)' : '';
                });

                if (primary) {
                    document.getElementById('inputPrimaryColor').value = primary;
                    document.getElementById('pickerPrimary').value = primary;
                    document.documentElement.style.setProperty('--executive-blue', primary);
                    document.documentElement.style.setProperty('--primary-blue', primary);
                    document.documentElement.style.setProperty('--executive-blue-light', primary + '25');
                    document.documentElement.style.setProperty('--executive-blue-border', primary + '55');
                }

                if (document.getElementById('inputThemeMode').value !== 'dark') {
                    if (name === 'default') {
                        document.documentElement.removeAttribute('data-theme');
                    } else {
                        document.documentElement.setAttribute('data-theme', name);
                    }
                }
            });
        });

        // Bảng màu kéo HSV Spectrum 2D
        const dragSpectrumBox = document.getElementById('dragSpectrumBox');
        const dragSpectrumHandle = document.getElementById('dragSpectrumHandle');
        const dragHueSlider = document.getElementById('dragHueSlider');
        const hueDegreeText = document.getElementById('hueDegreeText');
        const inputPrimary = document.getElementById('inputPrimaryColor');
        const pickerPrimary = document.getElementById('pickerPrimary');

        let currentHue = 221;
        let currentSat = 84;
        let currentVal = 92;
        let isDragging = false;

        function hsvToRgb(h, s, v) {
            s = s / 100;
            v = v / 100;
            const c = v * s;
            const x = c * (1 - Math.abs(((h / 60) % 2) - 1));
            const m = v - c;
            let r = 0, g = 0, b = 0;
            if (h >= 0 && h < 60) { r = c; g = x; b = 0; }
            else if (h >= 60 && h < 120) { r = x; g = c; b = 0; }
            else if (h >= 120 && h < 180) { r = 0; g = c; b = x; }
            else if (h >= 180 && h < 240) { r = 0; g = x; b = c; }
            else if (h >= 240 && h < 300) { r = x; g = 0; b = c; }
            else if (h >= 300 && h <= 360) { r = c; g = 0; b = x; }
            return {
                r: Math.round((r + m) * 255),
                g: Math.round((g + m) * 255),
                b: Math.round((b + m) * 255)
            };
        }

        function rgbToHex(r, g, b) {
            return '#' + [r, g, b].map(x => x.toString(16).padStart(2, '0')).join('').toUpperCase();
        }

        function updateSpectrumColor() {
            const rgb = hsvToRgb(currentHue, currentSat, currentVal);
            const hex = rgbToHex(rgb.r, rgb.g, rgb.b);

            if (inputPrimary) inputPrimary.value = hex;
            if (pickerPrimary) pickerPrimary.value = hex;
            if (dragSpectrumHandle) dragSpectrumHandle.style.backgroundColor = hex;

            document.documentElement.style.setProperty('--executive-blue', hex);
            document.documentElement.style.setProperty('--primary-blue', hex);
            document.documentElement.style.setProperty('--executive-blue-light', hex + '18');
            document.documentElement.style.setProperty('--executive-blue-border', hex + '40');
            document.getElementById('inputThemePreset').value = 'custom';
        }

        function handleDragPosition(e) {
            if (!dragSpectrumBox) return;
            const rect = dragSpectrumBox.getBoundingClientRect();
            let clientX = e.clientX !== undefined ? e.clientX : (e.touches ? e.touches[0].clientX : 0);
            let clientY = e.clientY !== undefined ? e.clientY : (e.touches ? e.touches[0].clientY : 0);

            let x = Math.max(0, Math.min(rect.width, clientX - rect.left));
            let y = Math.max(0, Math.min(rect.height, clientY - rect.top));

            dragSpectrumHandle.style.left = x + 'px';
            dragSpectrumHandle.style.top = y + 'px';

            currentSat = Math.round((x / rect.width) * 100);
            currentVal = Math.round((1 - y / rect.height) * 100);

            updateSpectrumColor();
        }

        if (dragSpectrumBox) {
            dragSpectrumBox.addEventListener('mousedown', (e) => {
                isDragging = true;
                handleDragPosition(e);
            });
            window.addEventListener('mousemove', (e) => {
                if (isDragging) handleDragPosition(e);
            });
            window.addEventListener('mouseup', () => { isDragging = false; });

            dragSpectrumBox.addEventListener('touchstart', (e) => {
                isDragging = true;
                handleDragPosition(e);
            });
            window.addEventListener('touchmove', (e) => {
                if (isDragging) handleDragPosition(e);
            });
            window.addEventListener('touchend', () => { isDragging = false; });
        }

        if (dragHueSlider) {
            dragHueSlider.addEventListener('input', (e) => {
                currentHue = parseInt(e.target.value, 10);
                if (dragSpectrumBox) dragSpectrumBox.style.backgroundColor = `hsl(${currentHue}, 100%, 50%)`;
                if (hueDegreeText) hueDegreeText.textContent = `${currentHue}°`;
                updateSpectrumColor();
            });
        }

        // Đồng bộ Color Pickers với Text Inputs
        const bindColorPair = (pickerId, inputId, cssVar) => {
            const picker = document.getElementById(pickerId);
            const input = document.getElementById(inputId);
            if (!picker || !input) return;

            picker.addEventListener('input', (e) => {
                input.value = e.target.value.toUpperCase();
                if (cssVar) document.documentElement.style.setProperty(cssVar, e.target.value);
            });

            input.addEventListener('input', (e) => {
                const val = e.target.value.trim();
                if (/^#?[0-9A-Fa-f]{6}$/.test(val)) {
                    const formatted = val.startsWith('#') ? val.toUpperCase() : '#' + val.toUpperCase();
                    picker.value = formatted;
                    if (cssVar) document.documentElement.style.setProperty(cssVar, formatted);
                }
            });
        };

        bindColorPair('pickerPrimary', 'inputPrimaryColor', '--executive-blue');
        bindColorPair('pickerAccent', 'inputAccentColor', '--gold-accent');
        bindColorPair('pickerBg', 'inputBgColor', '--bg-app');
        bindColorPair('pickerCard', 'inputCardColor', '--bg-card');

        // Tự động khôi phục tab đang mở từ localStorage (ngăn tải lại trang bị chuyển sang tab khác)
        try {
            const savedTab = localStorage.getItem('active_theme_tab');
            if (savedTab && document.getElementById(savedTab)) {
                window.switchThemeTab(savedTab);
            }
        } catch(e) {}

        // Bắt sự kiện lưu form Cài đặt giao diện bằng AJAX ngầm (Không reload trang, không nhảy tab)
        const themeForm = document.getElementById('themeSettingsForm');
        if (themeForm) {
            themeForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const submitBtn = themeForm.querySelector('button[type="submit"]');
                const origBtnContent = submitBtn ? submitBtn.innerHTML : '';
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Đang lưu cấu hình...';
                }

                const formData = new FormData(themeForm);

                fetch(themeForm.action, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(async res => {
                    const data = await res.json().catch(() => ({}));
                    if (!res.ok) {
                        throw new Error(data.message || 'Lưu cấu hình thất bại (Mã lỗi ' + res.status + ')');
                    }
                    return data;
                })
                .then(data => {
                    const savedModeVal = document.getElementById('inputThemeMode')?.value;
                    if (savedModeVal) {
                        localStorage.setItem('site-mode', savedModeVal);
                    }
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        submitBtn.style.backgroundColor = '#16a34a';
                        submitBtn.innerHTML = '<i class="fa-solid fa-check"></i> Đã lưu & áp dụng!';
                        setTimeout(() => {
                            submitBtn.style.backgroundColor = 'var(--executive-blue)';
                            submitBtn.innerHTML = origBtnContent;
                        }, 2500);
                    }
                    window.showThemeToast(data.message || 'Đã lưu và áp dụng toàn bộ cài đặt giao diện thành công!', 'success');
                })
                .catch(err => {
                    console.error(err);
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = origBtnContent;
                    }
                    window.showThemeToast('Có lỗi xảy ra: ' + (err.message || 'Vui lòng kiểm tra lại!'), 'error');
                });
            });
        }
    });