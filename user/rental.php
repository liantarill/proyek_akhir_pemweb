<?php
session_start();
include "../conn/db.php";

if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit;
}
$username = $_SESSION['username'];
$queryUser = mysqli_query($conn, "SELECT * FROM user WHERE username='$username'");
$user = mysqli_fetch_assoc($queryUser);

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
$step = 'upload_bukti'; // CHANGED: Default to upload_bukti step for direct payment
$id_rental = null;

// Check if there's an existing rental for this user and vehicle that needs payment
$id_user = $_SESSION['id'] ?? null;
if ($id_user) {
    $stmt = $conn->prepare("SELECT id_rental FROM rental WHERE id_user = ? AND id_vehicle = ? AND payment_proof IS NULL ORDER BY id_rental DESC LIMIT 1");
    $stmt->bind_param("ii", $id_user, $id_vehicle);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($existing_rental = $result->fetch_assoc()) {
        $id_rental = $existing_rental['id_rental'];
    }
    $stmt->close();
}

// If no existing rental, create a default one or redirect to form
if (!$id_rental) {
    // You can either create a default rental or redirect back to form
    // For now, let's redirect to form if no pending rental exists
    $step = 'form_sewa';
}

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
                $ext = pathinfo($_FILES['payment_proof']['name'], PATHINFO_EXTENSION);
                $new_file_name = "payment_" . uniqid() . "." . $ext;
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
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sewa Kendaraan - Luxury Car Rental</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/rental.css">

</head>

