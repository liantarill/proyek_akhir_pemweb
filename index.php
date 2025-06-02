<?php
session_start();

if (isset($_SESSION['login']) && $_SESSION['login'] === true) {
    if ($_SESSION['role'] === 'admin') {
        header("Location: admin/dashboard.php");
        exit();
    } elseif ($_SESSION['role'] === 'user') {
        header("Location: user/dashboard.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>caRent</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/index.css">

</head>

<body>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5 col-lg-4">
                <div class="card login-card">
                    <div class="card-header">

                        <h3 class="mb-0 fw-bold">caRent</h3>
                        <p class="mb-0 opacity-75">Selamat Datang Kembali</p>
                    </div>

                    <div class="card-body">
                        <form method="POST" action="auth/login.php">
                            <div class="form-floating">
                                <input type="text" name="username" class="form-control" id="username"
                                    placeholder="Username" required>
                                <label for="username">
                                    <i class="fas fa-user me-2"></i>Username
                                </label>
                            </div>

                            <div class="form-floating">
                                <input type="password" name="password" class="form-control" id="password"
                                    placeholder="Password" required>
                                <label for="password">
                                    <i class="fas fa-lock me-2"></i>Password
                                </label>
                            </div>

                            <button type="submit" class="btn btn-login w-100">
                                <i class="fas fa-sign-in-alt me-2"></i>
                                LOGIN
                            </button>
                        </form>

                        <div class="register-link">
                            <span class="text-light">Belum memiliki akun?</span>
                            <a href="auth/register-page.php" class="ms-1">
                                <i class="fas fa-user-plus me-1"></i>
                                Register Now
                            </a>
                        </div>

                        <?php if (isset($_GET['error'])): ?>
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>Access Ditolak!</strong> Username atau Password Salah.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>