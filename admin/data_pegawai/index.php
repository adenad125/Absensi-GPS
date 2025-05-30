<?php
session_start();
ob_start();
$judul = "Data Pegawai";

require_once realpath(__DIR__ . '/../../config/config.php');

$result = mysqli_query(
    $connection,
    "SELECT u.id_pegawai, u.username, u.password, u.status, u.role, 
            p.id, p.nip, p.nama, p.jenis_kelamin, p.alamat, p.no_handphone, p.foto, 
            j.nama_jabatan,
            lp.nama_lokasi as lokasi_presensi
            FROM users u 
            JOIN pegawai p ON u.id_pegawai = p.id 
            JOIN jabatan j ON p.id_jabatan = j.id
            JOIN lokasi_presensi lp ON p.id_lok_presensi = lp.id"
);
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
        <a href="<?php echo base_url('admin/data_pegawai/tambah.php'); ?>" class="btn btn-primary"><span class="text"><i
                    class="fa-solid fa-circle-plus"></i> Tambah Data</span></a>
        <table class="table table-bordered mt-3">
            <tr class="text-center">
                <th>No</th>
                <th>NIP</th>
                <th>Nama</th>
                <th>Lokasi Presensi</th>
                <th>Username</th>
                <th>Jabatan</th>
                <th>Role</th>
                <th>Aksi</th>
            </tr>

            <?php if (mysqli_num_rows($result) === 0) { ?>
                <tr>
                    <td colspan="7">Data Kosong, silahkan tambah data baru</td>
                </tr>
            <?php } else { ?>
                <?php $no = 1;
                while ($pegawai = mysqli_fetch_array($result)): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $pegawai['nip'] ?></td>
                        <td><?= $pegawai['nama'] ?></td>
                        <td><?= $pegawai['lokasi_presensi'] ?></td>
                        <td><?= $pegawai['username'] ?></td>
                        <td><?= $pegawai['nama_jabatan'] ?></td>
                        <td><span
                                class="text-black badge badge-pill bg-<?= $pegawai['role'] == 'admin' ? 'success' : 'warning' ?>">
                                <?= $pegawai['role'] ?>
                            </span>
                        </td>
                        <td class="text-center">
                            <a href="<?= base_url('admin/data_pegawai/detail.php?id=' . $pegawai
                            ['id']) ?>" class="badge badge-pill bg-primary">Detail</a>
                            <a href="<?= base_url('admin/data_pegawai/edit.php?id=' . $pegawai
                            ['id']) ?>" class="badge badge-pill bg-primary">Edit</a>
                            <a href="<?= base_url('admin/data_pegawai/hapus.php?id=' . $pegawai
                            ['id']) ?>" class="badge badge-pill bg-danger tombol-hapus">Hapus</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php } ?>
        </table>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layout/main.php';