<?php

session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

include '../config.php';

/* Get booking ID */
$booking_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($booking_id <= 0) {
    header("Location: admin_bookings.php");
    exit();
}

/* Get booking details */
$stmt = mysqli_prepare(
    $conn,
    "SELECT *
     FROM bookings
     WHERE id = ?"
);

mysqli_stmt_bind_param($stmt, "i", $booking_id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$booking = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);

/* Booking not found */
if (!$booking) {
    header("Location: admin_bookings.php");
    exit();
}

/* Safe helper */
function clean($value)
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

$status = $booking['status'] ?? 'Pending';

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>Booking Details | FleetSync Admin</title>

<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css"
rel="stylesheet">

<link
rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.0/css/all.min.css">

<link rel="stylesheet" href="css/admin.css">

</head>

<body>

<!-- ================= NAVBAR ================= -->

<nav class="navbar navbar-dark bg-dark shadow">

    <div class="container-fluid px-4">

        <a href="admin_dashboard.php"
           class="navbar-brand fw-bold fs-4">

            <i class="fa-solid fa-car-side text-info"></i>

            FleetSync Admin

        </a>

        <div class="d-flex align-items-center gap-3">

            <span class="text-light">

                <i class="fa-solid fa-user-shield text-info"></i>

                Admin

            </span>

            <a href="admin_logout.php"
               class="btn btn-outline-danger btn-sm">

                <i class="fa-solid fa-right-from-bracket"></i>

                Logout

            </a>

        </div>

    </div>

</nav>


<!-- ================= MAIN ================= -->

<main class="admin-details-page">

    <div class="container-fluid px-4">

        <!-- PAGE HEADER -->

        <div class="admin-page-header">

            <div>

                <h1>

                    <i class="fa-solid fa-file-lines text-info"></i>

                    Booking Details

                </h1>

                <p>
                    View complete information about this service booking.
                </p>

            </div>

           <div class="booking-actions">

    <a href="admin_edit_bookings.php"
       class="btn btn-secondary">

        <i class="fa-solid fa-arrow-left"></i>

        Back to Bookings

    </a>

    <a href="admin_edit_bookings.php?id=<?php echo (int)$booking['id']; ?>"
       class="btn btn-warning">

        <i class="fa-solid fa-pen-to-square"></i>

        Edit Booking

    </a>

