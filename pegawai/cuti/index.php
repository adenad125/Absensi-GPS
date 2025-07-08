<?php
session_start();
ob_start();
$judul = "Riwayat Pengajuan Cuti" . (isset($_SESSION['nama']) ? " - " . $_SESSION['nama'] : '');

require_once realpath(__DIR__ . '/../../config/config.php');

// Query for the selected month range
$result = mysqli_query($connection, "SELECT *, c.id as id_cuti 
    FROM cuti c 
    JOIN pegawai p ON p.id = c.id_user
    JOIN kategori_cuti kc ON kc.id = c.id_kategori_cuti
    WHERE c.id_user = '{$_SESSION['id']}'
    ORDER BY c.tgl_pengajuan DESC");
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
      <div class="col-md-12">
        <div class="card text-center">
          <div class="card-header">
            <a href="tambah_cuti.php" class="btn btn-primary btn-right">Tambah</a>
          </div>
          <div class="card-body">
            <table class="table table-bordered">
            <tr class="text-center">
                <th>No.</th>
                <th>Tanggal Pengajuan</th>
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
                    <td colspan="9" class="text-center">Data riwayat cuti masih kosong.</td>
                </tr>
            <?php } else {
                $no = 1;
                while ($rekap = mysqli_fetch_array($result)):
                    
                    ?>
                    <tr class="text-center<?= $terlambat_menit > 0 ? ' table-danger' : '' ?>">
                        <td><?= $no++ ?></td>
                        <td><?= date('d-m-Y H:i:s', strtotime($rekap['tgl_pengajuan'])) ?></td>
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
                            <a href="ubah_cuti.php?id=<?= $rekap['id_cuti']; ?>" class="btn btn-sm btn-warning btn-right">Ubah</a>
                            <?php } ?>
                        </td>
                    </tr>
                    <?php
                endwhile;
            } ?>
        </table>
          </div>
        </div>
        </div>
    </div>
  </div>
</div>

<!-- Modal Example -->
<div class="modal" id="exampleModal" tabindex="-1">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Export Excel</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Klik tombol "Export Excel" untuk menyimpan data rekap dalam format Excel.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn me-auto" data-bs-dismiss="modal">Tutup</button>
                <a href="#" class="btn btn-primary" data-bs-dismiss="modal">Export</a>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layout/main.php';