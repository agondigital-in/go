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
        <form action="upload.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <input type="file" name="image" accept="image/*" required>
            </div>
            <button type="submit" name="submit">Upload Image</button>
        </form>
        <div class="preview">
            <?php
            $files = glob("uploads/*.*");
            if ($files) {
                echo "<h3>Uploaded Images:</h3>";
                foreach ($files as $file) {
                    echo "<img src='$file' width='200'>";
                }
            }
            ?>
        </div>
    </div>
</body>
</html>