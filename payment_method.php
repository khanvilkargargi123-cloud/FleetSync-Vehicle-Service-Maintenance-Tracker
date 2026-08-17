<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.html");
    exit();
}

include 'config.php';

$email = $_SESSION['user'];

$booking_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($booking_id <= 0) {
    header("Location: my_booking.php");
    exit();
}

/* Verify booking belongs to logged-in user */
$sql = "SELECT *
        FROM bookings
        WHERE id = ?
        AND user_email = ?";

$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    die("Database Error: " . mysqli_error($conn));
}

mysqli_stmt_bind_param(
    $stmt,
    "is",
    $booking_id,
    $email
);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$booking = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);

if (!$booking) {
    die("Booking not found.");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Payment Method | FleetSync</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css"
rel="stylesheet">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.0/css/all.min.css">

<link rel="stylesheet"
href="css/payment.css">

</head>

<body>

<nav class="navbar navbar-dark bg-dark shadow">

<div class="container">

<a href="dashboard.php"
class="navbar-brand fw-bold">

<i class="fa-solid fa-car-side text-info"></i>
FleetSync

</a>

</div>

</nav>


<div class="container py-5">

<div class="row justify-content-center">

<div class="col-lg-7">

<div class="card shadow-lg border-0">

<div class="card-body p-5">

<div class="text-center mb-4">

<i class="fa-solid fa-credit-card fa-4x text-info"></i>

<h1 class="mt-3">
Choose Payment Method
</h1>

<p class="text-muted">
Select how you would like to pay for your vehicle service.
</p>

</div>


<div class="alert alert-light border">

<strong>Booking ID:</strong>
#<?php echo $booking_id; ?>

<br>

<strong>Vehicle:</strong>
<?php echo htmlspecialchars($booking['vehicle_number']); ?>

<br>

<strong>Service:</strong>
<?php echo htmlspecialchars($booking['service_type']); ?>

</div>


<form action="process_payment.php"
method="POST">

<input
type="hidden"
name="booking_id"
value="<?php echo $booking_id; ?>">


<div class="mb-3">

<label class="form-label fw-bold">
Payment Method
</label>
<div class="form-check border rounded p-3 mb-3">

    <input
        class="form-check-input"
        type="radio"
        name="payment_method"
        id="cash"
        value="Cash"
        required>

    <label
        class="form-check-label"
        for="cash">

        <i class="fa-solid fa-money-bill-wave text-success me-2"></i>

        <strong>Cash at Service Center</strong>

        <br>

        <small class="text-muted ms-4">
            Pay when you visit the service center.
        </small>

    </label>

</div>


<div class="form-check border rounded p-3 mb-3">

    <input
        class="form-check-input"
        type="radio"
        name="payment_method"
        id="upi"
        value="UPI">

    <label
        class="form-check-label"
        for="upi">

        <i class="fa-solid fa-mobile-screen-button text-info me-2"></i>

        <strong>UPI</strong>

        <br>

        <small class="text-muted ms-4">
           Preferred Payment Method: UPI
Payment will be collected at the service center.
        </small>

    </label>

</div>


<div class="form-check border rounded p-3">

    <input
        class="form-check-input"
        type="radio"
        name="payment_method"
        id="card"
        value="Card">

    <label
        class="form-check-label"
        for="card">

        <i class="fa-solid fa-credit-card text-primary me-2"></i>

        <strong>Card</strong>

        <br>

        <small class="text-muted ms-4">
        Pay using a debit or credit card & payment will be collected at service center...
        </small>

    </label>

</div>

</div>


<div class="text-center mt-4">

<button
type="submit"
class="btn btn-info text-white btn-lg px-5">

<i class="fa-solid fa-arrow-right"></i>

Continue

</button>

</div>

</form>


</div>

</div>

</div>

</div>

</div>


<footer class="bg-dark text-white text-center py-4">

<p class="mb-1">

<i class="fa-solid fa-car-side text-info"></i>

FleetSync | Vehicle Service & Maintenance Tracker

</p>

<small>
© 2026 FleetSync. All Rights Reserved.
</small>

</footer>

</body>
</html>