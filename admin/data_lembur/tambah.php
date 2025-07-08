<?php
session_start();
ob_start();
$judul = "Data Lembur";

date_default_timezone_set('Asia/Makassar');

require_once realpath(__DIR__ . '/../../config/config.php');

// Query for kategori cuti
$lembur = mysqli_query($connection, "SELECT * 
    FROM lembur l");
$l = mysqli_fetch_assoc($lembur);
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
            Formulir Tambah Data Lembur
        </div>
          <div class="card-body">
            <div class="container-xl">
                <form action="tambah_lembur_aksi.php" method="POST">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="">Pegawai</label>
                                        <select name="id_pegawai" class="form-control">
                                            <option value="">--Pilih Pegawai--</option>
                                            <?php
                                            $pegawais = mysqli_query($connection, "SELECT * FROM pegawai");
                                            while ($pegawai = mysqli_fetch_assoc($pegawais)) {
                                                echo '<option value="' . $pegawai['id'] . '">' . $pegawai['nama'] .' - '. $pegawai['nip'] .'</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="">Tanggal</label>
                                        <input type="date" class="form-control" id="tgl_lembur" value="<?= date('Y-m-d'); ?>" name="tgl_lembur">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="">Mulai</label>
                                            <input type="time" class="form-control" value="<?= date('H:i'); ?>" name="awal">
                                            </div>
                                        <div class="mb-3">
                                            <label for="">Selesai</label>
                                            <input type="time" class="form-control" value="<?= date('H:i'); ?>" name="akhir">
                                        </div>
                                        <div class="mb-3">
                                            <label for="">Keperluan</label>
                                            <input type="text" class="form-control" name="keperluan">
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