<?php
session_start();
ob_start();
require_once realpath(__DIR__ . '/../../config/config.php');

$id = $_GET['id'];

$result = mysqli_query($connection, "DELETE FROM users WHERE id_pegawai=$id");

$result = mysqli_query($connection, "DELETE FROM pegawai WHERE id=$id");

$_SESSION['berhasil'] = 'Data berhasil dihapus';
header("Location: index.php");
exit;
