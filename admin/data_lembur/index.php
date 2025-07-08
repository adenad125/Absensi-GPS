<?php
session_start();
ob_start();
$judul = "Data Lembur";

require_once realpath(__DIR__ . '/../../config/config.php');

$result = mysqli_query(
    $connection,
    "SELECT *, p.nama as nama_pegawai,
    timediff(l.akhir, l.awal) as selisih
    FROM lembur l 
    JOIN pegawai p ON p.id = l.id_pegawai
    ORDER BY l.tgl_lembur DESC"
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
        <a href="<?php echo base_url('admin/data_lembur/tambah.php'); ?>" class="btn btn-primary"><span class="text"><i
                    class="fa-solid fa-circle-plus"></i> Tambah Data</span></a>
        <table class="table table-bordered mt-3">
            <tr class="text-center">
                <th>No.</th>
                <th>Tanggal Lembur</th>
                <th>Nama Pegawai</th>
                <th>Mulai</th>
                <th>Berakhir</th>
                <th>Durasi</th>
                <th>Keperluan</th>
                <th>Aksi</th>
            </tr>

            <?php if (mysqli_num_rows($result) === 0) { ?>
                <tr>
                    <td colspan="5" class="text-center">Data Kosong, silahkan tambah data baru</td>
                </tr>
            <?php } else { ?>
                <?php $no = 1;
                while ($rekap = mysqli_fetch_array($result)): ?>
                    <tr class="text-center">
                        <td><?= $no++ ?></td>
                        <td><?= date('d-m-Y', strtotime($rekap['tgl_lembur'])) ?></td>
                        <td><?= $rekap['nama_pegawai'] ?></td>
                        <td><?= date('H:i', strtotime($rekap['awal'])) ?></td>
                        <td><?= date('H:i', strtotime($rekap['akhir'])) ?></td>
                        <td><?= $rekap['selisih'] ?></td>
                        <td><?= $rekap['keperluan'] ?></td>
                        <td>
                            <a href="ubah.php?id=<?= $rekap['id_lembur']; ?>" class="btn btn-sm btn-warning btn-right">Ubah</a>
                            <a href="hapus.php?id=<?= $rekap['id_lembur']; ?>" class="btn btn-sm btn-danger btn-right">Hapus</a>
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