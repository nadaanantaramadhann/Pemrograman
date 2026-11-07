<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: ../index.php");
    exit;
}
include '../koneksi.php';

$data = mysqli_query($conn, "SELECT id, nama, username FROM tb_users WHERE role='customer' ORDER BY nama ASC");
$rows = [];
while ($row = mysqli_fetch_assoc($data)) {
    $rows[] = $row;
}
$total_customer = count($rows);
?>
<!doctype html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Laporan Data Customer</title>
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
    tbody td { padding: 6px 8px; font-size: 10.5px; border: 1px solid #ccc; }
    .center { text-align: center; }
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
<a href="view_customer.php" class="back-btn">&#8592; Kembali</a>
<div class="page">
    <div class="report-header">
        <h1>Laporan Data Customer</h1>
        <p>Sistem Informasi Manajemen Pelanggan</p>
    </div>
    <div class="meta">
        <span>Tanggal Cetak &nbsp;: <strong><?= date('d F Y') ?></strong></span>
        <span>Pukul &nbsp;: <strong><?= date('H:i:s') ?></strong></span>
        <span>Total Customer: <strong><?= $total_customer ?></strong></span>
    </div>
    <table>
        <thead>
            <tr>
                <th style="width: 10%">No</th>
                <th style="width: 20%">ID Customer</th>
                <th style="width: 40%">Nama Lengkap</th>
                <th style="width: 30%">Email / Username</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; foreach ($rows as $row): ?>
            <tr>
                <td class="center"><?= $no++ ?></td>
                <td class="center"><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['nama']) ?></td>
                <td><?= htmlspecialchars($row['username']) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>