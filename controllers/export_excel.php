<?php
include "../config/koneksi.php";

header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=Absensi_Apotek.xls");

$tanggal = $_GET['tanggal'] ?? date('Y-m-d');

$data = mysqli_query($conn, "
SELECT * FROM absensi 
WHERE DATE(waktu) = '$tanggal'
ORDER BY waktu ASC
");

// ===== STYLE =====
echo "
<style>
body {
    font-family: Arial;
}

.title {
    font-size: 18px;
    font-weight: bold;
    text-align: center;
}

.subtitle {
    text-align: center;
    margin-bottom: 20px;
}

.logo {
    text-align: center;
    font-size: 20px;
    font-weight: bold;
    color: #c62828;
}

table {
    border-collapse: collapse;
    width: 100%;
}

th {
    background: #c62828;
    color: white;
    padding: 10px;
}

td {
    padding: 8px;
    text-align: center;
}

tr:nth-child(even) {
    background: #f9f9f9;
}

.status-telat {
    color: red;
    font-weight: bold;
}

.status-ontime {
    color: green;
    font-weight: bold;
}
</style>
";

// ===== HEADER =====
echo "
<div class='logo'>💊 APOTEK ALKES</div>
<div class='title'>LAPORAN ABSENSI CABANG</div>
<div class='subtitle'>Tanggal: $tanggal</div>
<br>
";

// ===== TABLE =====
echo "
<table border='1'>
<tr>
    <th>No</th>
    <th>Cabang</th>
    <th>Nama Tim</th>
    <th>Jam</th>
    <th>Status</th>
    <th>Latitude</th>
    <th>Longitude</th>
</tr>
";

$no = 1;

while($d = mysqli_fetch_assoc($data)){

    $jam = date("H:i:s", strtotime($d['waktu']));
    $status = ($jam > "06:25:00") ? "TERLAMBAT" : "ON TIME";

    $class = ($status == "TERLAMBAT") ? "status-telat" : "status-ontime";

    echo "
    <tr>
        <td>$no</td>
        <td>{$d['cabang']}</td>
        <td>{$d['nama_tim']}</td>
        <td>$jam</td>
        <td class='$class'>$status</td>
        <td>{$d['latitude']}</td>
        <td>{$d['longitude']}</td>
    </tr>
    ";

    $no++;
}

echo "</table>";