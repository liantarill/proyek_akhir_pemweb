<?php
session_start();
include "../conn/db.php";

// Cek apakah admin sudah login
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

// Ambil data kendaraan dari DB
$result = mysqli_query($conn, "SELECT * FROM vehicle ORDER BY created_at DESC");

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Data Kendaraan - Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>

<body>
    <?php include '../components/navbar_admin.php'; ?>

    <div class="container mt-4">

        <div class="d-flex justify-content-between mb-3">
            <h2>Data Kendaraan</h2>
            <a href="vehicle_add.php" class="btn btn-primary">Tambah Kendaraan Baru</a>
        </div>

        <table class="table table-bordered table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Foto</th>
                    <th>Merk</th>
                    <th>Tipe</th>
                    <th>No Plat</th>
                    <th>Tahun</th>
                    <th>Harga Per Hari</th>
                    <th>Status</th>
                    <th>Opsi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($result) > 0) : ?>
                    <?php $no = 1; ?>
                    <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td>
                                <?php if ($row['foto'] && file_exists("../assets/uploads/vehicles/" . $row['foto'])): ?>
                                    <img src="../assets/uploads/vehicles/<?= htmlspecialchars($row['foto']) ?>" alt="Foto <?= htmlspecialchars($row['merk']) ?>" style="width: 100px; height: auto; object-fit: cover;">
                                <?php else: ?>
                                    <span class="text-muted">No Image</span>
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($row['merk']) ?></td>
                            <td><?= htmlspecialchars($row['tipe']) ?></td>
                            <td><?= htmlspecialchars($row['no_plat']) ?></td>
                            <td><?= htmlspecialchars($row['tahun'] ?? '-') ?></td>
                            <td>Rp <?= number_format($row['harga_per_hari'], 0, ',', '.') ?></td>
                            <td><?= htmlspecialchars($row['status']) ?></td>
                            <td>
                                <a href="vehicle_edit.php?id=<?= $row['id_vehicle'] ?>" class="btn btn-primary btn-sm">Edit</a>
                                <a href="vehicle_delete.php?id=<?= $row['id_vehicle'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus kendaraan ini?')">Hapus</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="9" class="text-center">Belum ada data kendaraan.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</body>

</html>