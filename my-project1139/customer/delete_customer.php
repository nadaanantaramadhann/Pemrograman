<?php
include '../koneksi.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: view_customer.php');
    exit;
}

$id = (int) $_GET['id'];
$sql = "DELETE FROM tb_users WHERE id=$id AND role='customer'";

if (mysqli_query($conn, $sql)) {
    header('Location: view_customer.php?pesan=hapus');
} else {
    header('Location: view_customer.php?pesan=error');
}
exit;
?>