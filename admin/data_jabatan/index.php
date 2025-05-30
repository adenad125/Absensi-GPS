<?php
session_start();
ob_start();
$judul = "Data Jabatan";

require_once realpath(__DIR__ . '/../../config/config.php');

$result = mysqli_query($connection, "SELECT * FROM jabatan ORDER BY id DESC");
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
<!-- Page body -->
<div class="page-body">
  <div class="container-xl">

    <a href="<?php echo base_url('admin/data_jabatan/tambah.php'); ?>" class="btn btn-primary"><span class="text"><i
          class="fa-solid fa-circle-plus"></i> Tambah Data</span></a>

    <div class="row row-deck row-cards mt-2">
      <table class="table table-bordered">
        <tr class="text-center">
          <th>No.</th>
          <th>Nama Jabatan</th>
          <th>Aksi</th>
        </tr>

        <?php if (mysqli_num_rows($result) === 0): ?>
          <tr>
            <td colspan="3">Data masih kosong, silahkan tambahkan data baru</td>
          </tr>
        <?php else: ?>
          <?php $no = 1;
          while ($jabatan = mysqli_fetch_array($result)): ?>

            <tr>
              <td><?= $no++ ?></td>
              <td><?= $jabatan['nama_jabatan'] ?></td>
              <td class="text-center">
                <a href="<?= base_url('admin/data_jabatan/edit.php?id=' . $jabatan['id']) ?>"
                  class="badge bg-primary badge-pill">Edit</a>


                <a href="<?= base_url('admin/data_jabatan/hapus.php?id=' . $jabatan['id']) ?>"
                  class="badge bg-danger badge-pill tombol-hapus">Hapus</a>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php endif; ?>
      </table>
    </div>
  </div>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layout/main.php';