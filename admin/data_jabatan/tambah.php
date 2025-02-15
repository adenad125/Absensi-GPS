<?php 
session_start();
ob_start();
if(!isset($_SESSION["login"])) {
    header("Location: ../../auth/login.php?pesan=belum_login");
} else if($_SESSION["role"]  !== 'admin' ) {
    header("Location: ../../auth/login.php?pesan=tolak_akses");
}

$judul = "Tambah Data Jabatan";
include('../layout/header.php');
require_once 'C:/laragon/www/PRESENSI/config/config.php';

if(isset($_POST['submit'])) {
    $nama_jabatan = htmlspecialchars($_POST['nama_jabatan']);

    if($_SERVER["REQUEST_METHOD"] == "POST"){
      if(empty($nama_jabatan)) {
        $pesan_kesalahan = "Nama Jabatan wajib diisi";
      }
      if(!empty($pesan_kesalahan)){
        $_SESSION['validasi'] = $pesan_kesalahan;
      }else{
          $result = mysqli_query($connection, "INSERT INTO jabatan(nama_jabatan) VALUES('$nama_jabatan')");
          $_SESSION['berhasil'] = "Data jabatan berhasil disimpan";
          header("location: ./");
          exit;
      }
    }
}

?>  

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
      
<?php include('../layout/footer.php'); ?>    

