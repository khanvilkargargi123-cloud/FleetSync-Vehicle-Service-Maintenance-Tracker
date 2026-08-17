<?php
session_start();

if(!isset($_SESSION['user'])){
    header("Location: login.html");
    exit();
}

include 'config.php';

$email = $_SESSION['user'];

/* Get user details */
$stmt = mysqli_prepare(
    $conn,
    "SELECT name, email, phone, address, profile_photo
     FROM users
     WHERE email = ?"
);

mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);

if (!$user) {
    echo "User details not found.";
    exit();
}

/* Get latest vehicle */
$vehicle_sql = "SELECT * FROM vehicles
                WHERE user_email='$email'
                ORDER BY id DESC
                LIMIT 1";

$vehicle_result = mysqli_query($conn, $vehicle_sql);
$vehicle = mysqli_fetch_assoc($vehicle_result);
/* Get latest vehicle */
$vehicle_sql = "SELECT *
                FROM vehicles
                WHERE user_email = ?
                ORDER BY id DESC
                LIMIT 1";

$stmt = mysqli_prepare($conn, $vehicle_sql);
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);

$vehicle_result = mysqli_stmt_get_result($stmt);
$vehicle = mysqli_fetch_assoc($vehicle_result);

mysqli_stmt_close($stmt);


/* Get latest service information for this vehicle */
$latestService = null;

if ($vehicle) {

    $service_sql = "SELECT
                        service_type,
                        booking_date,
                        next_service,
                        last_service
                    FROM bookings
                    WHERE user_email = ?
                    AND vehicle_number = ?
                    ORDER BY id DESC
                    LIMIT 1";

    $stmt = mysqli_prepare($conn, $service_sql);

    mysqli_stmt_bind_param(
        $stmt,
        "ss",
        $email,
        $vehicle['vehicle_number']
    );

    mysqli_stmt_execute($stmt);

    $service_result = mysqli_stmt_get_result($stmt);
    $latestService = mysqli_fetch_assoc($service_result);

    mysqli_stmt_close($stmt);
}

// Total Bookings
$result = mysqli_query($conn,"SELECT COUNT(*) AS total FROM bookings WHERE user_email='$email'");
$totalBookings = mysqli_fetch_assoc($result)['total'];

// Completed Services
$result = mysqli_query($conn,"SELECT COUNT(*) AS total FROM bookings WHERE user_email='$email' AND status='Completed'");
$totalServices = mysqli_fetch_assoc($result)['total'];

// Registered Vehicles
$result = mysqli_query($conn,"SELECT COUNT(*) AS total FROM vehicles WHERE user_email='$email'");
$totalVehicles = mysqli_fetch_assoc($result)['total'];

// Account Status
$accountStatus = "Active";

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>My Profile | FleetSync</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.0/css/all.min.css">

<link rel="stylesheet" href="css/Profile.css">

</head>

<body>

<!-- ================= NAVBAR ================= -->

<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-lg sticky-top">

    <div class="container">

        <a class="navbar-brand fw-bold fs-3" href="index.php">
            <i class="fa-solid fa-car-side text-info"></i>
            FleetSync
        </a>

        <button class="navbar-toggler" type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarNav">

            <span class="navbar-toggler-icon"></span>

        </button>

        <div class="collapse navbar-collapse" id="navbarNav">

            <ul class="navbar-nav ms-auto align-items-center">

                <li class="nav-item mx-2">
                    <a class="nav-link" href="index.php">
                        <i class="fa-solid fa-house"></i>
                        Home
                    </a>
                </li>

                <li class="nav-item mx-2">
                    <a class="nav-link" href="About.html">
                        <i class="fa-solid fa-circle-info"></i>
                        About
                    </a>
                </li>

                <li class="nav-item mx-2">
                    <a class="nav-link" href="booking.html">
                        <i class="fa-solid fa-calendar-check"></i>
                        Book Service
                    </a>
                </li>

                <li class="nav-item mx-2">
                    <a class="nav-link" href="contact.html">
                        <i class="fa-solid fa-phone"></i>
                        Contact
                    </a>
                </li>

                <li class="nav-item mx-2">
                    <a class="nav-link active" href="profile.php">
                        <i class="fa-solid fa-user"></i>
                        Profile
                    </a>
                </li>

                <li class="nav-item ms-2">
                    <a href="logout.php" class="btn btn-danger text-white px-3">
                        <i class="fa-solid fa-right-from-bracket"></i>
                        Logout
                    </a>
                </li>

            </ul>

        </div>

    </div>

