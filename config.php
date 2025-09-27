<?php
// Base configuration
define('UPLOAD_DIR', __DIR__ . DIRECTORY_SEPARATOR . 'uploads');
define('MAX_FILE_SIZE', 5242880); // 5MB
define('ALLOWED_TYPES', ['jpg', 'jpeg', 'png', 'gif']);

// Create uploads directory if it doesn't exist
if (!file_exists(UPLOAD_DIR)) {
    mkdir(UPLOAD_DIR, 0755, true);
}

// Error logging configuration
ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL);
ini_set('error_log', __DIR__ . DIRECTORY_SEPARATOR . 'error.log');

// Function to check server requirements
function checkServerRequirements() {
    $requirements = array();
    
    // Check PHP version
    $requirements['php_version'] = version_compare(PHP_VERSION, '7.4.0', '>=');
    
    // Check if GD or Imagick extension is installed
    $requirements['image_extension'] = extension_loaded('gd') || extension_loaded('imagick');
    
    // Check if uploads directory is writable
    $requirements['upload_writable'] = is_writable(UPLOAD_DIR);
    
    // Check file upload settings in php.ini
    $requirements['file_uploads'] = ini_get('file_uploads');
    $requirements['post_max_size'] = convertToBytes(ini_get('post_max_size')) >= MAX_FILE_SIZE;
    $requirements['upload_max_filesize'] = convertToBytes(ini_get('upload_max_filesize')) >= MAX_FILE_SIZE;
    
    return $requirements;
}

// Helper function to convert PHP size strings to bytes
function convertToBytes($size_str) {
    $unit = strtolower(substr($size_str, -1));
    $value = (int)$size_str;
    
    switch ($unit) {
        case 'g':
            $value *= 1024;
        case 'm':
            $value *= 1024;
        case 'k':
            $value *= 1024;
    }
    
    return $value;
}