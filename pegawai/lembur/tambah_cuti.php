<?php
session_start();
ob_start();
$judul = "Pengajuan Cuti" . (isset($_SESSION['nama']) ? " - " . $_SESSION['nama'] : '');

require_once realpath(__DIR__ . '/../../config/config.php');

// Query for kategori cuti
$kategori = mysqli_query($connection, "SELECT * 
    FROM kategori_cuti kc");
$kc = mysqli_fetch_assoc($kategori);
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
          <div class="card-body">
            <div class="container-xl">
                <form action="tambah_cuti_aksi.php" method="POST">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="">Tanggal Pengajuan</label>
                                        <input type="date" class="form-control" name="tgl_pengajuan"
                                            value="<?= date('Y-m-d'); ?>" readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label for="">Kategori Cuti</label>
                                        <select name="id_kategori_cuti" class="form-control">
                                            <option value="">--Pilih Kategori--</option>
                                            <?php
                                            $kategoris = mysqli_query($connection, "SELECT * FROM kategori_cuti");
                                            while ($kategori = mysqli_fetch_assoc($kategoris)) {
                                                echo '<option value="' . $kategori['id'] . '">' . $kategori['nama_kategori'] . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="">Jumlah Hari</label>
                                        <input type="number" class="form-control" id="jlh_hari" name="jumlah_hari">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="">Tanggal Awal</label>
                                            <input type="date" class="form-control" id="tanggal_awal" value="<?= date('Y-m-d'); ?>" name="tgl_awal">
                                            </div>
                                        <div class="mb-3">
                                            <label for="">Tanggal Akhir</label>
                                            <input type="date" class="form-control" id="tanggal_akhir" value="<?= date('Y-m-d'); ?>" name="tgl_akhir">
                                        </div>
                                        <div class="mb-3">
                                            <label for="">Keterangan</label>
                                            <input type="text" class="form-control" name="keterangan">
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