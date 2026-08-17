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


/* Safe output */
function clean($value)
{
    return htmlspecialchars(
        $value ?? '',
        ENT_QUOTES,
        'UTF-8'
    );
}


/* Get booking */
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


/* Update booking */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $customer_name = trim($_POST['customer_name'] ?? '');
    $mobile = trim($_POST['mobile'] ?? '');

    $vehicle_number = trim($_POST['vehicle_number'] ?? '');
    $brand = trim($_POST['brand'] ?? '');
    $model = trim($_POST['model'] ?? '');
    $vehicle_type = trim($_POST['vehicle_type'] ?? '');
    $km_reading = trim($_POST['km_reading'] ?? '');

    $service_type = trim($_POST['service_type'] ?? '');
    $booking_date = trim($_POST['booking_date'] ?? '');
    $booking_time = trim($_POST['booking_time'] ?? '');

    $last_service = trim($_POST['last_service'] ?? '');
    $next_service = trim($_POST['next_service'] ?? '');

    $service_stage = trim($_POST['service_stage'] ?? '');
    $notes = trim($_POST['notes'] ?? '');


    $update = mysqli_prepare(
        $conn,
        "UPDATE bookings SET
            customer_name = ?,
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
            notes = ?
         WHERE id = ?"
    );


    mysqli_stmt_bind_param(
        $update,
        "ssssssssssssssi",
        $customer_name,
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
        $notes,
        $booking_id
    );


    if (mysqli_stmt_execute($update)) {

        mysqli_stmt_close($update);

        header(
            "Location: admin_booking_details.php?id=" .
            $booking_id
        );

        exit();

    } else {

        $error = "Unable to update booking: " .
                 mysqli_error($conn);

        mysqli_stmt_close($update);
    }
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


<link
rel="stylesheet"
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


        <div class="d-flex align-items-center gap-2">

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

<main class="admin-edit-page">

<div class="container-fluid px-4">


    <!-- HEADER -->

    <div class="admin-page-header">

        <div>

            <h1>

                <i class="fa-solid fa-pen-to-square text-info"></i>

                Edit Booking

            </h1>

            <p>
                Update customer, vehicle and service information.
            </p>

        </div>


        <a
            href="admin_booking_details.php?id=<?php echo (int)$booking['id']; ?>"
            class="btn btn-outline-secondary">

            <i class="fa-solid fa-arrow-left"></i>

            Back to Details

        </a>

    </div>


    <?php if (!empty($error)) { ?>

        <div class="alert alert-danger">

            <i class="fa-solid fa-triangle-exclamation"></i>

            <?php echo clean($error); ?>

        </div>

    <?php } ?>


    <!-- FORM -->

    <form method="POST">


        <!-- CUSTOMER -->

        <div class="admin-edit-card">

            <h3 class="details-title">

                <i class="fa-solid fa-user text-info"></i>

                Customer Information

            </h3>


            <div class="row g-3">


                <div class="col-md-6">

                    <label class="form-label">
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

                    <label class="form-label">
                        Mobile
                    </label>

                    <input
                        type="text"
                        name="mobile"
                        class="form-control"
                        value="<?php echo clean($booking['mobile']); ?>">

                </div>


            </div>

        </div>


        <!-- VEHICLE -->

        <div class="admin-edit-card">

            <h3 class="details-title">

                <i class="fa-solid fa-car text-info"></i>

                Vehicle Information

            </h3>


            <div class="row g-3">


                <div class="col-md-6">

                    <label class="form-label">
                        Vehicle Number
                    </label>

                    <input
                        type="text"
                        name="vehicle_number"
                        class="form-control"
                        value="<?php echo clean($booking['vehicle_number']); ?>"
                        required>

                </div>


                <div class="col-md-6">

                    <label class="form-label">
                        Brand
                    </label>

                    <input
                        type="text"
                        name="brand"
                        class="form-control"
                        value="<?php echo clean($booking['brand']); ?>">

                </div>


                <div class="col-md-6">

                    <label class="form-label">
                        Model
                    </label>

                    <input
                        type="text"
                        name="model"
                        class="form-control"
                        value="<?php echo clean($booking['model']); ?>">

                </div>


                <div class="col-md-6">

                    <label class="form-label">
                        Vehicle Type
                    </label>

                    <input
                        type="text"
                        name="vehicle_type"
                        class="form-control"
                        value="<?php echo clean($booking['vehicle_type']); ?>">

                </div>


                <div class="col-md-6">

                    <label class="form-label">
                        KM Reading
                    </label>

                    <input
                        type="text"
                        name="km_reading"
                        class="form-control"
                        value="<?php echo clean($booking['km_reading']); ?>">

                </div>


            </div>

        </div>


        <!-- SERVICE -->

        <div class="admin-edit-card">

            <h3 class="details-title">

                <i class="fa-solid fa-screwdriver-wrench text-info"></i>

                Service Information

            </h3>


            <div class="row g-3">


                <div class="col-md-6">

                    <label class="form-label">
                        Service Type
                    </label>

                    <input
                        type="text"
                        name="service_type"
                        class="form-control"
                        value="<?php echo clean($booking['service_type']); ?>"
                        required>

                </div>


                <div class="col-md-6">

                    <label class="form-label">
                        Service Stage
                    </label>

                    <input
                        type="text"
                        name="service_stage"
                        class="form-control"
                        value="<?php echo clean($booking['service_stage']); ?>">

                </div>


                <div class="col-md-6">

                    <label class="form-label">
                        Booking Date
                    </label>

                    <input
                        type="date"
                        name="booking_date"
                        class="form-control"
                        value="<?php echo clean($booking['booking_date']); ?>"
                        required>

                </div>


                <div class="col-md-6">

                    <label class="form-label">
                        Booking Time
                    </label>

                    <input
                        type="time"
                        name="booking_time"
                        class="form-control"
                        value="<?php echo clean($booking['booking_time']); ?>">

                </div>


                <div class="col-md-6">

                    <label class="form-label">
                        Last Service
                    </label>

                    <input
                        type="date"
                        name="last_service"
                        class="form-control"
                        value="<?php echo clean($booking['last_service']); ?>">

                </div>


              <label class="form-label fw-semibold">
    Service Reminder Date
</label>

<input
    type="date"
    name="next_service"
    class="form-control"
    value="<?php echo clean($booking['next_service']); ?>">

<small class="text-muted">
    Set the recommended date for the customer's next service.
</small>


            </div>

        </div>


        <!-- NOTES -->

        <div class="admin-edit-card">

            <h3 class="details-title">

                <i class="fa-solid fa-note-sticky text-info"></i>

                Customer Notes

            </h3>


            <textarea
                name="notes"
                class="form-control"
                rows="5"
                placeholder="Enter customer notes..."><?php echo clean($booking['notes']); ?></textarea>

        </div>


        <!-- ACTIONS -->

        <div class="admin-edit-actions">

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

</main>


<!-- FOOTER -->

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