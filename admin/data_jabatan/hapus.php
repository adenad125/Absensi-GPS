<?php 

session_start();
require_once 'C:/laragon/www/PRESENSI/pegawai/config.php';

$id = $_GET['id'];

$result = mysqli_query($connection, "DELETE FROM jabatan WHERE id=$id");

$_SESSION['berhasil'] = 'Data berhasil dihapus';
header("Location: jabatan.php");
exit;

include('../layout/footer.php');