<?php
session_start();

include '../config.php';

/* Admin login check */
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

/* Get bookings with payment information */
$sql = "SELECT 
            id,
            customer_name,
            user_email,
            vehicle_number,
            service_type,
            booking_date,
            service_amount,
            payment_method,
            payment_status,
            payment_id
        FROM bookings
        ORDER BY id DESC";

$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Database Error: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Payment Management | FleetSync Admin</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.0/css/all.min.css">

    <link rel="stylesheet" href="css/admin.css">

</head>

<body>

<nav class="navbar navbar-dark bg-dark shadow">

    <div class="container-fluid">

        <a href="admin_dashboard.php"
           class="navbar-brand fw-bold">

            <i class="fa-solid fa-car-side text-info"></i>

            FleetSync Admin

        </a>

        <a href="admin_dashboard.php"
           class="btn btn-outline-light">

            <i class="fa-solid fa-arrow-left"></i>
            Dashboard

        </a>

    </div>

</nav>


<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2 class="fw-bold">

                <i class="fa-solid fa-money-bill-wave text-success"></i>

                Payment Management

            </h2>

            <p class="text-muted mb-0">
                Manage customer service payments and payment status.
            </p>

        </div>

    </div>


    <div class="card shadow-sm border-0">

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead class="table-dark">

                        <tr>

                            <th>Booking ID</th>

                            <th>Customer</th>

                            <th>Vehicle</th>

                            <th>Service</th>

                            <th>Service Date</th>

                            <th>Amount</th>

                            <th>Payment Method</th>

                            <th>Payment Status</th>

                            <th>Payment ID</th>

                            <th>Action</th>

                        </tr>

                    </thead>

                    <tbody>

                    <?php if (mysqli_num_rows($result) > 0): ?>

                        <?php while ($row = mysqli_fetch_assoc($result)): ?>

                            <tr>

                                <td>
                                    #<?php echo $row['id']; ?>
                                </td>

                                <td>

                                    <strong>
                                        <?php
                                        echo htmlspecialchars(
                                            $row['customer_name']
                                        );
                                        ?>
                                    </strong>

                                    <br>

                                    <small class="text-muted">

                                        <?php
                                        echo htmlspecialchars(
                                            $row['user_email']
                                        );
                                        ?>

                                    </small>

                                </td>

                                <td>

                                    <?php
                                    echo htmlspecialchars(
                                        $row['vehicle_number']
                                    );
                                    ?>

                                </td>

                                <td>

                                    <?php
                                    echo htmlspecialchars(
                                        $row['service_type']
                                    );
                                    ?>

                                </td>

                                <td>

                                    <?php
                                    echo date(
                                        "d M Y",
                                        strtotime($row['booking_date'])
                                    );
                                    ?>

                                </td>

                                <td>

                                    <strong class="text-success">

                                        ₹<?php
                                        echo number_format(
                                            (float)$row['service_amount'],
                                            2
                                        );
                                        ?>

                                    </strong>

                                </td>

                                <td>

                                    <?php
                                    echo $row['payment_method']
                                        ? htmlspecialchars(
                                            $row['payment_method']
                                          )
                                        : '<span class="text-muted">Not selected</span>';
                                    ?>

                                </td>

                                <td>

                                    <?php

                                    if ($row['payment_status'] === 'Paid') {

                                        echo '<span class="badge bg-success">
                                                Paid
                                              </span>';

                                    } elseif (
                                        $row['payment_status'] === 'Partial'
                                    ) {

                                        echo '<span class="badge bg-warning text-dark">
                                                Partial
                                              </span>';

                                    } else {

                                        echo '<span class="badge bg-secondary">
                                                Pending
                                              </span>';
                                    }

                                    ?>

                                </td>

                                <td>

                                    <?php
                                    echo $row['payment_id']
                                        ? htmlspecialchars(
                                            $row['payment_id']
                                          )
                                        : '<span class="text-muted">—</span>';
                                    ?>

                                </td>

                                <td>

                                    <a
                                        href="edit_payment.php?id=<?php echo $row['id']; ?>"
                                        class="btn btn-sm btn-primary">

                                        <i class="fa-solid fa-pen"></i>

                                        Manage

                                    </a>

                                </td>

                            </tr>

                        <?php endwhile; ?>

                    <?php else: ?>

                        <tr>

                            <td colspan="10"
                                class="text-center text-muted py-4">

                                No payment records found.

                            </td>

                        </tr>

                    <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>


</body>
</html>