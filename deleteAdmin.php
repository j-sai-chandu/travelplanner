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

// Delete admin
$id = $_POST['id'];

$sql = "DELETE FROM admin1 WHERE id=$id";

if ($conn->query($sql) === TRUE) {
    echo "Admin deleted successfully";
} else {
    echo "Error deleting admin: " . $conn->error;
}

$conn->close();
?>
