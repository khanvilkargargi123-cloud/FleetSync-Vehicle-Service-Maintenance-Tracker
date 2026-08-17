<?php
session_start();

if (isset($_SESSION['admin'])) {
    header("Location: admin_dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Admin Login | FleetSync</title>

<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css"
rel="stylesheet">

<link
rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.0/css/all.min.css">

<link rel="stylesheet" href="css/admin.css">

</head>

<body>

<div class="container">

    <div class="admin-login-card">

        <div class="text-center mb-4">

            <i class="fa-solid fa-user-shield admin-icon"></i>

            <h2>FleetSync Admin</h2>

            <p>Administrator Login</p>

        </div>

        <form action="admin_login_process.php" method="POST">

            <div class="mb-3">

                <label class="form-label">
                    Email Address
                </label>

                <input
                    type="email"
                    name="email"
                    class="form-control"
                    placeholder="Admin email"
                    required>

            </div>

            <div class="mb-4">

                <label class="form-label">
                    Password
                </label>

                <input
                    type="password"
                    name="password"
                    class="form-control"
                    placeholder="Enter password"
                    required>

            </div>

            <button
                type="submit"
                class="btn btn-info text-white w-100">

                <i class="fa-solid fa-right-to-bracket"></i>

                Login as Admin

            </button>

        </form>

        <div class="text-center mt-4">

            <a href="../index.php">
                <i class="fa-solid fa-arrow-left"></i>
                Back to FleetSync
            </a>

        </div>

    </div>

</div>

</body>
</html>