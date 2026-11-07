<?php
session_start();
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit;
}

$input = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$i = mysqli_real_escape_string($conn, $input);

// Cari user berdasarkan username/email
$query = mysqli_query($conn, "SELECT * FROM tb_users WHERE username='$i'");

if ($query && mysqli_num_rows($query) > 0) {
    $data = mysqli_fetch_assoc($query);
    $pass_ok = false;
    $need_rehash = false;
    
    // 1. Cek pakai Bcrypt Hash
    if (password_verify($password, $data['password'])) {
        $pass_ok = true;
    } 
    // 2. Cek pakai Teks Biasa (Plain-text)
    elseif ($data['password'] === $password) {
        $pass_ok = true;
        $need_rehash = true; 
    }
    
    if ($pass_ok) {
        // Otomatis ubah password teks biasa ke hash biar aman
        if ($need_rehash) {
            $new_hash = password_hash($password, PASSWORD_DEFAULT);
            mysqli_query($conn, "UPDATE tb_users SET password='$new_hash' WHERE id=" . $data['id']);
        }

        $_SESSION['login'] = true;
        $_SESSION['user_id'] = $data['id'];
        $_SESSION['email'] = $data['username']; 
        $_SESSION['nama'] = !empty($data['nama']) ? $data['nama'] : $data['username'];
        $_SESSION['role'] = $data['role'] ?? 'admin';
        
        $role = $_SESSION['role'];
        header('Location: ' . ($role === 'customer' ? 'index.php' : 'dashboard/dashboard.php'));
        exit;
    }
}

// ---- BAGIAN DETEKSI ERROR (JIKA MASIH GAGAL LOGIN) ----
echo "<h2>Gagal Login! Ini Detail Analisisnya:</h2>";
echo "<pre>";
echo "<b>Input dari Form Login:</b>\n";
echo "Username/Email Input: " . htmlspecialchars($input) . "\n";
echo "Password Input       : " . htmlspecialchars($password) . "\n\n";

if (isset($data)) {
    echo "<b>Data yang Ditemukan di Database:</b>\n";
    echo "Username di DB : " . htmlspecialchars($data['username']) . "\n";
    echo "Password di DB : " . htmlspecialchars($data['password']) . "\n\n";
    
    echo "<b>Hasil Pengecekan:</b>\n";
    echo "Cocok pakai Hash?  : " . (password_verify($password, $data['password']) ? 'YA (True)' : 'TIDAK (False)') . "\n";
    echo "Cocok pakai Teks?  : " . (($data['password'] === $password) ? 'YA (True)' : 'TIDAK (False)') . "\n";
} else {
    echo "<b>Error:</b> Username/Email '" . htmlspecialchars($input) . "' TIDAK DITEMUKAN di database tb_users!";
}
echo "</pre>";
exit;
?>