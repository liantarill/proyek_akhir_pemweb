<?php
session_start();
include "../conn/db.php";

$result = mysqli_query($conn, "
    SELECT rental.*, user.username, vehicle.merk, vehicle.tipe 
    FROM rental
    JOIN user ON rental.id_user = user.id_user
    JOIN vehicle ON rental.id_vehicle = vehicle.id_vehicle
");
?>

<!DOCTYPE html>
<html>

<head>
    <title>caRent</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">

</head>

<body>
    <?php include '../components/navbar_admin.php'; ?>
    <div class="container mt-4">
        <h2>Data Transaksi</h2>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Nama Penyewa</th>
                    <th>Kendaraan</th>
                    <th>Tanggal Sewa</th>
                    <th>Tanggal Kembali</th>
                    <th>Total Harga</th>
                    <th>Status</th>
                    <th>Bukti Pembayaran</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($result) > 0) : ?>
                    <?php while ($r = mysqli_fetch_assoc($result)) : ?>
                        <tr>
                            <td><?= htmlspecialchars($r['username']) ?></td>
                            <td><?= htmlspecialchars($r['merk'] . ' ' . $r['tipe']) ?></td>
                            <td><?= htmlspecialchars($r['rental_date']) ?></td>
                            <td><?= htmlspecialchars($r['return_date']) ?></td>
                            <td>Rp <?= number_format($r['total_price'], 0, ',', '.') ?></td>
                            <td><?= htmlspecialchars($r['rental_status']) ?></td>
                            <td>
                                <?php if (!empty($r['payment_proof'])): ?>
                                    <a href="../assets/uploads/payment_proofs/<?= htmlspecialchars($r['payment_proof']) ?>" target="_blank">Lihat Bukti</a>
                                <?php else: ?>
                                    <span class="text-muted">Belum upload</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($r['rental_status'] === 'Menunggu Verifikasi' && !empty($r['payment_proof'])): ?>
                                    <a href="payment_verify.php?id=<?= $r['id_rental'] ?>&aksi=verifikasi" class="btn btn-success btn-sm" onclick="return confirm('Verifikasi pembayaran?')">Verifikasi</a>
                                    <a href="payment_verify.php?id=<?= $r['id_rental'] ?>&aksi=tolak" class="btn btn-danger btn-sm" onclick="return confirm('Tolak pembayaran?')">Tolak</a>
                                <?php else: ?>
                                    <span class="text-light btn btn-primary">
                                        <?= $r['rental_status'] ?>
                                    </span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="9" class="text-center">Belum ada Transaksi.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>

</html>