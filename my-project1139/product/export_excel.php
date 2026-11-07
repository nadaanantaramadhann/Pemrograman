<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: ../index.php");
    exit;
}
include '../koneksi.php';

$data = mysqli_query($conn, "SELECT * FROM tb_product ORDER BY code_product ASC");
$filename = 'laporan_produk_' . date('Ymd_His') . '.xls';

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
    .right { text-align: right; }
    .center { text-align: center; }
    .total-row { background-color: #ecf0f1; font-weight: bold; }
    .low-stock { background-color: #ffe0e0; }
</style>
</head>
<body>
<table>
    <tr>
        <td colspan="8" class="title no-border">LAPORAN DATA PRODUK</td>
    </tr>
    <tr>
        <td colspan="8" class="subtitle no-border">Tanggal Cetak: <?= date('d F Y H:i:s') ?></td>
    </tr>
    <tr><td colspan="8" class="no-border">&nbsp;</td></tr>
    <tr class="header-row">
        <th class="center">No</th>
        <th class="center">Kode Produk</th>
        <th class="center">Nama Produk</th>
        <th class="center">Harga</th>
        <th class="center">Stok</th>
        <th class="center">Min</th>
        <th class="center">Max</th>
        <th class="center">Status Stok</th>
    </tr>
    <?php
    $no = 1;
    $total_produk = 0;
    $total_nilai = 0;
    while ($row = mysqli_fetch_assoc($data)):
        $status = "";
        $row_class = "";
        if ($row['stock'] <= $row['min']) {
            $status = 'Stok Rendah';
            $row_class = 'low-stock';
        } elseif ($row['stock'] >= $row['max']) {
            $status = 'Stok Penuh';
        } else {
            $status = 'Normal';
        }
        $total_produk++;
        $total_nilai += $row['price'] * $row['stock'];
    ?>
    <tr class="<?= $row_class ?>">
        <td class="center"><?= $no++ ?></td>
        <td><?= htmlspecialchars($row['code_product']) ?></td>
        <td><?= htmlspecialchars($row['name_product']) ?></td>
        <td class="right">Rp <?= number_format($row['price'], 0, ',', '.') ?></td>
        <td class="center"><?= $row['stock'] ?></td>
        <td class="center"><?= $row['min'] ?></td>
        <td class="center"><?= $row['max'] ?></td>
        <td class="center"><?= $status ?></td>
    </tr>
    <?php endwhile; ?>
    <tr class="total-row">
        <td colspan="3" class="center"><strong>TOTAL</strong></td>
        <td colspan="2" class="center"><?= $total_produk ?> Produk</td>
        <td colspan="3" class="right">Nilai Stok: Rp <?= number_format($total_nilai, 0, ',', '.') ?></td>
    </tr>
</table>
</body>
</html>