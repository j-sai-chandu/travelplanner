<?php
// fetch_places.php
header('Content-Type: application/json');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve all destinations
$sql = "SELECT id, place_name FROM destinations";
$result = $conn->query($sql);

$places = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $places[] = $row;
    }
}

echo json_encode($places);
$conn->close();
?>
