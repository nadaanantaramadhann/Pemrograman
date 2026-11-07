<?php $_role = $_SESSION['role'] ?? 'customer'; ?>
<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block sidebar collapse">
    <div class="position-sticky pt-3">
        <p class="sidebar-heading px-3 mb-1">Menu Utama</p>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link " href="../dashboard/dashboard.php">
                    <span data-feather="home"></span> Dashboard
                </a>
            </li>
            <?php if ($_role === 'admin' || $_role === 'staff'): ?>
            <li class="nav-item">
                <a class="nav-link" href="../orders/view_orders.php">
                    <span data-feather="file-text"></span> Pesanan
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../product/view_product.php">
                    <span data-feather="box"></span> Produk
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../customer/view_customer.php">
                    <span data-feather="users"></span> Pelanggan
                </a>
            </li>
            <?php endif; ?>
            <?php if ($_role === 'admin'): ?>
            <hr class="my-2" style="border-color:rgba(226,185,111,0.2)">
            <p class="sidebar-heading px-3 mb-1">Admin</p>
            <li class="nav-item">
                <a class="nav-link nav-link-gold" href="../admin/view_users.php">
                    <span data-feather="shield"></span> Kelola User
                </a>
            </li>
            <?php endif; ?>
            <hr class="my-2" style="border-color:rgba(226,185,111,0.2)">
            <p class="sidebar-heading px-3 mb-1">Lainnya</p>
            <li class="nav-item">
                <a class="nav-link" href="../index.php">
                    <span data-feather="shopping-bag"></span> Kembali ke Toko
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link nav-link-danger" href="../logout.php">
                    <span data-feather="log-out"></span> Keluar
                </a>
            </li>
        </ul>
    </div>
</nav>