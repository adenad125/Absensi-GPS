<?php
session_start();
ob_start();
$judul = "Data Lokasi Presensi";

require_once realpath(__DIR__ . '/../../config/config.php');
$result = mysqli_query($connection, "SELECT * FROM lokasi_presensi ORDER BY id DESC");
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
        <a href="<?php echo base_url('admin/data_lokasi_presensi/tambah.php'); ?>" class="btn btn-primary"><span
                class="text"><i class="fa-solid fa-circle-plus"></i> Tambah Data</span></a>
        <div class="table-responsive mt-3">
            <table class="table table-bordered table-sm">
            <tr class="text-center">
            <th style="width: 5%;">No</th>
            <th style="width: 20%;">Nama Lokasi</th>
            <th style="width: 25%;">Tipe Lokasi</th>
            <th style="width: 15%;">Latitude</th>
            <th style="width: 15%;">Longitude</th>
            <th style="width: 10%;">Radius</th>
            <th style="width: 10%;">Aksi</th>
            </tr>

            <?php if (mysqli_num_rows($result) === 0) { ?>
            <tr>
                <td colspan="7" class="text-center">Data Kosong, silahkan tambah data baru</td>
            </tr>
            <?php } else { ?>
            <?php $no = 1;
            while ($lokasi = mysqli_fetch_array($result)): ?>
                <tr>
                <td class="text-center"><?= $no++ ?></td>
                <td><?= $lokasi['nama_lokasi'] ?></td>
                <td><?= $lokasi['alamat_lokasi'] ?></td>
                <td class="text-center"><span class="badge badge-pill bg-secondary text-black"><?= $lokasi['latitude'] ?></span></td>
                <td class="text-center"><span class="badge badge-pill bg-secondary text-black"><?= $lokasi['longitude'] ?></span></td>
                <td class="text-center"><?= $lokasi['radius'] ?></td>
                <td class="text-center">
                <a href="<?= base_url('admin/data_lokasi_presensi/detail.php?id=' . $lokasi['id']) ?>" class="badge badge-pill bg-primary">Detail</a>
                <a href="<?= base_url('admin/data_lokasi_presensi/edit.php?id=' . $lokasi['id']) ?>" class="badge badge-pill bg-primary">Edit</a>
                <a href="<?= base_url('admin/data_lokasi_presensi/hapus.php?id=' . $lokasi['id']) ?>" class="badge badge-pill bg-danger tombol-hapus">Hapus</a>
                </td>
                </tr>
            <?php endwhile; ?>
            <?php } ?>
            </table>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layout/main.php';