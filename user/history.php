<?php
session_start();
include "../conn/db.php";

// Pastikan user sudah login
if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit;
}

$id_user = $_SESSION['id'];

$queryUser = mysqli_query($conn, "SELECT * FROM user WHERE id_user='$id_user'");
$user = mysqli_fetch_assoc($queryUser);

$query = "
    SELECT rental.*, vehicle.merk, vehicle.tipe
    FROM rental
    JOIN vehicle ON rental.id_vehicle = vehicle.id_vehicle
    WHERE rental.id_user = ?
    ORDER BY rental.rental_date DESC
";

$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $id_user);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>User Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body style="overflow-y: scroll">
    <?php include '../components/navbar_user.php'; ?>




    <div class="container mt-5">
        <h2>Riwayat Sewa Saya</h2>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Kendaraan</th>
                    <th>Tanggal Sewa</th>
                    <th>Tanggal Kembali</th>
                    <th>Total Harga</th>
                    <th>Status</th>
                    <th>Bukti Pembayaran</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($result) > 0): ?>
                    <?php while ($r = mysqli_fetch_assoc($result)) : ?>
                        <tr>
                            <td><?= htmlspecialchars($r['merk'] . ' ' . $r['tipe']) ?></td>
                            <td><?= htmlspecialchars($r['rental_date']) ?></td>
                            <td><?= htmlspecialchars($r['return_date']) ?></td>
                            <td>Rp <?= number_format($r['total_price'], 0, ',', '.') ?></td>
                            <td><?= htmlspecialchars($r['rental_status']) ?></td>
                            <td>
                                <?php if (!empty($r['payment_proof'])): ?>
                                    <a href="../uploads/payment_proofs/<?= htmlspecialchars($r['payment_proof']) ?>" target="_blank">Lihat Bukti</a>
                                <?php else: ?>
                                    <span class="text-muted">Belum upload</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center">Belum ada riwayat sewa.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>

</html>