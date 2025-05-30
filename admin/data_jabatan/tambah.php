<?php
session_start();
ob_start();
$judul = "Tambah Jabatan";

require_once realpath(__DIR__ . '/../../config/config.php');

if (isset($_POST['submit'])) {
  $nama_jabatan = htmlspecialchars($_POST['nama_jabatan']);

  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($nama_jabatan)) {
      $pesan_kesalahan = "Nama Jabatan wajib diisi";
    }
    if (!empty($pesan_kesalahan)) {
      $_SESSION['validasi'] = $pesan_kesalahan;
    } else {
      $result = mysqli_query($connection, "INSERT INTO jabatan(nama_jabatan) VALUES('$nama_jabatan')");
      $_SESSION['berhasil'] = "Data jabatan berhasil disimpan";
      header("location: ./");
      exit;
    }
  }
}

?>

<!-- section -->
<div class="page-header d-print-none">
  <div class="container-xl">
    <div class="row g-2 align-items-center">
      <div class="col">
        <h2 class="page-title">
          <?= $judul ?>
        </h2>
      </div>
    </div>
  </div>
</div>
<!-- Page body -->
<div class="page-body">
  <div class="container-xl">
    <div class="card col-md-6">
      <div class="card-body">
        <form action="tambah.php" method="POST">
          <div class="mb-3">
            <label for="">Nama Jabatan</label>
            <input type="text" class="form-control" name="nama_jabatan">
          </div>
          <button type="submit" name="submit" class="btn btn-primary">Simpan</button>
        </form>
      </div>
    </div>
  </div>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layout/main.php';