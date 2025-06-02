<?php
include '../includes/auth_check.php';
include '../controllers/dashboard_controller.php';
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>caRent</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css" />
    <link rel="stylesheet" href="../assets/css/dashboard.css" />

</head>

<body style="overflow-y: scroll">
    <?php include '../components/navbar_user.php'; ?>


    <div class="container mt-4">
        <div class="page-header">
            <div class="d-flex align-items-center">
                <i class="fas fa-chart-line fa-2x me-3"></i>
                <div>
                    <h2 class="mb-1 fw-bold">Selamat datang, <?= htmlspecialchars($username) ?>!</h2>

                    <p class="mb-0 opacity-75">Kelola dan pantau semua aktivitas penyewaan Anda</p>
                </div>
            </div>
        </div>

        <div class="row g-4 mt-3">
            <div class="col-md-12">
                <div class="dashboard-container">
                    <div class="row text-center">
                        <div class="col-md-3 col-6 mb-3">
                            <div class="d-flex align-items-center justify-content-center">
                                <i class="fas fa-list-alt fa-2x text-warning me-3"></i>
                                <div>
                                    <h4 class="mb-0 text-warning"><?= $rental_stats['total'] ?></h4>
                                    <small class="text-light">Total Transaksi</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6 mb-3">
                            <div class="d-flex align-items-center justify-content-center">
                                <i class="fas fa-check-circle fa-2x text-success me-3"></i>
                                <div>
                                    <h4 class="mb-0 text-success">
                                        <?= $rental_stats['terverifikasi'] ?>
                                    </h4>
                                    <small class="text-light">Terverifikasi</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6 mb-3">
                            <div class="d-flex align-items-center justify-content-center">
                                <i class="fas fa-clock fa-2x text-warning me-3"></i>
                                <div>
                                    <h4 class="mb-0 text-warning">
                                        <?= $rental_stats['menunggu'] ?>
                                    </h4>
                                    <small class="text-light">Menunggu</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6 mb-3">
                            <div class="d-flex align-items-center justify-content-center">
                                <i class="fas fa-times-circle fa-2x text-danger me-3"></i>
                                <div>
                                    <h4 class="mb-0 text-danger">
                                        <?= $rental_stats['ditolak'] ?>
                                    </h4>
                                    <small class="text-light">Ditolak</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>