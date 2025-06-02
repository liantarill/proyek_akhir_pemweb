<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

include "../conn/db.php";

if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit;
}

$username = $_SESSION['username'];
$id_user = $_SESSION['id'];

$query = mysqli_query($conn, "SELECT * FROM user WHERE username='$username'");
$user = mysqli_fetch_assoc($query);

if (!$user) {
    session_destroy();
    header("Location: ../index.php");
    exit;
}