</nav>

<!-- ================= PROFILE HERO ================= -->

<section class="profile-hero">

    <div class="container text-center">

        <div class="profile-photo-wrapper">

          <img
    src="<?php
        echo !empty($user['profile_photo'])
            ? htmlspecialchars($user['profile_photo'])
            : 'https://cdn-icons-png.flaticon.com/512/3135/3135715.png';
              ?>"
                class="profile-img"
                id="profilePreview"
                 alt="Profile Photo">

            <form action="upload_profile.php"
                  method="POST"
                  enctype="multipart/form-data">

                <label for="profile_photo" class="change-photo-btn">
                    <i class="fa-solid fa-camera"></i>
                    Change Photo
                </label>

                <input type="file"
                       id="profile_photo"
                       name="profile_photo"
                       accept="image/*"
                       onchange="this.form.submit()">

            </form>

        </div>

        <h1>
            <?php echo htmlspecialchars($user['name']); ?>
        </h1>

        <p>
            <?php echo htmlspecialchars($user['email']); ?>
        </p>

    </div>

</section>

<!-- ================= PROFILE DETAILS ================= -->

<section class="py-5">

    <div class="container">

        <div class="row g-4">

            <!-- Personal Information -->

            <div class="col-lg-6">

                <div class="profile-card h-100">

                    <h3 class="section-title">
                        <i class="fa-solid fa-user text-info"></i>
                        Personal Information
                    </h3>

                    <div class="info-item">
                        <span>Name</span>
                        <strong><?php echo $user['name']; ?></strong>
                    </div>

                    <div class="info-item">
                        <span>Email</span>
                        <strong><?php echo $user['email']; ?></strong>
                    </div>

                    <div class="info-item">
                        <span>Phone</span>
                        <strong><?php echo $user['phone']; ?></strong>
                    </div>

                    <div class="info-item">
                        <span>Address</span>
                        <strong><?php echo $user['address']; ?></strong>
                    </div>

                </div>

            </div>

            <!-- Vehicle Information -->

           <div class="col-lg-6">

    <div class="profile-card h-100">

        <?php if(isset($vehicle) && $vehicle){ ?>

        <h3 class="section-title">
            <i class="fa-solid fa-car text-info"></i>
            Vehicle Information
        </h3>

        <div class="info-item">
            <span>Vehicle Number</span>
            <strong><?php echo $vehicle['vehicle_number']; ?></strong>
        </div>

        <div class="info-item">
            <span>Vehicle Brand</span>
            <strong><?php echo $vehicle['vehicle_brand']; ?></strong>
        </div>

        <div class="info-item">
            <span>Vehicle Model</span>
            <strong><?php echo $vehicle['vehicle_model']; ?></strong>
        </div>

        <div class="info-item">
            <span>Fuel Type</span>
            <strong><?php echo $vehicle['fuel_type']; ?></strong>
        </div>

        <div class="info-item">
            <span>Manufacturing Year</span>
            <strong><?php echo $vehicle['manufacturing_year']; ?></strong>
        </div>

        <div class="info-item">
            <span>Vehicle Color</span>
            <strong><?php echo $vehicle['vehicle_color']; ?></strong>
        </div>
   <?php if ($latestService) { ?>

    <hr class="my-4">

    <h5 class="section-title">
        <i class="fa-solid fa-screwdriver-wrench text-info"></i>
        Service Information
    </h5>

    <div class="info-item">
        <span>Latest Vehicle Service</span>

        <strong>
            <?php
            echo htmlspecialchars(
                $latestService['service_type'] ?? 'Not available'
            );
            ?>
        </strong>
    </div>

    <div class="info-item">
        <span>Latest Service Date</span>

        <strong>
            <?php
            echo !empty($latestService['booking_date'])
                ? date(
                    "d M Y",
                    strtotime($latestService['booking_date'])
                )
                : "Not available";
            ?>
        </strong>
    </div>

    <div class="info-item">
        <span>Next Service</span>

        <strong class="text-info">
            <?php
            echo !empty($latestService['next_service'])
                ? date(
                    "d M Y",
                    strtotime($latestService['next_service'])
                )
                : "Not scheduled";
            ?>
        </strong>
    </div>

<?php } else { ?>

    <hr class="my-4">

    <div class="text-center text-muted py-3">

        <i class="fa-solid fa-screwdriver-wrench fa-2x mb-2"></i>

        <p class="mb-0">
            No service history available.
        </p>

    </div>

<?php } ?>

        <div class="text-center mt-4">
            <a href="edit_vehicle.php" class="btn btn-warning">
                <i class="fa-solid fa-pen"></i>
                Edit Vehicle
            </a>
        </div>

        <?php } else { ?>

        <h3 class="section-title">
            <i class="fa-solid fa-car text-info"></i>
            Vehicle Information
        </h3>

        <div class="text-center py-5">

            <i class="fa-solid fa-car-side fa-4x text-info mb-3"></i>

            <p class="text-warning fw-bold">
                No vehicle has been added yet.
            </p>

            <a href="add_vehicle.php" class="btn btn-info text-white">
                <i class="fa-solid fa-plus"></i>
                Add Vehicle
            </a>

        </div>

        <?php } ?>

    </div>

