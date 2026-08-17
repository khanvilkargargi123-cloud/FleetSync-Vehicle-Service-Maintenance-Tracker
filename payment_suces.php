<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

include 'config.php';
include 'email_config.php';

session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.html");
    exit();
}

$email = $_SESSION['user'];

/* =========================
   GET DATA
========================= */

$booking_id = isset($_GET['id'])
    ? (int)$_GET['id']
    : 0;

$payment_method = $_GET['method'] ?? '';


/* =========================
   VALIDATE BOOKING ID
========================= */

if ($booking_id <= 0) {
    header("Location: my_booking.php");
    exit();
}


/* =========================
   VALIDATE PAYMENT METHOD
========================= */

$allowed_methods = ['Cash', 'UPI', 'Card'];

if (!in_array($payment_method, $allowed_methods, true)) {
    header(
        "Location: payment_method.php?id=" . $booking_id
    );
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
   PAYMENT MESSAGE
========================= */

if ($payment_method === 'Cash') {

    $title = "Booking Confirmed";

    $message =
        "Your service booking has been confirmed. " .
        "Payment will be completed at the service center.";

    $icon = "fa-money-bill-wave";
    $color = "success";

} else {

    $title = "Payment Successful";

    $message =
        "Your payment has been successfully processed " .
        "and your service booking is confirmed.";

    $icon = "fa-circle-check";
    $color = "info";
}


/*
   Display confirmed instead of database Pending status.
*/

$display_status = "Confirmed";

/* =========================
   SEND CONFIRMATION EMAIL
========================= */

$email_sent = false;

try {

    $mail = new PHPMailer(true);

    $mail->isSMTP();

    $mail->Host = SMTP_HOST;
    $mail->SMTPAuth = true;

    $mail->Username = SMTP_USERNAME;
    $mail->Password = SMTP_PASSWORD;

    $mail->SMTPSecure =
        PHPMailer::ENCRYPTION_STARTTLS;

    $mail->Port = SMTP_PORT;


    /* Sender */

    $mail->setFrom(
        SMTP_FROM_EMAIL,
        SMTP_FROM_NAME
    );


    /* Customer email */

    $mail->addAddress(
        $email,
        $booking['customer_name']
    );


    /* Email */

    $mail->isHTML(true);

    $mail->Subject =
        "FleetSync - Booking Confirmed";


    $mail->Body = "

    <div style='font-family:Arial,sans-serif'>

        <h2>FleetSync</h2>

        <h3>Service Booking Confirmed</h3>

        <p>
            Hello <strong>" .
            htmlspecialchars($booking['customer_name']) .
            "</strong>,
        </p>

        <p>
            Your vehicle service booking has been
            successfully confirmed.
        </p>

        <hr>

        <p>
            <strong>Booking ID:</strong>
            #$booking_id
        </p>

        <p>
            <strong>Vehicle:</strong>
            " .
            htmlspecialchars($booking['vehicle_number']) .
            "
        </p>

        <p>
            <strong>Service:</strong>
            " .
            htmlspecialchars($booking['service_type']) .
            "
        </p>

        <p>
            <strong>Date:</strong>
            " .
            htmlspecialchars($booking['booking_date']) .
            "
        </p>

        <p>
            <strong>Time:</strong>
            " .
            htmlspecialchars($booking['booking_time']) .
            "
        </p>

        <p>
            <strong>Payment Method:</strong>
            " .
            htmlspecialchars($payment_method) .
            "
        </p>

        <p>
            <strong>Status:</strong>
            Confirmed
        </p>

        <hr>

        <p>
            Please visit the service center on your
            scheduled date for further details.
        </p>

        <p>
            Thank you for choosing
            <strong>FleetSync</strong>.
        </p>

    </div>

    ";

    $mail->send();

    $email_sent = true;

} catch (Exception $e) {

    $email_sent = false;

}

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>Payment Successful | FleetSync</title>

<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css"
rel="stylesheet">

<link
rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.0/css/all.min.css">

<link
rel="stylesheet"
href="css/payment.css">

</head>


<body>


<!-- =========================
     NAVBAR
========================= -->

<nav class="navbar navbar-dark bg-dark shadow">

    <div class="container">

        <a
        href="dashboard.php"
        class="navbar-brand fw-bold">

            <i class="fa-solid fa-car-side text-info"></i>

            FleetSync

        </a>

    </div>

</nav>


<!-- =========================
     MAIN CONTENT
========================= -->

<div class="container py-5">

    <div class="row justify-content-center">

        <div class="col-lg-7">

            <div class="card shadow-lg border-0 text-center">

                <div class="card-body p-5">


                    <!-- ICON -->

                    <div class="mb-4">

                        <i
                        class="fa-solid <?php echo $icon; ?>
                        fa-5x text-<?php echo $color; ?>">
                        </i>

                    </div>


                    <!-- TITLE -->

                    <h1 class="mb-3">

                        <?php
                        echo htmlspecialchars($title);
                        ?>

                    </h1>


                    <!-- MESSAGE -->

                    <p class="lead">

                        <?php
                        echo htmlspecialchars($message);
                        ?>

                    </p>


                    <hr>


                    <!-- BOOKING DETAILS -->

                    <div class="text-start mt-4">


                        <!-- BOOKING ID -->

                        <div
                        class="d-flex justify-content-between
                        border-bottom py-3">

                            <span>Booking ID</span>

                            <strong>
                                #<?php echo $booking_id; ?>
                            </strong>

                        </div>


                        <!-- VEHICLE -->

                        <div
                        class="d-flex justify-content-between
                        border-bottom py-3">

                            <span>Vehicle</span>

                            <strong>

                                <?php

                                echo htmlspecialchars(
                                    $booking['vehicle_number']
                                );

                                ?>

                            </strong>

                        </div>


                        <!-- SERVICE -->

                        <div
                        class="d-flex justify-content-between
                        border-bottom py-3">

                            <span>Service</span>

                            <strong>

                                <?php

                                echo htmlspecialchars(
                                    $booking['service_type']
                                );

                                ?>

                            </strong>

                        </div>


                        <!-- DATE -->

                        <div
                        class="d-flex justify-content-between
                        border-bottom py-3">

                            <span>Preferred Date</span>

                            <strong>

                                <?php

                                echo date(
                                    "d M Y",
                                    strtotime(
                                        $booking['booking_date']
                                    )
                                );

                                ?>

                            </strong>

                        </div>


                        <!-- PAYMENT METHOD -->

                        <div
                        class="d-flex justify-content-between
                        border-bottom py-3">

                            <span>Payment Method</span>

                            <strong>

                                <?php

                                echo htmlspecialchars(
                                    $payment_method
                                );

                                ?>

                            </strong>

                        </div>


                        <!-- CONFIRMED STATUS -->

                        <div
                        class="d-flex justify-content-between
                        py-3">

                            <span>Booking Status</span>

                            <strong class="text-success">

                                <i
                                class="fa-solid
                                fa-circle-check me-1">
                                </i>

                                Confirmed

                            </strong>

                        </div>


                    </div>


                    <!-- IMPORTANT MESSAGE -->

                    <div class="alert alert-success mt-4">

                        <i
                        class="fa-solid fa-circle-check me-2">
                        </i>

                        <strong>Booking Confirmed!</strong>

                        <br>

                        Please visit the service center
                        for further details and service updates.

                    </div>


                    <!-- BUTTONS -->

                    <div class="mt-4">

<a
    href="download_receipt.php?id=<?php echo $booking_id; ?>"
    class="btn btn-success me-2">

    <i class="fa-solid fa-download me-1"></i>

    Download Receipt

</a>

                        <a
                        href="booking_details.php?id=<?php echo $booking_id; ?>"
                        class="btn btn-outline-dark me-2">

                            <i
                            class="fa-solid fa-eye">
                            </i>

                            View Booking

                        </a>


                        <a
                        href="my_booking.php"
                        class="btn btn-info text-white">

                            <i
                            class="fa-solid fa-calendar-check">
                            </i>

                            My Bookings

                        </a>


                    </div>


                </div>

            </div>

        </div>

    </div>

</div>


<!-- =========================
     FOOTER
========================= -->

<footer
class="bg-dark text-white text-center py-4 mt-5">

    <p class="mb-1">

        <i
        class="fa-solid fa-car-side text-info">
        </i>

        FleetSync | Vehicle Service &
        Maintenance Tracker

    </p>

    <small>

        © 2026 FleetSync. All Rights Reserved.

    </small>

</footer>


</body>

</html>