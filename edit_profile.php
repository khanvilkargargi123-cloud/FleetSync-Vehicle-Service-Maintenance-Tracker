<?php
include 'config.php';
session_start();

if(!isset($_SESSION['user'])){
    header("Location: login.html");
    exit();
}

$email = $_SESSION['user'];

$sql = "SELECT * FROM users WHERE email='$email'";
$result = mysqli_query($conn,$sql);
$user = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Edit Profile | FleetSync</title>

<link rel="stylesheet" href="css/edit_Profile.css">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.0/css/all.min.css">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

<!-- Navbar -->

<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-lg sticky-top">

<div class="container">

<a class="navbar-brand fw-bold fs-3" href="index.php">
<i class="fa-solid fa-car-side text-info"></i>
FleetSync
</a>

<button class="navbar-toggler"
type="button"
data-bs-toggle="collapse"
data-bs-target="#navbarNav">

<span class="navbar-toggler-icon"></span>

</button>

<div class="collapse navbar-collapse" id="navbarNav">

<ul class="navbar-nav ms-auto">

<li class="nav-item">
<a class="nav-link" href="index.php">
<i class="fa-solid fa-house"></i> Home
</a>
</li>

<li class="nav-item">
<a class="nav-link" href="About.html">
<i class="fa-solid fa-circle-info"></i> About
</a>
</li>

<li class="nav-item">
<a class="nav-link" href="booking.html">
<i class="fa-solid fa-calendar-check"></i> Book Service
</a>
</li>

<li class="nav-item">
<a class="nav-link" href="contact.html">
<i class="fa-solid fa-phone"></i> Contact
</a>
</li>

<li class="nav-item">
<a class="nav-link active" href="profile.php">
<i class="fa-solid fa-user"></i> Profile
</a>
</li>

<li class="nav-item ms-3">
<a href="logout.php" class="btn btn-danger">
<i class="fa-solid fa-right-from-bracket"></i>
Logout
</a>
</li>

</ul>

</div>

</div>

</nav>
<div class="container py-5">

    <div class="edit-profile-card">

        <h2 class="text-center mb-4">
            <i class="fa-solid fa-user-pen text-info"></i>
            Edit Profile
        </h2>

        <form action="update_profile.php" method="POST">

            <div class="row">

                <div class="col-md-6 mb-3">
                    <label class="form-label">Full Name</label>
                    <input
                        type="text"
                        name="name"
                        class="form-control"
                        value="<?php echo $user['name']; ?>"
                        required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Email Address</label>
                    <input
                        type="email"
                        name="email"
                        class="form-control"
                        value="<?php echo $user['email']; ?>"
                        required>
                </div>

            </div>

            <div class="row">

                <div class="col-md-6 mb-3">
                    <label class="form-label">Phone Number</label>
                    <input
                        type="text"
                        name="phone"
                        class="form-control"
                        value="<?php echo $user['phone']; ?>"
                        required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Address</label>
                    <input
                        type="text"
                        name="address"
                        class="form-control"
                        value="<?php echo $user['address']; ?>"
                        required>
                </div>

            </div>

            <div class="row">

                <div class="col-md-6 mb-3">
                    <label class="form-label">New Password</label>
                    <input
                        type="password"
                        name="password"
                        class="form-control"
                        placeholder="Enter new password">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Confirm Password</label>
                    <input
                        type="password"
                        name="confirm_password"
                        class="form-control"
                        placeholder="Confirm new password">
                </div>

            </div>

            <div class="text-center mt-4">

                <button type="submit" class="btn btn-info text-white px-5">
                    <i class="fa-solid fa-floppy-disk"></i>
                    Save Changes
                </button>

                <a href="profile.php" class="btn btn-secondary px-5 ms-3">
                    <i class="fa-solid fa-arrow-left"></i>
                    Cancel
                </a>

            </div>

        </form>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>