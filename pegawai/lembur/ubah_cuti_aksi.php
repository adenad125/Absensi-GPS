<?php 
require_once realpath(__DIR__ . '/../../config/config.php');
session_start();

// if (empty($_SESSION["login"]) || $_SESSION["role"] !== 'pegawai') {
//     header("Location: ../../auth/login.php?pesan=akses_ditolak");
//     exit;
// }

// Pastikan semua data POST tersedia
if (!isset($_POST['tgl_pengajuan'], $_POST['id_kategori_cuti'], $_POST['tgl_awal'], $_POST['tgl_akhir'], $_POST['jumlah_hari'])) {
    $_SESSION['gagal'] = "Data tidak lengkap. Harap coba lagi.";
    header("Location: ../home");
    exit;
}

$id = mysqli_real_escape_string($connection, $_POST['id_cuti']);
$tgl_awal = mysqli_real_escape_string($connection, $_POST['tgl_awal']);
$tgl_akhir = mysqli_real_escape_string($connection, $_POST['tgl_akhir']);
$jumlah_hari = mysqli_real_escape_string($connection, $_POST['jumlah_hari']);
$id_kategori_cuti = mysqli_real_escape_string($connection, $_POST['id_kategori_cuti']);
$keterangan = mysqli_real_escape_string($connection, $_POST['keterangan']);
$id_user = $_SESSION['id'];


// Simpan data ke database
$query = "UPDATE cuti SET 
            tgl_awal='$tgl_awal',
            tgl_akhir='$tgl_akhir',
            jumlah_hari=$jumlah_hari,
            id_kategori_cuti=$id_kategori_cuti,
            keterangan='$keterangan'
            WHERE id=$id";

if (mysqli_query($connection, $query)) {
    $_SESSION['berhasil'] = "Pengajuan cuti berhasil diubah.";
} else {
    $_SESSION['gagal'] = "Pengajuan cuti gagal: " . mysqli_error($connection);
}

// echo $id;
// echo $tgl_pengajuan;
// echo $tgl_awal;
// echo $tgl_akhir;
// echo $jumlah_hari;
// echo $id_kategori_cuti;
// echo $keterangan;
// echo $id_user;

header("Location: ../cuti");

