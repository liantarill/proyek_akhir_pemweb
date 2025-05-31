<?php
session_start();
include "../conn/db.php";

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

// Ambil data penyewa dari database
$query = "SELECT * FROM user ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Data Penyewa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">

</head>

<body>
    <?php include "../components/navbar_admin.php"; ?>

    <div class="container mt-4">
        <h2>Data Pengguna</h2>
        <table class="table table-bordered mt-3 table-hover">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Alamat</th>
                    <th>No HP</th>
                    <th>KTP</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>

                <?php if (mysqli_num_rows($result) > 0) : ?>
                    <?php
                    $no = 1;
                    while ($row = mysqli_fetch_assoc($result)) :
                    ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= htmlspecialchars($row['username']) ?></td>
                            <td><?= htmlspecialchars($row['email']) ?></td>
                            <td><?= htmlspecialchars($row['address']) ?></td>
                            <td><?= htmlspecialchars($row['no_hp']) ?></td>
                            <td>
                                <?php if (!empty($row['foto_ktp'])) : ?>
                                    <a href="../assets/uploads/ktp/<?= htmlspecialchars($row['foto_ktp']) ?>" target="_blank">Lihat KTP</a>
                                <?php else : ?>
                                    -
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="user_delete.php?id=<?= $row['id_user'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus penyewa ini?')">Hapus</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="9" class="text-center">Belum ada Pengguna.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>

</html>