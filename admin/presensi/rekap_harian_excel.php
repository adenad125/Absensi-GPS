<?php
// session_start();
// ob_start();
// if (!isset($_SESSION["login"])) {
//     header("Location:../../auth/login.php?pesan=belum_login");
// } else if ($_SESSION["role"] === 'admin') {
//     header("Location:../../auth/login.php?pesan=tolak_akses");
// }

// $judul = "Rekap Presensi Harian";
require_once realpath(__DIR__ . '/../../config/config.php');
require realpath(__DIR__ . '/../../vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$tanggal_dari = $_POST['tanggal_dari'] ?? '';
$tanggal_sampai = $_POST['tanggal_sampai'] ?? '';

$query = "SELECT pr.*, p.nama, l.nama_lokasi, p.nip FROM presensi pr 
    JOIN pegawai p ON  p.id = pr.id_pegawai
    JOIN lokasi_presensi l ON l.id = p.id_lok_presensi";

if (!empty($tanggal_dari) && !empty($tanggal_sampai)) {
    $tanggal_dari_mysql = date('Y-m-d', strtotime(str_replace('/', '-', $tanggal_dari)));
    $tanggal_sampai_mysql = date('Y-m-d', strtotime(str_replace('/', '-', $tanggal_sampai)));
    $query .= " WHERE pr.tanggal_masuk BETWEEN '$tanggal_dari_mysql' AND '$tanggal_sampai_mysql'";
}

$result = mysqli_query($connection, $query);


$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$borderStyle = [
    'alignment' => [
        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
    ],
    'borders' => [
        'allBorders' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
        ],
    ],
];

$headingStyle = [
    'font' => [
        'bold' => true,
    ],
    'alignment' => [
        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
    ],
];

//wrap text
$sheet->getStyle('5')
    ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)->setWrapText(true);
$sheet->getStyle("A5:I5")->applyFromArray($headingStyle);

$sheet->getColumnDimension('B')->setWidth(20);
$sheet->getColumnDimension('C')->setAutoSize(true);
$sheet->getColumnDimension('D')->setAutoSize(true);
$sheet->getColumnDimension('E')->setAutoSize(true);
$sheet->getColumnDimension('F')->setAutoSize(true);
$sheet->getColumnDimension('G')->setAutoSize(true);
$sheet->getColumnDimension('I')->setAutoSize(true);
$sheet->getDefaultColumnDimension()->setWidth(30, 'pt');

$sheet->setCellValue('A1', 'REKAP PRESENSI HARIAN');
$sheet->setCellValue('A2', 'Tanggal Awal');
$sheet->setCellValue('A3', 'Tanggal Akhir');
$sheet->setCellValue('C2', $tanggal_dari ?? '-');
$sheet->setCellValue('C3', $tanggal_sampai ?? '-');
$sheet->setCellValue('A5', 'NO');
$sheet->setCellValue('B5', 'NAMA');
$sheet->setCellValue('C5', 'NIP');
$sheet->setCellValue('D5', 'TANGGAL MASUK');
$sheet->setCellValue('E5', 'JAM MASUK');
$sheet->setCellValue('F5', 'TANGGAL KELUAR');
$sheet->setCellValue('G5', 'JAM KELUAR');
$sheet->setCellValue('H5', 'TOTAL JAM KERJA');
$sheet->setCellValue('I5', 'TERLAMBAT (Menit)');

$sheet->mergeCells('A1:I1');
$sheet->mergeCells('A2:B2');
$sheet->mergeCells('A3:B3');

$no = 1;
$row = 6;

while ($data = mysqli_fetch_array($result)) {
    $jam_masuk = $data['jam_masuk'];
    $jam_keluar = $data['jam_keluar'];
    $tanggal_masuk = $data['tanggal_masuk'];
    $tanggal_keluar = $data['tanggal_masuk']; // Asumsi tanggal sama

    $jam_tanggal_masuk = date('Y-m-d H:i:s', strtotime("$tanggal_masuk $jam_masuk"));
    $jam_tanggal_keluar = date('Y-m-d H:i:s', strtotime("$tanggal_keluar $jam_keluar"));

    $timestamp_masuk = strtotime($jam_tanggal_masuk);
    $timestamp_keluar = strtotime($jam_tanggal_keluar);

    $selisih = $timestamp_keluar - $timestamp_masuk;

    $total_jam_kerja = floor($selisih / 3600);
    $selisih -= $total_jam_kerja * 3600;
    $selisih_menit_kerja = floor($selisih / 60);

    // Cek jam standar dari lokasi
    $lokasi_presensi = $data['nama_lokasi'];
    $lokasi_q = mysqli_query($connection, "SELECT * FROM lokasi_presensi WHERE nama_lokasi = '$lokasi_presensi'");
    $lokasi_data = mysqli_fetch_assoc($lokasi_q);
    $jam_masuk_standar = $lokasi_data['jam_masuk'];

    // Hitung keterlambatan
    $jam_standar = strtotime($tanggal_masuk . ' ' . $jam_masuk_standar);
    $terlambat = $timestamp_masuk - $jam_standar;
    $terlambat_menit = $terlambat > 0 ? floor($terlambat / 60) : 0;

    $sheet->setCellValue('A' . $row, $no);
    $sheet->setCellValue('B' . $row, $data['nama']);
    $sheet->setCellValue('C' . $row, $data['nip']);
    $sheet->setCellValue('D' . $row, $data['tanggal_masuk']);
    $sheet->setCellValue('E' . $row, $data['jam_masuk']);
    $sheet->setCellValue('F' . $row, $data['tanggal_masuk']);
    $sheet->setCellValue('G' . $row, $data['jam_keluar']);
    $sheet->setCellValue('H' . $row, $total_jam_kerja . ' Jam ' . $selisih_menit_kerja . ' Menit');
    $sheet->setCellValue('I' . $row, $terlambat_menit . ' Menit');

    $no++;
    $row++;
}

$lastRow = $row - 1;
$sheet->getStyle("A5:I$lastRow")->applyFromArray($borderStyle);
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Laporan Presensi Harian.xlsx"');
header('Cache-Control: max-age=0');

$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
