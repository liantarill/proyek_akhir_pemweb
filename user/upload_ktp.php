<?php
session_start();
include "../conn/db.php";

if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit;
}

$username = $_SESSION['username'];

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_FILES['ktp'])) {
    header("Location: verifikasi_ktp.php?error=Invalid request");
    exit;
}

$file = $_FILES['ktp'];
$ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
$allowed = ['jpg', 'jpeg', 'png', 'gif'];

if (!in_array($ext, $allowed)) {
    header("Location: verifikasi_ktp.php?error=Format file tidak diizinkan");
    exit;
}

$newName = uniqid('ktp_') . '.' . $ext;
$uploadDir = '../user/uploads/ktp/';
if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

if (!move_uploaded_file($file['tmp_name'], $uploadDir . $newName)) {
    header("Location: verifikasi_ktp.php?error=Gagal upload");
    exit;
}

mysqli_query($conn, "UPDATE penyewa SET foto_ktp = '$newName' WHERE username = '$username'");

header("Location: verifikasi_ktp.php?success=Foto KTP berhasil diupload");
exit;
