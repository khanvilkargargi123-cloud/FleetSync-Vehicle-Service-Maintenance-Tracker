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

/* Get existing booking */
$stmt = mysqli_prepare(
    $conn,
    "SELECT * FROM bookings WHERE id = ?"
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


/* Safe output */
function clean($value)
{
    return htmlspecialchars(
        $value ?? '',
        ENT_QUOTES,
        'UTF-8'
    );
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<meta name="description"
      content="Edit FleetSync service booking">

<title>Edit Booking | FleetSync Admin</title>

<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css"
rel="stylesheet">

<link
rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.0/css/all.min.css">

<link rel="stylesheet"
      href="css/admin.css">

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


     <!-- ================= MAIN ================= -->

<main class="admin-details-page">

<div class="container-fluid px-4">

    <!-- PAGE HEADER -->

    <div class="admin-page-header">

        <div>

            <h1>
                <i class="fa-solid fa-pen-to-square text-info"></i>
                Edit Booking
            </h1>

            <p>
                Update customer, vehicle and service booking information.
            </p>

        </div>

        <a
            href="admin_booking_details.php?id=<?php echo (int)$booking['id']; ?>"
            class="btn btn-outline-secondary">

            <i class="fa-solid fa-arrow-left"></i>
            Back to Details

        </a>

    </div>

    <!-- ================= EDIT FORM ================= -->

    <div class="booking-details-card">

        <h3 class="details-title">

            <i class="fa-solid fa-file-pen text-info"></i>

            Booking #<?php echo (int)$booking['id']; ?>

        </h3>


        <form action="update_booking.php"
              method="POST">


            <input type="hidden"
                   name="booking_id"
                   value="<?php echo (int)$booking['id']; ?>">



            <!-- CUSTOMER -->

            <div class="row g-3 mb-4">

                <div class="col-md-6">

                    <label class="form-label fw-semibold">
                        Customer Name
                    </label>

                    <input
                        type="text"
                        name="customer_name"
                        class="form-control"
                        value="<?php echo clean($booking['customer_name']); ?>"
                        required>

                </div>


                <div class="col-md-6">

                    <label class="form-label fw-semibold">
                        Mobile
                    </label>

                    <input
                        type="text"
                        name="mobile"
                        class="form-control"
                        value="<?php echo clean($booking['mobile']); ?>">

                </div>


                <div class="col-md-12">

                    <label class="form-label fw-semibold">
                        Email
                    </label>

                    <input
                        type="email"
                        name="user_email"
                        class="form-control"
                        value="<?php echo clean($booking['user_email']); ?>"
                        required>

                </div>

            </div>



            <!-- VEHICLE -->

            <h5 class="fw-bold mb-3">

                <i class="fa-solid fa-car text-info"></i>

                Vehicle Information

            </h5>


            <div class="row g-3 mb-4">

                <div class="col-md-4">

                    <label class="form-label fw-semibold">
                        Vehicle Number
                    </label>

                    <input
                        type="text"
                        name="vehicle_number"
                        class="form-control"
                        value="<?php echo clean($booking['vehicle_number']); ?>"
                        required>

                </div>


                <div class="col-md-4">

                    <label class="form-label fw-semibold">
                        Brand
                    </label>

                    <input
                        type="text"
                        name="brand"
                        class="form-control"
                        value="<?php echo clean($booking['brand']); ?>">

                </div>


                <div class="col-md-4">

                    <label class="form-label fw-semibold">
                        Model
                    </label>

                    <input
                        type="text"
                        name="model"
                        class="form-control"
                        value="<?php echo clean($booking['model']); ?>">

                </div>


                <div class="col-md-6">

                    <label class="form-label fw-semibold">
                        Vehicle Type
                    </label>

                    <input
                        type="text"
                        name="vehicle_type"
                        class="form-control"
                        value="<?php echo clean($booking['vehicle_type']); ?>">

                </div>


                <div class="col-md-6">

                    <label class="form-label fw-semibold">
                        KM Reading
                    </label>

                    <input
                        type="number"
                        name="km_reading"
                        class="form-control"
                        value="<?php echo clean($booking['km_reading']); ?>">

                </div>

            </div>



            <!-- SERVICE -->

            <h5 class="fw-bold mb-3">

                <i class="fa-solid fa-screwdriver-wrench text-info"></i>

                Service Information

            </h5>


            <div class="row g-3 mb-4">

                <div class="col-md-6">

                    <label class="form-label fw-semibold">
                        Service Type
                    </label>

                    <input
                        type="text"
                        name="service_type"
                        class="form-control"
                        value="<?php echo clean($booking['service_type']); ?>"
                        required>

                </div>


                <div class="col-md-3">

                    <label class="form-label fw-semibold">
                        Booking Date
                    </label>

                    <input
                        type="date"
                        name="booking_date"
                        class="form-control"
                        value="<?php echo clean($booking['booking_date']); ?>"
                        required>

                </div>


                <div class="col-md-3">

                    <label class="form-label fw-semibold">
                        Booking Time
                    </label>

                    <input
                        type="time"
                        name="booking_time"
                        class="form-control"
                        value="<?php echo clean($booking['booking_time']); ?>">

                </div>


                <div class="col-md-6">

                    <label class="form-label fw-semibold">
                        Last Service
                    </label>

                    <input
                        type="date"
                        name="last_service"
                        class="form-control"
                        value="<?php echo clean($booking['last_service']); ?>">

                </div>


                <div class="col-md-6">

                    <label class="form-label fw-semibold">
                        Next Service
                    </label>

                    <input
                        type="date"
                        name="next_service"
                        class="form-control"
                        value="<?php echo clean($booking['next_service']); ?>">

                </div>

            </div>



            <!-- PROGRESS -->

            <h5 class="fw-bold mb-3">

                <i class="fa-solid fa-list-check text-info"></i>

                Booking Progress

            </h5>


            <div class="row g-3 mb-4">

                <div class="col-md-6">

                    <label class="form-label fw-semibold">
                        Service Stage
                    </label>

              <select name="service_stage" class="form-select">

    <option value="Booking Confirmed"
        <?php echo ($booking['service_stage'] === 'Booking Confirmed') ? 'selected' : ''; ?>>
        Booking Confirmed
    </option>

    <option value="Vehicle Received"
        <?php echo ($booking['service_stage'] === 'Vehicle Received') ? 'selected' : ''; ?>>
        Vehicle Received
    </option>

    <option value="Service In Progress"
        <?php echo ($booking['service_stage'] === 'Service In Progress') ? 'selected' : ''; ?>>
        Service In Progress
    </option>

    <option value="Quality Check"
        <?php echo ($booking['service_stage'] === 'Quality Check') ? 'selected' : ''; ?>>
        Quality Check
    </option>

    <option value="Ready for Pickup"
        <?php echo ($booking['service_stage'] === 'Ready for Pickup') ? 'selected' : ''; ?>>
        Ready for Pickup
    </option>

    <option value="Completed"
        <?php echo ($booking['service_stage'] === 'Completed') ? 'selected' : ''; ?>>
        Completed
    </option>

</select>

                </div>


                <div class="col-md-6">

                    <label class="form-label fw-semibold">
                        Status
                    </label>

                    <select
                        name="status"
                        class="form-select"
                        required>

                        <option value="Pending"
                            <?php
                            echo ($booking['status'] === 'Pending')
                                ? 'selected'
                                : '';
                            ?>>
                            Pending
                        </option>

                        <option value="Confirmed"
                            <?php
                            echo ($booking['status'] === 'Confirmed')
                                ? 'selected'
                                : '';
                            ?>>
                            Confirmed
                        </option>

                        <option value="Completed"
                            <?php
                            echo ($booking['status'] === 'Completed')
                                ? 'selected'
                                : '';
                            ?>>
                            Completed
                        </option>

                        <option value="Cancelled"
                            <?php
                            echo ($booking['status'] === 'Cancelled')
                                ? 'selected'
                                : '';
                            ?>>
                            Cancelled
                        </option>

                    </select>

                </div>

            </div>



            <!-- NOTES -->

            <div class="mb-4">

                <label class="form-label fw-semibold">

                    <i class="fa-solid fa-note-sticky text-info"></i>

                    Customer Notes

                </label>

                <textarea
                    name="notes"
                    class="form-control"
                    rows="5"><?php echo clean($booking['notes']); ?></textarea>

            </div>



            <!-- ACTIONS -->

            <div class="d-flex gap-2 flex-wrap">

                <button
                    type="submit"
                    class="btn btn-info text-white">

                    <i class="fa-solid fa-floppy-disk"></i>

                    Save Changes

                </button>


                <a
                    href="admin_booking_details.php?id=<?php echo (int)$booking['id']; ?>"
                    class="btn btn-secondary">

                    <i class="fa-solid fa-xmark"></i>

                    Cancel

                </a>

            </div>


        </form>

    </div>

</div>

</main>



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