<body>
    <?php include '../components/navbar_user.php'; ?>

    <div class="container mt-4">
        <div class="page-header">
            <div class="d-flex align-items-center">
                <i class="fas fa-car fa-2x me-3"></i>
                <div>
                    <h2 class="mb-1 fw-bold">Sewa Kendaraan Premium</h2>
                    <p class="mb-0 opacity-75">Proses penyewaan kendaraan luxury</p>
                </div>
            </div>
        </div>

        <div class="step-indicator">
            <div class="step <?= $step === 'form_sewa' ? 'active' : 'completed' ?>">
                <i class="fas fa-calendar-alt me-2"></i>
                1. Pilih Tanggal
            </div>
            <div class="step <?= $step === 'upload_bukti' ? 'active' : 'inactive' ?>">
                <i class="fas fa-upload me-2"></i>
                2. Upload Bukti
            </div>
        </div>

        <div class="vehicle-card">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="vehicle-info">
                        <h4 class="mb-2">
                            <i class="fas fa-car me-2"></i>
                            <?= htmlspecialchars($vehicle['merk'] . ' ' . $vehicle['tipe']) ?>
                        </h4>
                        <div class="row">
                            <div class="col-md-6">
                                <p class="mb-1">
                                    <i class="fas fa-cogs me-2 text-warning"></i>
                                    <strong>Transmisi:</strong> <?= htmlspecialchars($vehicle['transmisi']) ?>
                                </p>
                                <p class="mb-1">
                                    <i class="fas fa-gas-pump me-2 text-warning"></i>
                                    <strong>Bahan Bakar:</strong> <?= htmlspecialchars($vehicle['bahan_bakar']) ?>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-1">
                                    <i class="fas fa-users me-2 text-warning"></i>
                                    <strong>Kapasitas:</strong> <?= htmlspecialchars($vehicle['kapasitas']) ?> Penumpang
                                </p>
                                <p class="mb-1">
                                    <i class="fas fa-calendar me-2 text-warning"></i>
                                    <strong>Tahun:</strong> <?= htmlspecialchars($vehicle['tahun']) ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-end">
                    <div class="vehicle-price">
                        <i class="fas fa-tag me-2"></i>
                        Rp <?= number_format($vehicle['harga_per_hari'], 0, ',', '.') ?>
                    </div>
                    <small class="text-muted">per hari</small>
                </div>
            </div>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-luxury-danger" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="form-card">
                    <div class="card-body p-4">

                        <?php if ($step === 'form_sewa'): ?>
                            <h5 class="text-warning mb-4">
                                <i class="fas fa-calendar-alt me-2"></i>
                                Pilih Tanggal Penyewaan
                            </h5>

                            <form method="post" novalidate id="rentalForm">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="rental_date" class="form-label">
                                                <i class="fas fa-calendar-plus me-2"></i>Tanggal Sewa
                                            </label>
                                            <input type="date" id="rental_date" name="rental_date"
                                                class="form-control" required min="<?= date('Y-m-d') ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="return_date" class="form-label">
                                                <i class="fas fa-calendar-minus me-2"></i>Tanggal Kembali
                                            </label>
                                            <input type="date" id="return_date" name="return_date"
                                                class="form-control" required min="<?= date('Y-m-d', strtotime('+1 day')) ?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="price-calculation" id="priceCalculation" style="display: none;">
                                    <h6 class="text-warning mb-3">
                                        <i class="fas fa-calculator me-2"></i>
                                        Rincian Biaya
                                    </h6>
                                    <div class="price-row">
                                        <span>Harga per hari:</span>
                                        <span>Rp <?= number_format($vehicle['harga_per_hari'], 0, ',', '.') ?></span>
                                    </div>
                                    <div class="price-row">
                                        <span>Durasi sewa:</span>
                                        <span id="duration">0 hari</span>
                                    </div>
                                    <div class="price-row total">
                                        <span>Total Biaya:</span>
                                        <span id="totalPrice">Rp 0</span>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between mt-4">
                                    <a href="dashboard.php" class="btn btn-outline-luxury">
                                        <i class="fas fa-arrow-left me-2"></i>
                                        Batal
                                    </a>
                                    <button type="submit" class="btn btn-luxury">
                                        <i class="fas fa-arrow-right me-2"></i>
                                        Lanjut ke Pembayaran
                                    </button>
                                </div>
                            </form>

                        <?php else: ?>
                            <!-- CHANGED: Direct to payment upload step -->
                            <h5 class="text-warning mb-4">
                                <i class="fas fa-upload me-2"></i>
                                Upload Bukti Pembayaran
                            </h5>

                            <div class="alert alert-info border-0 mb-4" style="background: rgba(59, 130, 246, 0.1); color: #93c5fd;">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Informasi Pembayaran:</strong><br>
                                Silakan transfer ke rekening berikut dan upload bukti pembayaran:<br>
                                <strong>Bank BCA: 1234567890 a.n. Yusuf Herlian</strong>
                            </div>

                            <form method="post" enctype="multipart/form-data">
                                <input type="hidden" name="id_rental" value="<?= htmlspecialchars($id_rental) ?>">

                                <div class="mb-4">
                                    <label for="payment_proof" class="form-label">
                                        <i class="fas fa-receipt me-2"></i>Bukti Pembayaran
                                    </label>

                                    <div class="upload-area">
                                        <i class="fas fa-cloud-upload-alt upload-icon"></i>
                                        <h6 class="text-warning mb-2">Pilih File Bukti Pembayaran</h6>
                                        <p class="text-muted mb-3">Format: JPG, PNG, PDF (Maksimal 2MB)</p>
                                        <input type="file" id="payment_proof" name="payment_proof"
                                            class="form-control" required accept=".jpg,.jpeg,.png,.pdf">
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between mt-4">
                                    <a href="dashboard.php" class="btn btn-outline-luxury">
                                        <i class="fas fa-times me-2"></i>
                                        Batal
                                    </a>
                                    <button type="submit" class="btn btn-luxury">
                                        <i class="fas fa-check me-2"></i>
                                        Upload & Selesai
                                    </button>
                                </div>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const rentalDate = document.getElementById('rental_date');
        const returnDate = document.getElementById('return_date');
        const priceCalculation = document.getElementById('priceCalculation');
        const durationSpan = document.getElementById('duration');
        const totalPriceSpan = document.getElementById('totalPrice');
        const pricePerDay = <?= $vehicle['harga_per_hari'] ?>;

        function calculatePrice() {
            if (rentalDate.value && returnDate.value) {
                const startDate = new Date(rentalDate.value);
                const endDate = new Date(returnDate.value);
                const timeDiff = endDate.getTime() - startDate.getTime();
                const dayDiff = Math.ceil(timeDiff / (1000 * 3600 * 24));

                if (dayDiff > 0) {
                    const totalPrice = dayDiff * pricePerDay;
                    durationSpan.textContent = dayDiff + ' hari';
                    totalPriceSpan.textContent = 'Rp ' + totalPrice.toLocaleString('id-ID');
                    priceCalculation.style.display = 'block';
                } else {
                    priceCalculation.style.display = 'none';
                }
            }
        }

        if (rentalDate && returnDate) {
            rentalDate.addEventListener('change', calculatePrice);
            returnDate.addEventListener('change', calculatePrice);

            rentalDate.addEventListener('change', function() {
                if (this.value) {
                    const nextDay = new Date(this.value);
                    nextDay.setDate(nextDay.getDate() + 1);
                    returnDate.min = nextDay.toISOString().split('T')[0];

                    if (returnDate.value && returnDate.value <= this.value) {
                        returnDate.value = '';
                    }
                }
            });
        }
    </script>
</body>

</html>