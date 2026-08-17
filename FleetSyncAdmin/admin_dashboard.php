<?php

session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

include '../config.php';

/* Dashboard counts */

$bookingQuery = mysqli_query($conn, "SELECT COUNT(*) AS total FROM bookings");
$bookingData = mysqli_fetch_assoc($bookingQuery);
$totalBookings = $bookingData['total'];

$pendingQuery = mysqli_query($conn, "SELECT COUNT(*) AS total FROM bookings WHERE status='Pending'");
$pendingData = mysqli_fetch_assoc($pendingQuery);
$pendingBookings = $pendingData['total'];

$confirmedQuery = mysqli_query($conn, "SELECT COUNT(*) AS total FROM bookings WHERE status='Confirmed'");
$confirmedData = mysqli_fetch_assoc($confirmedQuery);
$confirmedBookings = $confirmedData['total'];

$completedQuery = mysqli_query($conn, "SELECT COUNT(*) AS total FROM bookings WHERE status='Completed'");
$completedData = mysqli_fetch_assoc($completedQuery);
$completedBookings = $completedData['total'];

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Admin Dashboard | FleetSync</title>

<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css"
rel="stylesheet">

<link
rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.0/css/all.min.css">

<link rel="stylesheet" href="css/admin.css">

</head>

<body>

<!-- NAVBAR -->

<nav class="navbar navbar-dark bg-dark shadow">

<div class="container-fluid px-4">

<a class="navbar-brand fw-bold fs-4">

<i class="fa-solid fa-car-side text-info"></i>

FleetSync Admin

</a>

<div class="d-flex align-items-center gap-3">

<span class="text-light">

<i class="fa-solid fa-user-shield text-info"></i>

Admin

</span>


<a href="admin_logout.php"
   class="btn btn-outline-danger btn-sm">

<i class="fa-solid fa-right-from-bracket"></i>

Logout

</a>

</div>

</div>

</nav>


<!-- MAIN -->

<div class="container-fluid py-5 px-4">

<div class="mb-4">

<h1 class="fw-bold">

<i class="fa-solid fa-chart-line text-info"></i>

Admin Dashboard

</h1>

<p class="text-muted">

Manage FleetSync bookings and services.

</p>

</div>


<!-- STATISTICS -->

<div class="row g-4 mb-5">


<div class="col-md-6 col-lg-3">

<div class="admin-card">

<div class="admin-card-icon">

<i class="fa-solid fa-calendar-check"></i>

</div>

<h6>Total Bookings</h6>

<h2><?php echo $totalBookings; ?></h2>

</div>

</div>


<div class="col-md-6 col-lg-3">

<div class="admin-card">

<div class="admin-card-icon pending-icon">

<i class="fa-solid fa-clock"></i>

</div>

<h6>Pending</h6>

<h2><?php echo $pendingBookings; ?></h2>

</div>

</div>


<div class="col-md-6 col-lg-3">

<div class="admin-card">

<div class="admin-card-icon confirmed-icon">

<i class="fa-solid fa-circle-check"></i>

</div>

<h6>Confirmed</h6>

<h2><?php echo $confirmedBookings; ?></h2>

</div>

</div>


<div class="col-md-6 col-lg-3">

<div class="admin-card">

<div class="admin-card-icon completed-icon">

<i class="fa-solid fa-flag-checkered"></i>

</div>

<h6>Completed</h6>

<h2><?php echo $completedBookings; ?></h2>

</div>

</div>

</div>


<!-- QUICK ACTIONS -->

<div class="admin-section">

<h3 class="mb-4">

<i class="fa-solid fa-bolt text-warning"></i>

Quick Actions

</h3>

<div class="row g-3">

    <div class="col-md-6 col-lg-3">

        <a href="admin_bookings.php"
           class="admin-action">

            <i class="fa-solid fa-calendar-days"></i>

            <span>Manage Bookings</span>

        </a>

    </div>


    <div class="col-md-6 col-lg-3">

        <a href="admin_customers.php"
           class="admin-action">

            <i class="fa-solid fa-users"></i>

            <span>Customers</span>

        </a>

    </div>


    <div class="col-md-6 col-lg-3">

        <a href="admin_vehicles.php"
           class="admin-action">

            <i class="fa-solid fa-car"></i>

            <span>Vehicles</span>

        </a>

    </div>


    <div class="col-md-6 col-lg-3">

        <a href="admin_messages.php"
           class="admin-action">

            <i class="fa-solid fa-envelope"></i>

            <span>Customer Messages</span>

        </a>

    </div>

      <div class="col-md-6 col-lg-3">

    <a href="admin_payments.php"
       class="admin-action">

        <i class="fa-solid fa-money-bill-wave"></i>

        <span>Payment Management</span>

    </a>

</div>

</div>

</div>



<footer class="bg-dark text-white text-center py-4">

<p class="mb-1">

<i class="fa-solid fa-car-side text-info"></i>

FleetSync | Admin Panel

</p>

<small>

© 2026 FleetSync. All Rights Reserved.

</small>

</footer>

</body>

</html>