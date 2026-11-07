<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['login']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header('Location: ../index.php');
    exit;
}

$id = (int) ($_GET['id'] ?? 0);

// Tidak boleh hapus diri sendiri
if (!$id || $id === (int) $_SESSION['user_id']) {
    header('Location: view_users.php');
    exit;
}

mysqli_query($conn, "DELETE FROM tb_users WHERE id=$id");
header('Location: view_users.php?pesan=hapus');
exit;
?>