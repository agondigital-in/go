<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image Upload !</title>
    <style>
        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .preview {
            max-width: 300px;
            margin: 20px 0;
            display: none;
        }
        .message {
            padding: 10px;
            margin: 10px 0;
            border-radius: 4px;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Image Upload !</h2>
        
        <?php
        if (isset($_SESSION['message'])) {
            $messageClass = isset($_SESSION['error']) && $_SESSION['error'] ? 'error' : 'success';
            echo '<div class="message ' . $messageClass . '">' . $_SESSION['message'] . '</div>';
            if (isset($_SESSION['uploaded_image'])) {
                echo '<img src="uploads/' . $_SESSION['uploaded_image'] . '" alt="Uploaded image" style="max-width: 300px;">';
            }
            unset($_SESSION['message']);
            unset($_SESSION['error']);
            unset($_SESSION['uploaded_image']);
        }
        ?>

        <form action="upload_handler.php" method="POST" enctype="multipart/form-data">
            <div>
                <label for="image">Select Image (JPG, JPEG, PNG, GIF only):</label>
                <input type="file" id="image" name="image" accept="image/*" onchange="previewImage(this);">
            </div>
            <div>
                <img id="preview" src="#" alt="Preview" class="preview">
            </div>
            <button type="submit">Upload Image</button>
        </form>
    </div>

    <script>
        function previewImage(input) {
            const preview = document.getElementById('preview');
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }
                
                reader.readAsDataURL(input.files[0]);
            } else {
                preview.src = '#';
                preview.style.display = 'none';
            }
        }
    </script>
</body>
</html>