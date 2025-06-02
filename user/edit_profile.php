<?php
// edit_profile.php

session_start();
include "../conn/db.php"; // File koneksi ke database

// Pastikan user sudah login
if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit;
}

$oldUsername = $_SESSION['username'];

// Ambil data user berdasarkan username lama
$query  = mysqli_query($conn, "SELECT * FROM `user` WHERE username = '$oldUsername'");
$user   = mysqli_fetch_assoc($query);

if (!$user) {
    // Jika user tidak ditemukan, logout dan redirect ke halaman login
    session_destroy();
    header("Location: ../index.php");
    exit;
}

// Inisialisasi variabel error dan success
$error   = "";
$success = "";

// Proses ketika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil input dari form dan sanitasi
    $newUsername = trim(mysqli_real_escape_string($conn, $_POST['username']));
    $email       = trim(mysqli_real_escape_string($conn, $_POST['email']));
    $no_hp       = trim(mysqli_real_escape_string($conn, $_POST['no_hp']));
    $address     = trim(mysqli_real_escape_string($conn, $_POST['address']));

    $errors = [];

    // Validasi sederhana
    if (empty($newUsername)) {
        $errors[] = "Username tidak boleh kosong.";
    }
    if (empty($email)) {
        $errors[] = "Email tidak boleh kosong.";
    }
    // Anda bisa menambahkan validasi format email, cek duplikasi username/email, dll.

    // Cek apakah user mengganti username dan pastikan username baru belum dipakai
    if ($newUsername !== $oldUsername) {
        $cekUsername = mysqli_query($conn, "SELECT id_user FROM `user` WHERE username = '$newUsername'");
        if (mysqli_num_rows($cekUsername) > 0) {
            $errors[] = "Username sudah digunakan oleh pengguna lain.";
        }
    }

    // Cek apakah user mengganti email dan pastikan email baru belum dipakai
    if ($email !== $user['email']) {
        $cekEmail = mysqli_query($conn, "SELECT id_user FROM `user` WHERE email = '$email'");
        if (mysqli_num_rows($cekEmail) > 0) {
            $errors[] = "Email sudah digunakan oleh pengguna lain.";
        }
    }

    // Siapkan variabel untuk menyimpan nama file baru (jika ada upload)
    $newProfilePic = $user['profile_picture'];
    $newFotoKTP    = $user['foto_ktp'];

    // Proses upload foto profil jika ada file baru
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] !== UPLOAD_ERR_NO_FILE) {
        $fileProfil     = $_FILES['profile_picture'];
        $allowedTypes   = ['image/jpeg', 'image/png', 'image/jpg'];
        $maxFileSize    = 2 * 1024 * 1024; // 2MB

        if ($fileProfil['error'] !== UPLOAD_ERR_OK) {
            $errors[] = "Terjadi kesalahan saat mengunggah foto profil.";
        } elseif (!in_array($fileProfil['type'], $allowedTypes)) {
            $errors[] = "Format foto profil tidak valid (hanya JPG/PNG).";
        } elseif ($fileProfil['size'] > $maxFileSize) {
            $errors[] = "Ukuran foto profil terlalu besar (maks 2MB).";
        } else {
            // Buat nama file unik
            $extProfil          = pathinfo($fileProfil['name'], PATHINFO_EXTENSION);
            $newFilenameProfil  = 'profile_' . $user['id_user'] . '_' . time() . '.' . $extProfil;
            $targetDirProfil    = __DIR__ . "/../assets/uploads/profile/";
            if (!is_dir($targetDirProfil)) {
                mkdir($targetDirProfil, 0755, true);
            }
            $targetPathProfil   = $targetDirProfil . $newFilenameProfil;

            // Pindahkan file ke folder tujuan
            if (move_uploaded_file($fileProfil['tmp_name'], $targetPathProfil)) {
                // Hapus file lama jika ada
                if (!empty($user['profile_picture']) && file_exists($targetDirProfil . $user['profile_picture'])) {
                    unlink($targetDirProfil . $user['profile_picture']);
                }
                $newProfilePic = $newFilenameProfil;
            } else {
                $errors[] = "Gagal memindahkan file foto profil.";
            }
        }
    }

    // Proses upload foto KTP jika ada file baru
    if (isset($_FILES['foto_ktp']) && $_FILES['foto_ktp']['error'] !== UPLOAD_ERR_NO_FILE) {
        $fileKTP        = $_FILES['foto_ktp'];
        $allowedTypesK   = ['image/jpeg', 'image/png', 'image/jpg'];
        $maxFileSizeK    = 2 * 1024 * 1024; // 2MB

        if ($fileKTP['error'] !== UPLOAD_ERR_OK) {
            $errors[] = "Terjadi kesalahan saat mengunggah foto KTP.";
        } elseif (!in_array($fileKTP['type'], $allowedTypesK)) {
            $errors[] = "Format foto KTP tidak valid (hanya JPG/PNG).";
        } elseif ($fileKTP['size'] > $maxFileSizeK) {
            $errors[] = "Ukuran foto KTP terlalu besar (maks 2MB).";
        } else {
            // Buat nama file unik
            $extKTP            = pathinfo($fileKTP['name'], PATHINFO_EXTENSION);
            $newFilenameKTP    = 'ktp_' . $user['id_user'] . '_' . time() . '.' . $extKTP;
            $targetDirKTP      = __DIR__ . "/../assets/uploads/ktp/";
            if (!is_dir($targetDirKTP)) {
                mkdir($targetDirKTP, 0755, true);
            }
            $targetPathKTP     = $targetDirKTP . $newFilenameKTP;

            // Pindahkan file ke folder tujuan
            if (move_uploaded_file($fileKTP['tmp_name'], $targetPathKTP)) {
                // Hapus file lama jika ada
                if (!empty($user['foto_ktp']) && file_exists($targetDirKTP . $user['foto_ktp'])) {
                    unlink($targetDirKTP . $user['foto_ktp']);
                }
                $newFotoKTP = $newFilenameKTP;
            } else {
                $errors[] = "Gagal memindahkan file foto KTP.";
            }
        }
    }

    if (empty($errors)) {
        $updateSQL = "UPDATE `user` SET
                        username        = '$newUsername',
                        email           = '$email',
                        no_hp           = '$no_hp',
                        address         = '$address',
                        profile_picture = '$newProfilePic',
                        foto_ktp        = '$newFotoKTP'
                      WHERE id_user      = " . $user['id_user'];

        if (mysqli_query($conn, $updateSQL)) {
            // Jika username berubah, perbarui session
            if ($newUsername !== $oldUsername) {
                $_SESSION['username'] = $newUsername;
            }
            $success = "Profil berhasil diperbarui.";
            // Reload data user setelah update
            $user = [
                'id_user'         => $user['id_user'],
                'username'        => $newUsername,
                'email'           => $email,
                'no_hp'           => $no_hp,
                'address'         => $address,
                'profile_picture' => $newProfilePic,
                'foto_ktp'        => $newFotoKTP
            ];
        } else {
            $errors[] = "Gagal memperbarui data: " . mysqli_error($conn);
        }
    }

    // Gabungkan semua error menjadi satu string
    if (!empty($errors)) {
        $error = implode("<br>", $errors);
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>caRent</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css" />
    <link rel="stylesheet" href="../assets/css/edit_profile.css" />
</head>

<body style="overflow-y: scroll">
    <?php include '../components/navbar_user.php'; ?>

    <div class="container mt-4">
        <div class="page-header">
            <div class="d-flex align-items-center">
                <i class="fas fa-user-edit fa-2x me-3"></i>
                <div>
                    <h2 class="mb-1 fw-bold">Edit Profil</h2>
                    <p class="mb-0 opacity-75">Perbarui informasi pribadi dan dokumen Anda</p>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="form-card">
                    <div class="card-body p-4">
                        <?php if (!empty($error)): ?>
                            <div class="alert alert-luxury-danger" role="alert">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <?= $error ?>
                            </div>
                        <?php elseif (!empty($success)): ?>
                            <div class="alert alert-luxury-success" role="alert">
                                <i class="fas fa-check-circle me-2"></i>
                                <?= $success ?>
                            </div>
                            <div class="d-flex justify-content-end mb-3">
                                <a href="profile.php" class="btn btn-outline-luxury">
                                    <i class="fas fa-arrow-left me-2"></i>
                                    Kembali
                                </a>
                            </div>

                        <?php endif; ?>

                        <form action="edit_profile.php" method="POST" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5 class="text-warning mb-3">
                                        <i class="fas fa-user me-2"></i>
                                        Informasi Dasar
                                    </h5>

                                    <div class="mb-3">
                                        <label for="username" class="form-label">
                                            <i class="fas fa-user me-2"></i>Username
                                        </label>
                                        <input type="text" name="username" id="username" class="form-control"
                                            value="<?= htmlspecialchars($user['username']) ?>"
                                            placeholder="Masukkan username" />
                                    </div>

                                    <div class="mb-3">
                                        <label for="email" class="form-label">
                                            <i class="fas fa-envelope me-2"></i>Email
                                        </label>
                                        <input type="email" name="email" id="email" class="form-control"
                                            value="<?= htmlspecialchars($user['email']) ?>"
                                            placeholder="Masukkan email" />
                                    </div>

                                    <div class="mb-3">
                                        <label for="no_hp" class="form-label">
                                            <i class="fas fa-phone me-2"></i>No HP
                                        </label>
                                        <input type="text" name="no_hp" id="no_hp" class="form-control"
                                            value="<?= htmlspecialchars($user['no_hp']) ?>"
                                            placeholder="Masukkan nomor HP" />
                                    </div>

                                    <div class="mb-4">
                                        <label for="address" class="form-label">
                                            <i class="fas fa-map-marker-alt me-2"></i>Alamat
                                        </label>
                                        <textarea name="address" id="address" rows="3" class="form-control"
                                            placeholder="Masukkan alamat lengkap"><?= htmlspecialchars($user['address']) ?></textarea>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <h5 class="text-warning mb-3">
                                        <i class="fas fa-images me-2"></i>
                                        Foto & Dokumen
                                    </h5>

                                    <div class="mb-4">
                                        <label class="form-label">
                                            <i class="fas fa-camera me-2"></i>Foto Profil
                                        </label>

                                        <?php if (!empty($user['profile_picture'])): ?>
                                            <div class="image-preview">
                                                <p class="text-warning mb-2">
                                                    <i class="fas fa-image me-2"></i>Foto Saat Ini
                                                </p>
                                                <img src="../assets/uploads/profile/<?= htmlspecialchars($user['profile_picture']) ?>"
                                                    alt="Current Profile" class="current-image profile-preview mb-2" />
                                            </div>
                                        <?php else: ?>
                                            <div class="upload-area">
                                                <i class="fas fa-user-circle upload-icon"></i>
                                                <p class="text-muted mb-0">Belum ada foto profil</p>
                                            </div>
                                        <?php endif; ?>

                                        <input type="file" name="profile_picture" class="form-control" accept="image/*" />
                                        <div class="file-info">
                                            <i class="fas fa-info-circle me-1"></i>
                                            Format JPG/PNG, maksimal 2MB (opsional)
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label">
                                            <i class="fas fa-id-card me-2"></i>Foto KTP
                                        </label>

                                        <?php if (!empty($user['foto_ktp'])): ?>
                                            <div class="image-preview">
                                                <p class="text-warning mb-2">
                                                    <i class="fas fa-id-card me-2"></i>KTP Saat Ini
                                                </p>
                                                <img src="../assets/uploads/ktp/<?= htmlspecialchars($user['foto_ktp']) ?>"
                                                    alt="Current KTP" class="current-image ktp-preview mb-2" />
                                            </div>
                                        <?php else: ?>
                                            <div class="upload-area">
                                                <i class="fas fa-id-card upload-icon"></i>
                                                <p class="text-muted mb-0">Belum ada foto KTP</p>
                                            </div>
                                        <?php endif; ?>

                                        <input type="file" name="foto_ktp" class="form-control" accept="image/*" />
                                        <div class="file-info">
                                            <i class="fas fa-info-circle me-1"></i>
                                            Format JPG/PNG, maksimal 2MB (opsional)
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between mt-4 pt-3" style="border-top: 1px solid rgba(212, 175, 55, 0.2);">
                                <a href="profile.php" class="btn btn-outline-luxury">
                                    <i class="fas fa-arrow-left me-2"></i>
                                    Batal
                                </a>
                                <button type="submit" class="btn btn-luxury">
                                    <i class="fas fa-save me-2"></i>
                                    Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.querySelector('form').addEventListener('submit', function(e) {
            const username = document.getElementById('username').value.trim();
            const email = document.getElementById('email').value.trim();

            if (!username || !email) {
                e.preventDefault();
                alert('Username dan Email harus diisi!');
                return false;
            }
        });
    </script>
</body>

</html>