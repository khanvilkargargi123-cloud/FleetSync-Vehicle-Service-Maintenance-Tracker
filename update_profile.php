<?php
include 'config.php';
session_start();

if(!isset($_SESSION['user'])){
    header("Location: login.html");
    exit();
}

$currentEmail = $_SESSION['user'];

$name = $_POST['name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$address = $_POST['address'];
$password = $_POST['password'];
$confirmPassword = $_POST['confirm_password'];

/* Check password */

if(!empty($password)){

    if($password != $confirmPassword){

        echo "<script>
        alert('Passwords do not match!');
        window.history.back();
        </script>";
        exit();

    }

    $sql = "UPDATE users
            SET
            name='$name',
            email='$email',
            phone='$phone',
            address='$address',
            password='$password'
            WHERE email='$currentEmail'";

}else{

    $sql = "UPDATE users
            SET
            name='$name',
            email='$email',
            phone='$phone',
            address='$address'
            WHERE email='$currentEmail'";

}

if(mysqli_query($conn,$sql)){

    $_SESSION['user'] = $email;

    echo "<script>
    alert('Profile Updated Successfully!');
    window.location='profile.php';
    </script>";

}else{

    echo "<script>
    alert('Update Failed!');
    window.history.back();
    </script>";

}
?>