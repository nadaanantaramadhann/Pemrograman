<?php
$host = "localhost"; // 172.0.0.1
$user = "root";
$pass = "";
$db = "db_project1139";

$conn = mysqli_connect($host, $user, $pass, $db);

if(!$conn) {
    die("Koneksi Gagal: " . mysqli_connect_error());
}
?>