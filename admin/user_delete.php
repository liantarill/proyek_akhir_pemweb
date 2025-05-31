<?php
include '../conn/db.php';

$id = $_GET['id'];
mysqli_query($conn, "DELETE FROM user WHERE id_user = $id");

header("Location: user_data.php?success=hapus");
exit;
