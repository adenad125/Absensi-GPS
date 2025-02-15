<?php
session_start();
ob_start();

if (!isset($_SESSION["login"])) {
  header("Location: ../../auth/login.php?pesan=belum_login");
} else if ($_SESSION["role"] !== 'admin') {
  header("Location: ../../auth/login.php?pesan=tolak_akses");
}

$judul = "Edit Data Jabatan";
include('../layout/header.php');
require_once 'C:/laragon/www/PRESENSI/config/config.php';

if (isset($_POST['update'])) {
  $id = $_POST['id'];
  $nama_jabatan = htmlspecialchars($_POST['nama_jabatan']);

  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($nama_jabatan)) {
      $pesan_kesalahan = "Nama Jabatan wajib diisi";
    }
    if (!empty($pesan_kesalahan)) {
      $_SESSION['validasi'] = $pesan_kesalahan;
    } else {
      $result = mysqli_query($connection, "UPDATE jabatan SET nama_jabatan='$nama_jabatan' WHERE id=$id ");
      $_SESSION['berhasil'] = "Data berhasil diupdate";
      header("location: ./");
      exit;
    }
  }
}


// $id = $_GET['id'];
$id = isset($_GET['id']) ? $_GET['id'] : $_POST['id'];
$result = mysqli_query($connection, "SELECT * FROM jabatan WHERE id=$id");

while ($jabatan = mysqli_fetch_array($result)) {
  $nama_jabatan = $jabatan['nama_jabatan'];
}
?>

<!-- Page body -->
<div class="page-body">
  <div class="container-xl">

    <div class="card col-md-6">
      <div class="card-body">

        <form action="edit.php" method="POST">
          <div class="mb-3">
            <label for="">Nama Jabatan</label>
            <input type="text" class="form-control" name="nama_jabatan" value="<?= $nama_jabatan ?>">
          </div>
          <input type="hidden" value="<?= $id ?> " name="id">
          <button type="submit" name="update" class="btn btn-primary">Update</button>
        </form>

      </div>
    </div>
  </div>
</div>

<?php include('../layout/footer.php'); ?>