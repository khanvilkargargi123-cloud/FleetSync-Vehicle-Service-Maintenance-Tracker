<?php
date_default_timezone_set('Asia/Kolkata');

$servername = "localhost";
$username = "root";
$password = "";
$database = "fleetsync";

$conn = mysqli_connect(
    $servername,
    $username,
    $password,
    $database
);

if(!$conn){
    die("Connection failed: " . mysqli_connect_error());
}
?>