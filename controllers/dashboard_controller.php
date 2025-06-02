<?php
include '../includes/auth_check.php';


$user_id = $user['id_user'];

$rental_query = mysqli_query($conn, "
    SELECT 
        COUNT(*) AS total,
        COALESCE(SUM(rental_status = 'Terverifikasi'), 0) AS terverifikasi,
        COALESCE(SUM(rental_status = 'Menunggu Verifikasi'), 0) AS menunggu,
        COALESCE(SUM(rental_status = 'Ditolak'), 0) AS ditolak
    FROM rental
    WHERE id_user = $user_id
");
$rental_stats = mysqli_fetch_assoc($rental_query);
