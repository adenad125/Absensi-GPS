<?php 
require_once realpath(__DIR__ . '/../../config/config.php');
session_start();

// if (empty($_SESSION["login"]) || $_SESSION["role"] !== 'pegawai') {
//     header("Location: ../../auth/login.php?pesan=akses_ditolak");
//     exit;
// }

// Pastikan semua data POST tersedia
if (!isset($_POST['tgl_pengajuan'], $_POST['id_kategori_cuti'], $_POST['tgl_awal'], $_POST['tgl_akhir'], $_POST['jumlah_hari'], $_POST['keterangan'])) {
    $_SESSION['gagal'] = "Data tidak lengkap. Harap coba lagi.";
    header("Location: ../home");
    exit;
}

$tgl_pengajuan = mysqli_real_escape_string($connection, $_POST['tgl_pengajuan']);
$tgl_awal = mysqli_real_escape_string($connection, $_POST['tgl_awal']);
$tgl_akhir = mysqli_real_escape_string($connection, $_POST['tgl_akhir']);
$jumlah_hari = mysqli_real_escape_string($connection, $_POST['jumlah_hari']);
$id_kategori_cuti = mysqli_real_escape_string($connection, $_POST['id_kategori_cuti']);
$keterangan = mysqli_real_escape_string($connection, $_POST['keterangan']);
$id_user = $_SESSION['id'];


// Simpan data ke database
$query = "INSERT INTO cuti(tgl_awal, tgl_akhir, jumlah_hari, id_kategori_cuti, keterangan, id_user) 
          VALUES ('$tgl_awal', '$tgl_akhir', $jumlah_hari, $id_kategori_cuti, '$keterangan', $id_user)";

if (mysqli_query($connection, $query)) {
    $_SESSION['berhasil'] = "Pengajuan cuti berhasil ditambahkan.";
} else {
    $_SESSION['gagal'] = "Pengajuan cuti gagal: " . mysqli_error($connection);
}

header("Location: ../cuti");

