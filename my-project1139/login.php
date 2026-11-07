<?php
session_start();
if (isset($_SESSION['login'])) {
    $role = $_SESSION['role'] ?? 'customer';
    header('Location: ' . ($role === 'customer' ? 'index.php' : 'dashboard/dashboard.php'));
    exit;
}
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - TechZone</title>
    <link href="assets/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --tech-primary: #00c6ff;
            --tech-secondary: #0072ff;
        }
        body {
            background: #0d1117;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', system-ui, sans-serif;
        }
        body::before {
            content: '';
            position: fixed; inset: 0;
            background: radial-gradient(ellipse at 30% 30%, rgba(0,114,255,0.12) 0%, transparent 60%),
                        radial-gradient(ellipse at 70% 70%, rgba(0,198,255,0.08) 0%, transparent 60%);
            pointer-events: none;
        }
        .login-wrap { width: 100%; max-width: 420px; padding: 0 16px; position: relative; }
        .login-card {
            background: #111827;
            border: 1px solid rgba(0,198,255,0.15);
            border-radius: 18px;
            padding: 40px 36px;
            box-shadow: 0 24px 80px rgba(0,0,0,0.5);
        }
        .brand-icon {
            width: 64px; height: 64px;
            background: linear-gradient(135deg, #0072ff, #00c6ff);
            border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            font-size: 2rem; margin: 0 auto 20px;
            box-shadow: 0 8px 24px rgba(0,198,255,0.3);
        }
        h2 { color: #e6edf3; font-weight: 800; font-size: 1.5rem; }
        .text-muted-custom { color: #8b949e !important; font-size: .85rem; }
        label { color: #8b949e; font-size: .84rem; margin-bottom: 6px; }
        .form-control {
            background: #0d1117;
            border: 1px solid rgba(0,198,255,0.18);
            color: #e6edf3;
            border-radius: 9px;
            padding: 11px 14px;
        }
        .form-control:focus {
            background: #0d1117;
            border-color: var(--tech-primary);
            color: #e6edf3;
            box-shadow: 0 0 0 3px rgba(0,198,255,0.12);
        }
        .form-control::placeholder { color: #4b5563; }
        .btn-login {
            background: linear-gradient(90deg, var(--tech-secondary), var(--tech-primary));
            border: none; color: #fff; font-weight: 700;
            border-radius: 9px; padding: 12px; width: 100%;
            font-size: 1rem; transition: all .3s;
            box-shadow: 0 4px 18px rgba(0,198,255,0.25);
        }
        .btn-login:hover { opacity: .88; transform: translateY(-1px); }
        .divider {
            display: flex; align-items: center; gap: 12px;
            color: #374151; font-size: .8rem; margin: 20px 0;
        }
        .divider::before, .divider::after { content: ''; flex: 1; height: 1px; background: rgba(0,198,255,0.12); }
        a.link-tech { color: var(--tech-primary); text-decoration: none; font-weight: 600; }
        a.link-tech:hover { text-decoration: underline; }
        .back-link { color: #6e7681; text-decoration: none; font-size: .82rem; }
        .back-link:hover { color: var(--tech-primary); }
        .alert-err {
            background: rgba(239,68,68,.1); border: 1px solid rgba(239,68,68,.3);
            color: #f87171; border-radius: 9px; padding: 10px 14px; font-size: .84rem;
        }
    </style>
</head>
<body>
<div class="login-wrap">
    <div class="login-card">
        <div class="text-center mb-4">
           
            <h2>Selamat Datang</h2>
            <p class="text-muted-custom mt-1">Masuk ke akun <strong style="color:#e6edf3">TechZone</strong> kamu</p>
        </div>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert-err mb-3">
                ❌ <?= htmlspecialchars($_GET['error']) ?>
            </div>
        <?php endif; ?>

        <form action="proses_login.php" method="POST">
            <div class="mb-3">
                <label>Email / Username</label>
                <input type="text" name="email" class="form-control" placeholder="Masukkan email atau username" required autofocus>
            </div>
            <div class="mb-4">
                <label>Password</label>
                <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
            </div>
            <button class="btn-login" type="submit"> Masuk ke TechZone</button>
        </form>

        <div class="divider">atau</div>

        <div class="text-center" style="font-size:.85rem;color:#8b949e">
            Belum punya akun?
            <a href="register.php" class="link-tech">Daftar gratis</a>
        </div>

        <div class="text-center mt-4">
            <a href="index.php" class="back-link">← Kembali ke Toko</a>
        </div>
    </div>
</div>
<script src="assets/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
