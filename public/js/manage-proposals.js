function openProposalModal(item) {
  const modal = document.getElementById('proposalDetailModal');
  if (!modal) return;

  document.getElementById('modalSubjectText').textContent = item.subject || 'Không có tiêu đề';
  document.getElementById('modalSenderName').textContent = item.name || '';
  
  let roleCompany = [];
  if (item.role) roleCompany.push(item.role);
  if (item.company) roleCompany.push(item.company);
  document.getElementById('modalSenderRoleCompany').textContent = roleCompany.join(' • ');

  const emailElem = document.getElementById('modalSenderEmail');
  emailElem.textContent = item.email || '';
  emailElem.href = 'mailto:' + (item.email || '');

  const phoneElem = document.getElementById('modalSenderPhone');
  phoneElem.textContent = item.phone || '';
  phoneElem.href = 'tel:' + (item.phone || '');

  document.getElementById('modalPurposeBadge').textContent = item.purpose || 'Đề xuất';
  
  const createdDate = new Date(item.created_at);
  document.getElementById('modalCreatedAtText').textContent = 'Thời gian gửi: ' + createdDate.toLocaleString('vi-VN');

  document.getElementById('modalMessageContent').textContent = item.message || '';

  // File đính kèm
  const attachmentWrapper = document.getElementById('modalAttachmentWrapper');
  if (item.attachment) {
    attachmentWrapper.style.display = 'block';
    const fileName = item.attachment_name || 'Ho-so-de-xuat';
    document.getElementById('modalAttachmentFileName').textContent = fileName;
    
    // Cập nhật biểu tượng và màu sắc theo đuôi file
    const ext = fileName.split('.').pop().toLowerCase();
    const iconElem = document.getElementById('modalAttachmentIcon');
    const iconBox = document.getElementById('modalAttachmentIconBox');

    if (iconElem) {
      if (['pdf'].includes(ext)) {
        iconElem.className = 'fa-solid fa-file-pdf';
        iconElem.style.color = '#ef4444';
        if (iconBox) iconBox.style.borderColor = '#fecaca';
      } else if (['doc', 'docx'].includes(ext)) {
        iconElem.className = 'fa-solid fa-file-word';
        iconElem.style.color = '#2563eb';
        if (iconBox) iconBox.style.borderColor = '#bfdbfe';
      } else if (['xls', 'xlsx'].includes(ext)) {
        iconElem.className = 'fa-solid fa-file-excel';
        iconElem.style.color = '#16a34a';
        if (iconBox) iconBox.style.borderColor = '#bbf7d0';
      } else if (['ppt', 'pptx'].includes(ext)) {
        iconElem.className = 'fa-solid fa-file-powerpoint';
        iconElem.style.color = '#ea580c';
        if (iconBox) iconBox.style.borderColor = '#fed7aa';
      } else if (['zip', 'rar', '7z'].includes(ext)) {
        iconElem.className = 'fa-solid fa-file-zipper';
        iconElem.style.color = '#ca8a04';
        if (iconBox) iconBox.style.borderColor = '#fef08a';
      } else if (['png', 'jpg', 'jpeg', 'webp', 'svg'].includes(ext)) {
        iconElem.className = 'fa-solid fa-file-image';
        iconElem.style.color = '#9333ea';
        if (iconBox) iconBox.style.borderColor = '#e9d5ff';
      } else {
        iconElem.className = 'fa-solid fa-file-lines';
        iconElem.style.color = '#475569';
        if (iconBox) iconBox.style.borderColor = '#e2e8f0';
      }
    }

    const downloadBtn = document.getElementById('modalAttachmentDownloadBtn');
    downloadBtn.href = '/admin/de-xuat-hop-tac/' + item.id + '/tai-file';
    downloadBtn.setAttribute('title', 'Tải về máy: ' + fileName);
  } else {
    attachmentWrapper.style.display = 'none';
  }

  // Admin notes form
  document.getElementById('modalAdminNotesInput').value = item.admin_notes || '';
  document.getElementById('modalAdminNotesForm').action = '/admin/de-xuat-hop-tac/' + item.id + '/ghi-chu';

  // Nếu đề xuất chưa đọc, tự động đánh dấu đã đọc và giảm số trên chuông thông báo
  if (!item.is_read) {
    item.is_read = true;
    const csrfMeta = document.querySelector('meta[name="csrf-token"]');
    const token = csrfMeta ? csrfMeta.getAttribute('content') : '';

    fetch('/admin/thong-bao/doc-muc', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': token,
        'Accept': 'application/json'
      },
      body: JSON.stringify({ type: 'proposal', id: item.id })
    })
    .then(function (res) { return res.json(); })
    .then(function (data) {
      if (data && typeof data.unread_count !== 'undefined' && window.updateBellNotificationBadge) {
        window.updateBellNotificationBadge(data.unread_count, data.proposals_count, data.comments_count);
      }
    })
    .catch(function () {});
  }

  modal.style.display = 'flex';
  document.body.style.overflow = 'hidden';
}

function closeProposalModal() {
  const modal = document.getElementById('proposalDetailModal');
  if (modal) {
    modal.style.display = 'none';
    document.body.style.overflow = '';
  }
}

// Đóng modal khi click ra ngoài vùng backdrop & xử lý lưu ghi chú qua AJAX
document.addEventListener('DOMContentLoaded', function () {
  const modal = document.getElementById('proposalDetailModal');
  if (modal) {
    modal.addEventListener('click', function (e) {
      if (e.target === modal) {
        closeProposalModal();
      }
    });
  }

  // Bắt sự kiện lưu ghi chú đề xuất bằng AJAX (Không reload trang)
  const notesForm = document.getElementById('modalAdminNotesForm');
  if (notesForm) {
    notesForm.addEventListener('submit', function (e) {
      e.preventDefault();
      const submitBtn = notesForm.querySelector('button[type="submit"]');
      const origHtml = submitBtn ? submitBtn.innerHTML : '';
      if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Đang lưu...';
      }

      fetch(notesForm.action, {
        method: 'POST',
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'Accept': 'application/json'
        },
        body: new FormData(notesForm)
      })
      .then(async res => {
        const data = await res.json().catch(() => ({}));
        if (!res.ok) throw new Error(data.message || 'Lỗi lưu: ' + res.status);
        return data;
      })
      .then(data => {
        if (submitBtn) {
          submitBtn.disabled = false;
          submitBtn.style.backgroundColor = '#16a34a';
          submitBtn.innerHTML = '<i class="fa-solid fa-check"></i> Đã lưu ghi chú!';
          setTimeout(() => {
            submitBtn.style.backgroundColor = 'var(--executive-blue)';
            submitBtn.innerHTML = origHtml;
          }, 2000);
        }
      })
      .catch(err => {
        console.error(err);
        if (submitBtn) {
          submitBtn.disabled = false;
          submitBtn.innerHTML = origHtml;
        }
        alert('Có lỗi xảy ra khi lưu ghi chú. Vui lòng thử lại!');
      });
    });
  }

  // Tự động mở modal xem chi tiết đề xuất nếu có thẻ #autoOpenProposalData
  const autoOpenProposalEl = document.getElementById('autoOpenProposalData');
  if (autoOpenProposalEl) {
    try {
      const proposalData = JSON.parse(autoOpenProposalEl.getAttribute('data-proposal'));
      if (proposalData && typeof openProposalModal === 'function') {
        setTimeout(() => {
          openProposalModal(proposalData);
        }, 150);
      }
    } catch (e) {
      console.error('Lỗi phân tích autoOpenProposalData:', e);
    }
  }
});