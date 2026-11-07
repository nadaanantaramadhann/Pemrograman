<?php
session_start();
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: register.php');
    exit;
}

$nama = trim($_POST['nama'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$konfirmasi = $_POST['konfirmasi'] ?? '';

function redirect_err($msg) {
    header('Location: register.php?error=' . urlencode($msg));
    exit;
}

if (!$nama || !$email || !$password) {
    redirect_err('Semua field wajib diisi.');
}
if (strlen($password) < 6) {
    redirect_err('Password minimal 6 karakter.');
}
if ($password !== $konfirmasi) {
    redirect_err('Password dan konfirmasi tidak cocok.');
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    redirect_err('Format email tidak valid.');
}

$e = mysqli_real_escape_string($conn, $email);
$cek = mysqli_query($conn, "SELECT id FROM tb_users WHERE username='$e'");
if (mysqli_num_rows($cek) > 0) {
    redirect_err('Email sudah terdaftar.');
}

$nama_esc = mysqli_real_escape_string($conn, $nama);
$pass_hash = password_hash($password, PASSWORD_DEFAULT);

$sql = "INSERT INTO tb_users (nama, username, password, role) VALUES ('$nama_esc', '$e', '$pass_hash', 'customer')";

if (mysqli_query($conn, $sql)) {
    $new_id = mysqli_insert_id($conn);
    $_SESSION['login'] = true;
    $_SESSION['user_id'] = $new_id;
    $_SESSION['email'] = $email;
    $_SESSION['nama'] = $nama;
    $_SESSION['role'] = 'customer';
    header('Location: index.php');
    exit;
} else {
    redirect_err('Gagal mendaftar: ' . mysqli_error($conn));
}
?>