<?php
// config.php - Database connection setup

$servername = "localhost";  // Database server name
$username = "root";         // Database username
$password = "";             // Database password (leave empty for XAMPP default)
$dbname = "project";        // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
