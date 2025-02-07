<?php
// Database connection
$servername = "localhost";
$username = "root";  // replace with your database username
$password = "";      // replace with your database password
$dbname = "project";  // replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the phone number from the AJAX request
$phone = $_POST['phone'];

// Check if the phone number exists in the database
$sql = "SELECT id1 FROM users1 WHERE phone = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $phone);
$stmt->execute();
$stmt->store_result();

// If the phone number exists in the database, return 'exists'
if ($stmt->num_rows > 0) {
    echo "exists";
} else {
    echo "available";
}

// Close the connection
$stmt->close();
$conn->close();
?>
