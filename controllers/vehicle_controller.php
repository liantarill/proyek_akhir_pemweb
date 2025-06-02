<?php

include '../includes/auth_check.php';

$vehicles = mysqli_query($conn, "SELECT * FROM vehicle WHERE status = 'Tersedia'");
