<?php
// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch id1 from the GET parameter
$id1 = isset($_GET['id1']) ? $_GET['id1'] : '';

// Prepare the SQL query to fetch user details
$sql = "SELECT first_name, last_name, have_subscription FROM users1 WHERE id1 = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id1);
$stmt->execute();
$result = $stmt->get_result();

// Fetch user details and return as JSON
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    echo json_encode($user);
} else {
    echo json_encode(["error" => "User not found"]);
}

// Close the connection
$stmt->close();
$conn->close();
?>
