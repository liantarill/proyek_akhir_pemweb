<?php
session_start();
include "../conn/db.php";

if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit;
}

$username = $_SESSION['username'];

if (!isset($_POST['upload'])) {
    header("Location: profile.php");
    exit;
}

if (!isset($_FILES['foto']) || $_FILES['foto']['error'] !== UPLOAD_ERR_OK) {
    header("Location: dashboard.php?error=File upload bermasalah atau tidak dipilih");
    exit;
}

$foto = $_FILES['foto']['name'];
$tmp = $_FILES['foto']['tmp_name'];
$ext = strtolower(pathinfo($foto, PATHINFO_EXTENSION));
$allowed = ['jpg', 'jpeg', 'png', 'gif'];

if (!in_array($ext, $allowed)) {
    header("Location: dashboard.php?error=Format file tidak diizinkan");
    exit;
}

$namaBaru =  uniqid("profile_") . "." . $ext;
$uploadDir = '../assets/uploads/profile/';



if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

$path = $uploadDir . $namaBaru;

if (!move_uploaded_file($tmp, $path)) {
    header("Location: dashboard.php?error=Gagal memindahkan file upload");
    exit;
}

// Update foto user di database
mysqli_query($conn, "UPDATE user SET profile_picture='$namaBaru' WHERE username='$username'");

header("Location: dashboard.php?success=Foto berhasil diupload");
exit;
