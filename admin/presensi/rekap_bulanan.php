<?php
$judul = "Rekap Presensi Bulanan";
ob_start();
require_once realpath(__DIR__ . '/../../config/config.php');

// Get selected values from form (prioritize POST/GET, fallback to current)
$bln_dari = $_GET['bln_dari'] ?? date('m');
$bln_sampai = $_GET['bln_sampai'] ?? date('m');
$tahun = $_GET['tahun'] ?? date('Y');

// Compose MySQL date range from selected month and year
$bulan_dari = $tahun . '-' . $bln_dari;
$bulan_sampai = $tahun . '-' . $bln_sampai;
$tanggal_dari = $bulan_dari . '-01';
$tanggal_sampai = date('Y-m-t', strtotime($bulan_sampai . '-01'));

// Query for the selected month range
$result = mysqli_query($connection, "SELECT pr.*, p.nama, l.nama_lokasi 
    FROM presensi pr 
    JOIN pegawai p ON p.id = pr.id_pegawai
    JOIN lokasi_presensi l ON l.id = p.id_lok_presensi
    WHERE pr.tanggal_masuk BETWEEN '$tanggal_dari' AND '$tanggal_sampai'
    ORDER BY pr.tanggal_masuk ASC");

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
                <a href="export_excel.php?bln_dari=<?= $bln_dari ?>&bln_sampai=<?= $bln_sampai ?>&tahun=<?= $tahun ?>"
                    class="btn btn-success">
                    Export Excel
                </a>
            </div>
            <div class="col-md-10">
                <form method="GET">
                    <div class="input-group">
                        <?php
                        // Helper for select options
                        function month_options($selected = null)
                        {
                            $bulan_arr = [
                                '01' => 'Januari',
                                '02' => 'Februari',
                                '03' => 'Maret',
                                '04' => 'April',
                                '05' => 'Mei',
                                '06' => 'Juni',
                                '07' => 'Juli',
                                '08' => 'Agustus',
                                '09' => 'September',
                                '10' => 'Oktober',
                                '11' => 'November',
                                '12' => 'Desember'
                            ];
                            foreach ($bulan_arr as $num => $nama) {
                                $sel = ($selected == $num) ? 'selected' : '';
                                echo "<option value=\"$num\" $sel>$nama</option>";
                            }
                        }
                        function year_options($selected = null)
                        {
                            $tahun_sekarang = date('Y');
                            for ($y = $tahun_sekarang - 5; $y <= $tahun_sekarang + 1; $y++) {
                                $sel = ($selected == $y) ? 'selected' : '';
                                echo "<option value=\"$y\" $sel>$y</option>";
                            }
                        }
                        ?>
                        <label class="input-group-text">Dari</label>
                        <select name="bln_dari" class="form-select" required>
                            <?php month_options($bln_dari); ?>
                        </select>
                        <label class="input-group-text">Sampai</label>
                        <select name="bln_sampai" class="form-select" required>
                            <?php month_options($bln_sampai); ?>
                        </select>
                        <label class="input-group-text">Tahun</label>
                        <select name="tahun" class="form-select" required>
                            <?php year_options($tahun); ?>
                        </select>
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
                <th>Total Jam</th>
                <th>Total Terlambat</th>
            </tr>

            <?php if (mysqli_num_rows($result) === 0) { ?>
                <tr>
                    <td colspan="7" class="text-center">Data rekap presensi masih kosong.</td>
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