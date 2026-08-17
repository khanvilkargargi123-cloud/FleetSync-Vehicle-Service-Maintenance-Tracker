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

/* Check booking ID */

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "Invalid booking ID.";
    exit();
}

$id = (int)$_GET['id'];


/* Get booking */

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


/* --------------------------------
   20 MINUTE EDITING LIMIT
-------------------------------- */

$createdTime = strtotime($booking['created_at']);
$currentTime = time();

$minutesPassed = ($currentTime - $createdTime) / 60;


/* Editing allowed only for Pending bookings */

if (
    $minutesPassed > 20 ||
    $minutesPassed < 0 ||
    $booking['status'] !== 'Pending'
) {

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>Booking Locked | FleetSync</title>

<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css"
rel="stylesheet">

<link
rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.0/css/all.min.css">

<link rel="stylesheet"
href="css/edit_booking.css">

</head>

<body>

<div class="container">

<div class="locked-card">

<div class="locked-icon">

<i class="fa-solid fa-lock"></i>

</div>

<h2>Booking Locked</h2>

<p>
The editing period for this booking has expired.
</p>

<p class="text-muted">
Bookings can only be edited within 20 minutes after they are created.
</p>

<a
href="booking_details.php?id=<?php echo $id; ?>"
class="btn btn-info text-white">

<i class="fa-solid fa-arrow-left"></i>

Back to Booking Details

</a>

</div>

</div>

</body>

</html>

<?php

exit();

}


/* --------------------------------
   UPDATE BOOKING
-------------------------------- */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $customer_name = trim($_POST['customer_name']);
    $mobile = trim($_POST['mobile']);
    $vehicle_number = trim($_POST['vehicle_number']);
    $brand = trim($_POST['brand']);
    $model = trim($_POST['model']);
    $vehicle_type = trim($_POST['vehicle_type']);
    $km_reading = (int)$_POST['km_reading'];

    $service_type = trim($_POST['service_type']);
    $booking_date = $_POST['booking_date'];
    $booking_time = $_POST['booking_time'];

    $last_service = !empty($_POST['last_service'])
        ? $_POST['last_service']
        : NULL;

    $notes = trim($_POST['notes']);


    /* Update next service date */

    $next_service = date(
        "Y-m-d",
        strtotime($booking_date . " +6 months")
    );


    $sql = "UPDATE bookings SET

        customer_name = ?,
        mobile = ?,
        vehicle_number = ?,
        brand = ?,
        model = ?,
        vehicle_type = ?,
        km_reading = ?,
        service_type = ?,
        booking_date = ?,
        booking_time = ?,
        last_service = ?,
        next_service = ?,
        notes = ?

        WHERE id = ?
        AND user_email = ?
        AND status = 'Pending'";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "ssssssissssssis",
        $customer_name,
        $mobile,
        $vehicle_number,
        $brand,
        $model,
        $vehicle_type,
        $km_reading,
        $service_type,
        $booking_date,
        $booking_time,
        $last_service,
        $next_service,
        $notes,
        $id,
        $email
    );


    if (mysqli_stmt_execute($stmt)) {

        echo "<script>

        alert('Booking updated successfully!');

        window.location='booking_details.php?id=$id';

        </script>";

        exit();

    } else {

        echo "Database Error: "
             . mysqli_error($conn);

        exit();

    }

}

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>Edit Booking | FleetSync</title>

<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css"
rel="stylesheet">

<link
rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.0/css/all.min.css">

<link rel="stylesheet"
href="css/edit_booking.css">

</head>

<body>


<!-- NAVBAR -->

<nav class="navbar navbar-dark bg-dark shadow">

<div class="container">

<a
href="dashboard.php"
class="navbar-brand fw-bold">

<i class="fa-solid fa-car-side text-info"></i>

FleetSync

</a>

<a
href="booking_details.php?id=<?php echo $id; ?>"
class="btn btn-outline-info">

<i class="fa-solid fa-arrow-left"></i>

Back

</a>

</div>

</nav>


<!-- PAGE -->

<div class="container py-5">

<div class="edit-card">

<div class="text-center mb-4">

<div class="edit-icon">

<i class="fa-solid fa-pen-to-square"></i>

</div>

<h1>Edit Booking</h1>

<p class="text-muted">

You can edit this booking within 20 minutes of booking.

</p>

</div>


<form method="POST">


<!-- CUSTOMER -->

<h4 class="section-title">

<i class="fa-solid fa-user"></i>

Customer Information

</h4>

<div class="row g-3">


<div class="col-md-6">

<label class="form-label">
Customer Name
</label>

<input
type="text"
name="customer_name"
class="form-control"
value="<?php echo htmlspecialchars($booking['customer_name']); ?>"
required>

