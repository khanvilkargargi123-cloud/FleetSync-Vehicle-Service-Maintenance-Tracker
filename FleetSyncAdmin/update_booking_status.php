<?php

session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

include '../config.php';

/* Get booking ID and status */
$booking_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$status = $_GET['status'] ?? '';

/* Allowed statuses */
$allowed_statuses = [
    'Confirmed',
    'Completed',
    'Cancelled'
];

/* Validate */
if ($booking_id <= 0 || !in_array($status, $allowed_statuses, true)) {
    header("Location: admin_bookings.php");
    exit();
}

/* Update booking status */
$stmt = mysqli_prepare(
    $conn,
    "UPDATE bookings SET status = ? WHERE id = ?"
);

mysqli_stmt_bind_param(
    $stmt,
    "si",
    $status,
    $booking_id
);

mysqli_stmt_execute($stmt);

mysqli_stmt_close($stmt);

/* Return to booking details */
header(
    "Location: admin_booking_details.php?id=" . $booking_id
);

exit();

?>