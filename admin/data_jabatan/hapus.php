<?php 
session_start();
ob_start();
require_once realpath(__DIR__ . '/../../config/config.php');

$id = $_GET['id'];

$result = mysqli_query($connection, "DELETE FROM jabatan WHERE id=$id");

$_SESSION['berhasil'] = 'Data berhasil dihapus';
header("Location: ./");
exit;

include('../layout/footer.php');