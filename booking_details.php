<?php
include 'config.php';

session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.html");
    exit();
}

$email = $_SESSION['user'];

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "Invalid booking ID.";
    exit();
}

$id = (int) $_GET['id'];

$sql = "SELECT * FROM bookings
        WHERE id='$id'
        AND user_email='$email'";

$result = mysqli_query($conn, $sql);

if (!$result) {
    echo "Database Error: " . mysqli_error($conn);
    exit();
}

$booking = mysqli_fetch_assoc($result);

if (!$booking) {
    echo "Booking not found.";
    exit();
}

/* 20-minute editing period */

$createdTime = strtotime($booking['created_at']);
$currentTime = time();

$secondsPassed = $currentTime - $createdTime;

$canEdit = (
    $secondsPassed >= 0 &&
    $secondsPassed <= (20 * 60) &&
    $booking['status'] === "Pending"
);

/* Status */
$status = $booking['status'];

/* Status class */
if ($status == "Pending") {
    $statusClass = "pending";
} elseif ($status == "Confirmed") {
    $statusClass = "confirmed";
} elseif ($status == "In Progress") {
    $statusClass = "progress";
} elseif ($status == "Completed") {
    $statusClass = "completed";
} elseif ($status == "Cancelled") {
    $statusClass = "cancelled";
} else {
    $statusClass = "confirmed";
}

/* Next service */
$nextService = !empty($booking['next_service'])
    ? date("d M Y", strtotime($booking['next_service']))
    : "Not available";

/* Booking date */
$bookingDate = date("d M Y", strtotime($booking['booking_date']));

/* Booking time */
$bookingTime = date("h:i A", strtotime($booking['booking_time']));

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Booking Details | FleetSync</title>

<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css"
rel="stylesheet">

<link
rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.0/css/all.min.css">

<link rel="stylesheet" href="css/booking_detail.css">

</head>

<body>

<!-- NAVBAR -->

<nav class="navbar navbar-dark bg-dark shadow">

<div class="container">

<a class="navbar-brand fw-bold" href="dashboard.php">

<i class="fa-solid fa-car-side text-info"></i>

FleetSync

</a>

<a href="my_booking.php" class="btn btn-outline-info">

<i class="fa-solid fa-arrow-left"></i>

My Bookings

</a>

</div>

</nav>


<!-- MAIN -->

<div class="container py-5">

<div class="details-card">


<!-- HEADER -->

<div class="details-header">

<div>

<h2>

<i class="fa-solid fa-calendar-check text-info"></i>

Booking Details

</h2>

<p>

Complete information about your vehicle service.

</p>

</div>

<div>

<span class="status-badge <?php echo $statusClass; ?>">

<?php echo htmlspecialchars($status); ?>

</span>

</div>

</div>


<!-- BOOKING ID -->

<div class="booking-id">

<i class="fa-solid fa-hashtag"></i>

Booking ID:

<strong><?php echo $booking['id']; ?></strong>

</div>


<hr>


<!-- CUSTOMER -->

<h4 class="section-title">

<i class="fa-solid fa-user"></i>

Customer Information

</h4>

<div class="row">

<div class="col-md-6">

<div class="info-item">

<span>Customer Name</span>

<strong>
<?php echo htmlspecialchars($booking['customer_name']); ?>
</strong>

</div>

</div>


<div class="col-md-6">

<div class="info-item">

<span>Mobile Number</span>

<strong>
<?php echo htmlspecialchars($booking['mobile']); ?>
</strong>

</div>

</div>

</div>


<hr>


<!-- VEHICLE -->

<h4 class="section-title">

<i class="fa-solid fa-car"></i>

Vehicle Information

</h4>

<div class="row">


<div class="col-md-6">

<div class="info-item">

<span>Vehicle Number</span>

<strong>
<?php echo htmlspecialchars($booking['vehicle_number']); ?>
</strong>

</div>

</div>


<div class="col-md-6">

<div class="info-item">

<span>Vehicle Brand</span>

<strong>
<?php echo htmlspecialchars($booking['brand']); ?>
</strong>

</div>

</div>


<div class="col-md-6">

<div class="info-item">

<span>Vehicle Model</span>

<strong>
<?php echo htmlspecialchars($booking['model']); ?>
</strong>

</div>

</div>


<div class="col-md-6">

<div class="info-item">

<span>Vehicle Type</span>

<strong>
<?php echo htmlspecialchars($booking['vehicle_type']); ?>
</strong>

</div>

</div>


<div class="col-md-6">

<div class="info-item">

<span>Current KM Reading</span>

<strong>
<?php echo htmlspecialchars($booking['km_reading']); ?> KM
</strong>

</div>

</div>

</div>


<!-- IMAGE -->

<?php if (!empty($booking['image'])) { ?>

<div class="vehicle-image-box">

<h5>

<i class="fa-solid fa-image"></i>

Vehicle Image

</h5>

<img
src="uploads/<?php echo htmlspecialchars($booking['image']); ?>"
alt="Vehicle Image">

</div>

<?php } ?>


<hr>


<!-- SERVICE -->

<h4 class="section-title">

<i class="fa-solid fa-screwdriver-wrench"></i>

Service Information

</h4>


<div class="row">


<div class="col-md-6">

<div class="info-item">

<span>Service Type</span>

<strong>
<?php echo htmlspecialchars($booking['service_type']); ?>
</strong>

</div>

</div>


<div class="col-md-6">

<div class="info-item">

<span>Preferred Date</span>

<strong>
<?php echo $bookingDate; ?>
</strong>

</div>

</div>


<div class="col-md-6">

<div class="info-item">

