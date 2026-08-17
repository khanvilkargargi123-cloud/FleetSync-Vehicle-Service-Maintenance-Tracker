<?php

session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

include '../config.php';

/*
 * Get unique customers from bookings.
 * One customer is identified by their email.
 */
$sql = "SELECT
            user_email,
            MAX(customer_name) AS customer_name,
            MAX(mobile) AS mobile,
            COUNT(*) AS total_bookings,
            COUNT(DISTINCT vehicle_number) AS total_vehicles,
            MAX(created_at) AS last_booking
        FROM bookings
        WHERE user_email IS NOT NULL
        AND user_email != ''
        GROUP BY user_email
        ORDER BY last_booking DESC";

$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Customer query failed: " . mysqli_error($conn));
}

$total_customers = mysqli_num_rows($result);

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<meta name="description"
      content="FleetSync Admin Customer Management">

<title>Customers | FleetSync Admin</title>

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

             <a href="admin_bookings.php"
               class="btn btn-outline-light btn-sm">

                <i class="fa-solid fa-calendar-check"></i>

                Bookings

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

<main class="admin-main">

    <div class="container-fluid px-4">

        <!-- PAGE HEADER -->

        <div class="admin-page-heading">

            <h1>

                <i class="fa-solid fa-users text-info"></i>

                Customer Management

            </h1>

            <p>
                View and manage FleetSync customers and their service activity.
            </p>

        </div>


        <!-- ================= CUSTOMER SUMMARY ================= -->

        <div class="customer-summary-card">

            <div class="customer-summary-icon">

                <i class="fa-solid fa-users"></i>

            </div>

            <div>

                <span>Total Customers</span>

                <strong>
                    <?php echo $total_customers; ?>
                </strong>

            </div>

        </div>


        <!-- ================= SEARCH ================= -->

        <div class="customer-filter-card">

            <label
                for="customerSearch"
                class="form-label">

                Search Customers

            </label>


            <div class="input-group">

                <span class="input-group-text">

                    <i class="fa-solid fa-magnifying-glass"></i>

                </span>

                <input
                    type="text"
                    id="customerSearch"
                    class="form-control"
                    placeholder="Search name, email or mobile...">

            </div>

        </div>


        <!-- ================= CUSTOMER TABLE ================= -->

        <div class="customer-table-card">

            <div class="customer-table-header">

                <div>

                    <h4>

                        <i class="fa-solid fa-address-book"></i>

                        All Customers

                    </h4>

                    <span>
                        Customers registered through FleetSync bookings.
                    </span>

                </div>


                <span class="customer-count">

                    <?php echo $total_customers; ?>

                    Customers

                </span>

            </div>


            <div class="table-responsive">

                <table
                    class="table customer-table"
                    id="customerTable">

                    <thead>

                        <tr>

                            <th>#</th>

                            <th>Customer</th>

                            <th>Contact</th>

                            <th>Bookings</th>

                            <th>Vehicles</th>

                            <th>Last Booking</th>

                            <th>Action</th>

                        </tr>

                    </thead>


                    <tbody>

                    <?php

                    if ($total_customers > 0) {

                        $count = 1;

                        while ($customer = mysqli_fetch_assoc($result)) {

                    ?>

                        <tr>

                            <!-- NUMBER -->

                            <td>

                                <span class="customer-number">

                                    <?php echo $count; ?>

                                </span>

                            </td>


                            <!-- CUSTOMER -->

                            <td>

                                <div class="customer-profile">

                                    <div class="customer-avatar">

                                        <i class="fa-solid fa-user"></i>

                                    </div>


                                    <div>

                                        <strong>

                                            <?php

                                            echo htmlspecialchars(
                                                $customer['customer_name']
                                                ?: 'Customer'
                                            );

                                            ?>

                                        </strong>

                                        <small>

                                            Customer

                                        </small>

                                    </div>

                                </div>

                            </td>


                            <!-- CONTACT -->

                            <td>

                                <div class="customer-contact">

                                    <span>

                                        <i class="fa-solid fa-envelope"></i>

                                        <?php

                                        echo htmlspecialchars(
                                            $customer['user_email']
                                        );

                                        ?>

                                    </span>


                                    <?php

                                    if (!empty($customer['mobile'])) {

                                    ?>

                                        <span>

                                            <i class="fa-solid fa-phone"></i>

                                            <?php

                                            echo htmlspecialchars(
                                                $customer['mobile']
                                            );

                                            ?>

                                        </span>

                                    <?php

                                    } else {

                                    ?>

                                        <span class="text-muted">

                                            Mobile not available

                                        </span>

                                    <?php

                                    }

                                    ?>

                                </div>

                            </td>


                            <!-- BOOKINGS -->

                            <td>

                                <span class="customer-stat booking-stat">

                                    <i class="fa-solid fa-calendar-check"></i>

                                    <?php

                                    echo (int)$customer['total_bookings'];

                                    ?>

                                </span>

                            </td>


                            <!-- VEHICLES -->

                            <td>

                                <span class="customer-stat vehicle-stat">

                                    <i class="fa-solid fa-car"></i>

                                    <?php

                                    echo (int)$customer['total_vehicles'];

                                    ?>

                                </span>

                            </td>


                            <!-- LAST BOOKING -->

                            <td>

                                <div class="customer-date">

                                    <?php

                                    if (!empty($customer['last_booking'])) {

                                        echo date(
                                            "d M Y",
                                            strtotime(
                                                $customer['last_booking']
                                            )
                                        );

                                    } else {

                                        echo "N/A";

                                    }

                                    ?>

                                </div>

                            </td>


                            <!-- ACTION -->

                            <td>

                                <a
                                    href="admin_customer_details.php?email=<?php echo urlencode($customer['user_email']); ?>"
                                    class="btn btn-info btn-sm text-white customer-view-btn">

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
                                colspan="7"
                                class="customer-empty">

                                <i class="fa-solid fa-users-slash"></i>

                                <h5>
                                    No Customers Found
                                </h5>

                                <p>
                                    Customer records will appear after bookings are created.
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


<!-- ================= SEARCH ================= -->

<script>

const customerSearch =
    document.getElementById("customerSearch");

const customerTable =
    document.getElementById("customerTable");

if (customerSearch && customerTable) {

    customerSearch.addEventListener("input", function () {

        const searchValue =
            this.value.toLowerCase().trim();

        const rows =
            customerTable.querySelectorAll("tbody tr");

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