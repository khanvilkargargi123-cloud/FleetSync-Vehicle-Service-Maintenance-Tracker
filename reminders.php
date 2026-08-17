<?php
include 'config.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user'])) {
    header("Location: login.html");
    exit();
}

$email = $_SESSION['user'];

$today = date("Y-m-d");

$sql = "SELECT * FROM bookings
        WHERE user_email='$email'
        AND status='Completed'
        AND next_service IS NOT NULL
        ORDER BY next_service ASC";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Service Reminders | FleetSync</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.0/css/all.min.css">

<link rel="stylesheet" href="css/reminder.css">

</head>

<body>

<nav class="navbar navbar-dark bg-dark shadow">

<div class="container">

<a href="dashboard.php" class="navbar-brand fw-bold">

<i class="fa-solid fa-car-side text-info"></i>
FleetSync

</a>

<a href="dashboard.php" class="btn btn-outline-info">

<i class="fa-solid fa-arrow-left"></i>
Dashboard

</a>

</div>

</nav>


<section class="reminder-header">

<div class="container text-center">

<div class="reminder-icon">

<i class="fa-solid fa-bell"></i>

</div>

<h1>Service Reminders</h1>

<p>
Never miss your vehicle's next scheduled service.
</p>

</div>

</section>


<div class="container py-5">

<div class="row g-4">

<?php

$found = false;

while ($row = mysqli_fetch_assoc($result)) {

    $found = true;

    $nextService = $row['next_service'];

    $daysLeft = floor(
        (strtotime($nextService) - strtotime($today)) / 86400
    );

    if ($daysLeft < 0) {

        $statusClass = "overdue";
        $statusText = "Service Overdue";
        $statusIcon = "fa-triangle-exclamation";

    } elseif ($daysLeft <= 7) {

        $statusClass = "urgent";
        $statusText = "Service Due Soon";
        $statusIcon = "fa-bell";

    } elseif ($daysLeft <= 30) {

        $statusClass = "upcoming";
        $statusText = "Upcoming Service";
        $statusIcon = "fa-calendar-days";

    } else {

        $statusClass = "normal";
        $statusText = "Scheduled";
        $statusIcon = "fa-circle-check";
    }

?>

<div class="col-lg-6">

<div class="reminder-card <?php echo $statusClass; ?>">

<div class="card-top">

<div>

<span class="status-badge">

<i class="fa-solid <?php echo $statusIcon; ?>"></i>

<?php echo $statusText; ?>

</span>

</div>

<div class="booking-id">

Booking #<?php echo $row['id']; ?>

</div>

</div>


<div class="vehicle-section">

<div class="vehicle-icon">

<i class="fa-solid fa-car"></i>

</div>

<div>

<h3>
<?php echo htmlspecialchars($row['vehicle_number']); ?>
</h3>

<p>
<?php echo htmlspecialchars($row['brand']); ?>
<?php echo htmlspecialchars($row['model']); ?>
</p>

</div>

</div>


<div class="service-info">

<div class="info-box">

<i class="fa-solid fa-screwdriver-wrench"></i>

<div>

<small>Service Type</small>

<strong>
<?php echo htmlspecialchars($row['service_type']); ?>
</strong>

</div>

</div>


<div class="info-box">

<i class="fa-solid fa-calendar-check"></i>

<div>

<small>Next Service</small>

<strong>
<?php echo date("d M Y", strtotime($nextService)); ?>
</strong>

</div>

</div>

</div>


<div class="countdown">

<?php

if ($daysLeft < 0) {

    echo "<strong>" . abs($daysLeft) . " days overdue</strong>";

} elseif ($daysLeft == 0) {

    echo "<strong>Service is due today!</strong>";

} elseif ($daysLeft == 1) {

    echo "<strong>Service due tomorrow</strong>";

} else {

    echo "<strong>$daysLeft days remaining</strong>";
}

?>

</div>


<div class="card-actions">

<a href="booking_details.php?id=<?php echo $row['id']; ?>"
class="btn btn-outline-info">

<i class="fa-solid fa-eye"></i>
View Details

</a>

<a href="book_service.php"
class="btn btn-info text-white">

<i class="fa-solid fa-calendar-plus"></i>
Book Service

</a>

</div>

</div>

</div>

<?php

}

if (!$found) {

?>

<div class="col-12">

<div class="empty-reminder">

<i class="fa-regular fa-bell-slash"></i>

<h3>No Service Reminders</h3>

<p>
You currently don't have any scheduled vehicle services.
</p>

<a href="book_service.php" class="btn btn-info text-white">

<i class="fa-solid fa-calendar-plus"></i>

Book Your First Service

</a>

</div>

</div>

<?php } ?>

</div>

</div>


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