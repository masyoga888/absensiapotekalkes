<?php
session_start();
include "config/koneksi.php";

if(isset($_POST['daftar'])){

    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $cabang   = $_POST['cabang'];
    $role     = "cabang";

    // cek username
    $cek = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");

    if(mysqli_num_rows($cek) > 0){
        $_SESSION['toast'] = "username_ada";
        header("Location: register.php");
        exit;
    } else {

        mysqli_query($conn, "
        INSERT INTO users (cabang, username, password, role)
        VALUES ('$cabang','$username','$password','$role')
        ");

        $_SESSION['toast'] = "register_sukses";
        header("Location: login.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Register</title>
<link rel="stylesheet" href="assets/css/style.css">
</head>

<body>

<div class="auth-wrapper">
    <form method="POST" class="auth-card">

        <div class="logo">💊</div>

        <h2>Registrasi Cabang</h2>
        <p>Mulai monitoring cabang apotek secara real-time</p>

        <input type="text" name="cabang" placeholder="Nama Cabang (PKYAA)" required>
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>

        <button name="daftar" class="btn">Daftar</button>

        <div class="link">
            Sudah punya akun? <a href="login.php">Login</a>
        </div>

    </form>
</div>
<!-- TOAST -->
<div id="toast" class="toast"></div>

<?php if(isset($_SESSION['toast'])): ?>
<script>
window.onload = function(){
    <?php if($_SESSION['toast'] == "username_ada"): ?>
        showToast("Username sudah digunakan ❌", "error");
    <?php endif; ?>
}
</script>
<?php unset($_SESSION['toast']); ?>
<?php endif; ?>

<!-- SCRIPT -->
<script>
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