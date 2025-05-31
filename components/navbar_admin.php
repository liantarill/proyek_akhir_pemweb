<nav class="navbar navbar-expand-lg navbar-dark bg-dark px-3">
    <a class="navbar-brand" href="#">caRent</a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTenant" aria-controls="navbarTenant" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarTenant">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item"><a class="nav-link" href="../admin/dashboard.php">Dashboard</a></li>
            <li class="nav-item"><a class="nav-link" href="../admin/vehicle_data.php">Data Kendaraan</a></li>
            <li class="nav-item"><a class="nav-link" href="../admin/user_data.php">Data Penyewa</a></li>
            <li class="nav-item"><a class="nav-link" href="../admin/payment_data.php">Transaksi</a></li>
        </ul>

        <a href="../auth/logout.php" class="btn btn-outline-light">Logout</a>
        <div class="d-flex align-items-center">

            <?php if (!empty($user['profile_picture'])): ?>
                <a href="profile.php">

                    <img src="../assets/uploads/profile/<?= htmlspecialchars($user['profile_picture']) ?>" alt="Foto Profil"
                        class="rounded-circle"
                        style="width: 40px; height: 40px; object-fit: cover;" />
                </a>
            <?php endif; ?>


        </div>

    </div>
</nav>