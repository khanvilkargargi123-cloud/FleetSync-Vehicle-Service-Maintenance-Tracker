<?php

session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

include '../config.php';

/*
 * Get unique vehicles from bookings.
 * Vehicle number is used as the unique identifier.
 */

$sql = "SELECT
            vehicle_number,
            MAX(customer_name) AS customer_name,
            MAX(user_email) AS user_email,
            MAX(mobile) AS mobile,
            MAX(brand) AS brand,
            MAX(model) AS model,
            COUNT(*) AS total_services,
            MAX(booking_date) AS last_service,
            MAX(status) AS status
        FROM bookings
        WHERE vehicle_number IS NOT NULL
        AND vehicle_number != ''
        GROUP BY vehicle_number
        ORDER BY last_service DESC";

$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Vehicle query failed: " . mysqli_error($conn));
}

$total_vehicles = mysqli_num_rows($result);


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
      content="FleetSync Admin Vehicle Management">

<title>Vehicles | FleetSync Admin</title>

<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css"
rel="stylesheet">

<link
rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.0/css/all.min.css">

<link rel="stylesheet" href="css/admin.css">

</head>

<body>


<!-- =====================================================
     NAVBAR
===================================================== -->

<nav class="navbar navbar-dark bg-dark shadow">

    <div class="container-fluid px-4">

        <a href="admin_dashboard.php"
           class="navbar-brand fw-bold fs-4">

            <i class="fa-solid fa-car-side text-info"></i>

            FleetSync Admin

        </a>


        <div class="d-flex align-items-center gap-2">

            <a href="admin_dashboard.php"
               class="btn btn-outline-info btn-sm">

                <i class="fa-solid fa-gauge-high"></i>

                Dashboard

            </a>


            <a href="admin_logout.php"
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

<main class="admin-main">

