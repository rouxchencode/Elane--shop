  <div class="container footer-content">
    <div>
      <div class="footer-logo">ELANE</div>
      <p class="footer-text">優雅與隨性並存，陪你穿出最自在的日常模樣。</p>
    </div>
    <div class="footer-text">
      © 2026 ELANE All rights reserved.
    </div>
  </div>

  <!-- GLightbox JS -->
<script src="https://cdn.jsdelivr.net/npm/glightbox/dist/js/glightbox.min.js"></script>
<?php require_once("js_lib.php"); ?>
<!-- Go Top 按鈕與 JS 腳本 -->
<button type="button" id="goTopBtn" class="go-top-btn" aria-label="回到頂部">▲</button>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const goTopBtn = document.getElementById('goTopBtn');

    window.addEventListener('scroll', function () {
        if (window.scrollY > 300) {
            goTopBtn.classList.add('show');
        } else {
            goTopBtn.classList.remove('show');
        }
    });

    goTopBtn.addEventListener('click', function () {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
});
</script>