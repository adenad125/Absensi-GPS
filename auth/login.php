<?php 
session_start();

require_once 'C:/laragon/www/PRESENSI/config/config.php';


if(isset($_POST["login"])){
    $username = $_POST["username"];
    $password = $_POST["password"];

    $result = mysqli_query($connection, "SELECT * FROM users JOIN pegawai ON users.id_pegawai = pegawai.id WHERE username = '$username'");

    if(mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);

        if(password_verify($password, $row["password"])) {
            if($row['status'] == 'aktif'){

                $_SESSION['login'] = true;
                $_SESSION['id'] = $row['id'];
                $_SESSION['role'] = $row['role'];
                $_SESSION['nama'] = $row['nama'];
                $_SESSION['nip'] = $row['nip'];
                $_SESSION['id_jabatan'] = $row['id_jabatan'];
                $_SESSION['id_lok_presensi'] = $row['id_lok_presensi'];
                

                if($row['role'] === 'admin'){
                    header("Location: ../admin/home");
                    exit();
                } else {
                    header("Location: ../pegawai/home");
                    exit();
                }
            } else {
                $_SESSION["gagal"] = "Akun Anda belum aktif";
            }
        } else {
            $_SESSION["gagal"] = "Password salah, silahkan coba lagi";
        }
    } else{
        $_SESSION["gagal"] = "Useername salah, silahkan coba lagi";
    }
}

?>

<!doctype html>
<!--
* Tabler - Premium and Open Source dashboard template with responsive and high quality UI.
* @version 1.0.0-beta20
* @link https://tabler.io
* Copyright 2018-2023 The Tabler Authors
* Copyright 2018-2023 codecalm.net Paweł Kuna
* Licensed under MIT (https://github.com/tabler/tabler/blob/master/LICENSE)
-->
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <title>Home</title>
    <!-- CSS files -->
    <link href="<?= base_url('assets/css/tabler.min.css') ?>" rel="stylesheet"/>
    <link href="<?= base_url('assets/css/tabler-vendors.min.css') ?>" rel="stylesheet"/>
    <link href="<?= base_url('assets/css/demo.min.css') ?>" rel="stylesheet"/>
    <style>
      body {
        background: url('<?= base_url("assets/img/background.jpeg") ?>') no-repeat center center fixed;
        background-size: cover;
        font-feature-settings: "cv03", "cv04", "cv11";
      }
      .card {
        background-color: rgba(255, 255, 255, 0.8); /* Transparansi */
        border-radius: 10px; /* Membuat sudut kartu melengkung */
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
                      <img src="<?= base_url('assets/img/logo.jpeg') ?>" height="70" alt="">
                    </a>
                  </div>
                  <h2 class="h2 text-center mb-4">Login</h2>
                  <form action="" method="POST" autocomplete="off" novalidate>
                    <div class="mb-3">
                      <label class="form-label">Username</label>
                      <input type="text" class="form-control" autofocus name="username" placeholder="Username" autocomplete="off">
                    </div>
                    <div class="mb-2">
                      <label class="form-label">Password</label>
                      <div class="input-group input-group-flat">
                        <input type="password" class="form-control" name="password" placeholder="Password" autocomplete="off">
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


    <!-- Libs JS -->
    <script src="<?= base_url('assets/libs/apexcharts/dist/apexcharts.min.js?1692870487') ?>" defer></script>
    <script src="<?= base_url('assets/libs/jsvectormap/dist/js/jsvectormap.min.js?1692870487') ?>" defer></script>
    <script src="<?= base_url('assets/libs/jsvectormap/dist/maps/world.js?1692870487') ?>" defer></script>
    <script src="<?= base_url('assets/libs/jsvectormap/dist/maps/world-merc.js?1692870487') ?>" defer></script>
    
    <script src="<?= base_url('assets/js/tabler.min.js?1692870487') ?>" defer></script>
    <script src="<?= base_url('assets/js/demo.min.js?1692870487') ?>" defer></script>

    <!--sweet alert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    
    <?php if (isset($_SESSION['gagal']) && $_SESSION['gagal']) { ?>
    <script>
        Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "<?= $_SESSION['gagal']; ?>",
        });
    </script>
    <?php unset($_SESSION['gagal']); ?>
<?php } ?>


  </body>
</html>