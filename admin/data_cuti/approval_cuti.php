<?php 
require_once realpath(__DIR__ . '/../../config/config.php');
session_start();

date_default_timezone_set('Asia/Makassar');

// if (empty($_SESSION["login"]) || $_SESSION["role"] !== 'pegawai') {
//     header("Location: ../../auth/login.php?pesan=akses_ditolak");
//     exit;
// }

$id = $_GET['id'];
$approval = $_GET['app'];
$tgl_approval = date('Y-m-d H:i:s');

// Simpan data ke database
if($approval != 'NULL'){
    $query = "UPDATE cuti SET 
            approval='$approval',
            tgl_approval='$tgl_approval'
            WHERE id=$id";
    } else {
    $query = "UPDATE cuti SET 
                approval=$approval,
                tgl_approval=$approval
                WHERE id=$id";
    }

if (mysqli_query($connection, $query)) {
    $_SESSION['berhasil'] = "Approval cuti berhasil.";
} else {
    $_SESSION['gagal'] = "Approval cuti gagal: " . mysqli_error($connection);
}

// echo $id;
// echo $tgl_pengajuan;
// echo $tgl_awal;
// echo $tgl_akhir;
// echo $jumlah_hari;
// echo $id_kategori_cuti;
// echo $keterangan;
// echo $id_user;

header("Location: ../data_cuti");

