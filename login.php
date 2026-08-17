<?php
include 'config.php';
session_start();

if(isset($_POST['login'])){

    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email='$email' AND password='$password'";

    $result = mysqli_query($conn, $sql);

    if(mysqli_num_rows($result) > 0){

        $_SESSION['user'] = $email;

        header("Location: profile.php");
        exit();

    }else{

        echo "<script>
        alert('Invalid Email or Password');
        window.location='login.html';
        </script>";

    }
}
?>