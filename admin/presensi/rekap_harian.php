<?php
session_start();
ob_start();
$judul = "Rekap Presensi Harian";
require_once realpath(__DIR__ . '/../../config/config.php');

if (empty($_GET['tanggal_dari'])) {
    $result = mysqli_query($connection, "SELECT pr.*, p.nama, l.nama_lokasi FROM presensi pr 
    JOIN pegawai p ON  p.id = pr.id_pegawai
    JOIN lokasi_presensi l ON l.id = p.id_lok_presensi");
} else {
    // Convert dd/mm/yyyy to yyyy-mm-dd for MySQL
    $tanggal_dari = $_GET['tanggal_dari'];
    $tanggal_sampai = $_GET['tanggal_sampai'];
    $tanggal_dari_mysql = date('Y-m-d', strtotime(str_replace('/', '-', $tanggal_dari)));
    $tanggal_sampai_mysql = date('Y-m-d', strtotime(str_replace('/', '-', $tanggal_sampai)));
    $result = mysqli_query($connection, "SELECT pr.*, p.nama, l.nama_lokasi FROM presensi pr 
    JOIN pegawai p ON  p.id = pr.id_pegawai
    JOIN lokasi_presensi l ON l.id = p.id_lok_presensi
    WHERE pr.tanggal_masuk BETWEEN '$tanggal_dari_mysql' AND '$tanggal_sampai_mysql'");
}
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
        <div class="row mb-3">
            <div class="col-md-2">
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    Export Excel
                </button>
            </div>
            <div class="col-md-10">
                <form method="GET">
                    <div class="input-group">
                        <input type="date" class="form-control" name="tanggal_dari" required
                            value="<?= isset($_GET['tanggal_dari']) ? $_GET['tanggal_dari'] : '' ?>">
                        <input type="date" class="form-control" name="tanggal_sampai" required
                            value="<?= isset($_GET['tanggal_sampai']) ? $_GET['tanggal_sampai'] : '' ?>">
                        <button type="submit" class="btn btn-primary">Tampilkan</button>
                    </div>
                </form>
            </div>
        </div>

        <table class="table table-bordered">
            <tr class="text-center">
                <th>NO.</th>
                <th>Nama</th>
                <th>Lokasi</th>
                <th>Tanggal</th>
                <th>Jam Masuk</th>
                <th>Jam Pulang</th>
                <th>Total Jam Kerja</th>
                <th>Total Terlambat</th>
            </tr>

            <?php if (mysqli_num_rows($result) === 0) { ?>
                <tr>
                    <td colspan="8" class="text-center">Data rekap presensi masih kosong.</td>
                </tr>
            <?php } else {
                $no = 1;
                while ($rekap = mysqli_fetch_array($result)):
                    $jam_masuk = $rekap['jam_masuk'];
                    $jam_keluar = $rekap['jam_keluar'];
                    $tanggal_masuk = $rekap['tanggal_masuk'];
                    $tanggal_keluar = $rekap['tanggal_masuk']; // Asumsi tanggal sama
            
                    $jam_tanggal_masuk = date('Y-m-d H:i:s', strtotime("$tanggal_masuk $jam_masuk"));
                    $jam_tanggal_keluar = date('Y-m-d H:i:s', strtotime("$tanggal_keluar $jam_keluar"));

                    $timestamp_masuk = strtotime($jam_tanggal_masuk);
                    $timestamp_keluar = strtotime($jam_tanggal_keluar);

                    $selisih = $timestamp_keluar - $timestamp_masuk;

                    $total_jam_kerja = floor($selisih / 3600);
                    $selisih -= $total_jam_kerja * 3600;
                    $selisih_menit_kerja = floor($selisih / 60);

                    // Cek jam standar dari lokasi
                    $lokasi_presensi = $rekap['nama_lokasi'];
                    $lokasi_q = mysqli_query($connection, "SELECT * FROM lokasi_presensi WHERE nama_lokasi = '$lokasi_presensi'");
                    $lokasi_data = mysqli_fetch_assoc($lokasi_q);
                    $jam_masuk_standar = $lokasi_data['jam_masuk'];

                    // Hitung keterlambatan
                    $jam_standar = strtotime($tanggal_masuk . ' ' . $jam_masuk_standar);
                    $terlambat = $timestamp_masuk - $jam_standar;
                    $terlambat_menit = $terlambat > 0 ? floor($terlambat / 60) : 0;
                    ?>
                    <tr class="text-center<?= $terlambat_menit > 0 ? ' table-danger' : '' ?>">
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($rekap['nama']) ?></td>
                        <td><?= $lokasi_presensi ?></td>
                        <td><?= $tanggal_masuk ?></td>
                        <td><?= $jam_masuk ?></td>
                        <td><?= $jam_keluar ?></td>
                        <td><?= $total_jam_kerja . ' jam ' . $selisih_menit_kerja . ' menit' ?></td>
                        <td><?= $terlambat_menit . ' menit' ?></td>
                    </tr>
                    <?php
                endwhile;
            } ?>
        </table>
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
            <form method="POST" action="<?= base_url('admin/presensi/rekap_harian_excel.php') ?>">
                <div class="modal-body">

                    <div class="mb-3">
                        <label for="">Tanggal Awal</label>
                        <input type="date" class="form-control" name="tanggal_dari">
                    </div>

                    <div class="mb-3">
                        <label for="">Tanggal Akhir</label>
                        <input type="date" class="form-control" name="tanggal_sampai">
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" c lass="btn me-auto" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" data-bs-dismiss="modal">Export</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layout/main.php';