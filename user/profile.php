<?php
session_start();
include "../conn/db.php";

if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit;
}

$username = $_SESSION['username'];

$query = mysqli_query($conn, "SELECT * FROM user WHERE username='$username'");
$user = mysqli_fetch_assoc($query);

if (!$user) {
    session_destroy();
    header("Location: ../index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>caRent</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/profile.css">


</head>

<body style="overflow-y: scroll">
    <?php include '../components/navbar_user.php'; ?>

    <div class="container mt-4">
        <div class="page-header">
            <div class="d-flex align-items-center">
                <i class="fas fa-user-circle fa-2x me-3"></i>
                <div>
                    <h2 class="mb-1 fw-bold">Profil Pengguna</h2>
                    <p class="mb-0 opacity-75">Kelola informasi pribadi dan dokumen Anda</p>
                </div>
            </div>
        </div>

        <!-- Alert Messages -->
        <?php if (!empty($_GET['error'])): ?>
            <div class="alert alert-luxury-danger" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <?= htmlspecialchars($_GET['error']) ?>
            </div>
        <?php elseif (!empty($_GET['success'])): ?>
            <div class="alert alert-luxury-success" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                <?= htmlspecialchars($_GET['success']) ?>
            </div>
        <?php endif; ?>

        <div class="row">
            <!-- Profile Card -->
            <div class="col-lg-8">
                <div class="profile-card">
                    <div class="card-body p-4">
                        <!-- Profile Header -->
                        <div class="text-center mb-4">
                            <!-- Profile Picture -->
                            <?php if (!empty($user['profile_picture'])): ?>
                                <img src="../assets/uploads/profile/<?= htmlspecialchars($user['profile_picture']) ?>"
                                    alt="Foto Profil" class="profile-avatar mb-3" />
                            <?php else: ?>
                                <div class="default-avatar mb-3">
                                    <i class="fas fa-user"></i>
                                </div>
                            <?php endif; ?>

                            <h3 class="text-warning fw-bold mb-1">
                                <?= htmlspecialchars($user['nama'] ?? $username) ?>
                            </h3>
                        </div>

                        <!-- Profile Information -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="profile-info">
                                    <h6><i class="fas fa-user me-2"></i>Username</h6>
                                    <p><?= htmlspecialchars($username) ?></p>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="profile-info">
                                    <h6><i class="fas fa-envelope me-2"></i>Email</h6>
                                    <p><?= htmlspecialchars($user['email'] ?? 'Belum diisi') ?></p>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="profile-info">
                                    <h6><i class="fas fa-phone me-2"></i>No HP</h6>
                                    <p><?= htmlspecialchars($user['no_hp'] ?? 'Belum diisi') ?></p>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="profile-info">
                                    <h6><i class="fas fa-map-marker-alt me-2"></i>Alamat</h6>
                                    <p><?= htmlspecialchars($user['address'] ?? 'Belum diisi') ?></p>
                                </div>
                            </div>
                        </div>

                        <!-- KTP Section -->
                        <?php if (!empty($user['foto_ktp'])): ?>
                            <div class="profile-info">
                                <h6><i class="fas fa-id-card me-2"></i>Foto KTP</h6>
                                <div class="text-center">
                                    <img src="../assets/uploads/ktp/<?= htmlspecialchars($user['foto_ktp']) ?>"
                                        alt="Foto KTP" class="img-fluid ktp-image" />
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="profile-info text-center">
                                <h6><i class="fas fa-id-card me-2"></i>Foto KTP</h6>
                                <div class="text-muted">
                                    <i class="fas fa-upload fa-3x mb-3 opacity-50"></i>
                                    <p>Belum ada foto KTP yang diupload</p>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Action Buttons -->
                        <div class="text-center mt-4">
                            <a href="edit_profile.php" class="btn btn-luxury me-3">
                                <i class="fas fa-edit me-2"></i>
                                Edit Profil
                            </a>
                            <a href="../auth/logout.php" class="btn btn-outline-luxury">
                                <i class="fas fa-sign-out-alt me-2"></i>
                                Logout
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Profile Stats -->
            <div class="col-lg-4">
                <div class="profile-stats mb-4">
                    <h5 class="text-warning mb-3">
                        <i class="fas fa-chart-bar me-2"></i>
                        Statistik Akun
                    </h5>

                    <?php
                    // Get user rental statistics
                    $user_id = $user['id_user'];
                    $stats_query = mysqli_query($conn, "
                        SELECT 
                            COUNT(*) as total_rentals,
                            COALESCE(SUM(rental_status = 'Terverifikasi'), 0) as verified_rentals,
                            COALESCE(SUM(rental_status = 'Menunggu Verifikasi'), 0) as pending_rentals
                        FROM rental 
                        WHERE id_user = $user_id
                    ");
                    $stats = mysqli_fetch_assoc($stats_query);
                    ?>

                    <div class="row">
                        <div class="col-12 stat-item mb-3">
                            <div class="stat-number"><?= $stats['total_rentals'] ?></div>
                            <div class="stat-label">Total Sewa</div>
                        </div>
                        <div class="col-12 stat-item mb-3">
                            <div class="stat-number"><?= $stats['verified_rentals'] ?></div>
                            <div class="stat-label">Terverifikasi</div>
                        </div>
                        <div class="col-12 stat-item">
                            <div class="stat-number"><?= $stats['pending_rentals'] ?></div>
                            <div class="stat-label">Menunggu</div>
                        </div>
                    </div>
                </div>

                <!-- Account Status -->
                <div class="profile-stats">
                    <h5 class="text-warning mb-3">
                        <i class="fas fa-shield-alt me-2"></i>
                        Status Akun
                    </h5>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span>Profil Lengkap</span>
                            <?php
                            $completeness = 0;
                            if (!empty($user['username'])) $completeness += 20;
                            if (!empty($user['email'])) $completeness += 20;
                            if (!empty($user['no_hp'])) $completeness += 20;
                            if (!empty($user['address'])) $completeness += 20;
                            if (!empty($user['foto_ktp'])) $completeness += 20;
                            ?>
                            <span class="text-warning"><?= $completeness ?>%</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-warning" style="width: <?= $completeness ?>%"></div>
                        </div>
                    </div>

                    <div class="text-center">
                        <?php if (!empty($user['foto_ktp'])): ?>
                            <span class="badge bg-success px-3 py-2">
                                <i class="fas fa-check-circle me-1"></i>
                                Terverifikasi
                            </span>
                        <?php else: ?>
                            <span class="badge bg-warning text-dark px-3 py-2">
                                <i class="fas fa-clock me-1"></i>
                                Perlu Verifikasi KTP
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>