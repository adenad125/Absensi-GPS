<?php
session_start();
ob_start();
$judul = "Home";
require_once realpath(__DIR__ . '/../../config/config.php');

$id_lok_presensi = $_SESSION['id_lok_presensi'];
$result = mysqli_query($connection, "SELECT * FROM lokasi_presensi WHERE id = '$id_lok_presensi'");

while ($lokasi = mysqli_fetch_array($result)) {
  $lat_kantor = $lokasi['latitude'];
  $lng_kantor = $lokasi['longitude'];
  $radius = $lokasi['radius'];
  $zona_waktu = $lokasi['zona_waktu'];
}

if ($zona_waktu == 'WIB') {
  date_default_timezone_set('Asia/Jakarta');
} elseif ($zona_waktu == 'WITA') {
  date_default_timezone_set('Asia/Makassar');
} elseif ($zona_waktu == 'WIT') {
  date_default_timezone_set('Asia/Jayapura');
}

// Remove unnecessary lat/lng pegawai variables
?>

<style>
  .parent_date {
    display: grid;
    grid-template-columns: auto auto auto auto auto;
    font-size: 20px;
    text-align: center;
    justify-content: center;
  }

  .parent_clock {
    display: grid;
    grid-template-columns: auto auto auto auto auto;
    font-size: 30px;
    text-align: center;
    font-weight: bold;
    justify-content: center;
  }
</style>

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
  <?php
  $id_pegawai = $_SESSION['id'];
  $tanggal_hari_ini = date('Y-m-d');
  $result = mysqli_query(
    $connection,
    "SELECT * FROM presensi WHERE id_pegawai = '$id_pegawai' AND tanggal_masuk = '$tanggal_hari_ini'"
  );
  $presensi = mysqli_fetch_array($result);
  
  ?>
  <div class="container-xl">
    <div class="row">
      <div class="col-md-2"></div>
      <div class="col-md-4">
        <div class="card text-center">
          <div class="card-header">Presensi Masuk</div>
          <div class="card-body">
            <div class="parent_date">
              <div id="tanggal_masuk"></div>
              <div class="ms-2"></div>
              <div id="bulan_masuk"></div>
              <div class="ms-2"></div>
              <div id="tahun_masuk"></div>
            </div>
            <div class="parent_clock">
              <div id="jam_masuk"></div>
              <div>:</div>
              <div id="menit_masuk"></div>
              <div>:</div>
              <div id="detik_masuk"></div>
            </div>
            <?php if (empty($presensi) && empty($presensi['jam_masuk'])): ?>
              <form method="POST" action="<?= base_url('pegawai/presensi/presensi_masuk.php') ?>" id="form-masuk">
                <input type="hidden" value="<?= $lat_kantor ?>" name="lat_kantor_masuk" readonly>
                <input type="hidden" value="<?= $lng_kantor ?>" name="lng_kantor_masuk" readonly>
                <input type="hidden" value="<?= $radius ?>" name="radius" readonly>
                <input type="hidden" value="<?= $zona_waktu ?>" name="zona_waktu" readonly>
                <input type="hidden" value="<?= date('Y-m-d') ?>" name="tanggal_masuk" readonly>
                <input type="hidden" value="<?= date('H:i:s') ?>" name="jam_masuk" readonly>
                <button type="submit" name="tombol_masuk" class="btn btn-primary mt-3">Masuk</button>
              </form>
            <?php else: ?>
              <p>Anda sudah melakukan presensi pada pukul : <b
                  class="text-success"><?= $presensi['jam_masuk'] ?></b></p>
            <?php endif; ?>
          </div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card text-center">
          <div class="card-header">Presensi Keluar</div>
          <div class="card-body">
            <div class="parent_date">
              <div id="tanggal_keluar"></div>
              <div class="ms-2"></div>
              <div id="bulan_keluar"></div>
              <div class="ms-2"></div>
              <div id="tahun_keluar"></div>
            </div>
            <div class="parent_clock">
              <div id="jam_keluar"></div>
              <div>:</div>
              <div id="menit_keluar"></div>
              <div>:</div>
              <div id="detik_keluar"></div>
            </div>
            <?php if (empty($presensi['jam_keluar'])): ?>
              <form method="POST" action="<?= base_url('pegawai/presensi/presensi_keluar.php') ?>">
                <input type="hidden" value="<?= $lat_kantor ?>" name="lat_kantor_keluar" readonly>
                <input type="hidden" value="<?= $lng_kantor ?>" name="lng_kantor_keluar" readonly>
                <input type="hidden" value="<?= $radius ?>" name="radius" readonly>
                <input type="hidden" value="<?= $zona_waktu ?>" name="zona_waktu" readonly>
                <input type="hidden" value="<?= date('Y-m-d') ?>" name="tanggal_keluar" readonly>
                <input type="hidden" value="<?= date('H:i:s') ?>" name="jam_keluar" readonly>
                <button type="submit" name="tombol_keluar" class="btn btn-danger mt-3">Keluar</button>
              </form>
            <?php else: ?>
              <p>Anda sudah melakukan presensi pada pukul : <b
                  class="text-success"><?= $presensi['jam_keluar'] ?></b></p>
            <?php endif; ?>
          </div>
        </div>
      </div>
      <div class="col-md-2"></div>
    </div>
  </div>
