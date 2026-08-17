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

$service_amount = isset($_POST['service_amount'])
    ? (float)$_POST['service_amount']
    : 0;

$payment_status = $_POST['payment_status'] ?? '';
$payment_method = $_POST['payment_method'] ?? '';
$payment_date = $_POST['payment_date'] ?? '';
$payment_id = trim($_POST['payment_id'] ?? '');


/* =========================
   VALIDATE BOOKING ID
========================= */

if ($booking_id <= 0) {
    die("Invalid booking ID.");
}


/* =========================
   ALLOWED VALUES
========================= */

$allowed_status = [
    'Pending',
    'Paid',
    'Partial'
];

$allowed_methods = [
    'Cash',
    'UPI',
    'Card',
    'Other'
];


/* =========================
   VALIDATE PAYMENT STATUS
========================= */

if (!in_array($payment_status, $allowed_status, true)) {
    die("Invalid payment status.");
}


/* =========================
   VALIDATE PAYMENT METHOD
========================= */

if ($payment_method !== '' &&
    !in_array($payment_method, $allowed_methods, true)) {

    die("Invalid payment method.");
}


/* =========================
   PAYMENT DATE
========================= */

if ($payment_date === '') {
    $payment_date = null;
}


/* =========================
   UPDATE PAYMENT
========================= */

$sql = "UPDATE bookings
        SET service_amount = ?,
            payment_status = ?,
            payment_method = NULLIF(?, ''),
            payment_date = ?,
            payment_id = NULLIF(?, '')
        WHERE id = ?";

$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    die("Prepare Error: " . mysqli_error($conn));
}


mysqli_stmt_bind_param(
    $stmt,
    "dssssi",
    $service_amount,
    $payment_status,
    $payment_method,
    $payment_date,
    $payment_id,
    $booking_id
);


if (!mysqli_stmt_execute($stmt)) {

    die(
        "Update Error: " .
        mysqli_stmt_error($stmt)
    );

}


mysqli_stmt_close($stmt);


/* =========================
   REDIRECT
========================= */

header(
    "Location: admin_payments.php?updated=1"
);

exit();

?>