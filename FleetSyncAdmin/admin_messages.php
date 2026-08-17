<?php

session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

include '../config.php';

$sql = "SELECT *
        FROM contact_messages
        ORDER BY id DESC";

$result = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<meta name="description"
      content="FleetSync Admin Customer Messages Management">

<title>Customer Messages | FleetSync Admin</title>

<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css"
rel="stylesheet">

<link
rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.0/css/all.min.css">

<link rel="stylesheet" href="css/admin.css">

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


<!-- MAIN -->

<main class="admin-main">

    <div class="container-fluid px-4">

        <!-- PAGE HEADER -->

        <div class="admin-page-heading">

            <h1>

                <i class="fa-solid fa-envelope text-info"></i>

                Customer Messages

            </h1>

            <p>
                View customer enquiries and service-related messages.
            </p>

        </div>


        <!-- SEARCH -->

        <div class="message-filter-card">

            <div class="row g-3 align-items-end">

                <div class="col-lg-8">

                    <label
                        for="messageSearch"
                        class="form-label">

                        Search Messages

                    </label>

                    <div class="input-group">

                        <span class="input-group-text">

                            <i class="fa-solid fa-magnifying-glass"></i>

                        </span>

                        <input
                            type="text"
                            id="messageSearch"
                            class="form-control"
                            placeholder="Search name, email, phone, vehicle, subject...">

                    </div>

                </div>


                <div class="col-lg-4">

                    <div class="message-count-box">

                        <i class="fa-solid fa-envelope-open-text"></i>

                        <span>

                            <?php echo mysqli_num_rows($result); ?>

                            Messages

                        </span>

                    </div>

                </div>

            </div>

        </div>


        <!-- MESSAGES TABLE -->

        <div class="message-table-card">

            <div class="message-table-header">

                <div>

                    <h4>

                        <i class="fa-solid fa-comments"></i>

                        Customer Enquiries

                    </h4>

                    <span>
                        Messages submitted through FleetSync contact form.
                    </span>

                </div>

            </div>


            <div class="table-responsive">

                <table
                    class="table message-table"
                    id="messageTable">

                    <thead>

                        <tr>

                            <th>ID</th>

                            <th>Customer</th>

                            <th>Contact</th>

                            <th>Vehicle</th>

                            <th>Subject</th>

                            <th>Message</th>

                            <th>Date</th>

                        </tr>

                    </thead>

                    <tbody>

                    <?php

                    if (mysqli_num_rows($result) > 0) {

                        while ($message = mysqli_fetch_assoc($result)) {

                    ?>

                        <tr>

                            <!-- ID -->

                            <td>

                                <span class="message-id">

                                    #<?php
                                    echo (int)$message['id'];
                                    ?>

                                </span>

                            </td>


                            <!-- CUSTOMER -->

                            <td>

                                <div class="message-customer">

                                    <strong>

                                        <?php

                                        echo htmlspecialchars(
                                            $message['fullname']
                                        );

                                        ?>

                                    </strong>

                                </div>

                            </td>


                            <!-- CONTACT -->

                            <td>

                                <div class="message-contact">

                                    <span>

                                        <i class="fa-solid fa-envelope"></i>

                                        <?php

                                        echo htmlspecialchars(
                                            $message['email']
                                        );

                                        ?>

                                    </span>

                                    <span>

                                        <i class="fa-solid fa-phone"></i>

                                        <?php

                                        echo htmlspecialchars(
                                            $message['phone']
                                        );

                                        ?>

                                    </span>

                                </div>

                            </td>


                            <!-- VEHICLE -->

                            <td>

                                <?php

                                if (!empty($message['vehicle'])) {

                                ?>

                                    <span class="message-vehicle">

                                        <i class="fa-solid fa-car"></i>

                                        <?php

                                        echo htmlspecialchars(
                                            $message['vehicle']
                                        );

                                        ?>

                                    </span>

                                <?php

                                } else {

                                ?>

                                    <span class="text-muted">
                                        Not provided
                                    </span>

                                <?php

                                }

                                ?>

                            </td>


                            <!-- SUBJECT -->

                            <td>

                                <span class="message-subject">

                                    <?php

                                    echo htmlspecialchars(
                                        $message['subject']
                                    );

                                    ?>

                                </span>

                            </td>


                            <!-- MESSAGE -->

                            <td>

                                <div class="message-text">

                                    <?php

                                    echo nl2br(
                                        htmlspecialchars(
                                            $message['message']
                                        )
                                    );

                                    ?>

                                </div>

                            </td>


                            <!-- DATE -->

                            <td>

                                <div class="message-date">

                                    <strong>

                                        <?php

                                        echo date(
                                            "d M Y",
                                            strtotime(
                                                $message['created_at']
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
                                                $message['created_at']
                                            )
                                        );

                                        ?>

                                    </small>

                                </div>

                            </td>

                        </tr>

                    <?php

                        }

                    } else {

                    ?>

                        <tr>

                            <td
                                colspan="7"
                                class="message-empty">

                                <i class="fa-regular fa-envelope-open"></i>

                                <h5>
                                    No Customer Messages
                                </h5>

                                <p>
                                    There are currently no messages.
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


<!-- FOOTER -->

<footer class="admin-footer">

    <p>

        <i class="fa-solid fa-car-side text-info"></i>

        FleetSync | Admin Panel

    </p>

    <small>
        © 2026 FleetSync. All Rights Reserved.
    </small>

</footer>


<!-- SEARCH SCRIPT -->

<script>

const messageSearch =
    document.getElementById("messageSearch");

const messageTable =
    document.getElementById("messageTable");

if (messageSearch && messageTable) {

    messageSearch.addEventListener("input", function () {

        const searchValue =
            this.value.toLowerCase().trim();

        const rows =
            messageTable.querySelectorAll("tbody tr");

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