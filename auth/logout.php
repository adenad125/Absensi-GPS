<?php 
session_unset(); // Unset all session variables
session_destroy(); // Destroy the session

session_start(); // Start the new session
$_SESSION['logout_success'] = true;
$_SESSION['message_logout'] = "Anda telah keluar dari sesi ini.";

header("Location: ../index.php");
