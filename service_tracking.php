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

/* Get booking ID */
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$sql = "SELECT *
        FROM bookings
        WHERE id = ?
        AND user_email = ?";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "is", $id, $email);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$booking = mysqli_fetch_assoc($result);

if (!$booking) {
    echo "Booking not found.";
    exit();
}

/* Current service stage */
$currentStage = $booking['service_stage'];

/*
 * Keep tracking synchronized with booking status.
 * If admin marks the booking as Completed,
 * tracking should always show Completed.
 */
if ($booking['status'] === 'Completed') {

    $currentStage = 'Completed';

} elseif ($booking['status'] === 'Cancelled') {

    $currentStage = 'Cancelled';

}

/* All service stages */
$stages = [
    "Booking Confirmed",
    "Vehicle Received",
    "Service In Progress",
    "Quality Check",
    "Ready for Pickup",
    "Completed"
];

$currentIndex = array_search($currentStage, $stages);

if ($currentIndex === false) {
    $currentIndex = 0;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>Service Tracking | FleetSync</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css"
rel="stylesheet">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.0/css/all.min.css">

<link rel="stylesheet"
href="css/service_tracking.css">

</head>

<body>

<!-- NAVBAR -->

<nav class="navbar navbar-dark bg-dark shadow">

<div class="container">

<a class="navbar-brand fw-bold"
href="dashboard.php">

<i class="fa-solid fa-car-side text-info"></i>

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


<!-- HERO -->

<section class="tracking-hero">

<div class="container text-center">

<div class="hero-icon">

<i class="fa-solid fa-location-dot"></i>

</div>

<h1>Service Tracking</h1>

<p>
Track the progress of your vehicle service in real time.
</p>

</div>

</section>


<!-- MAIN -->

<div class="container py-5">

<div class="tracking-card">

<!-- BOOKING INFORMATION -->

<div class="booking-summary">

<div>

<span>Vehicle</span>

<h3>
<?php echo htmlspecialchars($booking['vehicle_number']); ?>
</h3>

</div>

<div>

<span>Service</span>

<h3>
<?php echo htmlspecialchars($booking['service_type']); ?>
</h3>

</div>

<div>

<span>Booking Date</span>

<h3>
<?php
echo date(
"d M Y",
strtotime($booking['booking_date'])
);
?>
</h3>

</div>

</div>


<hr>


<!-- CURRENT STATUS -->

<div class="current-status text-center">

<p>Current Service Status</p>

<h2>

<i class="fa-solid fa-circle-check text-info"></i>

<?php echo htmlspecialchars($currentStage); ?>

</h2>

</div>


<!-- TRACKING -->

<div class="tracking-container">

<?php foreach ($stages as $index => $stage) { ?>

<?php

if ($index < $currentIndex) {

    $class = "completed";

} elseif ($index == $currentIndex) {

    $class = "active";

} else {

    $class = "upcoming";

}

?>

<div class="tracking-step <?php echo $class; ?>">

<div class="step-circle">

<?php if ($index < $currentIndex) { ?>

<i class="fa-solid fa-check"></i>

<?php } elseif ($index == $currentIndex) { ?>

<i class="fa-solid fa-car"></i>

<?php } else { ?>

<i class="fa-solid fa-clock"></i>

<?php } ?>

</div>

<div class="step-content">

<h4>
<?php echo $stage; ?>
</h4>

<?php if ($index < $currentIndex) { ?>

<p>Completed</p>

<?php } elseif ($index == $currentIndex) { ?>

<p>Current stage</p>

<?php } else { ?>

<p>Upcoming</p>

<?php } ?>

</div>

</div>

<?php } ?>

</div>


<!-- ACTIONS -->

<div class="text-center mt-5">

<a href="booking_details.php?id=<?php echo $booking['id']; ?>"
class="btn btn-outline-dark me-2">

<i class="fa-solid fa-eye"></i>

Booking Details

</a>

<a href="my_booking.php"
class="btn btn-info text-white">

<i class="fa-solid fa-calendar-check"></i>

My Bookings

</a>

</div>

</div>

</div>


<!-- FOOTER -->

<footer class="bg-dark text-white text-center py-4">

<p class="mb-1">

<i class="fa-solid fa-car-side"></i>

FleetSync | Vehicle Service & Maintenance Tracker

</p>

<small>

© 2026 FleetSync. All Rights Reserved.

</small>

</footer>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>