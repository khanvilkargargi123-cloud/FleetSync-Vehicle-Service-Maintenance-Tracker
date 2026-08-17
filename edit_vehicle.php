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


/* =========================
   GET LATEST VEHICLE
========================= */

$sql = "SELECT *
        FROM vehicles
        WHERE user_email = ?
        ORDER BY id DESC
        LIMIT 1";

$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    die("Database Error: " . mysqli_error($conn));
}

mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$vehicle = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);


if (!$vehicle) {
    header("Location: add_vehicle.php");
    exit();
}


/* =========================
   GET LATEST SERVICE
========================= */

$latestService = null;

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

if ($stmt) {

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

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Edit Vehicle | FleetSync</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.0/css/all.min.css">

<link rel="stylesheet" href="css/edit_vehicle.css">

</head>

<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-lg">

<div class="container">

<a class="navbar-brand fw-bold fs-3" href="dashboard.php">
<i class="fa-solid fa-car-side text-info"></i>
FleetSync
</a>

<a href="profile.php" class="btn btn-info text-white">
<i class="fa-solid fa-arrow-left"></i>
Back to Profile
</a>

</div>

</nav>

<section class="edit-section">

<div class="container">

<div class="row justify-content-center">

<div class="col-lg-7">

<div class="edit-vehicle-card">

<div class="text-center">

<i class="fa-solid fa-car-side vehicle-icon"></i>

<h2>Edit Vehicle</h2>

<p class="text-muted">
Update your registered vehicle information.
</p>

</div>

<form action="update_vehicle.php" method="POST">

<div class="mb-3">
<label class="form-label">Vehicle Number</label>
<input type="text" class="form-control" name="vehicle_number"
value="<?php echo $vehicle['vehicle_number']; ?>" required>
</div>

<div class="mb-3">
<label class="form-label">Vehicle Brand</label>
<input type="text" class="form-control" name="vehicle_brand"
value="<?php echo $vehicle['vehicle_brand']; ?>" required>
</div>

<div class="mb-3">
<label class="form-label">Vehicle Model</label>
<input type="text" class="form-control" name="vehicle_model"
value="<?php echo $vehicle['vehicle_model']; ?>" required>
</div>

<div class="mb-3">
<label class="form-label">Fuel Type</label>

<select class="form-select" name="fuel_type">

<option <?php if($vehicle['fuel_type']=="Petrol") echo "selected"; ?>>Petrol</option>

<option <?php if($vehicle['fuel_type']=="Diesel") echo "selected"; ?>>Diesel</option>

<option <?php if($vehicle['fuel_type']=="CNG") echo "selected"; ?>>CNG</option>

<option <?php if($vehicle['fuel_type']=="Electric") echo "selected"; ?>>Electric</option>

</select>

</div>

<div class="mb-3">
<label class="form-label">Manufacturing Year</label>

<input type="number"
class="form-control"
name="manufacturing_year"
min="1990"
max="2035"
value="<?php echo $vehicle['manufacturing_year']; ?>"
required>

</div>

<div class="mb-4">
<label class="form-label">Vehicle Color</label>

<input type="text"
class="form-control"
name="vehicle_color"
value="<?php echo $vehicle['vehicle_color']; ?>"
required>

</div>

<!-- =========================
     SERVICE INFORMATION
========================= -->

<div class="service-information">

    <h4 class="service-title">
        <i class="fa-solid fa-screwdriver-wrench"></i>
        Service Information
    </h4>

    <?php if ($latestService) { ?>

        <div class="service-info-box">

            <div class="service-info-item">

                <span>
                    <i class="fa-solid fa-wrench"></i>
                    Latest Vehicle Service
                </span>

                <strong>
                    <?php
                    echo htmlspecialchars(
                        $latestService['service_type']
                        ?? 'Not available'
                    );
                    ?>
                </strong>

            </div>


            <div class="service-info-item">

                <span>
                    <i class="fa-solid fa-calendar-check"></i>
                    Latest Service Date
                </span>

                <strong>

                    <?php

                    if (!empty($latestService['booking_date'])) {

                        echo date(
                            "d M Y",
                            strtotime(
                                $latestService['booking_date']
                            )
                        );

                    } else {

                        echo "Not available";

                    }

                    ?>

                </strong>

            </div>


            <div class="service-info-item">

                <span>
                    <i class="fa-solid fa-clock-rotate-left"></i>
                    Last Service
                </span>

                <strong>

                    <?php

                    if (!empty($latestService['last_service'])) {

                        echo date(
                            "d M Y",
                            strtotime(
                                $latestService['last_service']
                            )
                        );

                    } else {

                        echo "Not available";

                    }

                    ?>

                </strong>

            </div>


            <div class="service-info-item">

                <span>
                    <i class="fa-solid fa-bell"></i>
                    Next Service
                </span>

                <strong class="next-service">

                    <?php

                    if (!empty($latestService['next_service'])) {

                        echo date(
                            "d M Y",
                            strtotime(
                                $latestService['next_service']
                            )
                        );

                    } else {

                        echo "Not scheduled";

                    }

                    ?>

                </strong>

            </div>

        </div>

        <small class="service-note">
            <i class="fa-solid fa-circle-info"></i>
            Service information is managed through your service bookings.
        </small>

    <?php } else { ?>

        <div class="no-service-box">

            <i class="fa-solid fa-screwdriver-wrench"></i>

            <p>
                No service information available for this vehicle.
            </p>

            <a
                href="book_service.php"
                class="btn btn-info text-white">

                <i class="fa-solid fa-calendar-plus"></i>
                Book a Service

            </a>

        </div>

    <?php } ?>

</div>

<hr class="my-4">

<div class="text-center">

<button type="submit" class="btn btn-save">
<i class="fa-solid fa-floppy-disk"></i>
Update Vehicle
</button>

<a href="profile.php" class="btn btn-cancel">
Cancel
</a>

</div>

</form>

</div>

</div>

</div>

</div>

</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>