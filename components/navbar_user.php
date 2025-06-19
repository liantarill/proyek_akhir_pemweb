<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<nav class="navbar navbar-expand-lg navbar-dark px-3" style="background: #111827; border-bottom: 1px solid #d4af37;">
    <!-- Brand -->
    <a class="navbar-brand text-warning fw-bold" href="../user/dashboard.php">
        caRent
    </a>

    <!-- Mobile Toggle -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTenant"
        aria-controls="navbarTenant" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarTenant">
        <!-- Navigation Links -->
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
                <a class="nav-link <?= ($current_page == 'dashboard.php') ? 'active' : '' ?>" href=" ../user/dashboard.php">
                    <i class="fas fa-tachometer-alt me-2"></i>
                    Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= ($current_page == 'vehicle.php' || $current_page == 'rental.php') ? 'active' : '' ?>" href="../user/vehicle.php">
                    <i class="fas fa-car me-2"></i>
                    Kendaraan
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= ($current_page == 'history.php') ? 'active' : '' ?> " href="../user/history.php">
                    <i class="fas fa-history me-2"></i>
                    Riwayat Sewa
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= ($current_page == 'profile.php' || $current_page == 'edit_profile.php') ? 'active' : '' ?>" href="../user/profile.php">
                    <i class="fas fa-user me-2"></i>
                    Profil
                </a>
            </li>
        </ul>

        <div class="d-flex align-items-center">
            <?php if (!empty($user['profile_picture'])): ?>
                <a href="../user/profile.php">
                    <img src="../assets/uploads/profile/<?= htmlspecialchars($user['profile_picture']) ?>"
                        alt="Foto Profil" class="rounded-circle"
                        style="width: 40px; height: 40px; object-fit: cover; border: 2px solid #d4af37;" />
                </a>
            <?php else: ?>
                <a href="../user/profile.php" class="rounded-circle d-flex align-items-center justify-content-center"
                    style="width: 40px; height: 40px; background: #d4af37; color: #111827;">
                    <i class="fas fa-user"></i>
                </a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<style>
    /* Simple styling for active page */
    .navbar-nav .nav-link.active {
        color: #d4af37 !important;
        font-weight: 600;
    }

    /* Simple hover effect */
    .navbar-nav .nav-link:hover {
        color: #d4af37 !important;
    }
</style>

<script>
    // Add active class to current page
    document.addEventListener('DOMContentLoaded', function() {
        const currentPath = window.location.pathname;
        const navLinks = document.querySelectorAll('.navbar-nav .nav-link');

        navLinks.forEach(link => {
            if (link.getAttribute('href') && currentPath.includes(link.getAttribute('href'))) {
                link.classList.add('active');
            }
        });
    });
</script>