<div class="container-fluid px-4">


    <!-- =================================================
         PAGE HEADER
    ================================================== -->

    <div class="admin-page-heading">

        <h1>

            <i class="fa-solid fa-car text-info"></i>

            Vehicle Management

        </h1>

        <p>
            View and manage customer vehicles and their service activity.
        </p>

    </div>


    <!-- =================================================
         SUMMARY
    ================================================== -->

    <div class="vehicle-summary-card">

        <div class="vehicle-summary-icon">

            <i class="fa-solid fa-car"></i>

        </div>

        <div>

            <span>Total Vehicles</span>

            <strong>
                <?php echo $total_vehicles; ?>
            </strong>

        </div>

    </div>


    <!-- =================================================
         SEARCH
    ================================================== -->

    <div class="vehicle-filter-card">

        <label
            for="vehicleSearch"
            class="form-label">

            Search Vehicles

        </label>


        <div class="input-group">

            <span class="input-group-text">

                <i class="fa-solid fa-magnifying-glass"></i>

            </span>

            <input
                type="text"
                id="vehicleSearch"
                class="form-control"
                placeholder="Search vehicle number, customer, brand or model...">

        </div>

    </div>


    <!-- =================================================
         VEHICLE TABLE
    ================================================== -->

    <div class="vehicle-table-card">


        <!-- TABLE HEADER -->

        <div class="vehicle-table-header">

            <div>

                <h4>

                    <i class="fa-solid fa-car-side"></i>

                    All Vehicles

                </h4>

                <span>
                    Vehicles registered through FleetSync bookings.
                </span>

            </div>


            <span class="vehicle-count">

                <?php echo $total_vehicles; ?>

                Vehicles

            </span>

        </div>


        <!-- TABLE -->

        <div class="table-responsive">

            <table
                class="table vehicle-table"
                id="vehicleTable">

                <thead>

                    <tr>

                        <th>#</th>

                        <th>Vehicle</th>

                        <th>Customer</th>

                        <th>Contact</th>

                        <th>Services</th>

                        <th>Last Service</th>

                        <th>Status</th>

                        <th>Action</th>

                    </tr>

                </thead>


                <tbody>

                <?php

                if ($total_vehicles > 0) {

                    $count = 1;

                    while ($vehicle =
                        mysqli_fetch_assoc($result)
                    ) {

                        $status =
                            $vehicle['status']
                            ?: 'Pending';

                        $status_class =
                            strtolower(
                                trim($status)
                            );

                ?>

                    <tr>


                        <!-- NUMBER -->

                        <td>

                            <span class="vehicle-number-index">

                                <?php echo $count; ?>

                            </span>

                        </td>


                        <!-- VEHICLE -->

                        <td>

                            <div class="vehicle-profile">

                                <div class="vehicle-avatar">

                                    <i class="fa-solid fa-car"></i>

                                </div>


                                <div>

                                    <strong>

                                        <?php

                                        echo clean(
                                            $vehicle['vehicle_number']
                                        );

                                        ?>

                                    </strong>


                                    <small>

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

                                    </small>

                                </div>

                            </div>

                        </td>


                        <!-- CUSTOMER -->

                        <td>

                            <div class="vehicle-customer">

                                <strong>

                                    <?php

                                    echo clean(
                                        $vehicle['customer_name']
                                        ?: 'Customer'
                                    );

                                    ?>

                                </strong>

                                <small>

                                    <?php

                                    echo clean(
                                        $vehicle['user_email']
                                    );

                                    ?>

                                </small>

                            </div>

                        </td>


                        <!-- CONTACT -->

                        <td>

                            <?php

                            if (!empty($vehicle['mobile'])) {

                            ?>

                                <span class="vehicle-mobile">

                                    <i class="fa-solid fa-phone"></i>

                                    <?php

                                    echo clean(
                                        $vehicle['mobile']
                                    );

                                    ?>

                                </span>

                            <?php

                            } else {

                            ?>

                                <span class="text-muted">

                                    Not available

                                </span>

                            <?php

                            }

                            ?>

                        </td>


                        <!-- SERVICES -->

                        <td>

                            <span class="vehicle-service-count">

                                <i class="fa-solid fa-screwdriver-wrench"></i>

                                <?php

                                echo (int)
                                    $vehicle['total_services'];

                                ?>

                            </span>

                        </td>


                        <!-- LAST SERVICE -->

                        <td>

                            <span class="vehicle-date">

                                <?php

                                if (!empty(
                                    $vehicle['last_service']
                                )) {

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

                            </span>

                        </td>


                        <!-- STATUS -->

                        <td>

                            <span class="vehicle-status
                                vehicle-status-<?php
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

                                <?php echo clean($status); ?>

                            </span>

                        </td>


                        <!-- ACTION -->

                        <td>

                            <a
                                href="admin_vehicle_details.php?vehicle=<?php echo urlencode($vehicle['vehicle_number']); ?>"
                                class="btn btn-info btn-sm text-white vehicle-view-btn">

                                <i class="fa-solid fa-eye"></i>

                                View

                            </a>

                        </td>

                    </tr>

                <?php

                        $count++;

                    }

                } else {

                ?>

                    <tr>

                        <td
                            colspan="8"
                            class="vehicle-empty">

                            <i class="fa-solid fa-car-slash"></i>

                            <h5>
                                No Vehicles Found
                            </h5>

                            <p>
                                Vehicle records will appear after bookings are created.
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


<!-- =====================================================
     SEARCH JAVASCRIPT
===================================================== -->

<script>

const vehicleSearch =
    document.getElementById("vehicleSearch");

const vehicleTable =
    document.getElementById("vehicleTable");

if (vehicleSearch && vehicleTable) {

    vehicleSearch.addEventListener("input", function () {

        const searchValue =
            this.value.toLowerCase().trim();

        const rows =
            vehicleTable.querySelectorAll("tbody tr");

        rows.forEach(function (row) {

            const rowText =
                row.textContent.toLowerCase();

            row.style.display =
                rowText.includes(searchValue)
                ? ""
                : "none";

        });

    });

}

</script>


</body>

</html>