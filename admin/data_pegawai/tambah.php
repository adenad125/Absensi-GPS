<?php
$judul = "Tambah Pegawai";
ob_start();
require_once realpath(__DIR__ . '/../../config/config.php');

if (isset($_POST['submit'])) {

    $ambil_nip = mysqli_query($connection, "SELECT nip FROM pegawai ORDER BY nip DESC LIMIT 1");

    if (mysqli_num_rows($ambil_nip) > 0) {
        $row = mysqli_fetch_assoc($ambil_nip);
        $nip_db = $row['nip'];
        $nip_db = explode("-", $nip_db);
        $no_baru = (int) $nip_db[1] + 1;
        $nip_baru = "PEG-" . str_pad($no_baru, 4, 0, STR_PAD_LEFT);
    } else {
        $nip_baru = "PEG-0001";
    }

    $nip = $nip_baru;
    $nama = htmlspecialchars($_POST['nama']);
    $jenis_kelamin = htmlspecialchars($_POST['jenis_kelamin']);
    $alamat = htmlspecialchars($_POST['alamat']);
    $no_handphone = htmlspecialchars($_POST['no_handphone']);
    $id_jabatan = htmlspecialchars($_POST['id_jabatan']);
    $username = htmlspecialchars($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = htmlspecialchars($_POST['role']);
    $status = htmlspecialchars($_POST['status']);
    $id_lok_presensi = htmlspecialchars($_POST['id_lok_presensi']);

    if (isset($_FILES['foto'])) {
        $file = $_FILES['foto'];
        $nama_file = $file['name'];
        $file_tmp = $file['tmp_name'];
        $ukuran_file = $file['size'];
        $file_direktori = "../../assets/img/foto_pegawai/" . $nama_file;

        $ambil_ekstensi = pathinfo($nama_file, PATHINFO_EXTENSION);
        $ekstensi_diizinkan = ["jpg", "png", "jpeg"];
        $max_ukuran_file = 10 * 1024 * 1024;

        move_uploaded_file($file_tmp, $file_direktori);
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (empty($nama)) {
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i> Nama wajib diisi";
        }
        if (empty($jenis_kelamin)) {
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i> Jenis kelamin wajib diisi";
        }
        if (empty($alamat)) {
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i> Alamat wajib diisi";
        }
        if (empty($no_handphone)) {
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i> No. handphone  wajib diisi";
        }
        if (empty($id_jabatan)) {
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i> Jabatan  wajib diisi";
        }
        if (empty($username)) {
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i> Username wajib diisi";
        }
        if (empty($role)) {
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i>  Role wajib diisi";
        }
        if (empty($status)) {
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i> Status wajib diisi";
        }
        if (empty($id_lok_presensi)) {
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i> Lokasi presensi wajib diisi";
        }
        if (empty($password)) {
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i> Password wajib diisi";
        }
        if ($_POST['password'] != $_POST['ulangi_password']) {
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i> Password tidak cocok";
        }
        if (!in_array(strtolower($ambil_ekstensi), $ekstensi_diizinkan)) {
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i> Hanya file JPG, JPEG dan PNG yang diperbolehkan";
        }
        if ($ukuran_file > $max_ukuran_file) {
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i> Uuran file melebihi 10 MB";
        }

        if (!empty($pesan_kesalahan)) {
            $_SESSION['validasi'] = implode("<br>", array: $pesan_kesalahan);
        } else {
            $pegawai = mysqli_query($connection, "INSERT INTO pegawai(nip, nama, jenis_kelamin, alamat, no_handphone, id_jabatan, id_lok_presensi, foto) VALUES('$nip','$nama','$jenis_kelamin','$alamat','$no_handphone','$id_jabatan','$id_lok_presensi','$nama_file')");

            $id_pegawai = mysqli_insert_id($connection);
            $users = mysqli_query($connection, "INSERT INTO users(id_pegawai, username, password, status, role) VALUES('$id_pegawai','$username','$password','$status','$role')");

            $_SESSION['berhasil'] = "Data berhasil disimpan";
            header("Location: ./");
            exit;
        }
    }
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
        <form action="<?= base_url('admin/data_pegawai/tambah.php') ?>" method="POST" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="">Nama</label>
                                <input type="text" class="form-control" name="nama"
                                    value="<?= isset($_POST['nama']) ?? $_POST['nama'] ?>">
                            </div>
                            <div class="mb-3">
                                <label for="">Jenis Kelamin</label>
                                <select name="jenis_kelamin" class="form-control">
                                    <option value="">--Pilih Jenis Kelamin--</option>
                                    <option <?= isset($_POST['jenis_kelamin']) && $_POST['jenis_kelamin'] == 'L' ? 'selected' : '' ?> value="L">Laki-laki</option>

                                    <option <?= isset($_POST['jenis_kelamin']) && $_POST['jenis_kelamin'] == 'P' ? 'selected' : '' ?> value="P">Perempuan</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="">Alamat</label>
                                <input type="text" class="form-control" name="alamat"
                                    value="<?= isset($_POST['alamat']) ?? $_POST['alamat'] ?>">
                            </div>
                            <div class="mb-3">
                                <label for="">No. Handphone</label>
                                <input type="text" class="form-control" name="no_handphone"
                                    value="<?= isset($_POST['no_handphone']) ?? $_POST['no_handphone'] ?>">
                            </div>
                            <div class="mb-3">
                                <label for="">Jabatan</label>
                                <select name="id_jabatan" class="form-control">
                                    <option value="">--Pilih Jabatan--</option>
                                    <?php
                                    $jabatans = mysqli_query($connection, "SELECT * FROM jabatan ORDER BY nama_jabatan ASC");
                                    while ($jabatan = mysqli_fetch_assoc($jabatans)) {
                                        $selected = (isset($_POST['id_jabatan']) && $_POST['id_jabatan'] == $jabatan['id']) ? 'selected' : '';
                                        echo '<option value="' . $jabatan['id'] . '" ' . $selected . '>' . $jabatan['nama_jabatan'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="">Status</label>
                                <select name="status" class="form-control">
                                    <option value="">--Pilih Status--</option>
                                    <option <?php if (isset($_POST['status']) && $_POST['status'] == 'aktif') {
                                        echo 'selected';
                                    } ?> value="aktif">Aktif</option>
                                    <option <?php if (isset($_POST['status']) && $_POST['status'] == 'Tidak Aktif') {
                                        echo 'selected';
                                    } ?> value="non aktif">Tidak Aktif</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="">Username</label>
                                <input type="text" class="form-control" name="username" value="<?php if (isset($_POST['username']))
                                    echo $_POST['username'] ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="">Password</label>
                                    <input type="password" class="form-control" name="password">
                                </div>
                                <div class="mb-3">
                                    <label for="">Ulangi Password</label>
                                    <input type="password" class="form-control" name="ulangi_password">
                                </div>
                                <div class="mb-3">
                                    <label for="">Role</label>
                                    <select name="role" class="form-control">
                                        <option value="">--Pilih Role--</option>
                                        <option <?php if (isset($_POST['role']) && $_POST['role'] == 'admin') {
                                    echo 'selected';
                                } ?> value="admin">Admin</option>
                                    <option <?php if (isset($_POST['role']) && $_POST['role'] == 'pegawai') {
                                        echo 'selected';
                                    } ?> value="pegawai">Pegawai</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="">Lokasi Presensi</label>
                                <select name="id_lok_presensi" class="form-control">
                                    <option value="">--Pilih Lokasi Presensi--</option>
                                    <?php
                                    $lokasi_presensis = mysqli_query($connection, "SELECT * FROM lokasi_presensi ORDER BY id ASC");
                                    while ($lokasi_presensi = mysqli_fetch_assoc($lokasi_presensis)) {
                                        $selected = (isset($_POST['id_lok_presensi']) && $_POST['id_lok_presensi'] == $jabatan['id']) ? 'selected' : '';
                                        echo '<option value="' . $lokasi_presensi['id'] . '" ' . $selected . '>' . $lokasi_presensi['nama_lokasi'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="">Foto</label>
                                <input type="file" class="form-control" name="foto">
                            </div>
                            <button type="submit" class="btn btn-primary" name="submit">Simpan</button>
                        </div>
                    </div>
                </div>
        </form>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layout/main.php';