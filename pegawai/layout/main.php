<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
if (empty($_SESSION['login']) || !isset($_SESSION["role"]) || $_SESSION["role"] !== 'pegawai') {
  header("Location: ../../auth/login.php");
  exit;
}
require_once __DIR__ . '/../../config/config.php';
global $judul;
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
  <meta http-equiv="X-UA-Compatible" content="ie=edge" />
  <title><?= $judul ?> - Dashboard</title>

  <!-- Main css files -->
  <link href="<?= base_url('assets/css/tabler.min.css?1692870487') ?>" rel="stylesheet" />
  <link href="<?= base_url('assets/css/tabler-vendors.min.css?1692870487') ?>" rel="stylesheet" />
  <link href="<?= base_url('assets/css/demo.min.css?1692870487') ?>" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css"
    integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
  <style>
    @import url('https://rsms.me/inter/inter.css');

    :root {
      --tblr-font-sans-serif: 'Inter Var', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;
    }

    body {
      font-feature-settings: "cv03", "cv04", "cv11";
    }
  </style>

  <!-- Main js scripts -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"
    integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.26/webcam.min.js"
    integrity="sha512-dQIiHSl2hr3NWKKLycPndtpbh5iaHLo6MwrXm7F0FM5e+kL2U16oE9uIwPHUl6fQBeCthiEuV/rzP3MiAB8Vfw=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
    integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
</head>

<body>
  <div class="page">

    <?php include_once __DIR__ . '/navbar.php'; ?>

    <div class="page-wrapper">
      <?= $content ?>
    </div>

    <?php include_once __DIR__ . '/footer.php'; ?>
  </div>

  <?php if (isset($_SESSION['login_success']) && $_SESSION['login_success']) { ?>
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
        title: "Login Berhasil!",
        text: '<?= $_SESSION['message'] ?>',
      });
    </script>
  <?php }
  unset($_SESSION['message']);
  unset($_SESSION['login_success']);
  ?>

  <?php if (isset($_SESSION['validasi']) || isset($_SESSION['berhasil']) || isset($_SESSION['gagal'])) { ?>
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
        icon: "<?php
        if (isset($_SESSION['berhasil'])) {
          echo 'success';
        } elseif (isset($_SESSION['validasi'])) {
          echo 'warning';
        } elseif (isset($_SESSION['gagal'])) {
          echo 'error';
        }
        ?>",
      title: "<?php
      if (isset($_SESSION['berhasil'])) {
        echo 'Sukses';
      } elseif (isset($_SESSION['validasi'])) {
        echo 'Peringatan';
      } elseif (isset($_SESSION['gagal'])) {
        echo 'Gagal';
      }
      ?>",
      text: "<?php
      if (isset($_SESSION['berhasil'])) {
        echo $_SESSION['berhasil'];
      } elseif (isset($_SESSION['validasi'])) {
        echo $_SESSION['validasi'];
      } elseif (isset($_SESSION['gagal'])) {
        echo $_SESSION['gagal'];
      }
      ?>",
          });
    </script>
  <?php }
  unset($_SESSION['berhasil']);
  unset($_SESSION['validasi']);
  unset($_SESSION['gagal']);
  ?>
</body>

</html>