</div>
        </div>


        <!-- BOOKING STATUS -->

        <div class="booking-status-card">

            <div>

                <small>Booking ID</small>

                <h3>
                    #<?php echo clean($booking['id']); ?>
                </h3>

            </div>

            <div>

                <small>Current Status</small>

                <span class="booking-status <?php echo strtolower($status); ?>">

                    <?php echo clean($status); ?>

                </span>

            </div>

        </div>


        <!-- DETAILS -->

        <div class="row g-4">


            <!-- CUSTOMER INFORMATION -->

            <div class="col-lg-6">

                <div class="booking-details-card">

                    <h3 class="details-title">

                        <i class="fa-solid fa-user text-info"></i>

                        Customer Information

                    </h3>


                    <div class="details-row">

                        <span>Customer Name</span>

                        <strong>
                            <?php echo clean($booking['customer_name']); ?>
                        </strong>

                    </div>


                    <div class="details-row">

                        <span>Email</span>

                        <strong>
                            <?php echo clean($booking['user_email']); ?>
                        </strong>

                    </div>


                    <div class="details-row">

                        <span>Mobile</span>

                        <strong>
                            <?php echo clean($booking['mobile']); ?>
                        </strong>

                    </div>

                </div>

            </div>


            <!-- VEHICLE INFORMATION -->

            <div class="col-lg-6">

                <div class="booking-details-card">

                    <h3 class="details-title">

                        <i class="fa-solid fa-car text-info"></i>

                        Vehicle Information

                    </h3>


                    <div class="details-row">

                        <span>Vehicle Number</span>

                        <strong>
                            <?php echo clean($booking['vehicle_number']); ?>
                        </strong>

                    </div>


                    <div class="details-row">

                        <span>Brand</span>

                        <strong>
                            <?php echo clean($booking['brand']); ?>
                        </strong>

                    </div>


                    <div class="details-row">

                        <span>Model</span>

                        <strong>
                            <?php echo clean($booking['model']); ?>
                        </strong>

                    </div>


                    <div class="details-row">

                        <span>Vehicle Type</span>

                        <strong>
                            <?php echo clean($booking['vehicle_type']); ?>
                        </strong>

                    </div>


                    <div class="details-row">

                        <span>KM Reading</span>

                        <strong>
                            <?php echo clean($booking['km_reading']); ?> km
                        </strong>

                    </div>

                </div>

            </div>


            <!-- SERVICE INFORMATION -->

            <div class="col-lg-6">

                <div class="booking-details-card">

                    <h3 class="details-title">

                        <i class="fa-solid fa-screwdriver-wrench text-info"></i>

                        Service Information

                    </h3>


                    <div class="details-row">

                        <span>Service Type</span>

                        <strong>
                            <?php echo clean($booking['service_type']); ?>
                        </strong>

                    </div>


                    <div class="details-row">

                        <span>Booking Date</span>

                        <strong>
                            <?php echo clean($booking['booking_date']); ?>
                        </strong>

                    </div>


                    <div class="details-row">

                        <span>Booking Time</span>

                        <strong>
                            <?php echo clean($booking['booking_time']); ?>
                        </strong>

                    </div>


                    <div class="details-row">

                        <span>Last Service</span>

                        <strong>
                            <?php echo clean($booking['last_service']); ?>
                        </strong>

                    </div>


                    <div class="details-row">

                        <span>Next Service</span>

                        <strong>
                            <?php echo clean($booking['next_service']); ?>
                        </strong>

                    </div>

                </div>

            </div>


            <!-- BOOKING PROGRESS -->

            <div class="col-lg-6">

                <div class="booking-details-card">

                    <h3 class="details-title">

                        <i class="fa-solid fa-list-check text-info"></i>

                        Booking Progress

                    </h3>


                    <div class="details-row">

                        <span>Service Stage</span>

                        <strong>
                            <?php echo clean($booking['service_stage']); ?>
                        </strong>

                    </div>


                    <div class="details-row">

                        <span>Status</span>

                        <strong>
                            <?php echo clean($booking['status']); ?>
                        </strong>

                    </div>


                    <div class="details-row">

                        <span>Reminder Sent</span>

                        <strong>

                            <?php

                            if ((int)$booking['reminder_sent'] === 1) {

                                echo '<span class="text-success">
                                        <i class="fa-solid fa-circle-check"></i>
                                        Yes
                                      </span>';

                            } else {

                                echo '<span class="text-warning">
                                        <i class="fa-solid fa-clock"></i>
                                        No
                                      </span>';

                            }

                            ?>

                        </strong>

                    </div>


                    <div class="details-row">

                        <span>Created At</span>

                        <strong>
                            <?php echo clean($booking['created_at']); ?>
                        </strong>

                    </div>

                </div>

            </div>


<!-- =========================
     PAYMENT INFORMATION
========================= -->

