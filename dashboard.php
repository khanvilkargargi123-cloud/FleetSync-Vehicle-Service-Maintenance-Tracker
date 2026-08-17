<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.html");
    exit();
}

include 'config.php';

$email = $_SESSION['user'];

/* Dashboard Counts */

// Total Bookings
$result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM bookings WHERE user_email='$email'");
$row = mysqli_fetch_assoc($result);
$totalBookings = $row['total'];

// Pending
$result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM bookings WHERE user_email='$email' AND status='Pending'");
$row = mysqli_fetch_assoc($result);
$pendingBookings = $row['total'];

// Completed
$result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM bookings WHERE user_email='$email' AND status='Completed'");
$row = mysqli_fetch_assoc($result);
$completedBookings = $row['total'];

// Vehicles
$result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM vehicles WHERE user_email='$email'");
$row = mysqli_fetch_assoc($result);
$totalVehicles = $row['total'];
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Dashboard | FleetSync</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.0/css/all.min.css">

<link rel="stylesheet" href="css/dashboard.css">

</head>

<body>

<!-- Navbar -->

<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow">

<div class="container">

<a class="navbar-brand fw-bold" href="profile.php">

<i class="fa-solid fa-car-side text-info"></i>

FleetSync

</a>

<div>

<a href="profile.php" class="btn btn-info text-white me-2">

<i class="fa-solid fa-user"></i>

Profile

</a>

<a href="logout.php" class="btn btn-danger">

<i class="fa-solid fa-right-from-bracket"></i>

Logout

</a>

</div>

</div>

</nav>

<!-- Welcome Banner -->

<section class="hero-section">

<div class="container text-center">

<h1>

Welcome to FleetSync 🚗

</h1>

<p>

Manage your vehicles, bookings and maintenance in one place.

</p>

</div>

</section>

<div class="container py-5">

<?php
/* Service Reminder */

$today = date("Y-m-d");

$reminderSql = "SELECT *
                FROM bookings
                WHERE user_email = ?
                AND next_service IS NOT NULL
                AND next_service <= DATE_ADD(?, INTERVAL 30 DAY)
                ORDER BY next_service ASC";

$reminderStmt = mysqli_prepare($conn, $reminderSql);
mysqli_stmt_bind_param($reminderStmt, "ss", $email, $today);
mysqli_stmt_execute($reminderStmt);

$reminderResult = mysqli_stmt_get_result($reminderStmt);
?>

<?php if (mysqli_num_rows($reminderResult) > 0) { ?>

<div class="alert alert-warning shadow-sm mb-5">

    <h4 class="alert-heading">
        <i class="fa-solid fa-bell"></i>
        Service Reminder
    </h4>

    <hr>

    <?php while ($reminder = mysqli_fetch_assoc($reminderResult)) {

        $nextService = $reminder['next_service'];

        $daysLeft = floor(
            (strtotime($nextService) - strtotime($today)) / 86400
        );

    ?>

    <div class="mb-3">

        <strong>
            <i class="fa-solid fa-car"></i>
            <?php echo htmlspecialchars($reminder['vehicle_number']); ?>
        </strong>

        <br>

        <?php if ($daysLeft < 0) { ?>

            <span class="text-danger fw-bold">
                Service is overdue by <?php echo abs($daysLeft); ?> days.
            </span>

        <?php } elseif ($daysLeft == 0) { ?>

            <span class="text-danger fw-bold">
                Service is due today!
            </span>

        <?php } elseif ($daysLeft == 1) { ?>

            <span class="text-warning-emphasis fw-bold">
                Service is due tomorrow.
            </span>

        <?php } else { ?>

            <span>
                Service is due in
                <strong><?php echo $daysLeft; ?> days</strong>.
            </span>

        <?php } ?>

        <br>

        <small>
            Next Service:
            <strong>
                <?php echo date("d M Y", strtotime($nextService)); ?>
            </strong>
        </small>

    </div>

    <?php } ?>

    <a href="reminders.php"
       class="btn btn-warning btn-sm">

        <i class="fa-solid fa-bell"></i>
        View All Reminders

    </a>

</div>

<?php } ?>

<div class="row g-4">

<div class="col-lg-3 col-md-6">

<div class="dashboard-card">

<i class="fa-solid fa-calendar-check text-info"></i>

<h5>Total Bookings</h5>

<h2><?php echo $totalBookings; ?></h2>

</div>

</div>

<div class="col-lg-3 col-md-6">

<div class="dashboard-card">

<i class="fa-solid fa-hourglass-half text-warning"></i>

<h5>Pending</h5>

<h2><?php echo $pendingBookings; ?></h2>

</div>

</div>

<div class="col-lg-3 col-md-6">

<div class="dashboard-card">

<i class="fa-solid fa-circle-check text-success"></i>

<h5>Completed</h5>

<h2><?php echo $completedBookings; ?></h2>

</div>

</div>

<div class="col-lg-3 col-md-6">

<div class="dashboard-card">

<i class="fa-solid fa-car text-primary"></i>

<h5>Vehicles</h5>

<h2><?php echo $totalVehicles; ?></h2>

</div>

</div>

</div>

<!-- Recent Bookings -->

<div class="recent-bookings mt-5">

