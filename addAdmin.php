<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Add new admin
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$password = $_POST['password'];

$sql = "INSERT INTO admin1 (first_name, last_name, email, phone, password) VALUES ('$first_name', '$last_name', '$email', '$phone', '$password')";

if ($conn->query($sql) === TRUE) {
    echo "Admin added successfully";
} else {
    echo "Error adding admin: " . $conn->error;
}

$conn->close();
?>
