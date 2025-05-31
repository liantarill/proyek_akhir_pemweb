<?php
include '../conn/db.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = (int)$_GET['id'];

    mysqli_query($conn, "DELETE FROM users WHERE id = $id");

    header("Location:dashboard.php");
    exit;
} else {
    header("Location: dashboard.php?error=invalid_id");
    exit;
}
