<?php
include '../koneksi.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: view_product.php');
    exit;
}

$id = (int) $_GET['id'];
$sql = "DELETE FROM tb_product WHERE id=$id";

if (mysqli_query($conn, $sql)) {
    header('Location: view_product.php?pesan=hapus');
} else {
    header('Location: view_product.php?pesan=error');
}
exit;
?>