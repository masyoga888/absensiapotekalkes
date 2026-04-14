<?php
include "config/koneksi.php";

$username = "manajemen";
$password = password_hash("apotekalkes", PASSWORD_DEFAULT);
$cabang   = "PUSAT";
$role     = "admin";

mysqli_query($conn, "
INSERT INTO users (cabang, username, password, role)
VALUES ('$cabang','$username','$password','$role')
");

echo "Admin berhasil dibuat!";
?>