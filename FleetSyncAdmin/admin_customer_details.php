<?php  
  
session_start();  
  
if (!isset($_SESSION['admin'])) {  
    header("Location: admin_login.php");  
    exit();  
}  
  
include '../config.php';  
  
/* Get customer email */  
$email = isset($_GET['email']) ? trim($_GET['email']) : '';  
  
if ($email === '') {  
    header("Location: admin_customers.php");  
    exit();  
}  
  
/* Get customer information */  
$stmt = mysqli_prepare(  
    $conn,  
    "SELECT  
        MAX(customer_name) AS customer_name,  
        user_email,  
        MAX(mobile) AS mobile,  
        COUNT(*) AS total_bookings,  
        COUNT(DISTINCT vehicle_number) AS total_vehicles  
     FROM bookings  
     WHERE user_email = ?  
     GROUP BY user_email"  
);  
  
mysqli_stmt_bind_param($stmt, "s", $email);  
mysqli_stmt_execute($stmt);  
  
$result = mysqli_stmt_get_result($stmt);  
$customer = mysqli_fetch_assoc($result);  
  
mysqli_stmt_close($stmt);  
  
/* Customer not found */  
if (!$customer) {  
    header("Location: admin_customers.php");  
    exit();  
}  
  
/* Get customer's booking history */  
$stmt = mysqli_prepare(  
    $conn,  
    "SELECT *  
     FROM bookings  
     WHERE user_email = ?  
     ORDER BY id DESC"  
);  
  
mysqli_stmt_bind_param($stmt, "s", $email);  
mysqli_stmt_execute($stmt);  
  
$bookings_result = mysqli_stmt_get_result($stmt);  
  
/* Safe output */  
function clean($value)  
{  
    return htmlspecialchars(  
        $value ?? '',  
        ENT_QUOTES,  
        'UTF-8'  
    );  
}  
  
?>  <!DOCTYPE html>  <html lang="en">  <head>  <meta charset="UTF-8">  <meta name="viewport"  
content="width=device-width, initial-scale=1.0">

<meta name="description"  
content="FleetSync Admin Customer Details">

<title>  
    Customer Details | FleetSync Admin  
</title>  <link  
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css"  
rel="stylesheet">  <link  
rel="stylesheet"  
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.0/css/all.min.css">  <link  
rel="stylesheet"  
href="css/admin.css"> 

</head> 

<body> 
    
<!-- ================= NAVBAR ================= -->  <nav class="navbar navbar-dark bg-dark shadow">  <div class="container-fluid px-4">  

    <a href="admin_dashboard.php"  
       class="navbar-brand fw-bold fs-4">  

        <i class="fa-solid fa-car-side text-info"></i>  

        FleetSync Admin  

    </a>  

    <div class="d-flex align-items-center gap-2">  

        <a href="admin_customers.php"  
           class="btn btn-outline-info btn-sm">  

            <i class="fa-solid fa-users"></i>  

            Customers  

        </a>  

        <a href="admin_logout.php"  
           class="btn btn-outline-danger btn-sm">  

            <i class="fa-solid fa-right-from-bracket"></i>  

            Logout  

        </a>  

    </div>  

</div>

</nav>  <!-- ================= MAIN ================= -->  <main class="customer-details-page">  <div class="container-fluid px-4">  <!-- ================= HEADER ================= -->  

<div class="customer-details-header">  

    <div>  

        <h1>  

            <i class="fa-solid fa-user text-info"></i>  

            Customer Details  

        </h1>  

        <p>  
            View customer information and complete service history.  
        </p>  

    </div>  


    <a href="admin_customers.php"  
       class="btn btn-outline-secondary">  

        <i class="fa-solid fa-arrow-left"></i>  

        Back to Customers  

    </a>  

</div>  


<!-- ================= CUSTOMER SUMMARY ================= -->  

<div class="customer-details-summary">  

    <div class="customer-large-avatar">  

        <i class="fa-solid fa-user"></i>  

    </div>  


    <div class="customer-summary-info">  

        <h2>  

            <?php  
            echo clean(  
                $customer['customer_name']  
                ?: 'Customer'  
            );  
            ?>  

        </h2>  

        <p>  

            <i class="fa-solid fa-envelope"></i>  

            <?php  
            echo clean($customer['user_email']);  
            ?>  

        </p>  

        <?php if (!empty($customer['mobile'])) { ?>  

            <p>  

                <i class="fa-solid fa-phone"></i>  

                <?php  
                echo clean($customer['mobile']);  
                ?>  

            </p>  

        <?php } ?>  

    </div>  

</div>  


<!-- ================= STATISTICS ================= -->  

