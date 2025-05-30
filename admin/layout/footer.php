<footer class="footer footer-transparent d-print-none">
  <div class="container-xl">
    <div class="row text-center align-items-center flex-row-reverse">
      <div class="col-lg-auto ms-lg-auto">
        <ul class="list-inline list-inline-dots mb-0">
          <li class="list-inline-item">
            <a href="https://www.instagram.com" class="link-secondary" target="_blank">
              <i class="fab fa-instagram"></i> Instagram
            </a>
          </li>
          <li class="list-inline-item">
            <a href="https://www.tiktok.com" class="link-secondary" target="_blank">
              <i class="fab fa-tiktok"></i> TikTok
            </a>
          </li>
        </ul>
      </div>
      <div class="col-12 col-lg-auto mt-3 mt-lg-0">
        <div class="text-center text-muted">
          &copy; <?= date('Y') ?> PDAM Murakata - Kab. Hulu Sungai Tengah. All rights reserved.
        </div>
      </div>
    </div>
  </div>
</footer>

<!-- alert validasi -->
<?php if (isset($_SESSION['validasi'])): ?>

  <script>
    const Toast = Swal.mixin({
      toast: true,
      position: "top-end",
      showConfirmButton: false,
      timer: 3000,
      timerProgressBar: true,
      didOpen: (toast) => {
        toast.onmouseenter = Swal.stopTimer;
        toast.onmouseleave = Swal.resumeTimer;
      }
    });
    Toast.fire({
      icon: "error",
      title: "<?= $_SESSION['validasi'] ?>"
    });
  </script>
  <?php unset($_SESSION['validasi']); ?>

<?php endif; ?>

<!-- alert berhasil -->
<?php if (isset($_SESSION['berhasil'])): ?>
  <script>
    const Berhasil = Swal.mixin({
      toast: true,
      position: "top-end",
      showConfirmButton: false,
      timer: 3000,
      timerProgressBar: true,
      didOpen: (toast) => {
        toast.onmouseenter = Swal.stopTimer;
        toast.onmouseleave = Swal.resumeTimer;
      }
    });
    Berhasil.fire({
      icon: "success",
      title: "<?= $_SESSION['berhasil'] ?>"
    });
  </script>
  <?php unset($_SESSION['berhasil']); ?>

<?php endif; ?>


<!--alert konfirmasi -->
<script>
  $('.tombol-hapus').on('click', function () {
    var getlink = $(this).attr('href');
    Swal.fire({
      title: "Yakin hapus?",
      text: "Data  yang sudah dihapus tidak bisa dikembalikan",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Ya, hapus"
    }).then((result) => {
      if (result.isConfirmed) {
        window.location.href = getlink
      }
    });
    return false;
  });
</script>