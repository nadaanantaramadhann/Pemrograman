<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: ../index.php");
    exit;
}
include '../koneksi.php';

$data = mysqli_query($conn, "SELECT * FROM tb_product ORDER BY code_product ASC");
$rows = [];
while ($row = mysqli_fetch_assoc($data)) {
    $rows[] = $row;
}
$total_produk = count($rows);
$total_nilai = array_sum(array_map(fn($r) => $r['price'] * $r['stock'], $rows));
?>
<!doctype html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Laporan Data Produk</title>
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: Arial, sans-serif; font-size: 11px; color: #222; }
    .page { width: 100%; padding: 20px 30px; }
    .report-header { text-align: center; margin-bottom: 18px; border-bottom: 3px double #2c3e50; padding-bottom: 12px; }
    .report-header h1 { font-size: 16px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; color: #2c3e50; }
    .report-header p { font-size: 10px; color: #666; margin-top: 3px; }
    .meta { display: flex; justify-content: space-between; margin-bottom: 14px; font-size: 10px; color: #444; }
    table { width: 100%; border-collapse: collapse; margin-bottom: 14px; }
    thead tr { background-color: #2c3e50; color: #fff; }
    thead th { padding: 7px 8px; text-align: center; font-size: 10.5px; border: 1px solid #2c3e50; }
    tbody tr { border-bottom: 1px solid #ddd; }
    tbody tr:nth-child(even) { background-color: #f7f9fb; }
    tbody tr.low-stock { background-color: #ffe5e5; }
    tbody td { padding: 6px 8px; font-size: 10.5px; border: 1px solid #ccc; }
    .center { text-align: center; }
    .right { text-align: right; }
    .badge { display: inline-block; padding: 2px 7px; border-radius: 3px; font-size: 9.5px; font-weight: bold; }
    .badge-danger { background: #e74c3c; color: #fff; }
    .badge-success { background: #27ae60; color: #fff; }
    .badge-warning { background: #f39c12; color: #fff; }
    .summary { display: flex; gap: 14px; margin-bottom: 16px; }
    .summary-card { flex: 1; border: 1px solid #ddd; border-radius: 5px; padding: 10px 14px; background: #f9f9f9; }
    .summary-card .label { font-size: 9.5px; color: #888; text-transform: uppercase; letter-spacing: 0.5px; }
    .summary-card .value { font-size: 15px; font-weight: bold; color: #2c3e50; margin-top: 2px; }
    .report-footer { margin-top: 30px; display: flex; justify-content: flex-end; font-size: 10px; }
    .sign-box { text-align: center; }
    .sign-box .title { margin-bottom: 60px; }
    .sign-box .name { border-top: 1px solid #333; padding-top: 4px; width: 160px; margin: 0 auto; }
    .print-btn { position: fixed; top: 16px; right: 16px; padding: 8px 18px; background: #2c3e50; color: #fff; border: none; border-radius: 4px; cursor: pointer; font-size: 12px; z-index: 999; }
    .back-btn { position: fixed; top: 16px; right: 110px; padding: 8px 18px; background: #7f8c8d; color: #fff; border: none; border-radius: 4px; cursor: pointer; font-size: 12px; z-index: 999; text-decoration: none; display: inline-block; }
    @media print {
        .print-btn, .back-btn { display: none; }
        body { font-size: 10px; }
        .page { padding: 10px 15px; }
    }
</style>
</head>
<body>
<button class="print-btn" onclick="window.print()">&#128438; Cetak / PDF</button>
<a href="view_product.php" class="back-btn">&#8592; Kembali</a>
<div class="page">
    <div class="report-header">
        <h1>Laporan Data Produk</h1>
        <p>Sistem Informasi Manajemen Produk</p>
    </div>
    <div class="meta">
        <span>Tanggal Cetak &nbsp;: <strong><?= date('d F Y') ?></strong></span>
        <span>Pukul &nbsp;: <strong><?= date('H:i:s') ?></strong></span>
        <span>Total Produk: <strong><?= $total_produk ?></strong></span>
    </div>
    <div class="summary">
        <div class="summary-card">
            <div class="label">Total Produk</div>
            <div class="value"><?= $total_produk ?></div>
        </div>
        <div class="summary-card">
            <div class="label">Stok Rendah</div>
            <div class="value" style="color:#e74c3c;">
                <?= count(array_filter($rows, fn($r) => $r['stock'] <= $r['min'])) ?>
            </div>
        </div>
        <div class="summary-card">
            <div class="label">Stok Normal</div>
            <div class="value" style="color:#27ae60;">
                <?= count(array_filter($rows, fn($r) => $r['stock'] > $r['min'] && $r['stock'] < $r['max'])) ?>
            </div>
        </div>
        <div class="summary-card">
            <div class="label">Nilai Total Stok</div>
            <div class="value" style="font-size:12px;">Rp <?= number_format($total_nilai, 0, ',', '.') ?></div>
        </div>
    </div>
    <table>
        <thead>
            <tr>
                <th style="width: 4%">No</th>
                <th style="width:13%">Kode Produk</th>
                <th style="width: 28%">Nama Produk</th>
                <th style="width:14%">Harga</th>
                <th style="width: 8%">Stok</th>
                <th style="width:7%">Min</th>
                <th style="width:7%">Max</th>
                <th style="width:11%">Nilai Stok</th>
                <th style="width: 8%">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            foreach ($rows as $row):
                $nilai = $row['price'] * $row['stock'];
                if ($row['stock'] <= $row['min']) {
                    $status = '<span class="badge badge-danger">Rendah</span>';
                    $tr_class = 'low-stock';
                } elseif ($row['stock'] >= $row['max']) {
                    $status = '<span class="badge badge-warning">Penuh</span>';
                    $tr_class = '';
                } else {
                    $status = '<span class="badge badge-success">Normal</span>';
                    $tr_class = '';
                }
            ?>
            <tr class="<?= $tr_class ?>">
                <td class="center"><?= $no++ ?></td>
                <td><?= htmlspecialchars($row['code_product']) ?></td>
                <td><?= htmlspecialchars($row['name_product']) ?></td>
                <td class="right">Rp <?= number_format($row['price'], 0, ',', '.') ?></td>
                <td class="center"><?= $row['stock'] ?></td>
                <td class="center"><?= $row['min'] ?></td>
                <td class="center"><?= $row['max'] ?></td>
                <td class="right">Rp <?= number_format($nilai, 0, ',', '.') ?></td>
                <td class="center"><?= $status ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr style="background:#ecf0f1; font-weight:bold;">
                <td colspan="3" class="center">TOTAL</td>
                <td class="center" colspan="5">Nilai Stok Keseluruhan</td>
                <td class="right" colspan="1" style="font-size:10.5px;">Rp <?= number_format($total_nilai, 0, ',', '.') ?></td>
            </tr>
        </tfoot>
    </table>
    <div class="report-footer">
        <div class="sign-box">
            <div class="title">Mengetahui, <br>Manager</div>
            <div class="name">()</div>
        </div>
    </div>
</div>
</body>
</html>