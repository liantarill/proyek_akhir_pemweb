<?php
session_start();
include "../conn/db.php"; // Ubah path jika perlu

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email    = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // aman
    $address   = mysqli_real_escape_string($conn, $_POST['address']);
    $no_hp    = mysqli_real_escape_string($conn, $_POST['no_hp']);

    // Validasi upload file KTP
    if (!isset($_FILES['ktp']) || $_FILES['ktp']['error'] !== UPLOAD_ERR_OK) {
        header("Location: signup.php?error=Upload KTP gagal");

        die("Upload gagal: " . $_FILES['ktp']['error']);
        exit;
    }

    $ktp     = $_FILES['ktp'];
    $ext     = strtolower(pathinfo($ktp['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png'];

    if (!in_array($ext, $allowed)) {
        header("Location: signup.php?error=Format KTP tidak valid");
        exit;
    }

    $ktpName = uniqid("ktp_") . "." . $ext;
    $uploadDir = "../assets/uploads/ktp/";

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $targetPath = $uploadDir . $ktpName;
    if (!move_uploaded_file($ktp['tmp_name'], $targetPath)) {
        header("Location: signup.php?error=Gagal menyimpan file KTP");
        exit;
    }

    // Simpan ke database
    $query = "INSERT INTO user (username, email, password, address, no_hp, foto_ktp)
              VALUES ('$username', '$email', '$password', '$address', '$no_hp', '$ktpName')";

    if (mysqli_query($conn, $query)) {

        $_SESSION['username'] = $username;
        header("Location: ../user/dashboard.php");
        exit;
    } else {
        header("Location: signup.php?error=Gagal menyimpan ke database");
        exit;
    }
} else {
    header("Location: signup.php");
    exit;
}
