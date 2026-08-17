<?php
include 'config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user'])) {
    header("Location: login.html");
    exit();
}

$email = $_SESSION['user'];

$sql = "SELECT * FROM bookings
        WHERE user_email = ?
        ORDER BY id DESC";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

/* Remove duplicate vehicles */
$vehicles = [];

while ($row = mysqli_fetch_assoc($result)) {

    $vehicleNumber = strtoupper(trim($row['vehicle_number']));

    if (!isset($vehicles[$vehicleNumber])) {
        $vehicles[$vehicleNumber] = $row;
    } else {
        /*
         * Keep the latest booking information.
         */
        if ($row['id'] > $vehicles[$vehicleNumber]['id']) {
            $vehicles[$vehicleNumber] = $row;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>My Vehicles | FleetSync</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.0/css/all.min.css">

<link rel="stylesheet" href="css/my_vehicles.css">

</head>

<body>

<!-- NAVBAR -->

<nav class="navbar navbar-dark navbar-custom">

<div class="container">

<a class="navbar-brand fw-bold" href="dashboard.php">

<i class="fa-solid fa-car-side"></i>

FleetSync

</a>

<div>

<a href="dashboard.php" class="btn btn-outline-light me-2">

<i class="fa-solid fa-house"></i>

Dashboard

</a>

<a href="book_service.php" class="btn btn-info text-white">

<i class="fa-solid fa-plus"></i>

Book Service

</a>

</div>

</div>

</nav>


<!-- HERO -->

<section class="vehicle-hero">

<div class="container text-center">

<div class="hero-icon">

<i class="fa-solid fa-car"></i>

</div>

<h1>My Vehicles</h1>

<p>
Manage your registered vehicles and keep track of their maintenance.
</p>

</div>

</section>


<!-- VEHICLES -->

<div class="container py-5">

<div class="section-heading">

<div>

<h2>

<i class="fa-solid fa-car-rear"></i>

Your Vehicles

</h2>

<p>
All vehicles associated with your FleetSync account.
</p>

</div>

<a href="book_service.php" class="btn btn-info text-white">

<i class="fa-solid fa-plus"></i>

Add Vehicle

</a>

</div>


<?php if (count($vehicles) > 0) { ?>

<div class="row g-4">

<?php foreach ($vehicles as $vehicle) { ?>

<div class="col-lg-6 col-xl-4">

<div class="vehicle-card">


<!-- VEHICLE IMAGE -->

<div class="vehicle-image">

<?php if (!empty($vehicle['image']) &&
file_exists("uploads/" . $vehicle['image'])) { ?>

<img
src="uploads/<?php echo htmlspecialchars($vehicle['image']); ?>"
alt="Vehicle">

<?php } else { ?>

<i class="fa-solid fa-car"></i>

<?php } ?>

</div>


<div class="vehicle-content">


<!-- VEHICLE NUMBER -->

<div class="vehicle-number">

<?php echo htmlspecialchars($vehicle['vehicle_number']); ?>

</div>


<h3>

<?php echo htmlspecialchars($vehicle['brand']); ?>

<?php echo htmlspecialchars($vehicle['model']); ?>

</h3>


<span class="vehicle-type">

<i class="fa-solid fa-car-side"></i>

<?php echo htmlspecialchars($vehicle['vehicle_type']); ?>

</span>


<hr>


<!-- DETAILS -->

<div class="vehicle-info">

<div>

<i class="fa-solid fa-gauge-high"></i>

<span>

<strong>Current KM</strong>

<?php echo number_format((int)$vehicle['km_reading']); ?> km

</span>

</div>


<div>

<i class="fa-solid fa-calendar-check"></i>

<span>

<strong>Last Service</strong>

<?php

if (!empty($vehicle['last_service'])) {

echo date(
"d M Y",
strtotime($vehicle['last_service'])
);

} else {

echo "Not available";

}

?>

</span>

</div>


<div>

<i class="fa-solid fa-calendar-days"></i>

<span>

<strong>Next Service</strong>

<?php

if (!empty($vehicle['next_service'])) {

echo date(
"d M Y",
strtotime($vehicle['next_service'])
);

} else {

echo "Not scheduled";

}

?>

</span>

</div>

</div>


<!-- ACTIONS -->

<div class="vehicle-actions">

<a
href="book_service.php"
class="btn btn-info text-white">

<i class="fa-solid fa-screwdriver-wrench"></i>

Book Service

</a>


<a
href="service_history.php"
class="btn btn-outline-dark">

<i class="fa-solid fa-clock-rotate-left"></i>

Service History

</a>

</div>


</div>

</div>

</div>

<?php } ?>

</div>


<?php } else { ?>


<!-- EMPTY STATE -->

<div class="empty-state">

<div class="empty-icon">

<i class="fa-solid fa-car"></i>

</div>

<h3>No Vehicles Added Yet</h3>

<p>

You haven't registered a vehicle with FleetSync yet.

</p>

<a href="book_service.php"
class="btn btn-info text-white">

<i class="fa-solid fa-plus"></i>

Add Your First Vehicle

</a>

</div>


<?php } ?>

</div>


<!-- FOOTER -->

<footer class="footer">

<div class="container text-center">

<p>

<i class="fa-solid fa-car-side"></i>

FleetSync | Vehicle Service & Maintenance Tracker

</p>

<small>

© 2026 FleetSync. All Rights Reserved.

</small>

</div>

</footer>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>