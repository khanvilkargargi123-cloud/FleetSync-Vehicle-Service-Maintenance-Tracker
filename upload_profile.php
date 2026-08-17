<?php

session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.html");
    exit();
}

include 'config.php';

$email = $_SESSION['user'];

if (!isset($_FILES['profile_photo'])) {
    header("Location: profile.php");
    exit();
}

if ($_FILES['profile_photo']['error'] !== UPLOAD_ERR_OK) {
    die("Photo upload failed.");
}

/* Allowed file types */
$allowedTypes = [
    'image/jpeg',
    'image/png',
    'image/webp'
];

$fileType = mime_content_type($_FILES['profile_photo']['tmp_name']);

if (!in_array($fileType, $allowedTypes)) {
    die("Only JPG, PNG and WEBP images are allowed.");
}

/* Maximum size: 2 MB */
if ($_FILES['profile_photo']['size'] > 2 * 1024 * 1024) {
    die("Photo must be less than 2 MB.");
}

/* File extension */
$extension = strtolower(
    pathinfo($_FILES['profile_photo']['name'], PATHINFO_EXTENSION)
);

/* Generate unique name */
$fileName = 'profile_' . uniqid() . '.' . $extension;

/* Profile photo folder */
$uploadDir = 'profile_uploads/';

/* Create folder if it doesn't exist */
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

$filePath = $uploadDir . $fileName;

/* Upload */
if (move_uploaded_file(
    $_FILES['profile_photo']['tmp_name'],
    $filePath
)) {

    /* Save path in database */

    $stmt = mysqli_prepare(
        $conn,
        "UPDATE users SET profile_photo = ? WHERE email = ?"
    );

    mysqli_stmt_bind_param(
        $stmt,
        "ss",
        $filePath,
        $email
    );

    mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);

    header("Location: profile.php");
    exit();

} else {

    die("Unable to save profile photo.");
}

?>