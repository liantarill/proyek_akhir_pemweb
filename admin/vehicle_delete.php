<?php
include '../conn/db.php';

$id = $_GET['id'];
mysqli_query($conn, "DELETE FROM vehicle WHERE id_vehicle = $id");

header("Location: vehicle_data.php?success=hapus");
exit;
