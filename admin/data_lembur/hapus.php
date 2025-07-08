<?php 
require_once realpath(__DIR__ . '/../../config/config.php');
session_start();

// if (empty($_SESSION["login"]) || $_SESSION["role"] !== 'pegawai') {
//     header("Location: ../../auth/login.php?pesan=akses_ditolak");
//     exit;
// }

// Pastikan semua data POST tersedia
$id = $_GET['id'];


// Simpan data ke database
$query = "DELETE FROM lembur WHERE id_lembur = $id";

if (mysqli_query($connection, $query)) {
    $_SESSION['berhasil'] = "Data lembur berhasil dihapus.";
} else {
    $_SESSION['gagal'] = "Hapus data lembur gagal: " . mysqli_error($connection);
}

// echo $id;
// echo $tgl_pengajuan;
// echo $tgl_awal;
// echo $tgl_akhir;
// echo $jumlah_hari;
// echo $id_kategori_cuti;
// echo $keterangan;
// echo $id_user;

header("Location: ../data_lembur");

