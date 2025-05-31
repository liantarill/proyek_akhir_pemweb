<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Sign Up</title>
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card shadow-sm rounded-4">
                    <div class="card-body">
                        <h3 class="text-center mb-4">Sign Up</h3>

                        <form method="POST" action="register.php" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" name="username" class="form-control" id="username" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="formlabel">Email</label>
                                <input type="email" name="email"
                                    class="form-control" id="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" id="password" required>
                            </div>
                            <div class="mb-3">
                                <label for="address" class="form-label">Alamat</label>
                                <textarea name="address" id="address" class="form-control"></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="no_hp" class="form-label">No HP</label>
                                <input type="text" name="no_hp" id="no_hp" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label for="ktp" class="form-label">Upload Foto KTP</label>
                                <input type="file" name="ktp" id="ktp" class="form-control" accept=".jpg,.jpeg,.png" required />
                            </div>

                            <!-- role dihapus dari form -->
                            <button type="submit" class="btn btn-primary w-100">Sign Up</button>
                        </form>



                        <div class="mt-3 text-center">
                            <span>Sudah punya akun?</span>
                            <a href="index.php">Login</a>
                        </div>

                        <!-- Error alert -->
                        <?php if (isset($_GET['error'])): ?>
                            <div class="alert alert-danger mt-3">Gagal membuat akun. Silakan coba lagi!</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>