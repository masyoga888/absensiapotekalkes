<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>

<!-- HEADER -->
<div class="top-header">
    <div class="header-title">💊 Dashboard</div>
    <a href="logout.php" class="logout-btn" onclick="return confirm('Yakin logout?')">
        🚪 Logout
    </a>
</div>

<div class="container">

<?php
include "config/koneksi.php";

date_default_timezone_set('Asia/Jakarta');

$tanggal = isset($_GET['tanggal']) && $_GET['tanggal'] != '' 
    ? $_GET['tanggal'] 
    : date('Y-m-d');

// QUERY
$data = mysqli_query($conn, "
SELECT * FROM absensi 
WHERE DATE(waktu) = '$tanggal'
ORDER BY waktu ASC
");

// SUMMARY
$total = mysqli_num_rows($data);

$la = mysqli_fetch_assoc(mysqli_query($conn, "
SELECT COUNT(*) as jml FROM absensi 
WHERE DATE(waktu) = '$tanggal' 
AND TIME(waktu) < '06:25:00'
"))['jml'];

$ot = mysqli_fetch_assoc(mysqli_query($conn, "
SELECT COUNT(*) as jml FROM absensi 
WHERE DATE(waktu) = '$tanggal' 
AND TIME(waktu) = '06:25:00'
"))['jml'];

$t = mysqli_fetch_assoc(mysqli_query($conn, "
SELECT COUNT(*) as jml FROM absensi 
WHERE DATE(waktu) = '$tanggal' 
AND TIME(waktu) > '06:25:00'
"))['jml'];
?>

<!-- HEADER -->
<div style="text-align:center; margin-bottom:10px;">
    <h2>📊 Dashboard Absensi</h2>
</div>

<!-- JAM -->
<div id="jam-live" style="text-align:center; font-weight:bold; margin:10px;">
    🕒 --
</div>

<!-- ACTION -->
<div style="text-align:center; margin-bottom:15px;">
    <a href="detail.php?tanggal=<?= $tanggal ?>" class="btn">📋 Detail Cabang</a>
</div>

<!-- FILTER -->
<div class="filter-bar">
    <form method="GET" class="filter-form">
        <input type="date" name="tanggal" value="<?= $tanggal ?>">
        <button class="btn">Filter</button>

        <a href="controllers/export_excel.php?tanggal=<?= $tanggal ?>" class="btn excel">
           ⬇ Export Excel
        </a>
    </form>
</div>

<!-- SUMMARY -->
<div class="summary">
    <div class="card-summary">
        <h3><?= $total ?></h3>
        <p>Total Absensi</p>
    </div>

    <div class="card-summary green">
        <h3><?= $la ?></h3>
        <p>Lebih Awal</p>
    </div>

    <div class="card-summary yellow">
        <h3><?= $ot ?></h3>
        <p>On Time</p>
    </div>

    <div class="card-summary red">
        <h3><?= $t ?></h3>
        <p>Terlambat</p>
    </div>
</div>

<!-- TABLE -->
<div class="table-scroll">
<div class="table-modern-wrapper">
<table class="table-modern">
    <thead>
        <tr>
            <th>Cabang</th>
            <th>Nama Tim</th>
            <th>Jam</th>
            <th>Status</th>
            <th>Foto</th>
            <th>Lokasi</th>
        </tr>
    </thead>
    <tbody>

<?php
if(mysqli_num_rows($data) > 0){
    while($d = mysqli_fetch_assoc($data)){

        $jam = date("H:i:s", strtotime($d['waktu']));

        if($jam < "06:25:00"){
            $status = "<span class='badge green'>Lebih Awal</span>";
        } elseif($jam == "06:25:00"){
            $status = "<span class='badge yellow'>On Time</span>";
        } else {
            $status = "<span class='badge red'>Terlambat</span>";
        }

        echo "<tr>
            <td>{$d['cabang']}</td>
            <td>{$d['nama_tim']}</td>
            <td>$jam</td>
            <td>$status</td>
            <td>
                <img src='{$d['foto']}' class='img-preview' onclick=\"openModal('{$d['foto']}')\">
            </td>
            <td>
               <a href="#" onclick="openMap('{$d['latitude']}', '{$d['longitude']}')" class="maps-link">
📍 Maps
</a>
            </td>
        </tr>";
    }
} else {
    echo "<tr>
        <td colspan='6' style='text-align:center; padding:20px;'>
            🚫 Belum ada data absensi
        </td>
    </tr>";
}
?>

    </tbody>
</table>
</div>
</div>

</div>

<!-- JAM -->
<script>
setInterval(() => {
    let now = new Date();
    document.getElementById("jam-live").innerText =
        "🕒 " + now.toLocaleTimeString('id-ID');
}, 1000);
</script>

<!-- MODAL -->
<div id="imgModal" class="modal">
    <span class="close" onclick="closeModal()">&times;</span>
    <img class="modal-content" id="modalImg">
</div>

<script>
function openModal(src) {
    document.getElementById("imgModal").style.display = "flex";
    document.getElementById("modalImg").src = src;
}

function closeModal() {
    document.getElementById("imgModal").style.display = "none";
}
</script>
<script>
function openMap(lat, lng) {
    document.getElementById("mapModal").style.display = "flex";
    document.getElementById("mapFrame").src =
        "https://www.google.com/maps?q=" + lat + "," + lng + "&hl=id&z=17&output=embed";
}

function closeMap() {
    document.getElementById("mapModal").style.display = "none";
}
</script>
<div id="mapModal" class="modal">
    <span class="close" onclick="closeMap()">&times;</span>
    <iframe id="mapFrame"></iframe>
</div>
</body>
</html>