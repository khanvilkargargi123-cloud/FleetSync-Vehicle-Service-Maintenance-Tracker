<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Book Service | FleetSync</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.0/css/all.min.css">

<link rel="stylesheet" href="css/book_service.css">

</head>

<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow">
<div class="container">

<a class="navbar-brand fw-bold" href="index.php">
<i class="fa-solid fa-car-side text-info"></i>
FleetSync
</a>

<div class="ms-auto">

<a href="dashboard.php" class="btn btn-outline-info me-2">
Dashboard
</a>

<a href="my_booking.php" class="btn btn-info">
My Bookings
</a>

</div>

</div>
</nav>

<section class="booking-banner">

<div class="container text-center">

<h1>Book Vehicle Service</h1>

<p>
Professional vehicle maintenance with genuine spare parts and experienced mechanics.
</p>

</div>

</section>

<div class="container py-5">

<div class="row justify-content-center">

<div class="col-lg-10">

<div class="booking-card">

<form action="save_booking.php" method="POST" enctype="multipart/form-data">

<h3 class="section-title">

<i class="fa-solid fa-user"></i>

Customer Details

</h3>

<div class="row">

<div class="col-md-6 mb-3">

<label>Customer Name</label>

<input
type="text"
name="customer_name"
class="form-control"
required>

</div>

<div class="col-md-6 mb-3">

<label>Mobile Number</label>

<input
type="tel"
name="mobile"
class="form-control"
required>

</div>

</div>

<hr>

<h3 class="section-title">

<i class="fa-solid fa-car"></i>

Vehicle Details

</h3>

<div class="row">

<div class="col-md-6 mb-3">

<label>Vehicle Number</label>

<input
type="text"
name="vehicle_number"
class="form-control"
required>

</div>

<div class="col-md-6 mb-3">

<label>Vehicle Brand</label>

<input
type="text"
name="brand"
class="form-control"
required>

</div>

<div class="col-md-6 mb-3">

<label>Vehicle Model</label>

<input
type="text"
name="model"
class="form-control"
required>

</div>

<div class="col-md-6 mb-3">

<label>Vehicle Type</label>

<select
name="vehicle_type"
class="form-select"
required>

<option value="">Choose</option>

<option>Car</option>

<option>Bike</option>

<option>SUV</option>

<option>Truck</option>

<option>Other</option>

</select>

</div>

<div class="col-md-6 mb-3">

<label>Current KM Reading</label>

<input
type="number"
name="km_reading"
class="form-control"
required>

</div>

<div class="col-md-6 mb-3">

<label>Upload Car documents (optional)</label>

<input
type="file"
name="image"
class="form-control"
accept="image/*">

</div>

</div>

<hr>

<h3 class="section-title">

<i class="fa-solid fa-screwdriver-wrench"></i>

Service Details

</h3>

<div class="row">

<div class="col-md-6 mb-3">

<label>Service Type</label>

<select
name="service_type"
class="form-select"
required>

<option value="">Select</option>

<option>General Service</option>

<option>Oil Change</option>

<option>AC Service</option>

<option>Brake Service</option>

<option>Battery Check</option>

<option>Wheel Alignment</option>

<option>Full Service</option>

</select>

</div>

<div class="col-md-6 mb-3">

<label>Preferred Date</label>

<input
type="date"
name="booking_date"
class="form-control"
required>

</div>

<label>Preferred Service Time</label>

<select
    name="booking_time"
    class="form-select"
    required>

    <option value="">Select Time Slot</option>

    <option value="09:00">09:00 AM - 10:00 AM</option>
    <option value="10:00">10:00 AM - 11:00 AM</option>
    <option value="11:00">11:00 AM - 12:00 PM</option>
    <option value="12:00">12:00 PM - 01:00 PM</option>
    <option value="14:00">02:00 PM - 03:00 PM</option>
    <option value="15:00">03:00 PM - 04:00 PM</option>
    <option value="16:00">04:00 PM - 05:00 PM</option>
    <option value="17:00">05:00 PM - 06:00 PM</option>

</select>

<div class="col-md-6 mb-3">

<label>Last Service Date</label>

<input
type="date"
name="last_service"
class="form-control">

</div>

<div class="col-12 mb-3">

<label>Additional Notes (optional)</label>

<textarea

name="notes"

rows="4"

class="form-control"

placeholder="Mention any issue with your vehicle...">

</textarea>

</div>

</div>


<div class="text-center mt-4">

<button

type="submit"

class="btn btn-info btn-lg px-5">

<i class="fa-solid fa-calendar-check"></i>

Book Service

</button>

</div>

</form>

</div>

</div>

</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>