<div class="row g-4 mb-4">  


    <div class="col-md-4">  

        <div class="customer-detail-stat">  

            <div class="customer-detail-stat-icon">  

                <i class="fa-solid fa-calendar-check"></i>  

            </div>  

            <div>  

                <span>Total Bookings</span>  

                <strong>  

                    <?php  
                    echo (int)$customer['total_bookings'];  
                    ?>  

                </strong>  

            </div>  

        </div>  

    </div>  


    <div class="col-md-4">  

        <div class="customer-detail-stat">  

            <div class="customer-detail-stat-icon vehicle">  

                <i class="fa-solid fa-car"></i>  

            </div>  

            <div>  

                <span>Total Vehicles</span>  

                <strong>  

                    <?php  
                    echo (int)$customer['total_vehicles'];  
                    ?>  

                </strong>  

            </div>  

        </div>  

    </div>  


    <div class="col-md-4">  

        <div class="customer-detail-stat">  

            <div class="customer-detail-stat-icon email">  

                <i class="fa-solid fa-envelope"></i>  

            </div>  

            <div>  

                <span>Customer Email</span>  

                <strong class="small-email">  

                    <?php  
                    echo clean($customer['user_email']);  
                    ?>  

                </strong>  

            </div>  

        </div>  

    </div>  

</div>  


<!-- ================= BOOKING HISTORY ================= -->  

<div class="customer-history-card">  

    <div class="customer-history-header">  

        <div>  

            <h4>  

                <i class="fa-solid fa-clock-rotate-left"></i>  

                Booking History  

            </h4>  

            <span>  
                All service bookings made by this customer.  
            </span>  

        </div>  

    </div>  


    <div class="table-responsive">  

        <table class="table customer-history-table">  

            <thead>  

                <tr>  

                    <th>ID</th>  

                    <th>Vehicle</th>  

                    <th>Service</th>  

                    <th>Date</th>  

                    <th>Time</th>  

                    <th>Status</th>  

                    <th>Action</th>  

                </tr>  

            </thead>  


            <tbody>  

            <?php  

            if (mysqli_num_rows($bookings_result) > 0) {  

                while ($booking =  
                    mysqli_fetch_assoc($bookings_result)  
                ) {  

                    $status =  
                        $booking['status'] ?? 'Pending';  

            ?>  

                <tr>  

                    <!-- ID -->  

                    <td>  

                        <strong class="booking-id">  

                            #<?php  
                            echo (int)$booking['id'];  
                            ?>  

                        </strong>  

                    </td>  


                    <!-- VEHICLE -->  

                    <td>  

                        <strong>  

                            <i class="fa-solid fa-car text-info"></i>  

                            <?php  
                            echo clean(  
                                $booking['vehicle_number']  
                            );  
                            ?>  

                        </strong>  

                        <small class="d-block text-muted">  

                            <?php  
                            echo clean(  
                                $booking['brand'] ?? ''  
                            );  
                            ?>  

                            <?php  

                            if (!empty($booking['model'])) {  

                                echo ' ' .  
                                    clean($booking['model']);  

                            }  

                            ?>  

                        </small>  

                    </td>  


                    <!-- SERVICE -->  

                    <td>  

                        <strong class="service-name">  

                            <?php  
                            echo clean(  
                                $booking['service_type']  
                            );  
                            ?>  

                        </strong>  

                    </td>  


                    <!-- DATE -->  

                    <td>  

                        <?php  

                        if (!empty($booking['booking_date'])) {  

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

                        if (!empty($booking['booking_time'])) {  

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

                        <?php  

                        $status_class =  
                            strtolower($status);  

                        ?>  

                        <span  
                            class="customer-status  
                            customer-status-<?php  
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
                        colspan="7"  
                        class="text-center py-5">  

                        <i class="fa-solid fa-calendar-xmark fs-1 text-muted"></i>  

                        <h5 class="mt-3">  
                            No Booking History  
                        </h5>  

                        <p class="text-muted mb-0">  
                            This customer has no bookings yet.  
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


<!-- ================= BACK BUTTON ================= -->  

<div class="customer-details-actions">  

    <a href="admin_customers.php"  
       class="btn btn-secondary">  

        <i class="fa-solid fa-arrow-left"></i>  

        Back to Customers  

    </a>  

</div>

</div>  </main>  <!-- ================= FOOTER ================= -->  <footer class="admin-footer">  <p>  

    <i class="fa-solid fa-car-side text-info"></i>  

    FleetSync | Admin Panel  

</p>  

<small>  
    © 2026 FleetSync. All Rights Reserved.  
</small>

</footer>  </body>  </html>  <?php  
  
mysqli_stmt_close($stmt);  
  
?>  In this