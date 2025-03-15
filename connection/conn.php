<?php
$host = "localhost";
$user = "root"; // Change if you have a different user
$pass = ""; // Change if you have a password
$dbname = "pos"; // Change to your actual database name

// Create connection
$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "TRUNCATE TABLE bought";
if ($conn->query($sql) !== TRUE) {
    http_response_code(500);
    echo "Error truncating table: " . $conn->error;
}

// Set character encoding to support special characters like ñ, é, etc.
$conn->set_charset("utf8mb4");
?>
