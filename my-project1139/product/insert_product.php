<?php
include '../layout/header.php';
include '../koneksi.php';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $code_product = mysqli_real_escape_string($conn, trim($_POST['code_product']));
    $name_product = mysqli_real_escape_string($conn, trim($_POST['name_product']));
    $price = mysqli_real_escape_string($conn, trim($_POST['price']));
    $stock = mysqli_real_escape_string($conn, trim($_POST['stock']));
    $min = mysqli_real_escape_string($conn, trim($_POST['min']));
    $max = mysqli_real_escape_string($conn, trim($_POST['max']));
    
    if ($code_product == '' || $name_product == '' || $price == '' || $stock == '' || $min == '' || $max == '') {
        $error = 'Semua field wajib diisi.';
    } else {
        $cek = mysqli_query($conn, "SELECT code_product FROM tb_product WHERE code_product='$code_product'");
        if (mysqli_num_rows($cek) > 0) {
            $error = 'Code Product sudah terdaftar.';
        } else {
            $image_name = '';
            if (!empty($_FILES['image']['name'])) {
                $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
                if (!in_array($ext, $allowed)) {
                    $error = 'Format gambar tidak valid. Gunakan JPG, PNG, GIF, atau WEBP.';
                } elseif ($_FILES['image']['size'] > 2 * 1024 * 1024) {
                    $error = 'Ukuran gambar maksimal 2MB.';
                } else {
                    $image_name = uniqid('prod_').'.'.$ext;
                    $upload_dir = '../uploads/products/';
                    if (!move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $image_name)) {
                        $error = 'Gagal mengupload gambar.';
                        $image_name = '';
                    }
                }
            }
        }
    }
    
    if (!$error) {
        $img_val = mysqli_real_escape_string($conn, $image_name);
        $sql = "INSERT INTO tb_product (code_product, name_product, price, stock, min, max, image) VALUES ('$code_product', '$name_product', '$price', '$stock', '$min', '$max', '$img_val')";
        if (mysqli_query($conn, $sql)) {
            header('Location: view_product.php?pesan=tambah');
            exit;
        } else {
            $error = 'Gagal menyimpan data: ' . mysqli_error($conn);
        }
    }
}
?>
<div class="container-fluid">
    <div class="row">
        <?php include '../layout/sidebar.php'; ?>
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h2>Tambah Product</h2>
                <a href="view_product.php" class="btn btn-secondary btn-sm">&larr; Kembali</a>
            </div>
            <?php if ($error): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <div class="row">
                <div class="col-md-7">
                    <form method="POST" action="" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label class="form-label">Code Product</label>
                            <input type="text" class="form-control" name="code_product" value="<?= isset($_POST['code_product']) ? htmlspecialchars($_POST['code_product']) : '' ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Name Product</label>
                            <input type="text" class="form-control" name="name_product" value="<?= isset($_POST['name_product']) ? htmlspecialchars($_POST['name_product']) : '' ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Price (Rp)</label>
                            <input type="number" class="form-control" name="price" min="0" value="<?= isset($_POST['price']) ? htmlspecialchars($_POST['price']) : '' ?>" required>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Stock</label>
                                <input type="number" class="form-control" name="stock" min="0" value="<?= isset($_POST['stock']) ? htmlspecialchars($_POST['stock']) : '' ?>" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Min</label>
                                <input type="number" class="form-control" name="min" min="0" value="<?= isset($_POST['min']) ? htmlspecialchars($_POST['min']) : '' ?>" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Max</label>
                                <input type="number" class="form-control" name="max" min="0" value="<?= isset($_POST['max']) ? htmlspecialchars($_POST['max']) : '' ?>" required>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Gambar Produk <span class="text-muted">(opsional, maks 2MB)</span></label>
                            <input type="file" class="form-control" name="image" accept="image/*" onchange="previewImage(this)">
                            <div class="mt-2">
                                <img id="imgPreview" src="#" alt="Preview" class="img-thumbnail d-none" style="max-height:180px;">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="view_product.php" class="btn btn-secondary">Batal</a>
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>
<?php include '../layout/footer.php'; ?>
<script>
function previewImage(input) {
    const preview = document.getElementById('imgPreview');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = (e) => { preview.src = e.target.result; preview.classList.remove('d-none'); };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>