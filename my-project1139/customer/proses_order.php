<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['login']) || ($_SESSION['role'] ?? '') !== 'customer') {
    header('Location: ../login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../index.php');
    exit;
}

$customer_id = (int) $_SESSION['user_id'];
$product_id = (int) ($_POST['product_id'] ?? 0);
$qty = (int) ($_POST['qty'] ?? 1);
$catatan = mysqli_real_escape_string($conn, trim($_POST['catatan'] ?? ''));

if (!$product_id || $qty < 1) {
    header('Location: ../index.php?msg=error');
    exit;
}

// Ambil harga dan cek stok
$prod = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM tb_product WHERE id=$product_id"));
if (!$prod || $prod['stock'] < $qty) {
    header('Location: ../index.php?msg=stok_habis');
    exit;
}

$total = $prod['price'] * $qty;

// Insert order
$sql = "INSERT INTO tb_orders (user_id, product_id, qty, total_harga, catatan, status) VALUES ($customer_id, $product_id, $qty, $total, '$catatan', 'pending')";

if (mysqli_query($conn, $sql)) {
    // Kurangi stok
    $new_stock = $prod['stock'] - $qty;
    mysqli_query($conn, "UPDATE tb_product SET stock=$new_stock WHERE id=$product_id");
    header('Location: ../index.php?msg=order_ok');
} else {
    header('Location: ../index.php?msg=error');
}
exit;
?>