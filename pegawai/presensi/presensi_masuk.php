<style>
  #map {
    height: 300px;
  }
</style>

<?php
session_start();
ob_start();
$judul = "Presensi Masuk";
require_once realpath(__DIR__ . '/../../config/config.php');

session_regenerate_id(true);

$id_lok_presensi = $_SESSION['id_lok_presensi'];

$result = mysqli_query($connection, "SELECT * FROM lokasi_presensi WHERE id = '$id_lok_presensi'");

while ($lokasi = mysqli_fetch_array($result)) {
  $latitude_kantor = $lokasi['latitude'];
  $longitude_kantor = $lokasi['longitude'];
  $radius = $lokasi['radius'];
  $zona_waktu = $lokasi['zona_waktu'];
}

$latitude_pegawai = 0;
$longitude_pegawai = 0;
$tanggal_masuk = '';
$jam_masuk = '';

// Set pegawai coordinates from session if available
if (isset($_SESSION['lat_pegawai'])) {
  $latitude_pegawai = (float) $_SESSION['lat_pegawai'];
}
if (isset($_SESSION['lng_pegawai'])) {
  $longitude_pegawai = (float) $_SESSION['lng_pegawai'];
}

if (isset($_POST['tanggal_masuk'])) {
  $tanggal_masuk = htmlspecialchars($_POST['tanggal_masuk']);
}
if (isset($_POST['jam_masuk'])) {
  $jam_masuk = htmlspecialchars($_POST['jam_masuk']);
}

// Correct calculation: longitude - longitude, not longitude - latitude
$perbedaan_koordinat = $longitude_pegawai - $longitude_kantor;

if (!is_numeric($latitude_pegawai) || !is_numeric($latitude_kantor) || !is_numeric($perbedaan_koordinat)) {
  $_SESSION['gagal'] = "Invlalid coordinates. Please try again.";
  header('Location: ../home/index.php');
  exit;
}

$jarak = sin(deg2rad($latitude_pegawai)) * sin(deg2rad($latitude_kantor)) +
  cos(deg2rad($latitude_pegawai)) * cos(deg2rad($latitude_kantor)) *
  cos(deg2rad($perbedaan_koordinat));
$jarak = acos($jarak);
$jarak = rad2deg($jarak);
$mil = $jarak * 60 * 1.1515;
$jarak_km = $mil * 1.609344;
$jarak_meter = $jarak_km * 1000;
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
      <?php
      if ($jarak_meter > $radius) {
        $_SESSION['gagal'] = "Anda terlalu jauh dari lokasi kantor.";
        header('Location: ../home/index.php');
        exit;
      } else {
        ?>
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
    // Webcam error handling
    function handleWebcamError(err) {
      let msg = "Webcam tidak dapat diakses. Pastikan browser Anda mengizinkan akses kamera.";
      if (err && err.message) {
        msg += "<br>Error: " + err.message;
      }
      document.getElementById('my_camera').style.display = 'none';
      document.getElementById('my_result').innerHTML = '<div class="alert alert-danger">' + msg + '</div>';
      document.getElementById('ambil-foto').disabled = true;
    }

    // Try to attach webcam and handle errors
    try {
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

      Webcam.on('error', function(err) {
        handleWebcamError(err);
      });
    } catch (err) {
      handleWebcamError(err);
    }

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
          window.location.href = "../home/index.php";
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

    let map = L.map('map').setView([latitude_ktr, longitude_ktr], 14);
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
      maxZoom: 19,
      attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(map);

    var marker = L.marker([latitude_ktr, longitude_ktr]).addTo(map);
    var circle = L.circle([latitude_ktr, longitude_ktr], {
      color: 'red',
      fillColor: '#f03',
      fillOpacity: 0.5,
      radius: <?= $radius ?> // radius in meters
    }).addTo(map).bindPopup("lokasi Kantor").openPopup();

    // Add marker for pegawai (employee) location if coordinates are valid
    if (!isNaN(latitude_peg) && !isNaN(longitude_peg) && latitude_peg !== 0 && longitude_peg !== 0) {
      var pegawaiMarker = L.marker([latitude_peg, longitude_peg], {
        icon: L.icon({
          iconUrl: 'https://unpkg.com/leaflet@1.9.3/dist/images/marker-icon.png',
          iconSize: [25, 41],
          iconAnchor: [12, 41],
          popupAnchor: [1, -34],
          shadowUrl: 'https://unpkg.com/leaflet@1.9.3/dist/images/marker-shadow.png',
          shadowSize: [41, 41]
        })
      }).addTo(map).bindPopup("Lokasi Anda");
    }
  </script>
<?php } ?>
<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layout/main.php';