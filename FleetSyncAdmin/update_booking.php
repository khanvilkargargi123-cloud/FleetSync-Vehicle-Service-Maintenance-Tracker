<?php

session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

include '../config.php';

/* Only accept POST */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: admin_bookings.php");
    exit();
}

/* Get booking ID */
$booking_id = isset($_POST['booking_id'])
    ? (int)$_POST['booking_id']
    : 0;

if ($booking_id <= 0) {
    header("Location: admin_bookings.php");
    exit();
}


/* Get form values */

$customer_name  = trim($_POST['customer_name'] ?? '');
$user_email     = trim($_POST['user_email'] ?? '');
$mobile         = trim($_POST['mobile'] ?? '');

$vehicle_number = trim($_POST['vehicle_number'] ?? '');
$brand          = trim($_POST['brand'] ?? '');
$model          = trim($_POST['model'] ?? '');
$vehicle_type   = trim($_POST['vehicle_type'] ?? '');
$km_reading     = trim($_POST['km_reading'] ?? '');

$service_type   = trim($_POST['service_type'] ?? '');
$booking_date   = trim($_POST['booking_date'] ?? '');
$booking_time   = trim($_POST['booking_time'] ?? '');

$last_service   = trim($_POST['last_service'] ?? '');
$next_service   = trim($_POST['next_service'] ?? '');

$service_stage  = trim($_POST['service_stage'] ?? '');
$status         = trim($_POST['status'] ?? 'Pending');

$notes          = trim($_POST['notes'] ?? '');


/* Basic validation */

if (
    $customer_name === '' ||
    $user_email === '' ||
    $vehicle_number === '' ||
    $service_type === '' ||
    $booking_date === ''
) {

    header(
        "Location: admin_edit_bookings.php?id=" .
        $booking_id .
        "&error=required"
    );

    exit();
}


/* Validate email */

if (!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {

    header(
        "Location: admin_edit_bookings.php?id=" .
        $booking_id .
        "&error=email"
    );

    exit();
}


/* Allowed status */

$allowed_statuses = [
    'Pending',
    'Confirmed',
    'Completed',
    'Cancelled'
];

if (!in_array($status, $allowed_statuses, true)) {
    $status = 'Pending';
}


/* Update booking */

$stmt = mysqli_prepare(
    $conn,
    "UPDATE bookings SET

        customer_name = ?,
        user_email = ?,
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

        service_stage = ?,
        status = ?,

        notes = ?

     WHERE id = ?"
);


mysqli_stmt_bind_param(
    $stmt,
    "ssssssssssssssssi",
    $customer_name,
    $user_email,
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

    $service_stage,
    $status,

    $notes,

    $booking_id
);


/* Execute */

if (mysqli_stmt_execute($stmt)) {

    mysqli_stmt_close($stmt);

    header(
        "Location: admin_booking_details.php?id=" .
        $booking_id .
        "&updated=1"
    );

    exit();

}


/* Update failed */

mysqli_stmt_close($stmt);

header(
    "Location: admin_edit_bookings.php?id=" .
    $booking_id .
    "&error=update"
);

exit();

?>