<span>Preferred Time</span>

<strong>
<?php echo $bookingTime; ?>
</strong>

</div>

</div>


<div class="col-md-6">

<div class="info-item next-service">

<span>

<i class="fa-solid fa-bell"></i>

Next Service

</span>

<strong>

<?php echo $nextService; ?>

</strong>

</div>

</div>


<div class="col-12">

<div class="info-item">

<span>Last Service</span>

<strong>

<?php

if (!empty($booking['last_service'])) {

echo date(
"d M Y",
strtotime($booking['last_service'])
);

} else {

echo "Not provided";

}

?>

</strong>

</div>

</div>


</div>


<!-- PAYMENT INFORMATION -->

<div class="col-lg-6">

    <div class="booking-details-card">

        <h3 class="details-title">

            <i class="fa-solid fa-money-bill-wave text-success"></i>

            Payment Information

        </h3>


        <div class="details-row">

            <span>Service Amount</span>

            <strong class="text-success">

                ₹<?php
                echo number_format(
                    (float)($booking['service_amount'] ?? 0),
                    2
                );
                ?>

            </strong>

        </div>


        <div class="details-row">

            <span>Payment Method</span>

            <strong>

               <?php

echo !empty($booking['payment_method'])
    ? htmlspecialchars($booking['payment_method'])
    : 'Not selected';

?>
            </strong>

        </div>


        <div class="details-row">

            <span>Payment Status</span>

            <strong>

                <?php

                $paymentStatus =
                    $booking['payment_status'] ?? 'Pending';


                if ($paymentStatus === 'Paid') {

                    echo '<span class="text-success">
                            <i class="fa-solid fa-circle-check"></i>
                            Paid
                          </span>';

                } elseif ($paymentStatus === 'Partial') {

                    echo '<span class="text-warning">
                            <i class="fa-solid fa-clock"></i>
                            Partial
                          </span>';

                } else {

                    echo '<span class="text-secondary">
                            <i class="fa-solid fa-hourglass-half"></i>
                            Pending
                          </span>';

                }

                ?>

            </strong>

        </div>


        <div class="details-row">

            <span>Payment ID</span>

            <strong>

     <?php

echo !empty($booking['payment_id'])
    ? htmlspecialchars($booking['payment_id'])
    : '—';

?>

            </strong>

        </div>


        <div class="details-row">

            <span>Payment Date</span>

            <strong>

                <?php

                echo !empty($booking['payment_date'])
                    ? date(
                        "d M Y",
                        strtotime($booking['payment_date'])
                    )
                    : '—';

                ?>

            </strong>

        </div>

    </div>

</div>


<!-- NOTES -->

<?php if (!empty($booking['notes'])) { ?>

<div class="notes-box">

<h5>

<i class="fa-solid fa-note-sticky"></i>

Additional Notes

</h5>

<p>

<?php echo nl2br(htmlspecialchars($booking['notes'])); ?>

</p>

</div>

<?php } ?>


<hr>


<!-- SERVICE TRACKING -->

<h4 class="section-title">

<i class="fa-solid fa-route"></i>

Service Tracking

</h4>


<div class="tracking">


<div class="track-step active">

<div class="track-icon">

<i class="fa-solid fa-calendar-check"></i>

</div>

<div>

<strong>Booking Submitted</strong>

<small>Your service request has been received.</small>

</div>

</div>


<div class="track-line"></div>


<div class="track-step

<?php

if (
$status == "Confirmed" ||
$status == "In Progress" ||
$status == "Completed"
) {

echo "active";

}

?>">

<div class="track-icon">

<i class="fa-solid fa-circle-check"></i>

</div>

<div>

<strong>Booking Confirmed</strong>

<small>Service center confirmation.</small>

</div>

</div>


<div class="track-line"></div>


<div class="track-step

<?php

if (
$status == "In Progress" ||
$status == "Completed"
) {

echo "active";

}

?>">

<div class="track-icon">

<i class="fa-solid fa-screwdriver-wrench"></i>

</div>

<div>

<strong>Service In Progress</strong>

<small>Your vehicle is being serviced.</small>

</div>

</div>


<div class="track-line"></div>


<div class="track-step

<?php

if ($status == "Completed") {

echo "active";

}

?>">

<div class="track-icon">

<i class="fa-solid fa-car"></i>

</div>

<div>

<strong>Service Completed</strong>

<small>Your vehicle service is completed.</small>

</div>

</div>

</div>


<!-- BUTTONS -->

<div class="action-buttons">


<a
href="my_booking.php"
class="btn btn-secondary">

<i class="fa-solid fa-arrow-left"></i>

Back to Bookings

</a>

<?php if ($canEdit) { ?>

<a
href="edit_booking.php?id=<?php echo $booking['id']; ?>"
class="btn btn-warning">

<i class="fa-solid fa-pen-to-square"></i>

Edit Booking

</a>

<?php } else { ?>

<span class="btn btn-secondary disabled">

<i class="fa-solid fa-lock"></i>

Booking Locked

</span>

<?php } ?>


<?php if ($status == "Pending") { ?>

<a
href="cancel_booking.php?id=<?php echo $booking['id']; ?>"
class="btn btn-danger"
onclick="return confirm('Are you sure you want to cancel this booking?')">

<i class="fa-solid fa-ban"></i>

Cancel Booking

</a>

<?php } ?>


<a
href="book_service.php"
class="btn btn-info text-white">

<i class="fa-solid fa-plus"></i>

Book Another Service

</a>


</div>


</div>

</div>


<!-- FOOTER -->

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


<script
src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js">
</script>

</body>

</html>