<?php
session_start();
ob_start();
$judul = "Data Cuti";

require_once realpath(__DIR__ . '/../../config/config.php');

$result = mysqli_query(
    $connection,
    "SELECT *, c.id as id_cuti, p.nama as nama_pegawai
    FROM cuti c 
    JOIN pegawai p ON p.id = c.id_user
    JOIN kategori_cuti kc ON kc.id = c.id_kategori_cuti
    ORDER BY c.tgl_pengajuan DESC"
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
                <th>No.</th>
                <th>Tanggal Pengajuan</th>
                <th>Nama Pegawai</th>
                <th>Cuti</th>
                <th>Tanggal Awal</th>
                <th>Tanggal Akhir</th>
                <th>Jumlah Hari</th>
                <th>Keterangan</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>

            <?php if (mysqli_num_rows($result) === 0) { ?>
                <tr>
                    <td colspan="7">Data Kosong, silahkan tambah data baru</td>
                </tr>
            <?php } else { ?>
                <?php $no = 1;
                while ($rekap = mysqli_fetch_array($result)): ?>
                    <tr class="text-center<?= $terlambat_menit > 0 ? ' table-danger' : '' ?>">
                        <td><?= $no++ ?></td>
                        <td><?= date('d-m-Y H:i:s', strtotime($rekap['tgl_pengajuan'])) ?></td>
                        <td><?= $rekap['nama_pegawai'] ?></td>
                        <td><?= $rekap['nama_kategori'] ?></td>
                        <td><?= date('d-m-Y', strtotime($rekap['tgl_awal'])) ?></td>
                        <td><?= date('d-m-Y', strtotime($rekap['tgl_akhir'])) ?></td>
                        <td><?= $rekap['jumlah_hari'] ?></td>
                        <td><?= $rekap['keterangan'] ?></td>
                        <td>
                            <?php if($rekap['approval'] == 'Y'){ ?>
                                <span class="text-black badge badge-pill bg-success">Disetujui</span>
                            <?php } else if($rekap['approval'] == 'T') { ?>
                                <span class="text-black badge badge-pill bg-danger">Ditolak</span>
                            <?php } else { ?>
                                <span class="text-black badge badge-pill bg-warning">Menunggu</span>
                            <?php } ?>
                        </td>
                        <td>
                            <?php if($rekap['approval'] == NULL){ ?>
                                <a href="approval_cuti.php?id=<?= $rekap['id_cuti']; ?>&app=Y" class="btn btn-sm btn-primary btn-right">Terima</a>
                                <a href="approval_cuti.php?id=<?= $rekap['id_cuti']; ?>&app=T" class="btn btn-sm btn-danger btn-right">Tolak</a>
                            <?php } else { ?>
                                <a href="approval_cuti.php?id=<?= $rekap['id_cuti']; ?>&app=NULL" class="btn btn-sm btn-warning btn-right">Batalkan</a>
                            <?php } ?>
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