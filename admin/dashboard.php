<?php

session_start();


include '../conn/db.php';

if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit;
}

// $query = "SELECT id, name, role FROM users WHERE role='user'";
// $result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <?php include '../components/navbar_admin.php'; ?>


    <div class="container mt-5">
        <div class="alert alert-success rounded-4 shadow-sm"
            role="alert">
            <h4 class="alert-heading">Selamat Datang,
                <?php echo htmlspecialchars($_SESSION['username']); ?>!</h4>
            <p>kamu adalah admin. Ini adalah halaman
                dashboard sederhana.</p>
        </div>

    </div>


    <div class="container mt-5">
        <a href="add.php" class="btn btn-success mb-3">Tambah User</a>

        <h3 class="mb-4">Daftar Pengguna</h3>
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>Role</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>


            </tbody>
        </table>
    </div>
</body>

</html>