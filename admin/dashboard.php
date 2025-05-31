<?php
session_start();
include "../conn/db.php";

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

$totalUsers = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM user"))['total'];
$totalVehicles = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM vehicle"))['total'];
$totalRentals = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM rental"))['total'];
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <?php include '../components/navbar_admin.php'; ?>

    <div class="container mt-4">
        <h2 class="mb-4">Dashboard</h2>

        <div class="row">
            <div class="col-md-4 mb-3">
                <div class="card text-bg-primary">
                    <div class="card-body">
                        <h5 class="card-title">Total Pengguna</h5>
                        <p class="card-text fs-4"><?= $totalUsers ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card text-bg-success">
                    <div class="card-body">
                        <h5 class="card-title">Total Kendaraan</h5>
                        <p class="card-text fs-4"><?= $totalVehicles ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card  text-bg-danger">
                    <div class="card-body">
                        <h5 class="card-title">Total Transaksi</h5>
                        <p class="card-text fs-4"><?= $totalRentals ?></p>
                    </div>
                </div>
            </div>
        </div>

        <hr>
        <p>Selamat datang, <strong><?= htmlspecialchars($_SESSION['username']) ?></strong>! Ini adalah halaman utama admin.</p>
    </div>
</body>

</html>