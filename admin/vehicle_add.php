<?php
session_start();
include "../conn/db.php";

// Cek apakah admin sudah login
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $merk           = mysqli_real_escape_string($conn, $_POST['merk']);
    $tipe           = mysqli_real_escape_string($conn, $_POST['tipe']);
    $tahun          = intval($_POST['tahun']);
    $no_plat        = mysqli_real_escape_string($conn, $_POST['no_plat']);
    $harga_per_hari = floatval($_POST['harga_per_hari']);
    $deskripsi      = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $status         = mysqli_real_escape_string($conn, $_POST['status']);

    // Validasi file upload
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $fileName = $_FILES['foto']['name'];
        $tmpName  = $_FILES['foto']['tmp_name'];
        $ext      = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowed  = ['jpg', 'jpeg', 'png'];

        if (!in_array($ext, $allowed)) {
            $error = "Format foto tidak valid. Gunakan JPG, JPEG, atau PNG.";
        } else {
            $newName = uniqid("vehicle_") . "." . $ext;
            $uploadDir = "../assets/uploads/vehicles/";

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $destination = $uploadDir . $newName;

            if (move_uploaded_file($tmpName, $destination)) {
                // Simpan ke database
                $query = "INSERT INTO vehicle (merk, tipe, tahun, no_plat, harga_per_hari, deskripsi, foto, status)
                          VALUES ('$merk', '$tipe', '$tahun', '$no_plat', '$harga_per_hari', '$deskripsi', '$newName', '$status')";

                if (mysqli_query($conn, $query)) {
                    $success = "Data kendaraan berhasil ditambahkan.";
                } else {
                    $error = "Gagal menyimpan ke database.";
                }
            } else {
                $error = "Gagal mengunggah file foto.";
            }
        }
    } else {
        $error = "Harap unggah foto kendaraan.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Tambah Kendaraan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="..assets/css/style.css">
</head>

<body>
    <div class="container mt-4">
        <h2>Tambah Data Kendaraan</h2>

        <?php if ($success): ?>
            <div class="alert alert-success"><?= $success ?></div>
        <?php elseif ($error): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <form action="" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="merk" class="form-label">Merk</label>
                <input type="text" name="merk" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="tipe" class="form-label">Tipe</label>
                <input type="text" name="tipe" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="tahun" class="form-label">Tahun</label>
                <input type="number" name="tahun" class="form-control" min="2000" max="<?= date('Y') ?>" required>
            </div>
            <div class="mb-3">
                <label for="no_plat" class="form-label">Nomor Plat</label>
                <input type="text" name="no_plat" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="harga_per_hari" class="form-label">Harga per Hari</label>
                <input type="number" name="harga_per_hari" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="deskripsi" class="form-label">Deskripsi</label>
                <textarea name="deskripsi" class="form-control" rows="3"></textarea>
            </div>
            <div class="mb-3">
                <label for="foto" class="form-label">Foto Kendaraan</label>
                <input type="file" name="foto" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select name="status" class="form-select" required>
                    <option value="Tersedia" selected>Tersedia</option>
                    <option value="Disewa">Disewa</option>
                    <option value="Pemeliharaan">Pemeliharaan</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Tambah</button>
            <a href="vehicle_data.php" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</body>

</html>