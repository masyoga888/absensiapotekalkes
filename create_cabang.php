<?php
include "config/koneksi.php";

$username = "pky-aa";
$password = password_hash("123", PASSWORD_DEFAULT);
$cabang   = "PKYAA";
$role     = "cabang";

mysqli_query($conn, "
INSERT INTO users (cabang, username, password, role)
VALUES ('$cabang','$username','$password','$role')
");

echo "Cabang berhasil dibuat!";