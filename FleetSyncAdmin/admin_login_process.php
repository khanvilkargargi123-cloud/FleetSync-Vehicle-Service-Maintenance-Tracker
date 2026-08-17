<?php

session_start();

/* Make sure request is POST */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: admin_login.php");
    exit();
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

/* Admin credentials */
$admin_email = "admin@fleetsync.com";
$admin_password = "FleetSync@123";

/* Check login */

if ($email === $admin_email && $password === $admin_password) {

    $_SESSION['admin'] = $email;

    /* Redirect to dashboard */
    header("Location: admin_dashboard.php");
    exit();

}

/* Login failed */

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>Admin Login Failed | FleetSync</title>

<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css"
rel="stylesheet">

</head>

<body class="bg-dark">

<div class="container">

<div class="card shadow p-5 mx-auto mt-5 admin-error-card">
<h3 class="text-danger text-center">
Invalid Admin Login
</h3>

<p class="text-center mt-3">
Email or password is incorrect.
</p>

<div class="text-center">

<a href="admin_login.php"
   class="btn btn-info text-white">

Try Again

</a>

</div>

</div>

</div>

</body>

</html>