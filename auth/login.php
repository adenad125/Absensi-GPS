<?php
session_start();
require_once realpath(__DIR__ . '/../config/config.php');

// Removed redirect to prevent redirect loop.
// The redirect will only happen after a successful login below.
if (isset($_SESSION['login']) && $_SESSION['login'] === true) {
  if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] === 'admin') {
      header("Location: ../admin/home");
      exit();
    } elseif ($_SESSION['role'] === 'pegawai') {
      header("Location: ../pegawai/home");
      exit();
    }
  }
}
if (isset($_POST["login"])) {
  $_SESSION['login_success'] = false;

  $username = $_POST["username"];
  $password = $_POST["password"];

  $stmt = mysqli_prepare($connection, "SELECT * FROM users JOIN pegawai ON users.id_pegawai = pegawai.id WHERE username = ?");
  mysqli_stmt_bind_param($stmt, "s", $username);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);

  if (mysqli_num_rows($result) === 1) {
    $row = mysqli_fetch_assoc($result);

    if (!password_verify($password, $row["password"])) {
      $_SESSION["message"] = "Password salah, silahkan coba lagi";
    } elseif ($row['status'] !== 'aktif') {
      $_SESSION["message"] = "Akun Anda belum aktif";
    } else {
      $_SESSION['login'] = true;
      $_SESSION['id'] = $row['id_pegawai'];
      $_SESSION['role'] = $row['role'];
      $_SESSION['nama'] = $row['nama'];
      $_SESSION['nip'] = $row['nip'];
      $_SESSION['id_jabatan'] = $row['id_jabatan'];
      $_SESSION['id_lok_presensi'] = $row['id_lok_presensi'];
      $_SESSION['login_success'] = true;
      $_SESSION['message'] = "Selamat datang, " . $row['nama'];

      header("Location: ../" . ($row['role'] === 'admin' ? "admin/home" : "pegawai/home"));
      exit();
    }
  } else {
    $_SESSION["message"] = "Username salah, silahkan coba lagi";
  }
}

?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
  <meta http-equiv="X-UA-Compatible" content="ie=edge" />
  <title>Sistem Informasi Absen Berbasis WeB</title>
  <!-- CSS files -->
  <link href="<?= base_url('assets/css/tabler.min.css') ?>" rel="stylesheet" />
  <link href="<?= base_url('assets/css/tabler-vendors.min.css') ?>" rel="stylesheet" />
  <link href="<?= base_url('assets/css/demo.min.css') ?>" rel="stylesheet" />
  <style>
    body {
      background: url('<?= base_url("assets/img/background.png") ?>') no-repeat center center fixed;
      background-size: cover;
      font-feature-settings: "cv03", "cv04", "cv11";
    }

    .card {
      background-color: rgba(255, 255, 255, 0.8);
      border-radius: 10px;
    }
  </style>
</head>

<body class="d-flex flex-column">
  <div class="page page-center">
    <div class="container container-normal py-4">
      <div class="row align-items-center g-4">
        <div class="col-lg">
          <div class="container-tight">
            <div class="card card-md">
              <div class="card-body">
                <div class="container d-flex flex-column justify-content-center align-items-center vh-10">
                  <div class="text-center mb-4">
                    <div class="text-center mb-4">
                      <a href="." class="navbar-brand navbar-brand-autodark">
                        <img src="<?= base_url('assets/img/logo pdam.png') ?>" height="70" alt="">
                      </a>
                    </div>
                    <h2 class="h2 text-center mb-4">Login</h2>
                    <form action="" method="POST" autocomplete="off" novalidate>
                      <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" class="form-control" autofocus name="username" placeholder="Username"
                          autocomplete="off">
                      </div>
                      <div class="mb-2">
                        <label class="form-label">Password</label>
                        <div class="input-group input-group-flat">
                          <input type="password" class="form-control" name="password" placeholder="Password"
                            autocomplete="off">
                        </div>
                      </div>
                      <div class="form-footer">
                        <button type="submit" name="login" class="btn btn-primary w-100">Sign in</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg d-none d-lg-block">
              <img src="<?= base_url('assets/img/') ?>" height="300" class="d-block mx-auto" alt="">
            </div>
          </div>
        </div>
      </div>
</body>

</html>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<?php if (isset($_SESSION['login_success']) && !$_SESSION['login_success']) { ?>
  <script>
    Swal.fire({
      icon: "error",
      title: "Oops...",
      text: "<?= $_SESSION['message']; ?>",
    });
  </script>
<?php }
unset($_SESSION['login_success']);
unset($_SESSION['message']);
?>

<?php if (isset($_GET['status_logout']) && $_GET['status_logout'] == 'success') { ?>
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
      title: "Logout Berhasil!",
      text: "Anda telah keluar dari sesi ini.",
    });
  </script>
<?php } ?>
</body>

</html>