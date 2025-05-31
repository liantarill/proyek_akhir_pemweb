<?php
include "../conn/db.php";

$id = $_GET['id'];
$aksi = $_GET['aksi'];

$status = ($aksi === 'verifikasi') ? 'Terverifikasi' : 'Ditolak';

mysqli_query($conn, "UPDATE rental SET rental_status = '$status' WHERE id_rental = $id");

header("Location: payment_data.php");
exit;
