<?php

session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

include '../config.php';


/* =========================
   GET BOOKING ID
========================= */

$booking_id = isset($_GET['id'])
    ? (int) $_GET['id']
    : 0;

if ($booking_id <= 0) {
    die("Invalid booking ID.");
}


/* =========================
   GET LATEST PAYMENT DATA
========================= */

$sql = "SELECT
            id,
            customer_name,
            user_email,
            vehicle_number,
            brand,
            model,
            service_type,
            booking_date,
            booking_time,
            service_amount,
            payment_method,
            payment_status,
            payment_date,
            payment_id
        FROM bookings
        WHERE id = ?";

$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    die("Database Error: " . mysqli_error($conn));
}

mysqli_stmt_bind_param(
    $stmt,
    "i",
    $booking_id
);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$booking = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);


if (!$booking) {
    die("Booking not found.");
}


/* =========================
   PREPARE DATA
========================= */

$customer_name = htmlspecialchars(
    $booking['customer_name'] ?? ''
);

$email = htmlspecialchars(
    $booking['user_email'] ?? ''
);

$vehicle_number = htmlspecialchars(
    $booking['vehicle_number'] ?? ''
);

$brand = htmlspecialchars(
    $booking['brand'] ?? ''
);

$model = htmlspecialchars(
    $booking['model'] ?? ''
);

$service_type = htmlspecialchars(
    $booking['service_type'] ?? ''
);

$payment_method = !empty($booking['payment_method'])
    ? htmlspecialchars($booking['payment_method'])
    : 'Not selected';

$payment_status = $booking['payment_status']
    ?? 'Pending';

$payment_id = !empty($booking['payment_id'])
    ? htmlspecialchars($booking['payment_id'])
    : '—';

$service_amount = (float) (
    $booking['service_amount'] ?? 0
);


/* =========================
   DATE FORMATTING
========================= */

$booking_date = !empty($booking['booking_date'])
    ? date(
        "d M Y",
        strtotime($booking['booking_date'])
    )
    : 'Not available';


$booking_time = !empty($booking['booking_time'])
    ? date(
        "h:i A",
        strtotime($booking['booking_time'])
    )
    : 'Not available';


$payment_date = !empty($booking['payment_date'])
    ? date(
        "d M Y",
        strtotime($booking['payment_date'])
    )
    : '—';

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>
Payment Receipt #<?php echo $booking_id; ?> | FleetSync
</title>


<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css"
rel="stylesheet">


<link
rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.0/css/all.min.css">


<link
rel="stylesheet"
href="css/admin.css">


</head>


<body>


<div class="receipt-wrapper">


<div class="receipt-card">


<!-- HEADER -->

<div class="receipt-header">

<div class="fleetsync-logo">

<i class="fa-solid fa-car-side"></i>

FleetSync

</div>

<p class="text-muted mb-1">

Vehicle Service & Maintenance Tracker

</p>

<h2 class="receipt-heading">

SERVICE PAYMENT RECEIPT

</h2>

<p class="receipt-number">

Receipt / Booking #<?php echo $booking_id; ?>

</p>

</div>


<!-- CUSTOMER -->

<div class="receipt-section">

<div class="receipt-section-title">

<i class="fa-solid fa-user text-info me-2"></i>

Customer Information

</div>


<div class="receipt-row">

<span>Customer Name</span>

<strong>
<?php echo $customer_name; ?>
</strong>

</div>


<div class="receipt-row">

<span>Email</span>

<strong>
<?php echo $email; ?>
</strong>

</div>

</div>


<!-- VEHICLE -->

<div class="receipt-section">

<div class="receipt-section-title">

<i class="fa-solid fa-car text-info me-2"></i>

Vehicle Information

</div>


<div class="receipt-row">

<span>Vehicle Number</span>

<strong>
<?php echo $vehicle_number; ?>
</strong>

</div>


<div class="receipt-row">

<span>Brand</span>

<strong>
<?php echo $brand ?: '—'; ?>
</strong>

</div>


<div class="receipt-row">

<span>Model</span>

<strong>
<?php echo $model ?: '—'; ?>
</strong>

</div>

</div>


<!-- SERVICE -->

<div class="receipt-section">

<div class="receipt-section-title">

<i class="fa-solid fa-screwdriver-wrench text-info me-2"></i>

Service Information

</div>


<div class="receipt-row">

<span>Service Type</span>

<strong>
<?php echo $service_type; ?>
</strong>

</div>


<div class="receipt-row">

<span>Service Date</span>

<strong>
<?php echo $booking_date; ?>
</strong>

</div>


<div class="receipt-row">

<span>Service Time</span>

<strong>
<?php echo $booking_time; ?>
</strong>

</div>

</div>


<!-- PAYMENT -->

<div class="receipt-section">

<div class="receipt-section-title">

<i class="fa-solid fa-money-bill-wave text-success me-2"></i>

Payment Information

</div>


<div class="receipt-row">

<span>Payment Method</span>

<strong>
<?php echo $payment_method; ?>
</strong>

</div>


<div class="receipt-row">

<span>Payment Status</span>

<strong>

<?php

if ($payment_status === 'Paid') {

    echo '
    <span class="status-paid">
        <i class="fa-solid fa-circle-check"></i>
        Paid
    </span>';

} elseif ($payment_status === 'Partial') {

    echo '
    <span class="status-partial">
        <i class="fa-solid fa-clock"></i>
        Partial
    </span>';

} else {

    echo '
    <span class="status-pending">
        <i class="fa-solid fa-hourglass-half"></i>
        Pending
    </span>';

}

?>

</strong>

</div>


<div class="receipt-row">

<span>Payment Date</span>

<strong>
<?php echo $payment_date; ?>
</strong>

</div>


<div class="receipt-row">

<span>Transaction / Payment ID</span>

<strong>
<?php echo $payment_id; ?>
</strong>

</div>

</div>


<!-- AMOUNT -->

<div class="amount-box">

<span>

<i class="fa-solid fa-indian-rupee-sign me-2"></i>

Service Amount

</span>

<span class="amount">

₹<?php echo number_format(
    $service_amount,
    2
); ?>

</span>

</div>


<!-- NOTE -->

<div class="receipt-note">

<i class="fa-solid fa-circle-info me-2"></i>

This receipt displays the latest payment information
recorded by the FleetSync administrator.

</div>


<!-- ACTIONS -->

<div class="receipt-actions no-print">

<button
onclick="window.print()"
class="btn btn-success me-2">

<i class="fa-solid fa-print me-1"></i>

Print / Save Receipt

</button>


<a
href="edit_payment.php?id=<?php echo $booking_id; ?>"
class="btn btn-primary me-2">

<i class="fa-solid fa-pen me-1"></i>

Edit Payment

</a>


<a
href="admin_payments.php"
class="btn btn-outline-dark">

<i class="fa-solid fa-arrow-left me-1"></i>

Back

</a>

</div>


<div class="text-center text-muted mt-4">

<small>

Thank you for choosing FleetSync.

<br>

© 2026 FleetSync. All Rights Reserved.

</small>

</div>


</div>

</div>


</body>

</html>