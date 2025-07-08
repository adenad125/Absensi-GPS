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

$id_lembur = mysqli_real_escape_string($connection, $_POST['id_lembur']);
$tgl_lembur = mysqli_real_escape_string($connection, $_POST['tgl_lembur']);
$awal = mysqli_real_escape_string($connection, $_POST['awal']);
$akhir = mysqli_real_escape_string($connection, $_POST['akhir']);
$id_pegawai = mysqli_real_escape_string($connection, $_POST['id_pegawai']);
$keperluan = mysqli_real_escape_string($connection, $_POST['keperluan']);
$id_admin = $_SESSION['id'];


// Simpan data ke database
$query = "UPDATE lembur SET 
            tgl_lembur='$tgl_lembur',
            awal='$awal',
            akhir='$akhir',
            id_pegawai=$id_pegawai,
            keperluan='$keperluan',
            id_admin=$id_admin
            WHERE id_lembur=$id_lembur";

if (mysqli_query($connection, $query)) {
    $_SESSION['berhasil'] = "Data lembur berhasil diubah.";
} else {
    $_SESSION['gagal'] = "Ubah data lembur gagal: " . mysqli_error($connection);
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

