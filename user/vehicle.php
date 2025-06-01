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

$vehicles = mysqli_query($conn, "SELECT * FROM vehicle WHERE status = 'Tersedia' LIMIT 4");

?>


<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>User Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body style="overflow-y: scroll">
    <?php include '../components/navbar_user.php'; ?>




    <div class="container mt-4">

        <!-- SECTION 3: Kendaraan Tersedia -->
        <div class="card mb-4">
            <div class="card-header">
                Kendaraan Tersedia
                <a href="list_kendaraan.php" class="btn btn-sm btn-primary float-end">Lihat Semua</a>
            </div>
            <div class="card-body">
                <div class=" container">
                    <div class="row">

                        <?php if (mysqli_num_rows($vehicles) > 0): ?>
                            <?php while ($v = mysqli_fetch_assoc($vehicles)) : ?>
                                <div class="col-md-3 mb-4">
                                    <div class="card art-card overflow-hidden">
                                        <img src="../assets/uploads/vehicles/<?= htmlspecialchars($v['foto']) ?>" class="art-image" alt="<?= htmlspecialchars($v['merk']) ?>">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <h5 class="mb-0 art-title"><?= htmlspecialchars($v['merk'] . ' ' . $v['tipe']) ?></h5>
                                                <div class="rating d-flex">
                                                    <i class="text-warning bi-star-fill"></i>
                                                    <div class="art-rating">4.5</div>
                                                </div>
                                            </div>
                                            <p class="art-artist">Tahun <?= $v['tahun'] ?? '-' ?></p>
                                        </div>

                                        <div class="card-footer bg-transparent border-0">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <p class="fw-bold art-price mb-0">Rp <?= number_format($v['harga_per_hari'], 0, ',', '.') ?>/hari</p>
                                                <div class="d-flex gap-2">
                                                    <button class="btn btn-light rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                        <i class="bi bi-heart text-danger"></i>
                                                    </button>
                                                    <a href="rental.php?id=<?= $v['id_vehicle'] ?>" class="btn btn-light rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                        <i class="bi bi-cart text-dark"></i>
                                                    </a>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center">Belum ada riwayat sewa.</td>
                            </tr>
                        <?php endif; ?>


                    </div>
                </div>
            </div>


        </div>
</body>

</html>