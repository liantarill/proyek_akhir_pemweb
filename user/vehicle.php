<?php
session_start();
include "../conn/db.php";

if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit;
}

$username = $_SESSION['username'];

// Ambil data user
$query = mysqli_query($conn, "SELECT * FROM user WHERE username='$username'");
$user = mysqli_fetch_assoc($query);

if (!$user) {
    session_destroy();
    header("Location: ../index.php");
    exit;
}

$vehicles = mysqli_query($conn, "SELECT * FROM vehicle WHERE status = 'Tersedia'");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Kendaraan Premium - Luxury Car Rental</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/vehicle.css">


<body style="overflow-y: scroll">
    <?php include '../components/navbar_user.php'; ?>

    <div class="container mt-4">
        <div class="page-header">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <i class="fas fa-car-side fa-2x me-3"></i>
                    <div>
                        <h2 class="mb-1 fw-bold luxury-title">Koleksi Kendaraan</h2>
                        <p class="mb-0 opacity-75">Pilih kendaraan terbaik untuk perjalanan Anda</p>
                    </div>
                </div>
                <div class="text-end">
                    <div class="badge bg-warning text-dark px-3 py-2 rounded-pill">
                        <?= mysqli_num_rows($vehicles) ?> Kendaraan Tersedia
                    </div>
                </div>
            </div>
        </div>


        <!-- Vehicle Grid -->
        <?php if (mysqli_num_rows($vehicles) > 0): ?>
            <div class="vehicle-grid" id="vehicleGrid">
                <?php while ($v = mysqli_fetch_assoc($vehicles)) : ?>
                    <div class="vehicle-card" data-vehicle='<?= json_encode($v) ?>'>
                        <div class="status-badge">
                            <i class="fas fa-check-circle me-1"></i>
                            Tersedia
                        </div>

                        <?php if (!empty($v['foto'])): ?>
                            <img src="../assets/uploads/vehicles/<?= htmlspecialchars($v['foto']) ?>"
                                class="vehicle-image" alt="<?= htmlspecialchars($v['merk']) ?>">
                        <?php else: ?>
                            <div class="vehicle-image d-flex align-items-center justify-content-center"
                                style="background: rgba(31, 41, 55, 0.9);">
                                <i class="fas fa-car fa-3x text-warning opacity-50"></i>
                            </div>
                        <?php endif; ?>

                        <div class="vehicle-info">
                            <h5 class="vehicle-title">
                                <?= htmlspecialchars($v['merk'] . ' ' . $v['tipe']) ?>
                            </h5>
                            <p class="vehicle-year">
                                <i class="fas fa-calendar me-2"></i>
                                Tahun <?= htmlspecialchars($v['tahun'] ?? '2023') ?>
                            </p>

                            <div class="vehicle-features">
                                <span class="feature-badge">
                                    <i class="fas fa-cogs me-1"></i>
                                    <?= htmlspecialchars($v['transmisi']) ?>
                                </span>
                                <span class="feature-badge">
                                    <i class="fas fa-gas-pump me-1"></i>
                                    <?= htmlspecialchars($v['bahan_bakar']) ?>
                                </span>
                                <span class="feature-badge">
                                    <i class="fas fa-users me-1"></i>
                                    <?= htmlspecialchars($v['kapasitas']) ?> Seat
                                </span>
                            </div>

                            <div class="vehicle-price">
                                <i class="fas fa-tag me-2"></i>
                                Rp <?= number_format($v['harga_per_hari'], 0, ',', '.') ?>
                                <small class="text-light">/hari</small>
                            </div>

                            <div class="vehicle-actions">
                                <a href="rental.php?id=<?= $v['id_vehicle'] ?>" class="btn btn-luxury">
                                    <i class="fas fa-key me-2"></i>
                                    Sewa Sekarang
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-car-side"></i>
                <h3 class="text-warning mb-3">Belum Ada Kendaraan Tersedia</h3>
                <p class="mb-4">Maaf, saat ini belum ada kendaraan yang tersedia untuk disewa.</p>
                <a href="dashboard.php" class="btn btn-luxury">
                    <i class="fas fa-arrow-left me-2"></i>
                    Kembali ke Dashboard
                </a>
            </div>
        <?php endif; ?>


    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>