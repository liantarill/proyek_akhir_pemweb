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
    <title>Profil Pengguna</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body style="overflow-y: scroll">
    <?php include '../components/navbar_user.php'; ?>

    <div class="container mt-5">
        <?php if (!empty($_GET['error'])): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($_GET['error']) ?></div>
        <?php elseif (!empty($_GET['success'])): ?>
            <div class="alert alert-success"><?= htmlspecialchars($_GET['success']) ?></div>
        <?php endif; ?>

        <div class="row justify-content-center">
            <div class="col-md-8">

                <div class="card shadow rounded-4">
                    <div class="card-body text-center">

                        <!-- Foto Profil -->
                        <?php if (!empty($user['profile_picture'])): ?>
                            <img src="../assets/uploads/profile/<?= htmlspecialchars($user['profile_picture']) ?>" alt="Foto Profil"
                                class="img-thumbnail mb-3"
                                style="width: 150px; height: 150px; object-fit: cover; border-radius: 50%;" />
                        <?php endif; ?>

                        <!-- Data Diri -->
                        <h4 class="mb-2"><?= htmlspecialchars($user['nama'] ?? $username) ?></h4>
                        <p class="mb-1"><strong>Email:</strong> <?= htmlspecialchars($user['email'] ?? '-') ?></p>
                        <p class="mb-1"><strong>No HP:</strong> <?= htmlspecialchars($user['no_hp'] ?? '-') ?></p>
                        <p class="mb-1"><strong>Alamat:</strong> <?= htmlspecialchars($user['address'] ?? '-') ?></p>

                        <!-- Foto KTP -->
                        <?php if (!empty($user['foto_ktp'])): ?>
                            <div class="mt-3">
                                <strong>Foto KTP:</strong><br />
                                <img src="../assets/uploads/ktp/<?= htmlspecialchars($user['foto_ktp']) ?>" alt="Foto KTP"
                                    class="img-fluid rounded img-thumbnail" style="max-height: 200px;" />
                            </div>
                        <?php endif; ?>

                        <!-- Tombol Edit -->
                        <a href="edit_profile.php" class="btn btn-warning mt-4">Edit Profil</a>
                        <a href="../auth/logout.php" class="btn btn-outline-danger mt-4">Logout</a>
                    </div>
                </div>

            </div>
        </div>
    </div>

</body>

</html>