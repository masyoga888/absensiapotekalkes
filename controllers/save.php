<?php
session_start();
date_default_timezone_set("Asia/Jakarta");
include "../config/koneksi.php";

// 🔥 DEBUG (boleh hapus nanti)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// ==================
// AMBIL DATA JSON
// ==================
$raw = file_get_contents("php://input");
$data = json_decode($raw);

// 🔥 CEGAH ERROR KALAU BUKAN JSON
if(!$data){
    echo "❌ Akses tidak valid (bukan dari aplikasi)";
    exit;
}

// ==================
// AMBIL DATA
// ==================
$image     = $data->image ?? null;
$lat       = $data->latitude ?? null;
$lng       = $data->longitude ?? null;
$nama_tim  = $data->nama_tim ?? null;
$alamat    = $data->alamat ?? '-';
$cabang    = $_SESSION['cabang'] ?? '-';

// VALIDASI
if(!$image || !$nama_tim){
    echo "❌ Data tidak lengkap!";
    exit;
}

// ==================
// PROSES GAMBAR
// ==================
$image = str_replace('data:image/png;base64,', '', $image);
$image = base64_decode($image);

// Folder upload
$folder = __DIR__ . "/../uploads/";

if (!is_dir($folder)) {
    mkdir($folder, 0777, true);
}

// Nama file
$nama_file = time() . ".png";
$path_file = $folder . $nama_file;

// Simpan
if(!file_put_contents($path_file, $image)){
    echo "❌ Gagal simpan foto!";
    exit;
}

// Path DB
$path_db = "uploads/" . $nama_file;

// ==================
// WAKTU & STATUS
// ==================
$waktu  = date("Y-m-d H:i:s");

if (date("H:i:s") >= "06:00:00" && date("H:i:s") <= "06:24:59") {
    $status = "LEBIH AWAL";
} elseif (date("H:i:s") == "06:25:00") {
    $status = "ON TIME";
} else {
    $status = "TERLAMBAT";
}

// ==================
// SIMPAN DB
// ==================
$query = mysqli_query($conn, "
INSERT INTO absensi 
(cabang, nama_tim, foto, latitude, longitude, alamat, waktu, status)
VALUES (
    '$cabang',
    '$nama_tim',
    '$path_db',
    '$lat',
    '$lng',
    '$alamat',
    '$waktu',
    '$status'
)
");

// ==================
// RESPONSE
// ==================
if($query){
    echo "✅ Berhasil Absensi!";
} else {
    echo "❌ Gagal simpan ke database!";
}
?>