</div>
</div>
</div>
</section>

<!-- ================= QUICK STATS ================= -->

<section class="py-5">

<div class="container">

<div class="row g-4">

<div class="col-md-3">

<div class="stats-card text-center">

<i class="fa-solid fa-screwdriver-wrench stats-icon text-info"></i>

<h2><?php echo $totalServices; ?></h2>

<p>Total Services</p>

</div>

</div>

<div class="col-md-3">

<div class="stats-card text-center">

<i class="fa-solid fa-calendar-check stats-icon text-success"></i>

<h2><?php echo $totalBookings; ?></h2>

<p>Total Bookings</p>

</div>

</div>

<div class="col-md-3">

<div class="stats-card text-center">

<i class="fa-solid fa-car stats-icon text-warning"></i>

<h2><?php echo $totalVehicles; ?></h2>

<p>Registered Vehicles</p>

</div>

</div>

<div class="col-md-3">

<div class="stats-card text-center">

<i class="fa-solid fa-circle-check stats-icon text-primary"></i>

<h2><?php echo $accountStatus; ?></h2>

<p>Account Status</p>

</div>

</div>

</div>

</div>

</section>

<!-- ================= ACTION BUTTONS ================= -->

<section class="pb-5">

    <div class="container">

        <div class="text-center">

            <a href="edit_profile.php" class="btn btn-warning profile-btn">
                <i class="fa-solid fa-user-pen"></i>
                Edit Profile
            </a>

            <a href="my_booking.php" class="btn btn-info profile-btn text-white">
                <i class="fa-solid fa-calendar-plus"></i>
                My Bookings
            </a>

            <a href="dashboard.php" class="btn btn-primary profile-btn">
                <i class="fa-solid fa-gauge-high"></i>
                Dashboard
            </a>

            <a href="logout.php" class="btn btn-danger profile-btn">
                <i class="fa-solid fa-right-from-bracket"></i>
                Logout
            </a>

        </div>

    </div>

</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>