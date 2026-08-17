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


/* =========================
   CHECK IF VEHICLE ALREADY EXISTS
========================= */

$sql = "SELECT id
        FROM vehicles
        WHERE user_email = ?
        LIMIT 1";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if (mysqli_fetch_assoc($result)) {
    mysqli_stmt_close($stmt);

    header("Location: profile.php");
    exit();
}

mysqli_stmt_close($stmt);

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>Add Vehicle | FleetSync</title>

<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css"
rel="stylesheet">

<link
rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.0/css/all.min.css">

<link
rel="stylesheet"
href="css/add_vehicle.css">

</head>

<body>

<!-- =========================
     NAVBAR
========================= -->

<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-lg">

<div class="container">

<a
href="dashboard.php"
class="navbar-brand fw-bold fs-3">

<i class="fa-solid fa-car-side text-info"></i>

FleetSync

</a>

<a
href="profile.php"
class="btn btn-info text-white">

<i class="fa-solid fa-arrow-left"></i>

Back to Profile

</a>

</div>

</nav>


<!-- =========================
     ADD VEHICLE SECTION
========================= -->

<section class="add-vehicle-section">

<div class="container">

<div class="row justify-content-center">

<div class="col-lg-7">

<div class="add-vehicle-card">

<div class="text-center mb-4">

<i class="fa-solid fa-car-side vehicle-icon"></i>

<h2>Add Your Vehicle</h2>

<p>
Register your vehicle to manage its service
and maintenance information.
</p>

</div>


<!-- =========================
     VEHICLE FORM
========================= -->

<form
action="save_vehicle.php"
method="POST">


<!-- Vehicle Number -->

<div class="mb-3">

<label class="form-label">
Vehicle Number
</label>

<input
type="text"
class="form-control"
name="vehicle_number"
placeholder="Example: MH08AB1234"
required>

</div>


<!-- Vehicle Brand -->

<div class="mb-3">

<label class="form-label">
Vehicle Brand
</label>

<input
type="text"
class="form-control"
name="vehicle_brand"
placeholder="Example: Tata"
required>

</div>


<!-- Vehicle Model -->

<div class="mb-3">

<label class="form-label">
Vehicle Model
</label>

<input
type="text"
class="form-control"
name="vehicle_model"
placeholder="Example: Nexon"
required>

</div>


<!-- Fuel Type -->

<div class="mb-3">

<label class="form-label">
Fuel Type
</label>

<select
class="form-select"
name="fuel_type"
required>

<option value="">
Select Fuel Type
</option>

<option value="Petrol">
Petrol
</option>

<option value="Diesel">
Diesel
</option>

<option value="CNG">
CNG
</option>

<option value="Electric">
Electric
</option>

</select>

</div>


<!-- Manufacturing Year -->

<div class="mb-3">

<label class="form-label">
Manufacturing Year
</label>

<input
type="number"
class="form-control"
name="manufacturing_year"
min="1990"
max="2035"
placeholder="Example: 2022"
required>

</div>


<!-- Vehicle Color -->

<div class="mb-4">

<label class="form-label">
Vehicle Color
</label>

<input
type="text"
class="form-control"
name="vehicle_color"
placeholder="Example: White"
required>

</div>


<!-- BUTTONS -->

<div class="text-center">

<button
type="submit"
class="btn btn-save">

<i class="fa-solid fa-plus"></i>

Add Vehicle

</button>

<a
href="profile.php"
class="btn btn-cancel">

Cancel

</a>

</div>

</form>

</div>

</div>

</div>

</div>

</div>

</section>


<script
src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js">
</script>

</body>

</html>