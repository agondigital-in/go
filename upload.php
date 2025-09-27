<?php
require_once 'config.php';

// Function to log upload errors
function logUploadError($message) {
    error_log("[" . date('Y-m-d H:i:s') . "] Upload Error: " . $message . "\n", 3, __DIR__ . DIRECTORY_SEPARATOR . 'upload_errors.log');
}

if(isset($_POST['submit'])) {
    // Check server requirements first
    $requirements = checkServerRequirements();
    if (in_array(false, $requirements, true)) {
        logUploadError("Server requirements not met");
        header('Location: index.php?upload=error');
        exit();
    }

    // Check if a file was uploaded
    if(isset($_FILES['image'])) {
        $file = $_FILES['image'];
        
        // File properties
        $file_name = $file['name'];
        $file_tmp = $file['tmp_name'];
        $file_size = $file['size'];
        $file_error = $file['error'];
        
        // Get file extension
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        
        if(in_array($file_ext, ALLOWED_TYPES)) {
            if($file_error === 0) {
                if($file_size <= MAX_FILE_SIZE) {
                    // Create unique filename
                    $file_name_new = uniqid('img_', true) . '.' . $file_ext;
                    $file_destination = UPLOAD_DIR . DIRECTORY_SEPARATOR . $file_name_new;
                    
                    // Ensure upload directory exists and is writable
                    if (!is_dir(UPLOAD_DIR)) {
                        if (!mkdir(UPLOAD_DIR, 0755, true)) {
                            logUploadError("Failed to create upload directory");
                            header('Location: index.php?upload=failed');
                            exit();
                        }
                    }
                    
                    if(move_uploaded_file($file_tmp, $file_destination)) {
                        // Set proper permissions for the uploaded file
                        chmod($file_destination, 0644);
                        header('Location: index.php?upload=success');
                        exit();
                    } else {
                        logUploadError("Failed to move uploaded file to destination");
                        header('Location: index.php?upload=failed');
                        exit();
                    }
                } else {
                    logUploadError("File too large: " . $file_size . " bytes");
                    header('Location: index.php?upload=toolarge');
                    exit();
                }
            } else {
                logUploadError("Upload error code: " . $file_error);
                header('Location: index.php?upload=error');
                exit();
            }
        } else {
            logUploadError("Invalid file type: " . $file_ext);
            header('Location: index.php?upload=invalidtype');
            exit();
        }
    }
}
header('Location: index.php');
exit();