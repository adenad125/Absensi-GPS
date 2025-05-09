<script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.26/webcam.min.js"
  integrity="sha512-dQIiHSl2hr3NWKKLycPndtpbh5iaHLo6MwrXm7F0FM5e+kL2U16oE9uIwPHUl6fQBeCthiEuV/rzP3MiAB8Vfw=="
  crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
  integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
  integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<style>
  #map {
    height: 300px;
  }
</style>

<?php
session_start();
ob_start();

session_regenerate_id(true);

if (!isset($_SESSION["login"])) {
  header("Location: ../../auth/login.php?pesan=belum_login");
  exit;
} else if ($_SESSION["role"] !== 'pegawai') {
  header("Location: ../../auth/login.php?pesan=tolak_akses");
  exit;
}

include('../layout/header.php');

if (isset($_POST['tombol_masuk'])) {
  $latitude_pegawai = (float) $_POST['latitude_pegawai'];
  $longitude_pegawai = (float) $_POST['longitude_pegawai'];
  $latitude_kantor = (float) $_POST['latitude_kantor'];
  $longitude_kantor = (float) $_POST['longitude_kantor'];
  $radius = (float) $_POST['radius'];
  $zona_waktu = htmlspecialchars($_POST['zona_waktu']);
  $tanggal_masuk = htmlspecialchars($_POST['tanggal_masuk']);
  $jam_masuk = htmlspecialchars($_POST['jam_masuk']);
}

$perbedaan_koordinat = $longitude_pegawai - $longitude_kantor;

if (!is_numeric($latitude_pegawai) || !is_numeric($latitude_kantor) || !is_numeric($perbedaan_koordinat)) {
  die('Invalid coordinates provided.');
}

$jarak = sin(deg2rad($latitude_pegawai)) * sin(deg2rad($latitude_kantor)) +
  cos(deg2rad($latitude_pegawai)) * cos(deg2rad($latitude_kantor)) *
  cos(deg2rad($perbedaan_koordinat));
$jarak = acos($jarak);
$jarak = rad2deg($jarak);
$mil = $jarak * 60 * 1.1515;
$jarak_km = $mil * 1.609344;
$jarak_meter = $jarak_km * 1000;

if ($jarak_meter > $radius) {
  $_SESSION['gagal'] = "Anda keluar dari halaman kantor";
  header("Location: ../home");
  exit;
} else {
?>

<div class="page-body">
  <div class="container-xl">
    <div class="row">

      <div class="col-md-6">
        <div class="card">
          <div class="card-body">
            <div id="map"></div>
          </div>
        </div>
      </div>

      <div class="col-md-6">
        <div class="card text-center">
          <div class="card-body" style="margin: auto;">
            <input type="hidden" id="id" value="<?= $_SESSION['id'] ?>">
            <input type="hidden" id="tanggal_masuk" value="<?= $tanggal_masuk ?>">
            <input type="hidden" id="jam_masuk" value="<?= $jam_masuk ?>">
            <div id="my_camera" style="width:320px; height:240px;"></div>
            <div id="my_result"></div>
            <div><?= date('d F Y', strtotime($tanggal_masuk)) . '-' . $jam_masuk ?></div>
            <button class="btn btn-danger mt-2" id="ambil-foto">Ambil Foto</button>
            <button class="btn btn-warning mt-2" id="retake-foto" style="display: none;">Ulangi</button>
            <button class="btn btn-success mt-2" id="masuk" style="display: none;">Presensi Masuk</button>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>

<script language="JavaScript">
  Webcam.set({
    width: 320,
    height: 240,
    dest_width: 320,
    dest_height: 240,
    image_format: 'jpeg',
    jpeg_quality: 90,
    force_flash: false
  });

  Webcam.attach('#my_camera');

  document.getElementById('ambil-foto').addEventListener('click', function () {
    takeSnapshot();
  });

  function takeSnapshot() {
    let id = document.getElementById('id').value;
    let tanggal_masuk = document.getElementById('tanggal_masuk').value;
    let jam_masuk = document.getElementById('jam_masuk').value;
    Webcam.snap(function (data_uri) {
      document.getElementById('my_result').innerHTML = '<img src="' + data_uri + '"/>';
      document.getElementById('my_camera').style.display = 'none'; 
      document.getElementById('retake-foto').style.display = 'inline';
      document.getElementById('masuk').style.display = 'inline';
    });
  }

  document.getElementById('retake-foto').addEventListener('click', function () {
    document.getElementById('my_camera').style.display = 'block';
    document.getElementById('my_result').innerHTML = ''; 
    document.getElementById('retake-foto').style.display = 'none'; 
    document.getElementById('masuk').style.display = 'none'; 
  });

  document.getElementById('masuk').addEventListener('click', function () {
    let id = document.getElementById('id').value;
    let tanggal_masuk = document.getElementById('tanggal_masuk').value;
    let jam_masuk = document.getElementById('jam_masuk').value;
    let data_uri = document.querySelector('#my_result img').src;

    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
      if (xhttp.readyState == 4 && xhttp.status == 200) {
        
      }
    };
    xhttp.open("POST", "presensi_masuk_aksi.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send(
      'photo=' + encodeURIComponent(data_uri) +
      '&id=' + id +
      '&tanggal_masuk=' + tanggal_masuk +
      '&jam_masuk=' + jam_masuk
    );
  });

  //map leaftjs
  let latitude_ktr = <?= $latitude_kantor ?>;
  let longitude_ktr = <?= $longitude_kantor ?>;

  let latitude_peg = <?= $latitude_pegawai ?>;
  let longitude_peg = <?= $longitude_pegawai ?>;

  let map = L.map('map').setView([latitude_ktr, longitude_ktr], 13);
  L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
  }).addTo(map);

  var marker = L.marker([latitude_ktr, longitude_ktr]).addTo(map);

  var circle = L.circle([latitude_peg, longitude_peg], {
    color: 'red',
    fillColor: '#f03',
    fillOpacity: 0.5,
    radius: 500
  }).addTo(map).binPopup("lokasi Anda saat ini").openPopuo;
</script>
<?php } ?>

<?php include('../layout/footer.php'); ?>