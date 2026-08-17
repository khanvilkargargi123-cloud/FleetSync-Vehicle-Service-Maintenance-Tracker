<?php
include 'config.php';

$totalUsers = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) AS total FROM users"))['total'];

$totalVehicles = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) AS total FROM vehicles"))['total'];

$totalBookings = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) AS total FROM bookings"))['total'];

$completedServices = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) AS total FROM bookings WHERE status='Completed'"))['total'];
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <!-- SEO Meta Tags -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="FleetSync Vehicle Service and Maintenance Tracker">
    <meta name="keywords" content="Fleet Management, Vehicle Service, Car Maintenance">
    <meta name="author" content="Your Name">

    <title>FleetSync - Vehicle Service Tracker</title>


    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.0/css/all.min.css">

    <!-- External CSS -->
    <link rel="stylesheet" href="css/index.css">

</head>

<body>

    <!-- Navbar Start -->

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-lg sticky-top">
        <div class="container">

            <a class="navbar-brand fw-bold fs-3" href="index.php">
                <i class="fa-solid fa-car-side text-info"></i> FleetSync
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">

                <ul class="navbar-nav ms-auto align-items-center">

                    <li class="nav-item mx-2">
                        <a class="nav-link active" href="index.php">
                            <i class="fa-solid fa-house"></i> Home
                        </a>
                    </li>

                    <li class="nav-item mx-2">
                        <a class="nav-link" href="About.html">
                            <i class="fa-solid fa-circle-info"></i> About
                        </a>
                    </li>

                    <li class="nav-item mx-2">
                        <a class="nav-link" href="booking.html">
                            <i class="fa-solid fa-calendar-check"></i> Book Service
                        </a>
                    </li>

                    <li class="nav-item mx-2">
                        <a class="nav-link" href="contact.html">
                            <i class="fa-solid fa-phone"></i> Contact
                        </a>
                    </li>

                    <li class="nav-item mx-2">
                        <a class="nav-link" href="register.html">
                            <i class="fa-solid fa-user"></i> Profile
                        </a>
                    </li>

                    <li class="nav-item ms-2">
                        <a href="login.html" class="btn btn-info text-white px-3">
                            <i class="fa-solid fa-right-to-bracket"></i> Login
                        </a>
                    </li>

                </ul>

            </div>

        </div>
    </nav>

    <!-- Navbar End -->


    <!-- Hero Section Start -->

    <section class="hero-section">

        <div class="container">

            <div class="row align-items-center min-vh-100">

                <div class="col-lg-6">

                    <h1 class="display-4 fw-bold text-white">
                        Smart Vehicle Service & Maintenance Tracking
                    </h1>

                    <p class="text-light mt-3">
                        FleetSync helps service centers manage vehicles,
                        maintenance schedules, service history,
                        and automated reminders efficiently.
                    </p>

                    <a href="register.php" class="btn btn-primary btn-lg mt-3">
                        Get Started
                    </a>

                    <a href="About.html" class="btn btn-outline-light btn-lg mt-3 ms-2">
                        Learn More
                    </a>

                </div>

            </div>

        </div>

    </section>

    <!-- Hero Section End -->
    <!--Features Section Start-->
    <section class="py-5">

        <div class="container">

            <div class="text-center mb-5">
                <h2 class="fw-bold">Our Features</h2>
                <p>Powerful tools for vehicle service management</p>
            </div>

            <div class="row">

                <!-- Feature 1 -->

                <div class="col-md-4">

                    <div class="card feature-card h-100 text-center p-4">

                        <i class="fa-solid fa-car-side feature-icon"></i>

                        <h4 class="mt-3">Vehicle Tracking</h4>

                        <p>
                            Easily manage customer vehicles and monitor service status.
                        </p>

                    </div>

                </div>

                <!-- Feature 2 -->

                <div class="col-md-4">

                    <div class="card feature-card h-100 text-center p-4">

                        <i class="fa-solid fa-screwdriver-wrench feature-icon"></i>

                        <h4 class="mt-3">Service History</h4>

                        <p>
                            Maintain complete service records and maintenance details.
                        </p>

                    </div>

                </div>

                <!-- Feature 3 -->

                <div class="col-md-4">

                    <div class="card feature-card h-100 text-center p-4">

                        <i class="fa-solid fa-bell feature-icon"></i>

                        <h4 class="mt-3">Automated Reminders</h4>

                        <p>
                            Send reminders for upcoming maintenance schedules.
                        </p>

                    </div>

                </div>

            </div>

        </div>

    </section>


    </section>
    <!--Features Section End-->

    <!--Statistics Section Start-->
 <section class="stats-section py-5">

<div class="container">

<div class="text-center text-white mb-5">

<h2 class="fw-bold">FleetSync Statistics</h2>

<p>Live data from the FleetSync system.</p>

</div>

<div class="row text-center g-4">

<div class="col-md-3">
<div class="stats-box">
<i class="fa-solid fa-users fa-3x mb-3"></i>
<h1><?php echo $totalUsers; ?></h1>
<p>Registered Users</p>
</div>
</div>

<div class="col-md-3">
<div class="stats-box">
<i class="fa-solid fa-car fa-3x mb-3"></i>
<h1><?php echo $totalVehicles; ?></h1>
<p>Registered Vehicles</p>
</div>
</div>

<div class="col-md-3">
<div class="stats-box">
<i class="fa-solid fa-calendar-check fa-3x mb-3"></i>
<h1><?php echo $totalBookings; ?></h1>
<p>Total Bookings</p>
</div>
</div>

<div class="col-md-3">
<div class="stats-box">
<i class="fa-solid fa-screwdriver-wrench fa-3x mb-3"></i>
<h1><?php echo $completedServices; ?></h1>
<p>Completed Services</p>
</div>
</div>

</div>

</div>

</section>
    <!--statistics Section End-->

    <!--About Section Start-->
    <section class="py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <img src="images/car image.jpeg" alt="FleetSync Hero image" class="img-fluid">
                </div>
                <div class="col-lg-6 mt-4 mt-lg-0">
                    <h2 class="fw-bold">About FleetSync</h2>
                    <p class="mt-3"> FleetSync is a modern Vehicle Service and Maintenance Tracking System Designed for
                        Service Centers
                        To Efficiently Manage Customer Vehicles. Maintenace Schedules, and Service History. </p>
                    <p> Our Platform Improves WorkFlow Efficiently and Helps Provide Better Customer Service Through
                        Automated
                        Reminders and Organized Records.
                    </p>
                    <a href="About.html" class=" btn btn-primary-mt-3">  Read More...</a>
                </div>
            </div>
        </div>
    </section>
    <!--About Section End -->

    <!--Footer start-->
    <footer class="bg-dark text-light py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h4>FleetSync</h4>
                    <p> Smart Vehicle Service & Maintenace Tracker.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p> @ 2026 FleetSync . All Rights Reserved. </p>
                </div>
            </div>
        </div>
    </footer>
    <!--Footer End -->
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

    <!-- External JS -->
    <script src="js/app.js"></script>

</body>

</html>