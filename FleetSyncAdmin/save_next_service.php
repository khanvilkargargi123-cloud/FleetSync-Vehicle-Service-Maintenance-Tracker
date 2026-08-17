<?php

session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

include '../config.php';


/* =========================
   GET FORM DATA
========================= */

$booking_id = isset($_POST['booking_id'])
    ? (int)$_POST['booking_id']
    : 0;

$service_completed_date =
    $_POST['service_completed_date'] ?? '';

$next_service =
    $_POST['next_service'] ?? '';


/* =========================
   VALIDATE
========================= */

if (
    $booking_id <= 0 ||
    empty($service_completed_date) ||
    empty($next_service)
) {
    die("Invalid service date information.");
}


/* =========================
   CHECK DATE
========================= */

if ($next_service <= $service_completed_date) {
    die("Next service date must be after the service completed date.");
}


/* =========================
   UPDATE BOOKING
========================= */

$sql = "UPDATE bookings
        SET
            service_completed_date = ?,
            next_service = ?,
            status = 'Completed'
        WHERE id = ?";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param(
    $stmt,
    "ssi",
    $service_completed_date,
    $next_service,
    $booking_id
);

if (!mysqli_stmt_execute($stmt)) {

    die(
        "Database Error: " .
        mysqli_error($conn)
    );
}

mysqli_stmt_close($stmt);


/* =========================
   RETURN TO BOOKING
========================= */

header(
    "Location: admin_booking_details.php?id=" .
    $booking_id
);

exit();

?>