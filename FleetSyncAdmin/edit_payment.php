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
    header("Location: admin_payments.php");
    exit();
}

/* Get payment information */
$sql = "SELECT *
        FROM bookings
        WHERE id = ?";

$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    die("Prepare Error: " . mysqli_error($conn));
}

mysqli_stmt_bind_param($stmt, "i", $booking_id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$booking = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);

if (!$booking) {
    die("Booking not found.");
}

function clean($value)
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>Manage Payment | FleetSync Admin</title>

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

<!-- NAVBAR -->

<nav class="navbar navbar-dark bg-dark shadow">

    <div class="container-fluid px-4">

        <a href="admin_dashboard.php"
           class="navbar-brand fw-bold fs-4">

            <i class="fa-solid fa-car-side text-info"></i>

            FleetSync Admin

        </a>

        <a href="admin_payments.php"
           class="btn btn-outline-light">

            <i class="fa-solid fa-arrow-left"></i>

            Back to Payments

        </a>

    </div>

</nav>


<!-- MAIN -->

<div class="container py-5">

    <div class="row justify-content-center">

        <div class="col-lg-8">

            <div class="card shadow border-0">

                <div class="card-body p-4">

                    <h2 class="fw-bold mb-4">

                        <i class="fa-solid fa-money-bill-wave text-success"></i>

                        Manage Payment

                    </h2>


                    <!-- BOOKING INFORMATION -->

                    <div class="alert alert-light border mb-4">

                        <h5 class="fw-bold">
                            Booking Information
                        </h5>

                        <hr>

                        <p class="mb-2">

                            <strong>Booking ID:</strong>

                            #<?php echo clean($booking['id']); ?>

                        </p>

                        <p class="mb-2">

                            <strong>Customer:</strong>

                            <?php
                            echo clean($booking['customer_name']);
                            ?>

                        </p>

                        <p class="mb-2">

                            <strong>Vehicle:</strong>

                            <?php
                            echo clean($booking['vehicle_number']);
                            ?>

                        </p>

                        <p class="mb-0">

                            <strong>Service:</strong>

                            <?php
                            echo clean($booking['service_type']);
                            ?>

                        </p>

                    </div>


                    <!-- PAYMENT FORM -->

                    <form action="update_payment.php"
                          method="POST">

                        <input
                        type="hidden"
                        name="booking_id"
                        value="<?php echo (int)$booking['id']; ?>">


                        <!-- SERVICE AMOUNT -->

                        <div class="mb-3">

                            <label class="form-label fw-bold">

                                Service Amount

                            </label>

                            <div class="input-group">

                                <span class="input-group-text">
                                    ₹
                                </span>

                                <input
                                type="number"
                                name="service_amount"
                                class="form-control"
                                min="0"
                                step="0.01"
                                value="<?php echo clean($booking['service_amount']); ?>"
                                required>

                            </div>

                        </div>


                        <!-- PAYMENT METHOD -->

                        <div class="mb-3">

                            <label class="form-label fw-bold">

                                Payment Method

                            </label>

                            <select
                            name="payment_method"
                            class="form-select"
                            required>

                                <option value="">
                                    Select Payment Method
                                </option>

                                <option value="Cash"
                                <?php
                                echo ($booking['payment_method'] === 'Cash')
                                    ? 'selected'
                                    : '';
                                ?>>
                                    Cash
                                </option>

                                <option value="UPI"
                                <?php
                                echo ($booking['payment_method'] === 'UPI')
                                    ? 'selected'
                                    : '';
                                ?>>
                                    UPI
                                </option>

                                <option value="Card"
                                <?php
                                echo ($booking['payment_method'] === 'Card')
                                    ? 'selected'
                                    : '';
                                ?>>
                                    Card
                                </option>

                                <option value="Other"
                                <?php
                                echo ($booking['payment_method'] === 'Other')
                                    ? 'selected'
                                    : '';
                                ?>>
                                    Other
                                </option>

                            </select>

                        </div>


                        <!-- PAYMENT STATUS -->

                        <div class="mb-3">

                            <label class="form-label fw-bold">

                                Payment Status

                            </label>

                            <select
                            name="payment_status"
                            class="form-select"
                            required>

                                <option value="Pending"
                                <?php
                                echo ($booking['payment_status'] === 'Pending')
                                    ? 'selected'
                                    : '';
                                ?>>
                                    Pending
                                </option>

                                <option value="Partial"
                                <?php
                                echo ($booking['payment_status'] === 'Partial')
                                    ? 'selected'
                                    : '';
                                ?>>
                                    Partial
                                </option>

                                <option value="Paid"
                                <?php
                                echo ($booking['payment_status'] === 'Paid')
                                    ? 'selected'
                                    : '';
                                ?>>
                                    Paid
                                </option>

                            </select>

                        </div>


                        <!-- PAYMENT DATE -->

                        <div class="mb-3">

                            <label class="form-label fw-bold">

                                Payment Date

                            </label>

                            <input
                            type="date"
                            name="payment_date"
                            class="form-control"
                            value="<?php echo clean($booking['payment_date']); ?>">

                        </div>


                        <!-- TRANSACTION ID -->

                        <div class="mb-4">

                            <label class="form-label fw-bold">

                                Transaction / Payment ID

                            </label>

                            <input
                            type="text"
                            name="transaction_id"
                            class="form-control"
                            maxlength="100"
                            value="<?php echo clean($booking['payment_id']); ?>"
                            placeholder="Enter transaction ID if available">

                        </div>


                        <!-- BUTTONS -->

                        <div class="d-flex gap-2">

                            <a href="admin_payments.php"
                               class="btn btn-secondary">

                                <i class="fa-solid fa-arrow-left"></i>

                                Cancel

                            </a>

                            <button
                            type="submit"
                            class="btn btn-success">

                                <i class="fa-solid fa-floppy-disk"></i>

                                Save Payment

                            </button>

                           <a href="admin_payment_receipt.php?id=<?php echo (int)$booking['id']; ?>"
   class="btn btn-success"
   target="_blank">

    <i class="fa-solid fa-receipt me-1"></i>
    View Receipt

</a>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>


<footer class="bg-dark text-white text-center py-4 mt-5">

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