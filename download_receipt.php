<?php

session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.html");
    exit();
}

include 'config.php';

$email = $_SESSION['user'];

$booking_id = isset($_GET['id'])
    ? (int)$_GET['id']
    : 0;

if ($booking_id <= 0) {
    header("Location: my_booking.php");
    exit();
}


/* =========================
   GET BOOKING
========================= */

$sql = "SELECT *
        FROM bookings
        WHERE id = ?
        AND user_email = ?";

$stmt = mysqli_prepare($conn, $sql);

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


/* =========================
   PAYMENT METHOD
========================= */

$payment_method = htmlspecialchars(
    $booking['payment_method'] ?? 'Not selected'
);

$payment_status = $booking['payment_status'] ?? 'Pending';


/* =========================
   RECEIPT DATA
========================= */

$customer_name = htmlspecialchars(
    $booking['customer_name'] ?? ''
);

$vehicle_number = htmlspecialchars(
    $booking['vehicle_number'] ?? ''
);

$service_type = htmlspecialchars(
    $booking['service_type'] ?? ''
);

$booking_date = !empty($booking['booking_date'])
    ? date("d M Y", strtotime($booking['booking_date']))
    : "Not available";

$booking_time = htmlspecialchars(
    $booking['booking_time'] ?? ''
);

$payment_method = htmlspecialchars(
    $payment_method
);

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>FleetSync Service Receipt</title>

<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css"
rel="stylesheet">

<link
rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.0/css/all.min.css">

<!-- External CSS -->
    <link rel="stylesheet" href="css/payment_receipt.css">


</head>

<body>


<div class="receipt">


<!-- HEADER -->

<div class="text-center">

    <div class="logo">

        <i class="fa-solid fa-car-side"></i>

        FleetSync

    </div>

    <p class="text-muted mb-1">

        Vehicle Service & Maintenance Tracker

    </p>

    <h3 class="receipt-title">

        SERVICE PAYMENT RECEIPT

    </h3>

</div>


<hr>


<!-- RECEIPT DETAILS -->

<div class="details">


<div class="detail-row">

    <span>Receipt / Booking ID</span>

    <strong>
        #<?php echo $booking_id; ?>
    </strong>

</div>


<div class="detail-row">

    <span>Customer Name</span>

    <strong>
        <?php echo $customer_name; ?>
    </strong>

</div>


<div class="detail-row">

    <span>Vehicle Number</span>

    <strong>
        <?php echo $vehicle_number; ?>
    </strong>

</div>


<div class="detail-row">

    <span>Service Type</span>

    <strong>
        <?php echo $service_type; ?>
    </strong>

</div>


<div class="detail-row">

    <span>Preferred Date</span>

    <strong>
        <?php echo $booking_date; ?>
    </strong>

</div>


<div class="detail-row">

    <span>Preferred Time</span>

    <strong>
        <?php echo $booking_time; ?>
    </strong>

</div>


<div class="detail-row">

    <span>Payment Method</span>

    <strong>
        <?php echo $payment_method; ?>
    </strong>

</div>


<div class="detail-row">

    <span>Payment Status</span>

    <strong>

        <?php if ($payment_status === 'Paid'): ?>

            <span class="text-success">
                <i class="fa-solid fa-circle-check"></i>
                Paid
            </span>

        <?php elseif ($payment_status === 'Partial'): ?>

            <span class="text-warning">
                <i class="fa-solid fa-circle-half-stroke"></i>
                Partial
            </span>

        <?php else: ?>

            <span class="text-secondary">
                <i class="fa-solid fa-clock"></i>
                Pending
            </span>

        <?php endif; ?>

    </strong>

</div>



<!-- MESSAGE -->

<div class="footer-note">

<?php if ($payment_status === 'Paid'): ?>

    <i class="fa-solid fa-circle-check me-2"></i>

    <strong>Payment received successfully.</strong>

    <br>

    Your service booking is confirmed.

<?php elseif ($payment_status === 'Partial'): ?>

    <i class="fa-solid fa-circle-half-stroke me-2"></i>

    <strong>Partial payment received.</strong>

    <br>

    The remaining amount can be paid at the service center.

<?php else: ?>

    <i class="fa-solid fa-clock me-2"></i>

    <strong>Payment pending.</strong>

    <br>

    Payment will be completed at the service center.

<?php endif; ?>

<br><br>

Please visit the service center on your
scheduled date for further details.

</div>

<!-- BUTTONS -->

<div class="text-center mt-4 no-print">

    <button
    onclick="window.print()"
    class="btn btn-success me-2">

        <i class="fa-solid fa-download me-1"></i>

        Download / Save Receipt

    </button>


    <a
    href="my_booking.php"
    class="btn btn-outline-dark">

        <i class="fa-solid fa-arrow-left me-1"></i>

        My Bookings

    </a>

</div>


<!-- FOOTER -->

<div class="text-center mt-4 text-muted">

    <small>

        Thank you for choosing FleetSync.

        <br>

        © 2026 FleetSync. All Rights Reserved.

    </small>

</div>


</div>


</body>

</html>