<div class="col-12">

    <div class="booking-details-card">

        <h3 class="details-title">

            <i class="fa-solid fa-credit-card text-info"></i>

            Payment Information

        </h3>

        <div class="details-row">

            <span>Service Amount</span>

            <strong>
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

                if (!empty($booking['payment_method'])) {

                    echo clean($booking['payment_method']);

                } else {

                    echo '<span class="text-muted">
                            Not selected
                          </span>';

                }

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

                } elseif (
                    $paymentStatus === 'Pay at Service Center'
                ) {

                    echo '<span class="text-warning">
                            <i class="fa-solid fa-clock"></i>
                            Pay at Service Center
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

                if (!empty($booking['payment_id'])) {

                    echo clean($booking['payment_id']);

                } else {

                    echo '<span class="text-muted">
                            —
                          </span>';

                }

                ?>

            </strong>

        </div>


        <div class="details-row">

            <span>Payment Date</span>

            <strong>

                <?php

                if (!empty($booking['payment_date'])) {

                    echo date(
                        "d M Y",
                        strtotime($booking['payment_date'])
                    );

                } else {

                    echo '<span class="text-muted">
                            —
                          </span>';

                }

                ?>

            </strong>

        </div>

    </div>

</div>


<!-- STATUS MANAGEMENT -->

<div class="col-12">

    <div class="booking-details-card">

        <h3 class="details-title">
            <i class="fa-solid fa-gear text-info"></i>
            Manage Booking
        </h3>

        <div class="booking-management">

            <?php if ($status === 'Pending') { ?>

                <a href="update_booking_status.php?id=<?php echo (int)$booking['id']; ?>&status=Confirmed"
                   class="btn btn-primary booking-manage-btn">

                    <i class="fa-solid fa-circle-check"></i>
                    Confirm Booking

                </a>

                <a href="update_booking_status.php?id=<?php echo (int)$booking['id']; ?>&status=Cancelled"
                   class="btn btn-danger booking-manage-btn">

                    <i class="fa-solid fa-xmark"></i>
                    Cancel Booking

                </a>

            <?php } elseif ($status === 'Confirmed') { ?>

                <a href="update_booking_status.php?id=<?php echo (int)$booking['id']; ?>&status=Completed"
                   class="btn btn-success booking-manage-btn">

                    <i class="fa-solid fa-check"></i>
                    Mark as Completed

                </a>

                <a href="update_booking_status.php?id=<?php echo (int)$booking['id']; ?>&status=Cancelled"
                   class="btn btn-danger booking-manage-btn">

                    <i class="fa-solid fa-xmark"></i>
                    Cancel Booking

                </a>

            <?php } elseif ($status === 'Completed') { ?>

                <div class="alert alert-success">

                    <i class="fa-solid fa-circle-check me-2"></i>

                    This booking has been completed.

                </div>

            <?php } elseif ($status === 'Cancelled') { ?>

                <div class="alert alert-danger">

                    <i class="fa-solid fa-circle-xmark me-2"></i>

                    This booking has been cancelled.

                </div>

            <?php } ?>

        </div>


        <!-- NEXT SERVICE MANAGEMENT -->

        <?php if ($status === 'Completed') { ?>

            <hr class="my-4">

            <h4 class="mb-3">

                <i class="fa-solid fa-calendar-plus text-info"></i>

                Service Reminder

            </h4>

            <p class="text-muted">

                Set the date when this vehicle should come for its next service.

            </p>


            <form action="save_next_service.php"
                  method="POST">

                <input
                    type="hidden"
                    name="booking_id"
                    value="<?php echo (int)$booking['id']; ?>">


                <div class="row g-3 align-items-end">

                    <div class="col-md-5">

                        <label
                            for="service_completed_date"
                            class="form-label fw-semibold">

                            Service Completed Date

                        </label>

                        <input
                            type="date"
                            id="service_completed_date"
                            name="service_completed_date"
                            class="form-control"
                            value="<?php echo !empty($booking['service_completed_date']) ? htmlspecialchars($booking['service_completed_date']) : date('Y-m-d'); ?>"
                            required>

                    </div>


                    <div class="col-md-5">

                        <label
                            for="next_service"
                            class="form-label fw-semibold">

                            Next Service Date

                        </label>

                        <input
                            type="date"
                            id="next_service"
                            name="next_service"
                            class="form-control"
                            value="<?php echo !empty($booking['next_service']) ? htmlspecialchars($booking['next_service']) : ''; ?>"
                            required>

                    </div>


                    <div class="col-md-2">

                        <button
                            type="submit"
                            class="btn btn-info text-white w-100">

                            <i class="fa-solid fa-save me-1"></i>

                            Save

                        </button>

                    </div>

                </div>

            </form>

        <?php } ?>

    </div>

</div>



<!-- ================= FOOTER ================= -->

<footer class="bg-dark text-white text-center py-4">

    <p class="mb-1">

        <i class="fa-solid fa-car-side text-info"></i>

        FleetSync | Admin Panel

    </p>

    <small>
        © 2026 FleetSync. All Rights Reserved.
    </small>

</footer>

</body>

</html>