<?php 
require_once realpath(__DIR__ . '/../../config/config.php');
session_start();

// if (empty($_SESSION["login"]) || $_SESSION["role"] !== 'pegawai') {
//     header("Location: ../../auth/login.php?pesan=akses_ditolak");
//     exit;
// }

// Pastikan semua data POST tersedia
if (!isset($_POST['tgl_lembur'], $_POST['id_pegawai'], $_POST['awal'], $_POST['akhir'])) {
    $_SESSION['gagal'] = "Data tidak lengkap. Harap coba lagi.";
    header("Location: ../home");
    exit;
}

$tgl_lembur = mysqli_real_escape_string($connection, $_POST['tgl_lembur']);
$id_pegawai = mysqli_real_escape_string($connection, $_POST['id_pegawai']);
$awal = mysqli_real_escape_string($connection, $_POST['awal']);
$akhir = mysqli_real_escape_string($connection, $_POST['akhir']);
$keperluan = mysqli_real_escape_string($connection, $_POST['keperluan']);
$id_user = $_SESSION['id'];


// Simpan data ke database
$query = "INSERT INTO lembur(tgl_lembur, id_pegawai, awal, akhir, keperluan, id_admin) 
          VALUES ('$tgl_lembur', $id_pegawai, '$awal', '$akhir', '$keperluan', $id_user)";

if (mysqli_query($connection, $query)) {
    $_SESSION['berhasil'] = "Data lembur berhasil ditambahkan.";
} else {
    $_SESSION['gagal'] = "Tambah data lembur gagal: " . mysqli_error($connection);
}

header("Location: ../data_lembur");