</div>


<div class="col-md-6">

<label class="form-label">
Mobile Number
</label>

<input
type="tel"
name="mobile"
class="form-control"
value="<?php echo htmlspecialchars($booking['mobile']); ?>"
required>

</div>

</div>


<hr>


<!-- VEHICLE -->

<h4 class="section-title">

<i class="fa-solid fa-car"></i>

Vehicle Information

</h4>

<div class="row g-3">


<div class="col-md-6">

<label class="form-label">
Vehicle Number
</label>

<input
type="text"
name="vehicle_number"
class="form-control"
value="<?php echo htmlspecialchars($booking['vehicle_number']); ?>"
required>

</div>


<div class="col-md-6">

<label class="form-label">
Brand
</label>

<input
type="text"
name="brand"
class="form-control"
value="<?php echo htmlspecialchars($booking['brand']); ?>"
required>

</div>


<div class="col-md-6">

<label class="form-label">
Model
</label>

<input
type="text"
name="model"
class="form-control"
value="<?php echo htmlspecialchars($booking['model']); ?>"
required>

</div>


<div class="col-md-6">

<label class="form-label">
Vehicle Type
</label>

<select
name="vehicle_type"
class="form-select"
required>

<option value="Car"
<?php if ($booking['vehicle_type'] == "Car") echo "selected"; ?>>
Car
</option>

<option value="Bike"
<?php if ($booking['vehicle_type'] == "Bike") echo "selected"; ?>>
Bike
</option>

<option value="SUV"
<?php if ($booking['vehicle_type'] == "SUV") echo "selected"; ?>>
SUV
</option>

<option value="Other"
<?php if ($booking['vehicle_type'] == "Other") echo "selected"; ?>>
Other
</option>

</select>

</div>


<div class="col-md-6">

<label class="form-label">
Current KM Reading
</label>

<input
type="number"
name="km_reading"
class="form-control"
value="<?php echo htmlspecialchars($booking['km_reading']); ?>"
required>

</div>

</div>


<hr>


<!-- SERVICE -->

<h4 class="section-title">

<i class="fa-solid fa-screwdriver-wrench"></i>

Service Information

</h4>

<div class="row g-3">


<div class="col-md-6">

<label class="form-label">
Service Type
</label>

<select
name="service_type"
class="form-select"
required>

<option value="AC Service"
<?php if ($booking['service_type'] == "AC Service") echo "selected"; ?>>
AC Service
</option>

<option value="Brake Service"
<?php if ($booking['service_type'] == "Brake Service") echo "selected"; ?>>
Brake Service
</option>

<option value="Battery Check"
<?php if ($booking['service_type'] == "Battery Check") echo "selected"; ?>>
Battery Check
</option>

<option value="Wheel Alignment"
<?php if ($booking['service_type'] == "Wheel Alignment") echo "selected"; ?>>
Wheel Alignment
</option>

<option value="Full Service"
<?php if ($booking['service_type'] == "Full Service") echo "selected"; ?>>
Full Service
</option>

</select>

</div>


<div class="col-md-6">

<label class="form-label">
Booking Date
</label>

<input
type="date"
name="booking_date"
class="form-control"
value="<?php echo htmlspecialchars($booking['booking_date']); ?>"
required>

</div>


<div class="col-md-6">

<label class="form-label">
Booking Time
</label>

<input
type="time"
name="booking_time"
class="form-control"
value="<?php echo htmlspecialchars($booking['booking_time']); ?>"
required>

</div>


<div class="col-md-6">

<label class="form-label">
Last Service
</label>

<input
type="date"
name="last_service"
class="form-control"
value="<?php echo htmlspecialchars($booking['last_service'] ?? ''); ?>">

</div>


<div class="col-12">

<label class="form-label">
Additional Notes
</label>

<textarea
name="notes"
class="form-control"
rows="4"><?php echo htmlspecialchars($booking['notes']); ?></textarea>

</div>

</div>


<!-- BUTTONS -->

<div class="text-center mt-4">

<a
href="booking_details.php?id=<?php echo $id; ?>"
class="btn btn-secondary me-2">

<i class="fa-solid fa-arrow-left"></i>

Cancel

</a>

<button
type="submit"
class="btn btn-info text-white">

<i class="fa-solid fa-floppy-disk"></i>

Save Changes

</button>

</div>


</form>

</div>

</div>


<!-- FOOTER -->

<footer class="bg-dark text-white text-center py-4">

<p class="mb-0">

<i class="fa-solid fa-car-side"></i>

FleetSync | Vehicle Service & Maintenance Tracker

</p>

<small>

© 2026 FleetSync. All Rights Reserved.

</small>

</footer>


</body>

</html>