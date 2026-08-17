<?php
include 'config.php';

if(session_status()==PHP_SESSION_NONE){
    session_start();
}

if(!isset($_SESSION['user'])){
    header("Location: login.html");
    exit();
}

$email = $_SESSION['user'];

$sql = "SELECT * FROM bookings
        WHERE user_email='$email'
        ORDER BY id DESC";

$result = mysqli_query($conn,$sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>My Bookings | FleetSync</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.0/css/all.min.css">

<link rel="stylesheet" href="css/my_book.css">

</head>

<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">

<div class="container">

<a class="navbar-brand fw-bold" href="dashboard.php">
<i class="fa-solid fa-car-side text-info"></i>
FleetSync
</a>

<a href="profile.php" class="btn btn-info text-white">
<i class="fa-solid fa-arrow-left"></i>
Back
</a>

</div>

</nav>

<div class="container py-5">

<div class="booking-card">

<h2 class="text-center mb-4">
<i class="fa-solid fa-calendar-check text-info"></i>
My Bookings
</h2>

<?php if(mysqli_num_rows($result)>0){ ?>

<div class="table-responsive">

<table class="table table-hover table-bordered align-middle text-center">

<thead >

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

<?php while($row=mysqli_fetch_assoc($result)){ ?>

<tr>

<td><?php echo $row['id']; ?></td>

<td><?php echo $row['vehicle_number']; ?></td>

<td><?php echo $row['service_type']; ?></td>

<td><?php echo $row['booking_date']; ?></td>

<td><?php echo date("h:i A",strtotime($row['booking_time'])); ?></td>

<td>

<?php

if($row['status']=="Pending"){

echo "<span class='badge bg-warning text-dark'>Pending</span>";

}

elseif($row['status']=="Completed"){

echo "<span class='badge bg-success'>Completed</span>";

}

elseif($row['status']=="Cancelled"){

echo "<span class='badge bg-danger'>Cancelled</span>";

}

else{

echo "<span class='badge bg-primary'>".$row['status']."</span>";

}

?>

</td>
<td>

<a href="booking_details.php?id=<?php echo $row['id']; ?>"
   class="btn btn-info btn-sm text-white me-1">

    <i class="fa-solid fa-eye"></i>
    View

</a>

<a href="service_tracking.php?id=<?php echo $row['id']; ?>"
   class="btn btn-success btn-sm text-white">

    <i class="fa-solid fa-location-dot"></i>
    Track

</a>

</td>

</tr>

<?php } ?>

</tbody>

</table>

</div>

<?php } else { ?>

<div class="text-center py-5">

<i class="fa-solid fa-calendar-xmark fa-4x text-info mb-3"></i>

<h4>No Bookings Found</h4>

<p class="text-muted">

You haven't booked any service yet.

</p>

<a href="book_service.php" class="btn btn-info text-white">

<i class="fa-solid fa-plus"></i>

Book Service

</a>

</div>

<?php } ?>

</div>

</div>

<footer class="footer">

    <div class="container">

        <p>
            <i class="fa-solid fa-car-side"></i>
            FleetSync | Vehicle Service & Maintenance Tracker
        </p>

        <small>
            © 2026 All Rights Reserved.
        </small>

    </div>

</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>