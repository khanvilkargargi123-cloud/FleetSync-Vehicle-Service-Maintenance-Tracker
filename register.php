<?php
include 'config.php';

if(isset($_POST['register'])){

    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);

    // Check password
    if($password != $confirm_password){
        echo "<script>
                alert('Passwords do not match!');
                window.location='register.html';
              </script>";
        exit();
    }

    // Check if email already exists
    $check = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");

    if(mysqli_num_rows($check) > 0){
        echo "<script>
                alert('Email already registered!');
                window.location='register.html';
              </script>";
        exit();
    }

    // Insert new user
    $sql = "INSERT INTO users(name,email,phone,password,address)
            VALUES('$name','$email','$phone','$password','$address')";

    if(mysqli_query($conn,$sql)){
        echo "<script>
                alert('Registration Successful!');
                window.location='login.html';
              </script>";
    }else{
        die('Database Error: '.mysqli_error($conn));
    }

}else{
    header("Location: register.html");
    exit();
}
?>