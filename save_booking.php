<?php

session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.html");
    exit();
}

include 'config.php';

$user_email = $_SESSION['user'];


/* =========================
   FORM DATA
========================= */

$customer_name = mysqli_real_escape_string(
    $conn,
    $_POST['customer_name'] ?? ''
);

$mobile = mysqli_real_escape_string(
    $conn,
    $_POST['mobile'] ?? ''
);

$vehicle_number = mysqli_real_escape_string(
    $conn,
    $_POST['vehicle_number'] ?? ''
);

$brand = mysqli_real_escape_string(
    $conn,
    $_POST['brand'] ?? ''
);

$model = mysqli_real_escape_string(
    $conn,
    $_POST['model'] ?? ''
);

$vehicle_type = mysqli_real_escape_string(
    $conn,
    $_POST['vehicle_type'] ?? ''
);

$km_reading = (int)($_POST['km_reading'] ?? 0);

$service_type = mysqli_real_escape_string(
    $conn,
    $_POST['service_type'] ?? ''
);

$booking_date = mysqli_real_escape_string(
    $conn,
    $_POST['booking_date'] ?? ''
);

$booking_time = mysqli_real_escape_string(
    $conn,
    $_POST['booking_time'] ?? ''
);


/* =========================
   OPTIONAL FIELDS
========================= */

$last_service = !empty($_POST['last_service'])
    ? mysqli_real_escape_string(
        $conn,
        $_POST['last_service']
    )
    : NULL;

$next_service = NULL;

$notes = mysqli_real_escape_string(
    $conn,
    $_POST['notes'] ?? ''
);


/* =========================
   VEHICLE IMAGE
========================= */

$imageName = "";

if (
    isset($_FILES['image']) &&
    $_FILES['image']['error'] === UPLOAD_ERR_OK
) {

    $targetDir = "uploads/";

    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    $originalName = basename($_FILES['image']['name']);

    $safeName = preg_replace(
        "/[^A-Za-z0-9._-]/",
        "_",
        $originalName
    );

    $imageName = time() . "_" . $safeName;

    move_uploaded_file(
        $_FILES['image']['tmp_name'],
        $targetDir . $imageName
    );
}


/* =========================
   INSERT BOOKING
========================= */

$sql = "INSERT INTO bookings
(
    user_email,
    customer_name,
    mobile,
    vehicle_number,
    brand,
    model,
    vehicle_type,
    km_reading,
    service_type,
    booking_date,
    booking_time,
    last_service,
    next_service,
    notes,
    image,
    status
)
VALUES
(
    '$user_email',
    '$customer_name',
    '$mobile',
    '$vehicle_number',
    '$brand',
    '$model',
    '$vehicle_type',
    '$km_reading',
    '$service_type',
    '$booking_date',
    '$booking_time',
    " . ($last_service ? "'$last_service'" : "NULL") . ",
    NULL,
    '$notes',
    '$imageName',
    'Pending'
)";


/* =========================
   SAVE BOOKING
========================= */

if (!mysqli_query($conn, $sql)) {

    die(
        "Database Error: " .
        mysqli_error($conn)
    );
}


/* =========================
   GET BOOKING ID
========================= */

$booking_id = mysqli_insert_id($conn);


/* =========================
   GO TO PAYMENT METHOD
========================= */

header(
    "Location: payment_method.php?id=" .
    $booking_id
);

exit();

?>