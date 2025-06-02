<?php
include '../includes/auth_check.php';

$query = "
    SELECT rental.*, vehicle.merk, vehicle.tipe
    FROM rental
    JOIN vehicle ON rental.id_vehicle = vehicle.id_vehicle
    WHERE rental.id_user = $id_user
    ORDER BY rental.rental_date DESC
";

$result = mysqli_query($conn, $query);
