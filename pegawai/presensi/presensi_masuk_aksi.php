<?php 
session_start();
ob_start();

if (!isset($_SESSION["login"])) {
    header("Location: ../../auth/login.php?pesan=belum_login");
    exit;
} 

if ($_SESSION["role"] !== 'pegawai') {
    header("Location: ../../auth/login.php?pesan=tolak_akses");
    exit;
}

require_once '../config.php';

// Pastikan semua data POST tersedia
if (!isset($_POST['photo'], $_POST['id'], $_POST['tanggal_masuk'], $_POST['jam_masuk'])) {
    $_SESSION['gagal'] = "Data tidak lengkap. Harap coba lagi.";
    header("Location: ../home/home.php");
    exit;
}

$file_foto = $_POST['photo'];
$id_pegawai = mysqli_real_escape_string($connection, $_POST['id']);
$tanggal_masuk = mysqli_real_escape_string($connection, $_POST['tanggal_masuk']);
$jam_masuk = mysqli_real_escape_string($connection, $_POST['jam_masuk']);

// Validasi apakah foto dikirim dalam format Base64
if (strpos($file_foto, 'data:image/jpeg;base64,') === false) {
    $_SESSION['gagal'] = "Format foto tidak valid.";
    header("Location: ../home/home.php");
    exit;
}

// Membersihkan data Base64 sebelum decoding
$foto = str_replace('data:image/jpeg;base64,', '', $file_foto);
$foto = str_replace(' ', '+', $foto);
$data = base64_decode($foto);

// Pastikan decoding berhasil
if (!$data) {
    $_SESSION['gagal'] = "Gagal memproses foto.";
    header("Location: ../home/home.php");
    exit;
}

// Folder penyimpanan foto
$folderPath = '../uploads/';
if (!is_dir($folderPath)) {
    mkdir($folderPath, 0777, true); // Buat folder jika belum ada
}

// Menyimpan file dengan nama unik
$nama_file = $folderPath . 'masuk_' . date('Ymd_His') . '.jpeg';
$file = 'masuk_' . date('Ymd_His') . '.jpeg';
file_put_contents($nama_file, $data);

// Simpan data ke database
$query = "INSERT INTO presensi (id_pegawai, tanggal_masuk, jam_masuk, foto_masuk) 
          VALUES ('$id_pegawai', '$tanggal_masuk', '$jam_masuk', '$file')";

if (mysqli_query($connection, $query)) {
    $_SESSION['berhasil'] = "Presensi masuk berhasil.";
} else {
    $_SESSION['gagal'] = "Presensi masuk gagal: " . mysqli_error($connection);
}

// Redirect kembali ke halaman home
header("Location: ../home/home.php");
exit;
?>
