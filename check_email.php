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

// Get the email from the AJAX request
$email = $_POST['email'];

// Check if the email exists in the database
$sql = "SELECT id1 FROM users1 WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

// If the email exists in the database, return 'exists'
if ($stmt->num_rows > 0) {
    echo "exists";
} else {
    echo "available";
}

// Close the connection
$stmt->close();
$conn->close();
?>
