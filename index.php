<?php
require_once 'config.php';

// Check server requirements
$requirements = checkServerRequirements();
$canUpload = !in_array(false, $requirements, true);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image Upload</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Upload Image</h2>
        <?php if (!$canUpload): ?>
            <div class="error-message">
                <h3>Server Configuration Issues:</h3>
                <ul>
                    <?php if (!$requirements['php_version']): ?>
                        <li>PHP version 7.4 or higher is required</li>
                    <?php endif; ?>
                    <?php if (!$requirements['image_extension']): ?>
                        <li>GD or Imagick extension is required</li>
                    <?php endif; ?>
                    <?php if (!$requirements['upload_writable']): ?>
                        <li>Upload directory is not writable</li>
                    <?php endif; ?>
                    <?php if (!$requirements['file_uploads']): ?>
                        <li>File uploads are disabled in PHP configuration</li>
                    <?php endif; ?>
                    <?php if (!$requirements['post_max_size'] || !$requirements['upload_max_filesize']): ?>
                        <li>PHP file upload size limits are too low</li>
                    <?php endif; ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_GET['upload'])): ?>
            <div class="message <?php echo $_GET['upload'] === 'success' ? 'success' : 'error'; ?>">
                <?php
                switch ($_GET['upload']) {
                    case 'success':
                        echo "File uploaded successfully!";
                        break;
                    case 'failed':
                        echo "Failed to upload file.";
                        break;
                    case 'toolarge':
                        echo "File is too large (max 5MB).";
                        break;
                    case 'invalidtype':
                        echo "Invalid file type. Allowed types: " . implode(', ', ALLOWED_TYPES);
                        break;
                    default:
                        echo "An error occurred.";
                }
                ?>
            </div>
        <?php endif; ?>

        <form action="upload.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <input type="file" name="image" accept="image/*" required <?php echo !$canUpload ? 'disabled' : ''; ?>>
            </div>
            <button type="submit" name="submit" <?php echo !$canUpload ? 'disabled' : ''; ?>>Upload Image</button>
        </form>
        
        <div class="preview">
            <?php
            $files = glob(UPLOAD_DIR . DIRECTORY_SEPARATOR . "*.*");
            if ($files) {
                echo "<h3>Uploaded Images:</h3>";
                foreach ($files as $file) {
                    $relativePath = str_replace(__DIR__ . DIRECTORY_SEPARATOR, '', $file);
                    echo "<img src='$relativePath' width='200' alt='Uploaded image'>";
                }
            }
            ?>
        </div>
    </div>
</body>
</html>