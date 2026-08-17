<?php

session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.html");
    exit();
}

include 'config.php';

$email = $_SESSION['user'];

/* =========================
   GET FORM DATA
========================= */

$booking_id = isset($_POST['booking_id'])
    ? (int)$_POST['booking_id']
    : 0;

$payment_method = $_POST['payment_method'] ?? '';

/* =========================
   VALIDATE
========================= */

if ($booking_id <= 0) {
    die("Invalid booking ID.");
}

$allowed_methods = ['Cash', 'UPI', 'Card'];

if (!in_array($payment_method, $allowed_methods, true)) {
    die("Invalid payment method.");
}

/* =========================
   VERIFY BOOKING
========================= */

$sql = "SELECT *
        FROM bookings
        WHERE id = ?
        AND user_email = ?";

$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    die("Prepare Error: " . mysqli_error($conn));
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
    die("Booking not found for this user.");
}

/* =========================
   PAYMENT STATUS
========================= */

$payment_status = 'Pending';
/* =========================
   PAYMENT DATE
========================= */

$payment_date = null;

/* =========================
   UPDATE DATABASE
========================= */

$update_sql = "UPDATE bookings
               SET payment_method = ?,
                   payment_status = ?,
                   payment_date = ?
               WHERE id = ?
               AND user_email = ?";

$stmt = mysqli_prepare($conn, $update_sql);

if (!$stmt) {
    die("Update Prepare Error: " . mysqli_error($conn));
}

mysqli_stmt_bind_param(
    $stmt,
    "sssis",
    $payment_method,
    $payment_status,
    $payment_date,
    $booking_id,
    $email
);

if (!mysqli_stmt_execute($stmt)) {

    die(
        "Update Error: " .
        mysqli_stmt_error($stmt)
    );
}

mysqli_stmt_close($stmt);

/* =========================
   SUCCESS REDIRECT
========================= */

header(
    "Location: payment_suces.php?id=" .
    $booking_id .
    "&method=" .
    urlencode($payment_method)
);

exit();

?>