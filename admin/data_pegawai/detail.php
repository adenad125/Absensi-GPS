<?php
$judul = "Detail Pegawai";
ob_start();
require_once realpath(__DIR__ . '/../../config/config.php');

$id = $_GET['id'];
$result = mysqli_query(
    $connection,
    "SELECT u.id_pegawai, u.username, u.password, u.status, u.role, 
            p.id, p.nip, p.nama, p.jenis_kelamin, p.alamat, p.no_handphone, p.foto, 
            j.nama_jabatan,
            lp.nama_lokasi as lokasi_presensi
            FROM users u 
            JOIN pegawai p ON u.id_pegawai = p.id 
            JOIN jabatan j ON p.id_jabatan = j.id
            JOIN lokasi_presensi lp ON p.id_lok_presensi = lp.id 
            WHERE p.id=$id"
);

$pegawai = mysqli_fetch_array($result);
?>

<!-- section -->
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">
                    <?= $judul ?>
                </h2>
            </div>
        </div>
    </div>
</div>
<div class="page-body">
    <div class="container-xl">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <table class="table">
                            <tr>
                                <td>Nama</td>
                                <td>: <?= $pegawai['nama'] ?></td>
                            </tr>
                            <tr>
                                <td>Jenis Kelamin</td>
                                <td>: <?= $pegawai['jenis_kelamin'] ?></td>
                            </tr>
                            <tr>
                                <td>Alamat</td>
                                <td>: <?= $pegawai['alamat'] ?></td>
                            </tr>
                            <tr>
                                <td>No. Handphone</td>
                                <td>: <?= $pegawai['no_handphone'] ?></td>
                            </tr>
                            <tr>
                                <td>Jabatan</td>
                                <td>: <?= $pegawai['nama_jabatan'] ?></td>
                            </tr>
                            <tr>
                                <td>Username</td>
                                <td>: <?= $pegawai['username'] ?></td>
                            </tr>
                            <tr>
                                <td>Role</td>
                                <td>: <?= $pegawai['role'] ?></td>
                            </tr>
                            <tr>
                                <td>Lokasi_Presensi</td>
                                <td>: <?= $pegawai['lokasi_presensi'] ?></td>
                            </tr>
                            <tr>
                                <td>Status</td>
                                <td>: <?= $pegawai['status'] ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <img style="width: 350px; border-radius: 15px"
                    src="<?= base_url('assets/img/foto_pegawai/' . $pegawai['foto']) ?>" alt="">
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layout/main.php';
