<?php
$judul = "Edit Pegawai ";
ob_start();
require_once realpath(__DIR__ . '/../../config/config.php');

if (isset($_POST['edit'])) {
    $id = $_POST['id'];
    $nama = htmlspecialchars($_POST['nama']);
    $jenis_kelamin = htmlspecialchars($_POST['jenis_kelamin']);
    $alamat = htmlspecialchars($_POST['alamat']);
    $no_handphone = htmlspecialchars($_POST['no_handphone']);
    $id_jabatan = htmlspecialchars($_POST['id_jabatan']);
    $username = htmlspecialchars($_POST['username']);
    $role = htmlspecialchars($_POST['role']);
    $status = htmlspecialchars($_POST['status']);
    $id_lok_presensi = htmlspecialchars($_POST['id_lok_presensi']);

    if (empty($_POST['password'])) {
        $password = $_POST['password_lama'];
    } else {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    }

    if ($_FILES['foto_baru']['error'] === 4) {
        $nama_file = $_POST['foto_lama'];
    } else {
        if (isset($_FILES['foto_baru'])) {
            $file = $_FILES['foto_baru'];
            $nama_file = $file['name'];
            $file_tmp = $file['tmp_name'];
            $ukuran_file = $file['size'];
            $file_direktori = "../../assets/img/foto_pegawai/" . $nama_file;

            $ambil_ekstensi = pathinfo($nama_file, PATHINFO_EXTENSION);
            $ekstensi_diizinkan = ["jpg", "png", "jpeg"];
            $max_ukuran_file = 10 * 1024 * 1024;

            move_uploaded_file($file_tmp, $file_direktori);
        }
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

        if ($_FILES['foto_baru']['error'] != 4) {

            if (!in_array(strtolower($ambil_ekstensi), $ekstensi_diizinkan)) {
                $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i> Hanya file JPG, JPEG dan PNG yang diperbolehkan";
            }
            if ($ukuran_file > $max_ukuran_file) {
                $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i> Uuran file melebihi 10 MB";
            }
        }

        if (!empty($pesan_kesalahan)) {
            $_SESSION['validasi'] = implode("<br>", array: $pesan_kesalahan);
        } else {
            $pegawai = mysqli_query($connection, "UPDATE pegawai SET 
                nama = '$nama',
                jenis_kelamin = '$jenis_kelamin',
                alamat = '$alamat',
                no_handphone = '$no_handphone',
                id_jabatan = '$id_jabatan',
                id_lok_presensi = '$id_lok_presensi',
                foto = '$nama_file'
                WHERE id = '$id'");

            $users = mysqli_query($connection, "UPDATE users SET 
                username = '$username',
                password = '$password',
                status = '$status',
                role = '$role'
                WHERE id = '$id'");

            $_SESSION['berhasil'] = "Data berhasil diupdate";
            header("Location: ./");
            exit;
        }
    }
}


$id = isset($_GET['id']) ? $_GET['id'] : $_POST['id'];
$result = mysqli_query(
    $connection,
    "SELECT u.id_pegawai, u.username, u.password, u.status, u.role, 
            p.id, p.nip, p.nama, p.jenis_kelamin, p.alamat, p.no_handphone, p.foto, p.id_jabatan, p.id_lok_presensi,
            j.nama_jabatan,
            lp.nama_lokasi as lokasi_presensi
            FROM users u 
            JOIN pegawai p ON u.id_pegawai = p.id 
            JOIN jabatan j ON p.id_jabatan = j.id
            JOIN lokasi_presensi lp ON p.id_lok_presensi = lp.id 
            WHERE p.id=$id"
);

while ($pegawai = mysqli_fetch_array($result)) {
    $nama = $pegawai['nama'];
    $jenis_kelamin = $pegawai['jenis_kelamin'];
    $alamat = $pegawai['alamat'];
    $no_handphone = $pegawai['no_handphone'];
    $id_jabatan = $pegawai['id_jabatan'];
    $username = $pegawai['username'];
    $password = $pegawai['password'];
    $status = $pegawai['status'];
    $id_lok_presensi = $pegawai['id_lok_presensi'];
    $role = $pegawai['role'];
    $foto = $pegawai['foto'];
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
        <form action="<?= base_url('admin/data_pegawai/edit.php') ?>" method="POST" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="">Nama</label>
                                <input type="text" class="form-control" name="nama" value="<?= $nama ?>">
                            </div>

                            <div class="mb-3">
                                <label for="">Jenis Kelamin</label>
                                <select name="jenis_kelamin" class="form-control">
                                    <option value="">--Pilih Jenis Kelamin--</option>
                                    <option <?= $jenis_kelamin == 'L' ? 'selected' : '' ?> value="L">Laki-laki</option>

                                    <option <?= $jenis_kelamin == 'P' ? 'selected' : '' ?> value="P">Perempuan</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="">Alamat</label>
                                <input type="text" class="form-control" name="alamat" value="<?= $alamat ?>">
                            </div>

                            <div class="mb-3">
                                <label for="">No. Handphone</label>
                                <input type="text" class="form-control" name="no_handphone"
                                    value="<?= $no_handphone ?>">
                            </div>

                            <div class="mb-3">
                                <label for="">Jabatan</label>
                                <select name="id_jabatan" class="form-control">
                                    <option value="">--Pilih Jabatan--</option>
                                    <?php
                                    $jabatans = mysqli_query($connection, "SELECT * FROM jabatan ORDER BY nama_jabatan ASC");
                                    while ($jabatan = mysqli_fetch_assoc($jabatans)) {
                                        $selected = $jabatan['id'] == $id_jabatan ? 'selected' : '';
                                        echo '<option value="' . $jabatan['id'] . '" ' . $selected . '>' . $jabatan['nama_jabatan'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="">Status</label>
                                <select name="status" class="form-control">
                                    <option value="">--Pilih Status--</option>
                                    <option <?= $status == 'aktif' ? 'selected' : '' ?> value="aktif">Aktif</option>

                                    <option <?= $status == 'non aktif' ? 'selected' : '' ?> value="non aktif">Tidak Aktif
                                    </option>
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
                                <input type="text" class="form-control" name="username" value="<?= $username ?>">
                            </div>

                            <div class="mb-3">
                                <label for="">Password</label>
                                <input type="hidden" value="<?= $password; ?>" name="password_lama">
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
                                    <option <?= $role == 'admin' ? 'selected' : '' ?> value="admin">Admin</option>

                                    <option <?= $role == 'pegawai' ? 'selected' : '' ?> value="pegawai">Pegawai</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="">Lokasi Presensi</label>
                                <select name="id_lok_presensi" class="form-control">
                                    <option value="">--Pilih Lokasi Presensi--</option>

                                    <?php
                                    $lokasi_presensis = mysqli_query($connection, "SELECT * FROM lokasi_presensi ORDER BY id ASC");
                                    while ($lokasi_presensi = mysqli_fetch_assoc($lokasi_presensis)) {
                                        $selected = $lokasi_presensi['id'] == $id_lok_presensi ? 'selected' : '';
                                        echo '<option value="' . $lokasi_presensi['id'] . '" ' . $selected . '>' . $lokasi_presensi['nama_lokasi'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="">Foto</label>
                                <input type="hidden" value="<?= $foto ?>" name="foto_lama">
                                <input type="file" class="form-control" name="foto_baru">
                            </div>
                            <input type="hidden" value="<?= $id ?>" name="id">
                            <button type="submit" class="btn btn-primary" name="edit">Update</button>
                        </div>
                    </div>
                </div>
        </form>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../layout/main.php';