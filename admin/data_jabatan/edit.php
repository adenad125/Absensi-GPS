<?php
$judul = "Edit Jabatan";
ob_start();
require_once realpath(__DIR__ . '/../../config/config.php');

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

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layout/main.php';