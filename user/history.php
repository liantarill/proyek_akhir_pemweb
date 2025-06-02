<?php
include '../controllers/history_controller.php';
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>caRent</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/history.css">


</head>

<body style="overflow-y: scroll">
    <?php include '../components/navbar_user.php'; ?>

    <div class="container mt-4">
        <div class="page-header">
            <div class="d-flex align-items-center">
                <i class="fas fa-history fa-2x me-3"></i>
                <div>
                    <h2 class="mb-1 fw-bold">Riwayat Sewa Saya</h2>
                    <p class="mb-0 opacity-75">Kelola dan pantau semua aktivitas penyewaan Anda</p>
                </div>
            </div>
        </div>
        <div class="table-container">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                        <tr>
                            <th style="width: 15%;"><i class="fas fa-car me-2"></i>Kendaraan</th>
                            <th style="width: 15%;"><i class="fas fa-calendar-alt me-2"></i>Tanggal Sewa</th>
                            <th style="width: 15%;"><i class="fas fa-calendar-check me-2"></i>Tanggal Kembali</th>
                            <th style="width: 15%;"><i class="fas fa-money-bill-wave me-2"></i>Total Harga</th>
                            <th style="width: 20%;"><i class="fas fa-info-circle me-2"></i>Status</th>
                            <th style="width: 20%;"><i class="fas fa-receipt me-2"></i>Bukti Pembayaran</th>
                        </tr>

                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($result) > 0): ?>
                            <?php while ($r = mysqli_fetch_assoc($result)) : ?>
                                <tr>
                                    <td>
                                        <div class="vehicle-info">
                                            <?= htmlspecialchars($r['merk'] . '-' . $r['tipe']) ?>
                                        </div>
                                    </td>
                                    <td>
                                        <i class="fas fa-calendar me-2 text-warning"></i>
                                        <?= date('d M Y', strtotime($r['rental_date'])) ?>
                                    </td>
                                    <td>
                                        <i class="fas fa-calendar-check me-2 text-warning"></i>
                                        <?= date('d M Y', strtotime($r['return_date'])) ?>
                                    </td>
                                    <td>
                                        <div class="price-info">
                                            <i class="fas fa-rupiah-sign me-1"></i>
                                            <?= number_format($r['total_price'], 0, ',', '.') ?>
                                        </div>
                                    </td>
                                    <td>
                                        <?php
                                        $status_class = '';
                                        switch ($r['rental_status']) {
                                            case 'Terverifikasi':
                                                $status_class = 'status-terverifikasi';
                                                $icon = 'fas fa-check-circle';
                                                break;
                                            case 'Menunggu Verifikasi':
                                                $status_class = 'status-menunggu';
                                                $icon = 'fas fa-clock';
                                                break;
                                            case 'Ditolak':
                                                $status_class = 'status-ditolak';
                                                $icon = 'fas fa-times-circle';
                                                break;
                                            default:
                                                $status_class = 'status-menunggu';
                                                $icon = 'fas fa-question-circle';
                                        }
                                        ?>
                                        <span class="status-badge <?= $status_class ?>">
                                            <i class="<?= $icon ?> me-1"></i>
                                            <?= htmlspecialchars($r['rental_status']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if (!empty($r['payment_proof'])): ?>
                                            <a href="../assets/uploads/payment_proofs/<?= htmlspecialchars($r['payment_proof']) ?>"
                                                target="_blank" class="payment-link">
                                                <i class="fas fa-eye me-2"></i>
                                                Lihat Bukti
                                            </a>
                                        <?php else: ?>
                                            <a href="rental.php?id=<?= $r['id_vehicle'] ?>&step=2" class="btn btn-warning btn-sm">
                                                <i class="fas fa-credit-card me-2"></i>
                                                Bayar Sekarang
                                            </a>
                                        <?php endif; ?>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6">
                                    <div class="empty-state">
                                        <i class="fas fa-car"></i>
                                        <h5 class="mt-3 mb-2">Belum Ada Riwayat Sewa</h5>
                                        <p class="">Anda belum memiliki riwayat penyewaan kendaraan.</p>
                                        <a href="vehicle.php" class="btn  btn-warning mt-2">
                                            <div class="d-flex align-items-center">
                                                <span class="text-light">Mulai Sewa Sekarang</span>
                                            </div>
                                        </a>

                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <script src=" https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>