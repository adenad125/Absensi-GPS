<?php 
session_start();
if(!isset($_SESSION["login"])) {
    header("Location: ../../auth/login.php?pesan=belum_login");
    exit;
} else if($_SESSION["role"] !== 'admin') {
    header("Location: ../../auth/login.php?pesan=tolak_akses");
    exit;
}

$judul = "Rekap Presensi Harian";
include('../layout/header.php'); 
require_once 'C:/laragon/www/PRESENSI/config/config.php';

if (empty($_GET['tanggal_dari'])) {
    $result = mysqli_query($connection, "SELECT presensi.*, pegawai.nama, pegawai.lokasi_presensi FROM presensi JOIN pegawai ON presensi.id_pegawai = pegawai.id WHERE tanggal = CURDATE()");
} else {
    $tanggal_dari = $_GET['tanggal_dari'];
    $tanggal_sampai = $_GET['tanggal_sampai'];
    $result = mysqli_query($connection, "SELECT presensi.*, pegawai.nama, pegawai.lokasi_presensi FROM presensi JOIN pegawai ON presensi.id_pegawai = pegawai.id WHERE tanggal BETWEEN '$tanggal_dari' AND '$tanggal_sampai'");
}
?>  

<div class="page-body">
    <div class="container-xl">

        <div class="row mb-3">
            <div class="col-md-2">
                <a href="export_excel.php?tanggal_dari=<?= $_GET['tanggal_dari'] ?? '' ?>&tanggal_sampai=<?= $_GET['tanggal_sampai'] ?? '' ?>" class="btn btn-success">
                    Export Excel
                </a>
            </div>
            <div class="col-md-10">
                <form method="GET">
                    <div class="input-group">
                        <input type="date" class="form-control" name="tanggal_dari" required>
                        <input type="date" class="form-control" name="tanggal_sampai" required>
                        <button type="submit" class="btn btn-primary">Tampilkan</button>
                    </div>
                </form>
            </div>
        </div>

        <table class="table table-bordered">
            <tr class="text-center">
                <th>NO.</th>
                <th>Nama</th>
                <th>Tanggal</th>
                <th>Jam Masuk</th>
                <th>Jam Pulang</th>
                <th>Total Jam</th>
                <th>Total Terlambat</th>
            </tr>

            <?php if (mysqli_num_rows($result) === 0) { ?>
                <tr>
                    <td colspan="7" class="text-center">Data rekap presensi masih kosong.</td>
                </tr>
            <?php } else { 
                $no = 1;
                while ($rekap = mysqli_fetch_array($result)) :
                    $jam_masuk = $rekap['jam_masuk'];
                    $jam_keluar = $rekap['jam_keluar'];
                    $tanggal_masuk = $rekap['tanggal'];
                    $tanggal_keluar = $rekap['tanggal']; // Asumsi tanggal sama

                    $jam_tanggal_masuk = date('Y-m-d H:i:s', strtotime("$tanggal_masuk $jam_masuk"));
                    $jam_tanggal_keluar = date('Y-m-d H:i:s', strtotime("$tanggal_keluar $jam_keluar"));

                    $timestamp_masuk = strtotime($jam_tanggal_masuk);
                    $timestamp_keluar = strtotime($jam_tanggal_keluar);

                    $selisih = $timestamp_keluar - $timestamp_masuk;

                    $total_jam_kerja = floor($selisih / 3600);
                    $selisih -= $total_jam_kerja * 3600;
                    $selisih_menit_kerja = floor($selisih / 60);

                    // Cek jam standar dari lokasi
                    $lokasi_presensi = $rekap['lokasi_presensi'];
                    $lokasi_q = mysqli_query($connection, "SELECT * FROM lokasi_presensi WHERE nama_lokasi = '$lokasi_presensi'");
                    $lokasi_data = mysqli_fetch_assoc($lokasi_q);
                    $jam_masuk_standar = $lokasi_data['jam_masuk'];

                    // Hitung keterlambatan
                    $jam_standar = strtotime($tanggal_masuk . ' ' . $jam_masuk_standar);
                    $terlambat = $timestamp_masuk - $jam_standar;
                    $terlambat_menit = $terlambat > 0 ? floor($terlambat / 60) : 0;
            ?>
                <tr class="text-center">
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($rekap['nama']) ?></td>
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

<?php include('../layout/footer.php'); ?>