<h3 class="mb-4">

<i class="fa-solid fa-clock-rotate-left text-info"></i>

Recent Bookings

</h3>

<div class="table-responsive">

<table class="table table-hover align-middle text-center">

<thead>

<tr>

<th>Vehicle</th>
<th>Service</th>
<th>Date</th>
<th>Status</th>
<th>Action</th>

</tr>

</thead>

<tbody>

<?php

$recent = mysqli_query($conn,"
    SELECT * FROM bookings
    WHERE user_email='$email'
    AND TRIM(LOWER(status)) != 'cancelled'
    ORDER BY id DESC
    LIMIT 5
");

if(mysqli_num_rows($recent)>0){

while($row=mysqli_fetch_assoc($recent)){

?>

<tr>

<td><?php echo $row['vehicle_number']; ?></td>

<td><?php echo $row['service_type']; ?></td>

<td><?php echo $row['booking_date']; ?></td>

<td>

<?php
$status = trim($row['status']);

if ($status == "Pending") {

    echo "<span class='badge bg-warning text-dark'>Pending</span>";

} elseif ($status == "Confirmed") {

    echo "<span class='badge bg-info text-dark'>Confirmed</span>";

} elseif ($status == "Completed") {

    echo "<span class='badge bg-success'>Completed</span>";

} else {

    echo "<span class='badge bg-secondary'>" .
         htmlspecialchars($status) .
         "</span>";
}
?>

</td>

<td>

<a href="booking_details.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-info text-white">

View

</a>

</td>

</tr>

<?php

}

}else{

?>

<tr>

<td colspan="5">

No bookings found.

</td>

</tr>

<?php } ?>

</tbody>

</table>

</div>

</div>


<!-- Maintenance Schedule -->

<div class="maintenance-section mt-5">

    <h3 class="mb-4">
        <i class="fa-solid fa-screwdriver-wrench text-info"></i>
        Maintenance Schedule
    </h3>

    <?php
    $scheduleSql = "SELECT vehicle_number, service_type, next_service
                    FROM bookings
                    WHERE user_email = ?
                    AND next_service IS NOT NULL
                    ORDER BY next_service ASC";

    $scheduleStmt = mysqli_prepare($conn, $scheduleSql);
    mysqli_stmt_bind_param($scheduleStmt, "s", $email);
    mysqli_stmt_execute($scheduleStmt);

    $scheduleResult = mysqli_stmt_get_result($scheduleStmt);
    ?>

    <?php if (mysqli_num_rows($scheduleResult) > 0) { ?>

        <div class="table-responsive">

            <table class="table table-hover align-middle text-center">

                <thead>
                    <tr>
                        <th>Vehicle</th>
                        <th>Maintenance / Service</th>
                        <th>Next Service</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>

                <?php while ($schedule = mysqli_fetch_assoc($scheduleResult)) {

                    $serviceDate = $schedule['next_service'];

                    $daysLeft = floor(
                        (strtotime($serviceDate) - strtotime($today)) / 86400
                    );

                ?>

                    <tr>

                        <td>
                            <i class="fa-solid fa-car text-primary"></i>
                            <?php echo htmlspecialchars($schedule['vehicle_number']); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($schedule['service_type']); ?>
                        </td>

                        <td>
                            <?php echo date("d M Y", strtotime($serviceDate)); ?>
                        </td>

                        <td>

                            <?php if ($daysLeft < 0) { ?>

                                <span class="badge bg-danger">
                                    Overdue
                                </span>

                            <?php } elseif ($daysLeft == 0) { ?>

                                <span class="badge bg-danger">
                                    Due Today
                                </span>

                            <?php } elseif ($daysLeft <= 30) { ?>

                                <span class="badge bg-warning text-dark">
                                    Due Soon
                                </span>

                            <?php } else { ?>

                                <span class="badge bg-success">
                                    Scheduled
                                </span>

                            <?php } ?>

                        </td>

                    </tr>

                <?php } ?>

                </tbody>

            </table>

        </div>

    <?php } else { ?>

        <div class="alert alert-info">
            <i class="fa-solid fa-circle-info"></i>
            No maintenance schedules available.
        </div>

    <?php } ?>

</div>

<!-- Quick Actions -->

<div class="text-center mt-5">

<a href="book_service.php" class="btn btn-info btn-lg m-2">

<i class="fa-solid fa-calendar-plus"></i>

Book Service

</a>

<a href="my_booking.php" class="btn btn-success btn-lg m-2">

<i class="fa-solid fa-list"></i>

My Bookings

</a>

<a href="profile.php" class="btn btn-secondary btn-lg m-2">

<i class="fa-solid fa-user"></i>

Profile

</a>
<a href="my_vehicles.php" class="btn btn-primary btn-lg m-2">
    <i class="fa-solid fa-car me-2"></i>
    Vehicles
</a>

<a href="reminders.php" class="btn btn-warning text-dark btn-lg m-2">

    <i class="fa-solid fa-bell me-2"></i>

    Service Reminders

</a>
</div>

</div>



<!-- Footer -->

<footer class="bg-dark text-white text-center py-3 mt-5">

<p class="mb-0">

© 2026 FleetSync | Vehicle Service & Maintenance Tracker

</p>

</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>