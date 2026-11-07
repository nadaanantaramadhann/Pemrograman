<?php
include '../layout/header.php';
include '../koneksi.php';

if ($_role !== 'admin') { header('Location: ../index.php'); exit; }

$id = (int) ($_GET['id'] ?? 0);
if (!$id) { header('Location: view_users.php'); exit; }

$row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM tb_users WHERE id=$id"));
if (!$row) { header('Location: view_users.php'); exit; }

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = mysqli_real_escape_string($conn, trim($_POST['nama']));
    $username = mysqli_real_escape_string($conn, trim($_POST['username'])); // Ini yang dipakai untuk email/username di DB
    $role = in_array($_POST['role'], ['admin', 'staff', 'customer']) ? $_POST['role'] : 'customer';
    $password = trim($_POST['password']);
    
    // Perbaikan Baris 21: Mencari duplikasi hanya di kolom 'username' yang ada di database
    $cek = mysqli_query($conn, "SELECT id FROM tb_users WHERE username='$username' AND id != $id");
    if (mysqli_num_rows($cek) > 0) {
        $error = 'Username / Email sudah dipakai user lain.';
    } else {
        if ($password) {
            $pass_hash = password_hash($password, PASSWORD_DEFAULT);
            $pass_sql = ", password='$pass_hash'";
        } else {
            $pass_sql = '';
        }
        
        // Perbaikan Baris 31: Menghilangkan kolom 'email' yang tidak ada di tb_users
        $sql = "UPDATE tb_users SET nama='$nama', username='$username', role='$role' $pass_sql WHERE id=$id";
        if (mysqli_query($conn, $sql)) {
            header('Location: view_users.php?pesan=edit');
            exit;
        } else {
            $error = 'Gagal: ' . mysqli_error($conn);
        }
    }
}
?>
<div class="container-fluid">
    <div class="row">
        <?php include '../layout/sidebar.php'; ?>
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h2>Edit User</h2>
                <a href="view_users.php" class="btn btn-secondary btn-sm">&larr; Kembali</a>
            </div>
            <?php if ($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
            <div class="col-md-6">
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($row['nama'] ?? '') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Username / Email</label>
                        <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($row['username'] ?? '') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password Baru <span class="text-muted">(kosongkan jika tidak ingin mengubah)</span></label>
                        <input type="password" name="password" class="form-control" minlength="6">
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Role</label>
                        <select name="role" class="form-select">
                            <?php foreach (['customer', 'staff', 'admin'] as $r): ?>
                                <option value="<?= $r ?>" <?= ($row['role'] ?? '') === $r ? 'selected' : '' ?>><?= ucfirst($r) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-warning">Update</button>
                    <a href="view_users.php" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </main>
    </div>
</div>
<?php include '../layout/footer.php'; ?>