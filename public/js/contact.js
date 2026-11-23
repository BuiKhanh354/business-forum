document.addEventListener('DOMContentLoaded', function () {
    const uploadZone = document.getElementById('uploadZoneTrigger');
    const fileInput = document.getElementById('fileAttachment');
    const uploadStatus = document.getElementById('uploadStatusText');
    const uploadIcon = document.getElementById('uploadIconWrapper');

    if (uploadZone && fileInput) {
        uploadZone.addEventListener('click', function () {
            fileInput.click();
        });

        fileInput.addEventListener('change', function () {
            if (this.files && this.files.length > 0) {
                const file = this.files[0];
                const sizeMb = (file.size / (1024 * 1024)).toFixed(2);
                uploadStatus.innerHTML = '<strong style="color: #16a34a;">' + file.name + '</strong> (' + sizeMb + ' MB) - Đã sẵn sàng gửi';
                uploadZone.style.borderColor = '#16a34a';
                uploadZone.style.backgroundColor = '#f0fdf4';
                if (uploadIcon) {
                    uploadIcon.innerHTML = '<i class="fa-solid fa-file-circle-check" style="color: #16a34a; font-size: 1.5rem;"></i>';
                }
            }
        });

        ['dragenter', 'dragover'].forEach(eventName => {
            uploadZone.addEventListener(eventName, function (e) {
                e.preventDefault();
                uploadZone.style.borderColor = 'var(--executive-blue)';
                uploadZone.style.backgroundColor = '#eff6ff';
            }, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            uploadZone.addEventListener(eventName, function (e) {
                e.preventDefault();
                uploadZone.style.borderColor = '';
                uploadZone.style.backgroundColor = '';
            }, false);
        });

        uploadZone.addEventListener('drop', function (e) {
            if (e.dataTransfer && e.dataTransfer.files.length > 0) {
                fileInput.files = e.dataTransfer.files;
                fileInput.dispatchEvent(new Event('change'));
            }
        });
    }

    const btnReset = document.getElementById('btnResetForm');
    if (btnReset && uploadStatus && uploadZone) {
        btnReset.addEventListener('click', function () {
            uploadStatus.textContent = 'Nhấp để chọn file hoặc kéo thả tài liệu vào đây';
            uploadZone.style.borderColor = '';
            uploadZone.style.backgroundColor = '';
            if (uploadIcon) {
                uploadIcon.innerHTML = '<i class="fa-solid fa-cloud-arrow-up"></i>';
            }
        });
    }
});