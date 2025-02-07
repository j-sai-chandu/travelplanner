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

// Update admin details
$id = $_POST['id'];
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$password = $_POST['password'];

$sql = "UPDATE admin1 SET first_name='$first_name', last_name='$last_name', email='$email', phone='$phone', password='$password' WHERE id=$id";

if ($conn->query($sql) === TRUE) {
    echo "Admin updated successfully";
} else {
    echo "Error updating admin: " . $conn->error;
}

$conn->close();
?>
