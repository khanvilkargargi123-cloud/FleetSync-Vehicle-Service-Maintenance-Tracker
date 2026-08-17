<?php
include 'config.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user'])) {
    header("Location: login.html");
    exit();
}

$email = $_SESSION['user'];

$vehicle_number = $_POST['vehicle_number'];
$vehicle_brand = $_POST['vehicle_brand'];
$vehicle_model = $_POST['vehicle_model'];
$fuel_type = $_POST['fuel_type'];
$manufacturing_year = $_POST['manufacturing_year'];
$vehicle_color = $_POST['vehicle_color'];

$sql = "UPDATE vehicles SET
vehicle_number='$vehicle_number',
vehicle_brand='$vehicle_brand',
vehicle_model='$vehicle_model',
fuel_type='$fuel_type',
manufacturing_year='$manufacturing_year',
vehicle_color='$vehicle_color'
WHERE user_email='$email'";

if(mysqli_query($conn, $sql)){

    echo "<script>
    alert('Vehicle Updated Successfully!');
    window.location='profile.php';
    </script>";

}else{

    echo "Error: " . mysqli_error($conn);

}
?>