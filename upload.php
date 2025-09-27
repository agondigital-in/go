<?php
if(isset($_POST['submit'])) {
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
        
        // Allowed extensions
        $allowed = array('jpg', 'jpeg', 'png', 'gif');
        
        if(in_array($file_ext, $allowed)) {
            if($file_error === 0) {
                if($file_size <= 5242880) { // 5MB max file size
                    // Create unique filename
                    $file_name_new = uniqid('img_', true) . '.' . $file_ext;
                    $file_destination = 'uploads/' . $file_name_new;
                    
                    if(move_uploaded_file($file_tmp, $file_destination)) {
                        header('Location: index.php?upload=success');
                        exit();
                    } else {
                        header('Location: index.php?upload=failed');
                        exit();
                    }
                } else {
                    header('Location: index.php?upload=toolarge');
                    exit();
                }
            } else {
                header('Location: index.php?upload=error');
                exit();
            }
        } else {
            header('Location: index.php?upload=invalidtype');
            exit();
        }
    }
}
header('Location: index.php');
exit();