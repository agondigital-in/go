<?php
session_start();

// Set maximum file size (5MB)
$maxFileSize = 5 * 1024 * 1024; // 5MB in bytes

// Allowed file types
$allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];

// Check if a file was uploaded
if (!isset($_FILES['image']) || $_FILES['image']['error'] === UPLOAD_ERR_NO_FILE) {
    $_SESSION['message'] = 'Please select an image to upload.';
    $_SESSION['error'] = true;
    header('Location: upload.php');
    exit();
}

$file = $_FILES['image'];

// Check for upload errors
if ($file['error'] !== UPLOAD_ERR_OK) {
    $message = 'Error uploading file: ';
    switch ($file['error']) {
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
            $message .= 'File is too large.';
            break;
        default:
            $message .= 'Unknown error occurred.';
    }
    $_SESSION['message'] = $message;
    $_SESSION['error'] = true;
    header('Location: upload.php');
    exit();
}

// Check file size
if ($file['size'] > $maxFileSize) {
    $_SESSION['message'] = 'File is too large. Maximum size is 5MB.';
    $_SESSION['error'] = true;
    header('Location: upload.php');
    exit();
}

// Check file type
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mimeType = finfo_file($finfo, $file['tmp_name']);
finfo_close($finfo);

if (!in_array($mimeType, $allowedTypes)) {
    $_SESSION['message'] = 'Invalid file type. Only JPG, PNG and GIF images are allowed.';
    $_SESSION['error'] = true;
    header('Location: upload.php');
    exit();
}

// Generate unique filename
$extension = pathinfo($file['name'], PATHINFO_EXTENSION);
$filename = uniqid() . '-' . time() . '.' . $extension;
$uploadPath = __DIR__ . '/uploads/' . $filename;

// Create uploads directory if it doesn't exist
if (!file_exists(__DIR__ . '/uploads')) {
    mkdir(__DIR__ . '/uploads', 0777, true);
}

// Try to move the uploaded file
if (!move_uploaded_file($file['tmp_name'], $uploadPath)) {
    $_SESSION['message'] = 'Failed to save the uploaded file.';
    $_SESSION['error'] = true;
    header('Location: upload.php');
    exit();
}

// Success!
$_SESSION['message'] = 'Image uploaded successfully!';
$_SESSION['error'] = false;
$_SESSION['uploaded_image'] = $filename;
header('Location: upload.php');
exit();