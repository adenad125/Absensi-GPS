<?php
session_start();
ob_start();
$judul = "Riwayat Lembur" . (isset($_SESSION['nama']) ? " - " . $_SESSION['nama'] : '');

require_once realpath(__DIR__ . '/../../config/config.php');

// Query for the selected month range
$result = mysqli_query($connection, "SELECT *, p.nama as nama_pegawai,
    timediff(l.akhir, l.awal) as selisih
    FROM lembur l 
    JOIN pegawai p ON p.id = l.id_pegawai
    WHERE l.id_pegawai = '{$_SESSION['id']}'
    ORDER BY l.tgl_lembur DESC");
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
          <div class="card-body">
            <table class="table table-bordered">
            <tr class="text-center">
                <th>No.</th>
                <th>Tanggal Lembur</th>
                <th>Mulai</th>
                <th>Berakhir</th>
                <th>Durasi</th>
                <th>Keperluan</th>
            </tr>

            <?php if (mysqli_num_rows($result) === 0) { ?>
                <tr>
                    <td colspan="4" class="text-center">Data riwayat lembur masih kosong.</td>
                </tr>
            <?php } else {
                $no = 1;
                while ($rekap = mysqli_fetch_array($result)):
                    
                    ?>
                    <tr class="text-center<?= $terlambat_menit > 0 ? ' table-danger' : '' ?>">
                        <td><?= $no++ ?></td>
                        <td><?= date('d-m-Y H:i:s', strtotime($rekap['tgl_lembur'])) ?></td>
                        <td><?= date('H:i:s', strtotime($rekap['awal'])) ?></td>
                        <td><?= date('H:i:s', strtotime($rekap['akhir'])) ?></td>
                        <td><?= $rekap['selisih'] ?></td>
                        <td><?= $rekap['keperluan'] ?></td>
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