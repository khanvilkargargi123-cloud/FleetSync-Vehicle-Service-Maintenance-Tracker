<?php

session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

include '../config.php';

$sql = "SELECT *
        FROM bookings
        ORDER BY id DESC";

$result = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>Manage Bookings | FleetSync Admin</title>

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


<!-- ================= MAIN ================= -->

<main class="container-fluid admin-main">

    <div class="admin-page-heading">

        <div>

            <h1>

                <i class="fa-solid fa-calendar-check text-info"></i>

                Manage Bookings

            </h1>

            <p>
                View and manage customer service bookings.
            </p>

        </div>

    </div>


    <!-- ================= SEARCH / FILTER ================= -->

    <div class="booking-filter-card">

        <div class="row g-3">

            <div class="col-lg-8">

                <label for="bookingSearch"
                       class="form-label">

                    Search Bookings

                </label>

                <div class="input-group">

                    <span class="input-group-text">

                        <i class="fa-solid fa-magnifying-glass"></i>

                    </span>

                    <input
                        type="text"
                        id="bookingSearch"
                        class="form-control"
                        placeholder="Search customer, email, vehicle, service...">

                </div>

            </div>


            <div class="col-lg-4">

                <label for="statusFilter"
                       class="form-label">

                    Filter by Status

                </label>

                <select id="statusFilter"
                        class="form-select">

                    <option value="">All Status</option>

                    <option value="Pending">
                        Pending
                    </option>

                    <option value="Confirmed">
                        Confirmed
                    </option>

                    <option value="Completed">
                        Completed
                    </option>

                    <option value="Cancelled">
                        Cancelled
                    </option>

                </select>

            </div>

        </div>

    </div>


    <!-- ================= BOOKING TABLE ================= -->

    <div class="booking-table-card">

        <div class="booking-table-header">

            <div>

                <h4>

                    <i class="fa-solid fa-list-check"></i>

                    All Bookings

                </h4>

                <span>
                    Customer service booking records
                </span>

            </div>

            <span class="booking-count">

                <?php echo mysqli_num_rows($result); ?>

                Bookings

            </span>

        </div>


        <div class="table-responsive">

            <table class="table booking-table"
                   id="bookingTable">

                <thead>

                    <tr>

                        <th>ID</th>

                        <th>Customer</th>

                        <th>Vehicle</th>

                        <th>Service</th>

                        <th>Date & Time</th>

                        <th>Status</th>

                        <th>Action</th>

                    </tr>

                </thead>

                <tbody>

                <?php

                if (mysqli_num_rows($result) > 0) {

                    while ($booking = mysqli_fetch_assoc($result)) {

                        $status = $booking['status'];

                ?>

                    <tr>

                        <!-- ID -->

                        <td>

                            <span class="booking-id">

                                #<?php
                                echo (int)$booking['id'];
                                ?>

                            </span>

                        </td>


                        <!-- CUSTOMER -->

                        <td>

                            <div class="customer-info">

                                <strong>

                                    <?php

                                    echo htmlspecialchars(
                                        $booking['customer_name']
                                        ?: 'Customer'
                                    );

                                    ?>

                                </strong>

                                <small>

                                    <?php

                                    echo htmlspecialchars(
                                        $booking['user_email']
                                    );

                                    ?>

                                </small>

                                <?php if (!empty($booking['mobile'])) { ?>

                                    <small>

                                        <i class="fa-solid fa-phone"></i>

                                        <?php

                                        echo htmlspecialchars(
                                            $booking['mobile']
                                        );

                                        ?>

                                    </small>

                                <?php } ?>

                            </div>

                        </td>


                        <!-- VEHICLE -->

                        <td>

                            <div class="vehicle-info">

                                <strong>

                                    <i class="fa-solid fa-car text-info"></i>

                                    <?php

                                    echo htmlspecialchars(
                                        $booking['vehicle_number']
                                    );

                                    ?>

                                </strong>

                                <small>

                                    <?php

                                    echo htmlspecialchars(
                                        $booking['brand'] ?? ''
                                    );

                                    ?>

                                    <?php

                                    if (!empty($booking['model'])) {

                                        echo ' ' .
                                             htmlspecialchars(
                                                 $booking['model']
                                             );

                                    }

                                    ?>

                                </small>

                            </div>

                        </td>


                        <!-- SERVICE -->

                        <td>

                            <span class="service-name">

                                <?php

                                echo htmlspecialchars(
                                    $booking['service_type']
                                );

                                ?>

                            </span>

                            <?php if (!empty($booking['km_reading'])) { ?>

                                <small class="d-block text-muted">

                                    <?php

                                    echo number_format(
                                        (int)$booking['km_reading']
                                    );

                                    ?>

                                    km

                                </small>

                            <?php } ?>

                        </td>


                        <!-- DATE / TIME -->

                        <td>

                            <div class="date-info">

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

                                <small>

                                    <i class="fa-regular fa-clock"></i>

                                    <?php

                                    echo date(
                                        "h:i A",
                                        strtotime(
                                            $booking['booking_time']
                                        )
                                    );

                                    ?>

                                </small>

                            </div>

                        </td>


                        <!-- STATUS -->

                        <td>

                            <?php

                            if ($status === 'Pending') {

                            ?>

                                <span class="status-badge status-pending">

                                    <i class="fa-solid fa-clock"></i>

                                    Pending

                                </span>

                            <?php

                            } elseif ($status === 'Confirmed') {

                            ?>

                                <span class="status-badge status-confirmed">

                                    <i class="fa-solid fa-circle-check"></i>

                                    Confirmed

                                </span>

                            <?php

                            } elseif ($status === 'Completed') {

                            ?>

                                <span class="status-badge status-completed">

                                    <i class="fa-solid fa-check"></i>

                                    Completed

                                </span>

                            <?php

                            } elseif ($status === 'Cancelled') {

                            ?>

                                <span class="status-badge status-cancelled">

                                    <i class="fa-solid fa-xmark"></i>

                                    Cancelled

                                </span>

                            <?php

                            } else {

                            ?>

                                <span class="status-badge status-default">

                                    <?php

                                    echo htmlspecialchars(
                                        $status ?: 'Unknown'
                                    );

                                    ?>

                                </span>

                            <?php

                            }

                            ?>

                        </td>


                        <!-- ACTION -->

                        <td>

                            <a
                                href="admin_booking_details.php?id=<?php echo (int)$booking['id']; ?>"
                                class="btn btn-info btn-sm text-white booking-view-btn">

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

                        <td colspan="7">

                            <div class="empty-bookings">

                                <i class="fa-solid fa-calendar-xmark"></i>

                                <h5>No Bookings Found</h5>

                                <p>
                                    There are currently no customer bookings.
                                </p>

                            </div>

                        </td>

                    </tr>

                <?php

                }

                ?>

                </tbody>

            </table>

        </div>

    </div>

</main>


<!-- ================= FOOTER ================= -->

<footer class="admin-footer">

    <p>

        <i class="fa-solid fa-car-side text-info"></i>

        FleetSync | Admin Panel

    </p>

    <small>

        © 2026 FleetSync. All Rights Reserved.

    </small>

</footer>


<script>

const bookingSearch =
    document.getElementById("bookingSearch");

const statusFilter =
    document.getElementById("statusFilter");

const bookingTable =
    document.getElementById("bookingTable");


function filterBookings() {

    const searchText =
        bookingSearch.value.toLowerCase().trim();

    const selectedStatus =
        statusFilter.value.toLowerCase();

    const rows =
        bookingTable.querySelectorAll("tbody tr");


    rows.forEach(function(row) {

        if (row.cells.length < 7) {
            return;
        }

        const rowText =
            row.innerText.toLowerCase();

        const rowStatus =
            row.cells[5].innerText.toLowerCase();


        const matchesSearch =
            rowText.includes(searchText);

        const matchesStatus =
            selectedStatus === "" ||
            rowStatus.includes(selectedStatus);


        if (matchesSearch && matchesStatus) {

            row.style.display = "";

        } else {

            row.style.display = "none";

        }

    });

}


bookingSearch.addEventListener(
    "input",
    filterBookings
);

statusFilter.addEventListener(
    "change",
    filterBookings
);

</script>

</body>

</html>