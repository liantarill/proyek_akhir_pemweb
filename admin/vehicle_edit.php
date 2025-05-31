<?php
session_start();
include "../conn/db.php";

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

$id = $_GET['id'];
$kendaraan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM vehicle WHERE id_vehicle = $id"));

if (!$kendaraan) {
    die("Kendaraan tidak ditemukan.");
}

$success = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $merk = $_POST['merk'];
    $tipe = $_POST['tipe'];
    $tahun = $_POST['tahun'];
    $no_plat = $_POST['no_plat'];
    $harga = $_POST['harga_per_hari'];
    $deskripsi = $_POST['deskripsi'];
    $status = $_POST['status'];

    // Update foto jika ada upload baru
    if ($_FILES['foto']['name']) {
        $fileName = $_FILES['foto']['name'];
        $tmp = $_FILES['foto']['tmp_name'];
        $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $newName = uniqid('vehicle_') . '.' . $ext;

        move_uploaded_file($tmp, "../assets/uploads/vehicles/$newName");
        $foto = ", foto = '$newName'";
    } else {
        $foto = "";
    }

    $query = "UPDATE vehicle SET merk='$merk', tipe='$tipe', tahun='$tahun', no_plat='$no_plat',
              harga_per_hari='$harga', deskripsi='$deskripsi', status='$status' $foto
              WHERE id_vehicle = $id";

    if (mysqli_query($conn, $query)) {
        $success = "Berhasil diperbarui.";
        $kendaraan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM vehicle WHERE id_vehicle = $id"));
    } else {
        $error = "Gagal mengupdate.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Edit Kendaraan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <div class="container mt-4">
        <h2>Edit Kendaraan</h2>

        <?php if ($success): ?>
            <div class="alert alert-success"><?= $success ?></div>
        <?php elseif ($error): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label>Merk</label>
                <input type="text" name="merk" class="form-control" value="<?= $kendaraan['merk'] ?>" required>
            </div>
            <div class="mb-3">
                <label>Tipe</label>
                <input type="text" name="tipe" class="form-control" value="<?= $kendaraan['tipe'] ?>" required>
            </div>
            <div class="mb-3">
                <label>Tahun</label>
                <input type="number" name="tahun" class="form-control" value="<?= $kendaraan['tahun'] ?>" required>
            </div>
            <div class="mb-3">
                <label>No Plat</label>
                <input type="text" name="no_plat" class="form-control" value="<?= $kendaraan['no_plat'] ?>" required>
            </div>
            <div class="mb-3">
                <label>Harga per Hari</label>
                <input type="number" name="harga_per_hari" class="form-control" value="<?= $kendaraan['harga_per_hari'] ?>" required>
            </div>
            <div class="mb-3">
                <label>Deskripsi</label>
                <textarea name="deskripsi" class="form-control"><?= $kendaraan['deskripsi'] ?></textarea>
            </div>
            <div class="mb-3">
                <label>Ganti Foto (Opsional)</label><br>
                <img src="../assets/uploads/vehicles/<?= $kendaraan['foto'] ?>" width="150"><br><br>
                <input type="file" name="foto" class="form-control">
            </div>
            <div class="mb-3">
                <label>Status</label>
                <select name="status" class="form-select" required>
                    <option value="Tersedia" <?= $kendaraan['status'] == 'Tersedia' ? 'selected' : '' ?>>Tersedia</option>
                    <option value="Disewa" <?= $kendaraan['status'] == 'Disewa' ? 'selected' : '' ?>>Disewa</option>
                    <option value="Pemeliharaan" <?= $kendaraan['status'] == 'Pemeliharaan' ? 'selected' : '' ?>>Pemeliharaan</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            <a href="vehicle_data.php" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</body>

</html>