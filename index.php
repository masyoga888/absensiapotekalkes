<?php
session_start();

if(!isset($_SESSION['login'])){
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Absensi</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="assets/css/style.css">

    <style>
    body {
        margin: 0;
        font-family: Arial;
        background: #f5f5f5;
    }

    .camera-wrapper {
        display: flex;
        justify-content: center;
        padding: 20px;
    }

    .camera-card {
        width: 100%;
        max-width: 400px;
        background: #fff;
        border-radius: 20px;
        padding: 20px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }

    .top-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #c62828;
        color: white;
        padding: 12px;
        border-radius: 10px;
        margin-bottom: 10px;
    }

    .logout-top {
        background: white;
        color: #c62828;
        padding: 5px 10px;
        border-radius: 20px;
        text-decoration: none;
        font-size: 12px;
    }

    .info-box {
        text-align: center;
        margin: 10px 0;
        font-weight: bold;
    }

    .status {
        text-align: center;
        font-size: 18px;
        font-weight: bold;
        margin-bottom: 10px;
    }

    video {
        width: 100%;
        border-radius: 15px;
        margin: 10px 0;
        object-fit: cover;
    }

    input {
        width: 100%;
        padding: 12px;
        border-radius: 10px;
        border: 1px solid #ddd;
        margin-top: 10px;
    }

    .btn-group {
        display: flex;
        gap: 10px;
        margin-top: 15px;
    }

    .btn-group button {
        flex: 1;
        padding: 12px;
        border: none;
        border-radius: 10px;
        background: #c62828;
        color: white;
        font-weight: bold;
    }
    </style>

</head>

<body>

<div class="camera-wrapper">
    <div class="camera-card">

        <div class="top-bar">
            <span>💊 Apotek Alkes</span>

            <a href="logout.php" class="logout-top" onclick="return confirm('Yakin mau logout?')">
                Logout
            </a>
        </div>

        <div class="info-box">
            🕒 <span id="jam"></span>
        </div>

        <div class="status" id="status"></div>

        <h3 style="text-align:center;">
            📍 Cabang: <?= $_SESSION['cabang'] ?>
        </h3>

        <!-- CAMERA -->
        <video id="video" autoplay playsinline></video>
        <canvas id="canvas" style="display:none;"></canvas>

        <!-- INPUT -->
        <input type="text" id="nama_tim" placeholder="Masukkan Nama Tim" required>

        <!-- BUTTON -->
        <div class="btn-group">
            <button onclick="switchCamera()">🔄 Kamera</button>
            <button onclick="ambilFoto()">📸 Foto</button>
        </div>

    </div>
</div>

<script>
let video = document.getElementById("video");
let canvas = document.getElementById("canvas");

let currentStream;
let useFrontCamera = true; // 🔥 default depan


// =======================
// START CAMERA
// =======================
async function startCamera() {
    try {
        if (currentStream) {
            currentStream.getTracks().forEach(track => track.stop());
        }

        currentStream = await navigator.mediaDevices.getUserMedia({
            video: {
                facingMode: useFrontCamera ? "user" : "environment"
            }
        });

        video.srcObject = currentStream;

        // 🔥 FIX MIRROR
        if(useFrontCamera){
            video.style.transform = "scaleX(-1)";
        } else {
            video.style.transform = "scaleX(1)";
        }

    } catch (err) {
        alert("❌ Kamera tidak bisa diakses!");
        console.log(err);
    }
}


// =======================
// SWITCH CAMERA
// =======================
function switchCamera() {
    useFrontCamera = !useFrontCamera;
    startCamera();
}


// =======================
// AMBIL FOTO
// =======================
function ambilFoto() {
    let nama = document.getElementById("nama_tim").value;

    if(nama === ""){
        alert("Nama wajib diisi!");
        return;
    }

    let ctx = canvas.getContext("2d");

    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;

    ctx.save();

    // 🔥 mirror hanya kalau kamera depan
    if(useFrontCamera){
        ctx.translate(canvas.width, 0);
        ctx.scale(-1, 1);
    }

    ctx.drawImage(video, 0, 0);
    ctx.restore();

    let image = canvas.toDataURL("image/png");

    navigator.geolocation.getCurrentPosition(function(pos){
        fetch("controllers/save.php", {
            method: "POST",
            headers: {"Content-Type": "application/json"},
            body: JSON.stringify({
                image: image,
                latitude: pos.coords.latitude,
                longitude: pos.coords.longitude,
                nama_tim: nama
            })
        })
        .then(res => res.text())
        .then(data => alert("✅ " + data));
    });
}


// =======================
// JAM REALTIME
// =======================
setInterval(() => {
    document.getElementById("jam").innerText =
        new Date().toLocaleTimeString("id-ID");
}, 1000);


// =======================
// STATUS
// =======================
function setStatus() {
    let now = new Date();

    let total = now.getHours() * 60 + now.getMinutes();

    let awal = 6 * 60;
    let batas = 6 * 60 + 25;

    let el = document.getElementById("status");

    if(total >= awal && total <= 6*60+24){
        el.innerHTML = "🟡 LEBIH AWAL";
        el.style.color = "orange";
    }
    else if(total === batas){
        el.innerHTML = "🟢 ON TIME";
        el.style.color = "green";
    }
    else{
        el.innerHTML = "🔴 TERLAMBAT";
        el.style.color = "red";
    }
}

setStatus();


// INIT
startCamera();
</script>

</body>
</html>