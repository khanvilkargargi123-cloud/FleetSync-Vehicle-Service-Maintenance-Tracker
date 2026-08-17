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

/* Get completed service history */
$sql = "SELECT *
        FROM bookings
        WHERE user_email = ?
        AND status = 'Completed'
        ORDER BY booking_date DESC, id DESC";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Service History | FleetSync</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.0/css/all.min.css">

<link rel="stylesheet" href="css/service_history.css">

</head>

<body>

<!-- NAVBAR -->

<nav class="navbar navbar-dark fleet-navbar">

<div class="container">

<a class="navbar-brand fw-bold" href="dashboard.php">

<i class="fa-solid fa-car-side"></i>

FleetSync

</a>

<div>

<a href="my_vehicles.php"
class="btn btn-outline-light me-2">

<i class="fa-solid fa-car"></i>

My Vehicles

</a>

<a href="dashboard.php"
class="btn btn-info text-white">

<i class="fa-solid fa-house"></i>

Dashboard

</a>

</div>

</div>

</nav>


<!-- HEADER -->

<section class="history-hero">

<div class="container text-center">

<div class="hero-icon">

<i class="fa-solid fa-clock-rotate-left"></i>

</div>

<h1>Service History</h1>

<p>
Keep track of your vehicle's previous maintenance and service records.
</p>

</div>

</section>


<div class="container py-5">


<!-- INTRO -->

<div class="history-heading">

<div>

<h2>

<i class="fa-solid fa-file-circle-check"></i>

Your Service Records

</h2>

<p>
A complete record of your completed vehicle services.
</p>

</div>

<a href="book_service.php"
class="btn btn-info text-white">

<i class="fa-solid fa-calendar-plus"></i>

Book New Service

</a>

</div>


<?php if (mysqli_num_rows($result) > 0) { ?>


<!-- SERVICE RECORDS -->

<div class="row g-4">

<?php while ($service = mysqli_fetch_assoc($result)) { ?>

<div class="col-lg-6">


<div class="history-card">


<!-- TOP -->

<div class="history-top">

<div class="service-icon">

<i class="fa-solid fa-screwdriver-wrench"></i>

</div>

<div>

<span class="completed-badge">

<i class="fa-solid fa-circle-check"></i>

Completed

</span>

<h3>

<?php echo htmlspecialchars($service['service_type']); ?>

</h3>

</div>

</div>


<hr>


<!-- VEHICLE -->

<div class="vehicle-section">

<div class="vehicle-symbol">

<i class="fa-solid fa-car"></i>

</div>

<div>

<small>Vehicle</small>

<strong>

<?php echo htmlspecialchars($service['vehicle_number']); ?>

</strong>

<p>

<?php echo htmlspecialchars($service['brand']); ?>

<?php echo htmlspecialchars($service['model']); ?>

</p>

</div>

</div>


<!-- DETAILS -->

<div class="service-details">


<div class="detail-box">

<i class="fa-solid fa-calendar-check"></i>

<span>

<small>Service Date</small>

<strong>

<?php

echo date(
"d M Y",
strtotime($service['booking_date'])
);

?>

</strong>

</span>

</div>


<div class="detail-box">

<i class="fa-solid fa-gauge-high"></i>

<span>

<small>KM Reading</small>

<strong>

<?php

echo number_format(
(int)$service['km_reading']
);

?>

 km

</strong>

</span>

</div>


<div class="detail-box">

<i class="fa-solid fa-calendar-days"></i>

<span>

<small>Next Service</small>

<strong>

<?php

if (!empty($service['next_service'])) {

echo date(
"d M Y",
strtotime($service['next_service'])
);

} else {

echo "Not scheduled";

}

?>

</strong>

</span>

</div>


<div class="detail-box">

<i class="fa-solid fa-clock"></i>

<span>

<small>Booking Time</small>

<strong>

<?php

echo date(
"h:i A",
strtotime($service['booking_time'])
);

?>

</strong>

</span>

</div>

</div>


<!-- NOTES -->

<?php if (!empty($service['notes'])) { ?>

<div class="service-notes">

<strong>

<i class="fa-solid fa-note-sticky"></i>

Service Notes

</strong>

<p>

<?php echo nl2br(
htmlspecialchars($service['notes'])
); ?>

</p>

</div>

<?php } ?>


<!-- FOOTER -->

<div class="history-footer">

<span>

<i class="fa-solid fa-shield-heart"></i>

FleetSync Service Record

</span>

<a href="booking_details.php?id=<?php echo $service['id']; ?>"
class="btn btn-outline-info btn-sm">

<i class="fa-solid fa-eye"></i>

View Details

</a>

</div>


</div>

</div>

<?php } ?>

</div>


<?php } else { ?>


<!-- EMPTY -->

<div class="empty-history">

<div class="empty-history-icon">

<i class="fa-solid fa-clock-rotate-left"></i>

</div>

<h3>No Service History Yet</h3>

<p>

Your completed services will appear here automatically.

</p>

<a href="book_service.php"
class="btn btn-info text-white">

<i class="fa-solid fa-calendar-plus"></i>

Book Your First Service

</a>

</div>


<?php } ?>

</div>


<!-- FOOTER -->

<footer class="fleet-footer">

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