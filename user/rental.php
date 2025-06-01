<?php
session_start();
include "../conn/db.php";

if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit;
}

$id_vehicle = $_GET['id'] ?? null;
if (!$id_vehicle || !is_numeric($id_vehicle)) {
    die("ID kendaraan tidak ditemukan atau tidak valid.");
}

// Ambil data kendaraan
$stmt = $conn->prepare("SELECT * FROM vehicle WHERE id_vehicle = ?");
$stmt->bind_param("i", $id_vehicle);
$stmt->execute();
$result = $stmt->get_result();
$vehicle = $result->fetch_assoc();
$stmt->close();

if (!$vehicle) {
    die("Kendaraan tidak ditemukan.");
}

$error = '';
$step = 'form_sewa'; // state untuk form mana yang tampil
$id_rental = null;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['rental_date'], $_POST['return_date'])) {
        // Step 1: Proses form sewa
        $id_user = $_SESSION['id'] ?? null;
        if (!$id_user) {
            die("User tidak dikenali.");
        }

        $rental_date = $_POST['rental_date'];
        $return_date = $_POST['return_date'];

        $today = date('Y-m-d');
        if ($rental_date < $today) {
            $error = "Tanggal sewa tidak boleh sebelum hari ini.";
        } elseif ($return_date <= $rental_date) {
            $error = "Tanggal kembali harus setelah tanggal sewa.";
        } else {
            $diff = (strtotime($return_date) - strtotime($rental_date)) / (60 * 60 * 24);
            if ($diff < 1) {
                $error = "Durasi sewa minimal 1 hari.";
            } else {
                $total_price = $diff * $vehicle['harga_per_hari'];

                // Insert rental dengan status pending dan tanpa payment_proof
                $stmt = $conn->prepare("INSERT INTO rental (id_user, id_vehicle, rental_date, return_date, total_price) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("iissd", $id_user, $id_vehicle, $rental_date, $return_date, $total_price);

                if ($stmt->execute()) {
                    $id_rental = $stmt->insert_id;
                    $step = 'upload_bukti';
                } else {
                    $error = "Gagal menyimpan transaksi: " . $conn->error;
                }
                $stmt->close();
            }
        }
    } elseif (isset($_POST['id_rental']) && isset($_FILES['payment_proof'])) {
        // Step 2: Proses upload bukti pembayaran
        $id_rental = $_POST['id_rental'];
        $allowed_types = ['image/jpeg', 'image/png', 'image/jpg', 'application/pdf'];
        $max_size = 2 * 1024 * 1024; // max 2 MB

        if ($_FILES['payment_proof']['error'] === UPLOAD_ERR_OK) {
            $file_tmp = $_FILES['payment_proof']['tmp_name'];
            $file_name = basename($_FILES['payment_proof']['name']);
            $file_type = mime_content_type($file_tmp);
            $file_size = $_FILES['payment_proof']['size'];

            if (!in_array($file_type, $allowed_types)) {
                $error = "Format file tidak didukung. Gunakan JPG, PNG, atau PDF.";
            } elseif ($file_size > $max_size) {
                $error = "Ukuran file terlalu besar, maksimal 2 MB.";
            } else {
                $upload_dir = "../assets/uploads/payment_proofs/";
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0755, true);
                }

                $new_file_name = time() . "_" . preg_replace("/[^a-zA-Z0-9.\-_]/", "", $file_name);
                $destination = $upload_dir . $new_file_name;

                if (move_uploaded_file($file_tmp, $destination)) {
                    // Update payment_proof dan status rental jadi waiting_verification
                    $stmt = $conn->prepare("UPDATE rental SET payment_proof = ? WHERE id_rental = ?");
                    $stmt->bind_param("si", $new_file_name, $id_rental);
                    if ($stmt->execute()) {
                        header("Location: dashboard.php?success=Upload bukti pembayaran berhasil, tunggu verifikasi admin.");
                        exit;
                    } else {
                        $error = "Gagal update bukti pembayaran: " . $conn->error;
                    }
                    $stmt->close();
                } else {
                    $error = "Gagal mengupload file.";
                }
            }
        } else {
            $error = "Terjadi kesalahan saat upload file.";
        }

        $step = 'upload_bukti';
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Form Sewa & Upload Bukti Pembayaran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-4">
        <h3>Sewa Kendaraan: <?= htmlspecialchars($vehicle['merk'] . ' ' . $vehicle['tipe']) ?></h3>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if ($step === 'form_sewa'): ?>
            <form method="post" novalidate>
                <div class="mb-3">
                    <label for="rental_date" class="form-label">Tanggal Sewa</label>
                    <input type="date" id="rental_date" name="rental_date" class="form-control" required min="<?= date('Y-m-d') ?>">
                </div>
                <div class="mb-3">
                    <label for="return_date" class="form-label">Tanggal Kembali</label>
                    <input type="date" id="return_date" name="return_date" class="form-control" required min="<?= date('Y-m-d', strtotime('+1 day')) ?>">
                </div>
                <button type="submit" class="btn btn-primary">Ajukan Sewa</button>
                <a href="dashboard.php" class="btn btn-secondary">Batal</a>
            </form>
        <?php elseif ($step === 'upload_bukti'): ?>
            <h5>Upload Bukti Pembayaran</h5>
            <form method="post" enctype="multipart/form-data">
                <input type="hidden" name="id_rental" value="<?= htmlspecialchars($id_rental) ?>">
                <div class="mb-3">
                    <label for="payment_proof" class="form-label">Bukti Pembayaran (JPG, PNG, PDF max 2MB)</label>
                    <input type="file" id="payment_proof" name="payment_proof" class="form-control" required accept=".jpg,.jpeg,.png,.pdf">
                </div>
                <button type="submit" class="btn btn-success">Upload</button>
                <a href="dashboard.php" class="btn btn-secondary">Batal</a>
            </form>
        <?php endif; ?>
    </div>
</body>

</html>