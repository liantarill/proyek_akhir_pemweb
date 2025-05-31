<?php
session_start();
include '../conn/db.php';

$username = $_POST['username'];
$password = $_POST['password'];

$queryAdmin = "SELECT * FROM admin WHERE username = '$username'";
$resultAdmin = mysqli_query($conn, $queryAdmin);
$admin = mysqli_fetch_assoc($resultAdmin);


$queryUser = "SELECT * FROM user WHERE username = '$username'";
$resultUser = mysqli_query($conn, $queryUser);
$user = mysqli_fetch_assoc($resultUser);



if ($admin && password_verify($password, $admin['password'])) {
    $_SESSION['id'] = (int) $admin['id_admin'];
    $_SESSION['username'] = $admin['username'];
    $_SESSION['role'] = 'admin';
    $_SESSION['login'] = true;


    header("Location: ../admin/dashboard.php");
    exit;
} else if ($user && password_verify($password, $user['password'])) {
    $_SESSION['id'] = (int) $user['id_user'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['role'] = 'user';
    $_SESSION['login'] = true;


    header("Location: ../user/dashboard.php");

    exit;
} else {
    header("Location: ../index.php?error=1");
    exit;
}
