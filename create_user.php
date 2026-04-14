<?php
include "config/koneksi.php";

$username = "admin";
$password = password_hash("admin123", PASSWORD_DEFAULT);
$cabang   = "PUSAT";
$role     = "admin";

mysqli_query($conn, "
INSERT INTO users (cabang, username, password, role)
VALUES ('$cabang','$username','$password','$role')
");

echo "User berhasil dibuat!";