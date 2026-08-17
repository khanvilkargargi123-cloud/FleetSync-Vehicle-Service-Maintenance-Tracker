<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

include 'config.php';
include 'email_config.php';

date_default_timezone_set('Asia/Kolkata');

$today = date("Y-m-d");


/* =========================
   FIND UPCOMING SERVICES
========================= */

$sql = "SELECT *
        FROM bookings
        WHERE booking_date IS NOT NULL
        AND booking_date >= '$today'
        AND booking_date <= DATE_ADD('$today', INTERVAL 7 DAY)
        AND status = 'Confirmed'
        AND reminder_sent = 0";

$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Database Error: " . mysqli_error($conn));
}


if (mysqli_num_rows($result) == 0) {

 echo '<h3 class="no-reminders-message">
      No service reminders are due today or within the next 7 days.
      </h3>';

    exit();
}


/* =========================
   SEND EMAILS
========================= */

while ($booking = mysqli_fetch_assoc($result)) {

    $serviceDate = $booking['booking_date'];

 $daysLeft = floor(
    (strtotime($serviceDate) - strtotime($today)) / 86400
);


    if ($daysLeft == 0) {

        $message =
            "Your vehicle service is due today.";

    } elseif ($daysLeft == 1) {

        $message =
            "Your vehicle service is due tomorrow.";

    } else {

        $message =
            "Your vehicle service is due in " .
            $daysLeft .
            " days.";
    }


    $mail = new PHPMailer(true);


    try {

        /* SMTP */

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


        /* Customer */

        $mail->addAddress(
            $booking['user_email'],
            $booking['customer_name']
        );


        /* Email */

        $mail->isHTML(true);

        $mail->Subject =
            "FleetSync - Upcoming Service Reminder";


        $mail->Body = "

        <div style='font-family:Arial,sans-serif;'>

            <h2>FleetSync Service Reminder</h2>

            <p>
                Hello
                <strong>" .
                htmlspecialchars(
                    $booking['customer_name']
                ) .
                "</strong>,
            </p>

            <p>
                <strong>" .
                htmlspecialchars($message) .
                "</strong>
            </p>

            <hr>

            <p>
                <strong>Vehicle:</strong>
                " .
                htmlspecialchars(
                    $booking['vehicle_number']
                ) .
                "
            </p>

            <p>
                <strong>Service Type:</strong>
                " .
                htmlspecialchars(
                    $booking['service_type']
                ) .
                "
            </p>

            <p>
    <strong>Service Date:</strong>
    " .
    date("d M Y", strtotime($serviceDate))
    . "
</p>
       

            <p>
                Please book your service on time.
            </p>

            <p>
                Thank you for choosing
                <strong>FleetSync</strong>.
            </p>

        </div>

        ";


        $mail->send();


        /* =========================
           MARK EMAIL AS SENT
        ========================= */

        $bookingId =
            (int)$booking['id'];

        $updateSql =
            "UPDATE bookings
             SET reminder_sent = 1
             WHERE id = $bookingId";

        mysqli_query(
            $conn,
            $updateSql
        );


        echo
            "Reminder sent to: " .
            htmlspecialchars(
                $booking['user_email']
            ) .
            "<br>";


    } catch (Exception $e) {

        echo
            "Email failed for " .
            htmlspecialchars(
                $booking['user_email']
            ) .
            "<br>";

    }

}

?>