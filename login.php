<?php
session_start();
include "config/koneksi.php";

if(isset($_POST['login'])){
    $u = $_POST['username'];
    $p = $_POST['password'];

    $q = mysqli_query($conn, "SELECT * FROM users WHERE username='$u'");
    $d = mysqli_fetch_array($q);

    if($d && password_verify($p, $d['password'])){

        $_SESSION['cabang']  = $d['cabang'];
        $_SESSION['role']    = $d['role'];
        $_SESSION['login']   = true;
        $_SESSION['username'] = $d['username'];

        $_SESSION['toast'] = "login_sukses";

        if($d['role'] == 'admin'){
            header("Location: dashboard.php");
        } else {
            header("Location: index.php");
        }
        exit;

    } else {
        $_SESSION['toast'] = "login_gagal";
        header("Location: login.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Login</title>    
<link rel="stylesheet" href="assets/css/style.css">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>

<div class="login-container fade-in">

<!-- KIRI -->
<div class="login-left">
    <div class="login-overlay">
        <div class="branding">
            <h1>💊 Apotek Alkes</h1>
            <p class="sub-brand">by Denmasyoga</p>
            <p class="tagline">Monitoring Cabang Apotek Real-Time</p>
        </div>
    </div>
</div>

<!-- KANAN -->
<div class="login-right">
    <form method="POST" class="login-box fade-in" onsubmit="showLoading()">

        <div class="logo">💊 Apotek Alkes</div>

        <h2>Login Cabang</h2>
        <p>Silakan masuk untuk mulai absensi</p>

        <div class="input-group">
            <input type="text" name="username" placeholder="Username" required>
        </div>

        <div class="input-group password-group">
            <input type="password" name="password" id="password" placeholder="Password" required>
            <span onclick="togglePassword()">👁️</span>
        </div>

        <button name="login">Masuk</button>

        <p style="margin-top:15px; text-align:center;">
            Belum punya akun? 
            <a href="register.php" style="color:#c62828; font-weight:bold;">
                Daftar Cabang
            </a>
        </p>

        <div class="loading" id="loading" style="display:none;">
            Memproses...
        </div>

    </form>
</div>

</div>

<!-- TOAST -->
<div id="toast" class="toast"></div>

<?php if(isset($_SESSION['toast'])): ?>
<script>
window.onload = function(){
    <?php if($_SESSION['toast'] == "login_sukses"): ?>
        showToast("Login berhasil 🎉", "success");
    <?php elseif($_SESSION['toast'] == "login_gagal"): ?>
        showToast("Username / Password salah ❌", "error");
    <?php elseif($_SESSION['toast'] == "register_sukses"): ?>
        showToast("Register berhasil 🎉", "success");
    <?php endif; ?>
}
</script>
<?php unset($_SESSION['toast']); ?>
<?php endif; ?>

<!-- SCRIPT -->
<script>
function togglePassword() {
    let pass = document.getElementById("password");
    pass.type = pass.type === "password" ? "text" : "password";
}

function showLoading() {
    document.getElementById("loading").style.display = "block";
}

function showToast(message, type = "success") {
  let toast = document.getElementById("toast");
  toast.innerText = message;
  toast.className = "toast show " + type;

  setTimeout(() => {
    toast.className = "toast";
  }, 3000);
}
</script>

</body>
</html>