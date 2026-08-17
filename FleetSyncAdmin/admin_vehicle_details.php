<?php

session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

include '../config.php';

$vehicle_number = isset($_GET['vehicle'])
    ? trim($_GET['vehicle'])
    : '';

if ($vehicle_number === '') {
    header("Location: admin_vehicles.php");
    exit();
}


/* =========================
   VEHICLE INFORMATION
========================= */

$stmt = mysqli_prepare(
    $conn,
    "SELECT
        vehicle_number,
        MAX(customer_name) AS customer_name,
        MAX(user_email) AS user_email,
        MAX(mobile) AS mobile,
        MAX(brand) AS brand,
        MAX(model) AS model,
        COUNT(*) AS total_services,
        MAX(booking_date) AS last_service
     FROM bookings
     WHERE vehicle_number = ?
     GROUP BY vehicle_number"
);

mysqli_stmt_bind_param(
    $stmt,
    "s",
    $vehicle_number
);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$vehicle = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);


/* Vehicle not found */

if (!$vehicle) {
    header("Location: admin_vehicles.php");
    exit();
}


/* =========================
   SERVICE HISTORY
========================= */

$stmt = mysqli_prepare(
    $conn,
    "SELECT *
     FROM bookings
     WHERE vehicle_number = ?
     ORDER BY id DESC"
);

mysqli_stmt_bind_param(
    $stmt,
    "s",
    $vehicle_number
);

mysqli_stmt_execute($stmt);

$history_result = mysqli_stmt_get_result($stmt);


/* =========================
   SAFE OUTPUT
========================= */

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
      content="FleetSync Admin Vehicle Details">

<title>
    Vehicle Details | FleetSync Admin
</title>


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


<!-- =====================================================
     NAVBAR
===================================================== -->

<nav class="navbar navbar-dark bg-dark shadow">

    <div class="container-fluid px-4">

        <a
            href="admin_dashboard.php"
            class="navbar-brand fw-bold fs-4">

            <i class="fa-solid fa-car-side text-info"></i>

            FleetSync Admin

        </a>


        <div class="d-flex align-items-center gap-2">

            <a
                href="admin_vehicles.php"
                class="btn btn-outline-info btn-sm">

                <i class="fa-solid fa-car"></i>

                Vehicles

            </a>


            <a
                href="admin_logout.php"
                class="btn btn-outline-danger btn-sm">

                <i class="fa-solid fa-right-from-bracket"></i>

                Logout

            </a>

        </div>

    </div>

</nav>


<!-- =====================================================
     MAIN
===================================================== -->

<main class="vehicle-details-page">

