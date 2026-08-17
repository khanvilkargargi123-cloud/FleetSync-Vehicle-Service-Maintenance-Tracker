<?php
include 'config.php';

session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.html");
    exit();
}

$user_email = $_SESSION['user'];

$check = mysqli_query($conn, "SELECT * FROM vehicles WHERE user_email='$user_email'");

if(mysqli_num_rows($check) > 0){
    echo "<script>
        alert('You have already added a vehicle. Please edit it from your Profile.');
        window.location='profile.php';
    </script>";
    exit();
}

$vehicle_number = $_POST['vehicle_number'];
$vehicle_brand = $_POST['vehicle_brand'];
$vehicle_model = $_POST['vehicle_model'];
$fuel_type = $_POST['fuel_type'];
$manufacturing_year = $_POST['manufacturing_year'];
$vehicle_color = $_POST['vehicle_color'];

$sql = "INSERT INTO vehicles
(user_email, vehicle_number, vehicle_brand, vehicle_model, fuel_type, manufacturing_year, vehicle_color)
VALUES
('$user_email','$vehicle_number','$vehicle_brand','$vehicle_model','$fuel_type','$manufacturing_year','$vehicle_color')";

if(mysqli_query($conn,$sql)){
    header("Location: profile.php");
}else{
    echo "Error: ".mysqli_error($conn);
}
?>