</div>

<script>
  // Set waktu di card presensi masuk
  namaBulan = ["Januari", "Febuari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
  function waktuMasuk() {
    const waktu = new Date();
    setTimeout(waktuMasuk, 1000);
    document.getElementById("tanggal_masuk").innerHTML = waktu.getDate();
    document.getElementById("bulan_masuk").innerHTML = namaBulan[waktu.getMonth()];
    document.getElementById("tahun_masuk").innerHTML = waktu.getFullYear();
    document.getElementById("jam_masuk").innerHTML = waktu.getHours();
    document.getElementById("menit_masuk").innerHTML = waktu.getMinutes();
    document.getElementById("detik_masuk").innerHTML = waktu.getSeconds();
  }
  function waktuKeluar() {
    const waktu = new Date();
    setTimeout(waktuKeluar, 1000);
    document.getElementById("tanggal_keluar").innerHTML = waktu.getDate();
    document.getElementById("bulan_keluar").innerHTML = namaBulan[waktu.getMonth()];
    document.getElementById("tahun_keluar").innerHTML = waktu.getFullYear();
    document.getElementById("jam_keluar").innerHTML = waktu.getHours();
    document.getElementById("menit_keluar").innerHTML = waktu.getMinutes();
    document.getElementById("detik_keluar").innerHTML = waktu.getSeconds();
  }
  waktuMasuk();
  waktuKeluar();

  function updateLocationSession(lat, lng) {
    $.post('update_location_session.php', { lat_pegawai: lat, lng_pegawai: lng });
  }

  function getLocation(callback) {
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(function (position) {
        updateLocationSession(position.coords.latitude, position.coords.longitude);
        if (typeof callback === "function") callback();
      }, showError);
    } else {
      alert("Browser Anda tidak mendukung");
      if (typeof callback === "function") callback();
    }
  }

  document.addEventListener('DOMContentLoaded', function () {
    getLocation();
  });

  function showError(error) {
    switch (error.code) {
      case error.PERMISSION_DENIED:
        alert("Izin lokasi ditolak. Presensi tidak dapat dilakukan tanpa lokasi."); break;
      case error.POSITION_UNAVAILABLE:
        alert("Informasi lokasi tidak tersedia."); break;
      case error.TIMEOUT:
        alert("Permintaan lokasi melebihi batas waktu."); break;
      default:
        alert("Terjadi kesalahan saat mengambil lokasi.");
    }
  }
</script>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layout/main.php';