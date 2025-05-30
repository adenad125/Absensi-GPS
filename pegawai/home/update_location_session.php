<?php
session_start();
if (isset($_POST['lat_pegawai']) && isset($_POST['lng_pegawai'])) {
    $_SESSION['lat_pegawai'] = $_POST['lat_pegawai'];
    $_SESSION['lng_pegawai'] = $_POST['lng_pegawai'];
    echo 'OK';
} else {
    http_response_code(400);
    echo 'Invalid';
}
