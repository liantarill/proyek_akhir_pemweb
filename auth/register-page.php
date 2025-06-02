<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>caRent</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/register-page.css">

</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5 col-lg-7">
                <div class="card signup-card">
                    <div class="card-header">
                        <h3 class="mb-0 fw-bold">caRent</h3>
                        <p class="mb-0 opacity-75">Buat Akun Baru</p>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="register.php" enctype="multipart/form-data">

                            <div class="form-floating">
                                <input type="text" name="username" id="username" class="form-control" placeholder="Username" required>
                                <label for="username"><i class="fas fa-user me-2"></i>Username</label>
                            </div>

                            <div class="form-floating">
                                <input type="email" name="email" id="email" class="form-control" placeholder="Email" required>
                                <label for="email"><i class="fas fa-envelope me-2"></i>Email</label>
                            </div>

                            <div class="form-floating">
                                <input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
                                <label for="password"><i class="fas fa-lock me-2"></i>Password</label>
                            </div>

                            <div class="form-floating">
                                <textarea name="address" id="address" class="form-control" placeholder="Alamat" style="height: 100px"></textarea>
                                <label for="address"><i class="fas fa-map-marker-alt me-2"></i>Alamat</label>
                            </div>

                            <div class="form-floating">
                                <input type="text" name="no_hp" id="no_hp" class="form-control" placeholder="No HP">
                                <label for="no_hp"><i class="fas fa-phone me-2"></i>No HP</label>
                            </div>

                            <div class="mb-3">
                                <label for="ktp" class="form-label text-light"><i class="fas fa-id-card me-2"></i>Upload Foto KTP</label>
                                <input type="file" name="ktp" id="ktp" class="form-control" accept=".jpg,.jpeg,.png" required />
                            </div>

                            <button type="submit" class="btn btn-signup w-100">
                                <i class="fas fa-user-plus me-2"></i>Sign Up
                            </button>
                        </form>

                        <div class="login-link">
                            <span class="text-light">Sudah punya akun?</span>
                            <a href="../index.php"><i class="fas fa-sign-in-alt me-1"></i>Login</a>
                        </div>

                        <?php if (isset($_GET['error'])): ?>
                            <div class="alert alert-danger mt-3">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                Gagal membuat akun. Silakan coba lagi!
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>