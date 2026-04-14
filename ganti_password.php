<?php
session_start();
include "config/koneksi.php";

if(!isset($_SESSION['login'])){
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'];

if(isset($_POST['simpan'])){

    $pass_lama = $_POST['pass_lama'];
    $pass_baru = $_POST['pass_baru'];
    $konfirmasi = $_POST['konfirmasi'];

    $q = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
    $d = mysqli_fetch_array($q);

    if(!$d){
        $error = "User tidak ditemukan!";
    }
    elseif(!password_verify($pass_lama, $d['password'])){
        $error = "Password lama salah!";
    }
    elseif($pass_baru != $konfirmasi){
        $error = "Konfirmasi password tidak cocok!";
    }
    else{

        $new_pass = password_hash($pass_baru, PASSWORD_DEFAULT);

        mysqli_query($conn, "
        UPDATE users SET password='$new_pass' WHERE username='$username'
        ");

        $success = "✅ Password berhasil diubah!";
    }
}
?>

<link rel="stylesheet" href="assets/css/style.css">

<div class="login-container">

    <div class="login-right" style="margin:auto;">
        <form method="POST" class="login-box">

            <h2>🔐 Ganti Password</h2>

            <?php if(isset($error)) echo "<p style='color:red'>$error</p>"; ?>
            <?php if(isset($success)) echo "<p style='color:green'>$success</p>"; ?>

            <input type="password" name="pass_lama" placeholder="Password Lama" required>
            <input type="password" name="pass_baru" placeholder="Password Baru" required>
            <input type="password" name="konfirmasi" placeholder="Konfirmasi Password" required>

            <button name="simpan">Simpan</button>

        </form>
    </div>

</div>