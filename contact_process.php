<?php

include 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: contact.html");
    exit();
}

/* Get form data */

$fullname = trim($_POST['fullname'] ?? '');
$email    = trim($_POST['email'] ?? '');
$phone    = trim($_POST['phone'] ?? '');
$vehicle  = trim($_POST['vehicle'] ?? '');
$subject  = trim($_POST['subject'] ?? '');
$message  = trim($_POST['message'] ?? '');


/* Basic validation */

if (
    empty($fullname) ||
    empty($email) ||
    empty($phone) ||
    empty($vehicle) ||
    empty($subject) ||
    empty($message)
) {
    echo "Please fill in all fields.";
    exit();
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "Please enter a valid email address.";
    exit();
}


/* Save message in database */

$sql = "INSERT INTO contact_messages
        (fullname, email, phone, vehicle, subject, message)
        VALUES (?, ?, ?, ?, ?, ?)";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param(
    $stmt,
    "ssssss",
    $fullname,
    $email,
    $phone,
    $vehicle,
    $subject,
    $message
);

if (mysqli_stmt_execute($stmt)) {

    echo "
    <!DOCTYPE html>
    <html>
    <head>
        <title>Message Sent | FleetSync</title>

        <link
        href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css'
        rel='stylesheet'>
    </head>

    <body>

    <div class='container text-center mt-5'>

        <div class='card shadow p-5'>

            <h2 class='text-success'>
                Message Sent Successfully!
            </h2>

            <p class='mt-3'>
                Thank you for contacting FleetSync.
                We have received your message.
            </p>

            <a href='contact.html'
               class='btn btn-info text-white mt-3'>
                Back to Contact Page
            </a>

        </div>

    </div>

    </body>
    </html>
    ";

} else {

    echo "Database Error: " . mysqli_error($conn);
}

?>