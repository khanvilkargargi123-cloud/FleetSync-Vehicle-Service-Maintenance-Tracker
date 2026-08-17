<?php
include 'config.php';

session_start();

if(!isset($_SESSION['user'])){
    header("Location: login.html");
    exit();
}

$email = $_SESSION['user'];

$id = $_GET['id'];

$sql = "UPDATE bookings
        SET status='Cancelled'
        WHERE id='$id'
        AND user_email='$email'";

mysqli_query($conn,$sql);

header("Location: my_booking.php");
exit();
?>