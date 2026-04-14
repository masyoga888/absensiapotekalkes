<?php
include "config/koneksi.php";

$cabang_list = [
"PKYADS",
"PKYAPV",
"PKYBBS",
"PKYBIO",
"PKYBKH",
"PKYBKM",
"PKYBLG",
"PKYGLX",
"PKYKCN",
"PKYKCP",
"PKYKHY",
"PKYKNB",
"PKYKPS",
"PKYKRP",
"PKYKRT",
"PKYKSG",
"PKYMDK",
"PKYMHR",
"PKYMRJ",
"PKYPRT",
"PKYRJL",
"PKYRTA",
"PKYSSG",
"PKYSTJ",
"PKYTGG",
"PKYTKL",
"PKYTLG"

];

foreach($cabang_list as $cabang){

    $username = strtolower($cabang); // pky-aa style bisa diubah nanti
    $password_plain = "123"; // password default
    $password = password_hash($password_plain, PASSWORD_DEFAULT);
    $role = "cabang";

    mysqli_query($conn, "
    INSERT INTO users (cabang, username, password, role)
    VALUES ('$cabang','$username','$password','$role')
    ");

    echo "✅ $cabang berhasil dibuat (user: $username / pass: $password_plain)<br>";
}

echo "<br><b>SELESAI 🔥</b>";
?>