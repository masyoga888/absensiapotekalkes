<?php
include "config/koneksi.php";

$tanggal = $_GET['tanggal'] ?? date('Y-m-d');

// ambil semua cabang
$cabang = mysqli_query($conn, "SELECT * FROM cabang");

// ambil absensi hari ini
$absensi = mysqli_query($conn, "
SELECT cabang FROM absensi 
WHERE DATE(waktu) = '$tanggal'
");

$absen = [];
while($a = mysqli_fetch_assoc($absensi)){
    $absen[] = $a['cabang'];
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Detail Cabang</title>
<link rel="stylesheet" href="assets/css/style.css">
</head>

<body class="full-page">
<div class="top-bar">
    <div class="title">
        📋 Detail Absensi Cabang
    </div>

    <div class="top-actions">
        <a href="dashboard.php" class="btn-back">
            ← Kembali
        </a>

        <a href="logout.php" class="btn-logout" onclick="return confirm('Yakin logout?')">
            Logout
        </a>
    </div>
</div>
<div class="container">

<h2 style="text-align:center;">📋 Detail Absensi Cabang</h2>

<div class="table-modern-wrapper">
<table class="table-modern">
<tr>
    <th>Kode</th>
    <th>Nama Cabang</th>
    <th>Status</th>
</tr>

<?php
while($c = mysqli_fetch_assoc($cabang)){

    $status = in_array($c['kode'], $absen)
        ? "<span class='badge green'>Sudah Absen</span>"
        : "<span class='badge red'>Belum Absen</span>";

    echo "<tr>
        <td>{$c['kode']}</td>
        <td>{$c['nama']}</td>
        <td>$status</td>
    </tr>";
}
?>

</table>
</div>

</div>
</div>

</body>
</html>