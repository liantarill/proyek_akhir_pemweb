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
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>User Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../assets/css/style.css">

</head>

<body style="overflow-y: scroll">
    <?php include '../components/navbar_user.php'; ?>



    <div class="container mt-4">
        <!-- SECTION 1: Welcome -->
        <div class="alert alert-primary">
            <h4>Selamat datang, <?= htmlspecialchars($username) ?>!</h4>
            <p>Berikut ringkasan akun dan aktivitas Anda.</p>
        </div>

        <!-- SECTION 2: Statistik Ringkas -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card text-white bg-success">
                    <div class="card-body">
                        <h5 class="card-title">Total Sewa</h5>
                        <p class="card-text fs-4">5</p> <!-- Ambil dari database -->
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-warning">
                    <div class="card-body">
                        <h5 class="card-title">Aktif Disewa</h5>
                        <p class="card-text fs-4">1</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-info">
                    <div class="card-body">
                        <h5 class="card-title">Menunggu Verifikasi</h5>
                        <p class="card-text fs-4">2</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-danger">
                    <div class="card-body">
                        <h5 class="card-title">Ditolak</h5>
                        <p class="card-text fs-4">0</p>
                    </div>
                </div>
            </div>
        </div>




    </div>
</body>

</html>