<?php
session_start();
ob_start();
$judul = "Pengajuan Cuti" . (isset($_SESSION['nama']) ? " - " . $_SESSION['nama'] : '');

require_once realpath(__DIR__ . '/../../config/config.php');

$id_cuti = $_GET['id'];

// Query for kategori cuti
$kategori = mysqli_query($connection, "SELECT * 
    FROM kategori_cuti kc");
$kc = mysqli_fetch_assoc($kategori);

$cuti = mysqli_query($connection, "SELECT * FROM cuti WHERE id = $id_cuti");

while ($c = mysqli_fetch_array($cuti)) {
  $id_cuti = $c['id'];
  $tgl_pengajuan = $c['tgl_pengajuan'];
  $tgl_awal = $c['tgl_awal'];
  $tgl_akhir = $c['tgl_akhir'];
  $jlh_hari = $c['jumlah_hari'];
  $keterangan = $c['keterangan'];
  $id_kategori_cuti = $c['id_kategori_cuti'];
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
    <div class="row">
      <div class="col-md-12">
        <div class="card text-center">
          <div class="card-header">
            Formulir Pengajuan Cuti
        </div>
          <div class="card-body"><div class="container-xl">
                <form action="ubah_cuti_aksi.php" method="POST">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="">Tanggal Pengajuan</label>
                                        <input type="date" class="form-control" name="tgl_pengajuan"
                                            value="<?= date('Y-m-d'); ?>" readonly>
                                        <input type="hidden" name="id_cuti" value="<?= $id_cuti; ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label for="">Kategori Cuti</label>
                                        <select name="id_kategori_cuti" class="form-control">
                                            <option value="">--Pilih Kategori--</option>
                                            <?php
                                            $kategoris = mysqli_query($connection, "SELECT * FROM kategori_cuti");
                                            while ($kategori = mysqli_fetch_assoc($kategoris)) { ?>
                                                <option value="<?= $kategori['id']; ?>"
                                                <?php if($kategori['id'] == $id_kategori_cuti){ echo "selected"; } ?>>
                                                <?= $kategori['nama_kategori']; ?></option>
                                            <?php }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="">Jumlah Hari</label>
                                        <input type="number" class="form-control" id="jlh_hari" name="jumlah_hari" value="<?= $jlh_hari; ?>">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="">Tanggal Awal</label>
                                            <input type="date" class="form-control" id="tanggal_awal" name="tgl_awal" value="<?= $tgl_awal; ?>">
                                            </div>
                                        <div class="mb-3">
                                            <label for="">Tanggal Akhir</label>
                                            <input type="date" class="form-control" id="tanggal_akhir" name="tgl_akhir" value="<?= $tgl_akhir; ?>">
                                        </div>
                                        <div class="mb-3">
                                            <label for="">Keterangan</label>
                                            <input type="text" class="form-control" name="keterangan" value="<?= $keterangan; ?>">
                                        </div>
                                    <a href="index.php" class="btn btn-danger">Kembali</a>
                                    <button type="submit" class="btn btn-primary" name="submit">Simpan</button>
                                </div>
                            </div>
                        </div>
                </form>
            </div>
            
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