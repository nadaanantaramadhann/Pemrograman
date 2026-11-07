<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: ../index.php");
    exit;
}
include '../koneksi.php';

$data = mysqli_query($conn, "SELECT id, nama, username FROM tb_users WHERE role='customer' ORDER BY nama ASC");
$filename = 'laporan_customer_' . date('Ymd_His') . '.xls';

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Cache-Control: max-age=0');
?>
<html>
<head>
<meta charset="UTF-8">
<style>
    body { font-family: Arial, sans-serif; font-size: 11pt; }
    table { border-collapse: collapse; width: 100%; }
    th, td { border: 1px solid #000; padding: 6px 10px; }
    .title { font-size: 14pt; font-weight: bold; text-align: center; }
    .subtitle { font-size: 10pt; text-align: center; color: #555; }
    .header-row { background-color: #2c3e50; color: #fff; font-weight: bold; text-align: center; }
    .no-border { border: none; }
    .center { text-align: center; }
</style>
</head>
<body>
<table>
    <tr>
        <td colspan="4" class="title no-border">LAPORAN DATA CUSTOMER</td>
    </tr>
    <tr>
        <td colspan="4" class="subtitle no-border">Tanggal Cetak: <?= date('d F Y H:i:s') ?></td>
    </tr>
    <tr><td colspan="4" class="no-border">&nbsp;</td></tr>
    <tr class="header-row">
        <th class="center">No</th>
        <th>ID Customer</th>
        <th>Nama Lengkap</th>
        <th>Email / Username</th>
    </tr>
    <?php
    $no = 1;
    while ($row = mysqli_fetch_assoc($data)):
    ?>
    <tr>
        <td class="center"><?= $no++ ?></td>
        <td class="center"><?= $row['id'] ?></td>
        <td><?= htmlspecialchars($row['nama']) ?></td>
        <td><?= htmlspecialchars($row['username']) ?></td>
    </tr>
    <?php endwhile; ?>
</table>
</body>
</html>