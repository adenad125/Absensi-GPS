<?php 
session_start();
ob_start();
require_once realpath(__DIR__ . '/../../config/config.php');

$id = $_GET['id'];

$result = mysqli_query($connection, "DELETE FROM lokasi_presensi WHERE id=$id");

$_SESSION['berhasil'] = 'Data berhasil dihapus';
header("Location: lokasi_presensi.php");
exit;

include('../layout/footer.php');