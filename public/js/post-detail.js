window.openLightbox = function(src) {
    const modal = document.getElementById('imageLightboxModal');
    const img = document.getElementById('lightboxImg');
    if (modal && img) {
      img.src = src;
      modal.style.display = 'flex';
      document.body.style.overflow = 'hidden';
    }
  };