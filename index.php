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
    <style>
        body {
            background: #111827;
            min-height: 100vh;
            display: flex;
            align-items: center;
            position: relative;
            overflow-x: hidden;
        }



        .login-card {
            background: rgba(17, 24, 39, 0.85);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(212, 175, 55, 0.3);
            border-radius: 8px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.5);
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }


        .card-header {
            background: linear-gradient(135deg, #d4af37 0%, #aa8c2c 100%);
            color: #111827;
            text-align: center;
            padding: 2rem 1.5rem;
            border: none;
            position: relative;
        }

        .card-body {
            padding: 2.5rem;
            color: #e5e7eb;
        }

        .form-floating {
            margin-bottom: 1.5rem;
        }

        .form-control {
            border: 1px solid rgba(212, 175, 55, 0.3);
            border-radius: 4px;
            padding: 1rem;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.05);
            color: #e5e7eb !important;
        }

        .form-control:focus {
            border-color: #d4af37;
            box-shadow: 0 0 0 0.2rem rgba(212, 175, 55, 0.25);
            background: rgba(255, 255, 255, 0.1);
        }

        .form-floating>label {
            color: #9ca3af;
            font-weight: 400;

        }

        .form-floating>.form-control:focus~label,
        .form-floating>.form-control:not(:placeholder-shown)~label {
            color: #d4af37;
        }



        .btn-login {
            background: linear-gradient(135deg, #d4af37 0%, #aa8c2c 100%);
            border: none;
            border-radius: 4px;
            padding: 1rem;
            font-weight: 600;
            font-size: 1rem;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            color: #111827;
            ;
        }



        .register-link {
            text-align: center;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid rgba(212, 175, 55, 0.2);
        }

        .register-link a {
            color: #d4af37;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .register-link a:hover {
            color: #f3d77f;
            text-decoration: none;
        }

        .alert {
            border-radius: 4px;
            border: none;
            margin-top: 1.5rem;
            animation: slideIn 0.3s ease;
        }

        .alert-danger {
            background: linear-gradient(135deg, #991b1b, #b91c1c);
            color: white;
            border-left: 4px solid #f87171;
        }
    </style>
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
                            <span class="text-light">First time client?</span>
                            <a href="auth/register-page.php" class="ms-1">
                                <i class="fas fa-user-plus me-1"></i>
                                Register Now
                            </a>
                        </div>

                        <?php if (isset($_GET['error'])): ?>
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>Access Denied!</strong> Invalid credentials provided.
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