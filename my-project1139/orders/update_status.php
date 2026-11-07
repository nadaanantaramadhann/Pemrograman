<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['login']) || !in_array($_SESSION['role'] ?? '', ['admin', 'staff'])) {
    header('Location: ../index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: view_orders.php');
    exit;
}

$id = (int) ($_POST['id'] ?? 0);
$status = $_POST['status'] ?? '';
$allowed = ['pending', 'proses', 'selesai', 'batal'];

if (!$id || !in_array($status, $allowed)) {
    header('Location: view_orders.php');
    exit;
}

$s = mysqli_real_escape_string($conn, $status);
mysqli_query($conn, "UPDATE tb_orders SET status='$s' WHERE id=$id");
header('Location: view_orders.php?pesan=update');
exit;
?>