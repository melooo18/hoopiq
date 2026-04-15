<?php
session_start();

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'hoopiq');

// Connect to Database
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    die("Database connection failed. Please ensure the MySQL server is running and the 'hoopiq' database exists.");
}

// API Keys - Note: The user swapped these in the prompt so we corrected them based on typical key formats.
define('BALLDONTLIE_API_KEY', 'fd919e87-34d0-4663-8512-fe604f4a45ba');
define('GEMINI_API_KEY', 'AIzaSyAqOzSe6LJKQc3C-mnhOM3sPI-pYqHyX3A');

// Redirect helper
function redirect($url) {
    header("Location: $url");
    exit();
}
?>