<div class="container-fluid px-4">


    <!-- =================================================
         HEADER
    ================================================== -->

    <div class="vehicle-details-header">

        <div>

            <h1>

                <i class="fa-solid fa-car text-info"></i>

                Vehicle Details

            </h1>

            <p>
                View vehicle information and complete service history.
            </p>

        </div>


        <a
            href="admin_vehicles.php"
            class="btn btn-outline-secondary">

            <i class="fa-solid fa-arrow-left"></i>

            Back to Vehicles

        </a>

    </div>


    <!-- =================================================
         VEHICLE SUMMARY
    ================================================== -->

    <div class="vehicle-details-summary">

        <div class="vehicle-details-avatar">

            <i class="fa-solid fa-car"></i>

        </div>


        <div class="vehicle-details-info">

            <span>Vehicle Number</span>

            <h2>

                <?php
                echo clean(
                    $vehicle['vehicle_number']
                );
                ?>

            </h2>


            <p>

                <i class="fa-solid fa-car-side"></i>

                <?php

                $vehicle_name = trim(
                    ($vehicle['brand'] ?? '') .
                    ' ' .
                    ($vehicle['model'] ?? '')
                );

                echo clean(
                    $vehicle_name
                    ?: 'Vehicle details unavailable'
                );

                ?>

            </p>

        </div>

    </div>


    <!-- =================================================
         VEHICLE INFORMATION
    ================================================== -->

    <div class="row g-4 mb-4">


        <!-- CUSTOMER -->

        <div class="col-lg-6">

            <div class="vehicle-info-card">

                <h4>

                    <i class="fa-solid fa-user"></i>

                    Customer Information

                </h4>


                <div class="vehicle-info-row">

                    <span>
                        Customer Name
                    </span>

                    <strong>

                        <?php
                        echo clean(
                            $vehicle['customer_name']
                            ?: 'Customer'
                        );
                        ?>

                    </strong>

                </div>


                <div class="vehicle-info-row">

                    <span>
                        Email
                    </span>

                    <strong>

                        <?php
                        echo clean(
                            $vehicle['user_email']
                        );
                        ?>

                    </strong>

                </div>


                <div class="vehicle-info-row">

                    <span>
                        Mobile
                    </span>

                    <strong>

                        <?php

                        echo !empty($vehicle['mobile'])
                            ? clean($vehicle['mobile'])
                            : 'Not available';

                        ?>

                    </strong>

                </div>

            </div>

        </div>


        <!-- VEHICLE -->

        <div class="col-lg-6">

            <div class="vehicle-info-card">

                <h4>

                    <i class="fa-solid fa-car"></i>

                    Vehicle Information

                </h4>


                <div class="vehicle-info-row">

                    <span>
                        Vehicle Number
                    </span>

                    <strong>

                        <?php
                        echo clean(
                            $vehicle['vehicle_number']
                        );
                        ?>

                    </strong>

                </div>


                <div class="vehicle-info-row">

                    <span>
                        Brand
                    </span>

                    <strong>

                        <?php
                        echo clean(
                            $vehicle['brand']
                            ?: 'Not available'
                        );
                        ?>

                    </strong>

                </div>


                <div class="vehicle-info-row">

                    <span>
                        Model
                    </span>

                    <strong>

                        <?php
                        echo clean(
                            $vehicle['model']
                            ?: 'Not available'
                        );
                        ?>

                    </strong>

                </div>

            </div>

        </div>

    </div>


    <!-- =================================================
         STATISTICS
    ================================================== -->

    <div class="row g-4 mb-4">


        <div class="col-md-6">

            <div class="vehicle-detail-stat">

                <div class="vehicle-detail-stat-icon">

                    <i class="fa-solid fa-screwdriver-wrench"></i>

                </div>

                <div>

                    <span>
                        Total Services
                    </span>

                    <strong>

                        <?php
                        echo (int)$vehicle['total_services'];
                        ?>

                    </strong>

                </div>

            </div>

        </div>


        <div class="col-md-6">

            <div class="vehicle-detail-stat">

                <div class="vehicle-detail-stat-icon last">

                    <i class="fa-solid fa-calendar-check"></i>

                </div>

                <div>

                    <span>
                        Last Service
                    </span>

                    <strong>

                        <?php

                        if (!empty($vehicle['last_service'])) {

                            echo date(
                                "d M Y",
                                strtotime(
                                    $vehicle['last_service']
                                )
                            );

                        } else {

                            echo "N/A";

                        }

                        ?>

                    </strong>

                </div>

            </div>

        </div>

    </div>


    <!-- =================================================
         SERVICE HISTORY
    ================================================== -->

    <div class="vehicle-history-card">


        <div class="vehicle-history-header">

            <div>

                <h4>

                    <i class="fa-solid fa-clock-rotate-left"></i>

                    Service History

                </h4>

                <span>
                    All service bookings for this vehicle.
                </span>

            </div>


            <span class="vehicle-history-count">

                <?php
                echo (int)$vehicle['total_services'];
                ?>

                Services

            </span>

        </div>


        <!-- TABLE -->

        <div class="table-responsive">

            <table class="table vehicle-history-table">

                <thead>

                    <tr>

                        <th>ID</th>

                        <th>Service</th>

                        <th>Date</th>

                        <th>Time</th>

                        <th>Status</th>

                        <th>Action</th>

                    </tr>

                </thead>


                <tbody>

                <?php

                if (mysqli_num_rows($history_result) > 0) {

                    while (
                        $booking =
                        mysqli_fetch_assoc($history_result)
                    ) {

                        $status =
                            $booking['status']
                            ?: 'Pending';

                        $status_class =
                            strtolower(
                                trim($status)
                            );

                ?>

                    <tr>


                        <!-- ID -->

                        <td>

                            <strong class="vehicle-booking-id">

                                #

                                <?php
                                echo (int)$booking['id'];
                                ?>

                            </strong>

                        </td>


                        <!-- SERVICE -->

                        <td>

                            <strong class="vehicle-service-name">

                                <i class="fa-solid fa-screwdriver-wrench"></i>

                                <?php

                                echo clean(
                                    $booking['service_type']
                                    ?? 'Service'
                                );

                                ?>

                            </strong>

                        </td>


                        <!-- DATE -->

                        <td>

                            <?php

                            if (!empty(
                                $booking['booking_date']
                            )) {

                                echo date(
                                    "d M Y",
                                    strtotime(
                                        $booking['booking_date']
                                    )
                                );

                            } else {

                                echo "N/A";

                            }

                            ?>

                        </td>


                        <!-- TIME -->

                        <td>

                            <?php

                            if (!empty(
                                $booking['booking_time']
                            )) {

                                echo date(
                                    "h:i A",
                                    strtotime(
                                        $booking['booking_time']
                                    )
                                );

                            } else {

                                echo "N/A";

                            }

                            ?>

                        </td>


                        <!-- STATUS -->

                        <td>

                            <span class="vehicle-detail-status
                                vehicle-detail-status-<?php
                                echo clean($status_class);
                                ?>">

                                <?php

                                if ($status === 'Pending') {

                                    echo '<i class="fa-solid fa-clock"></i>';

                                } elseif ($status === 'Confirmed') {

                                    echo '<i class="fa-solid fa-circle-check"></i>';

                                } elseif ($status === 'Completed') {

                                    echo '<i class="fa-solid fa-check"></i>';

                                } elseif ($status === 'Cancelled') {

                                    echo '<i class="fa-solid fa-xmark"></i>';

                                }

                                ?>

                                <?php
                                echo clean($status);
                                ?>

                            </span>

                        </td>


                        <!-- ACTION -->

                        <td>

                            <a
                                href="admin_booking_details.php?id=<?php echo (int)$booking['id']; ?>"
                                class="btn btn-info btn-sm text-white">

                                <i class="fa-solid fa-eye"></i>

                                View

                            </a>

                        </td>

                    </tr>

                <?php

                    }

                } else {

                ?>

                    <tr>

                        <td
                            colspan="6"
                            class="text-center py-5">

                            <i class="fa-solid fa-calendar-xmark fs-1 text-muted"></i>

                            <h5 class="mt-3">
                                No Service History
                            </h5>

                            <p class="text-muted mb-0">

                                No service bookings found
                                for this vehicle.

                            </p>

                        </td>

                    </tr>

                <?php

                }

                ?>

                </tbody>

            </table>

        </div>

    </div>


    <!-- =================================================
         BACK BUTTON
    ================================================== -->

    <div class="vehicle-details-actions">

        <a
            href="admin_vehicles.php"
            class="btn btn-secondary">

            <i class="fa-solid fa-arrow-left"></i>

            Back to Vehicles

        </a>

    </div>

</div>

</main>


<!-- =====================================================
     FOOTER
===================================================== -->

<footer class="admin-footer">

    <p>

        <i class="fa-solid fa-car-side text-info"></i>

        FleetSync | Admin Panel

    </p>

    <small>
        © 2026 FleetSync. All Rights Reserved.
    </small>

</footer>


</body>

</html>

<?php

mysqli_stmt_close($stmt);

?>