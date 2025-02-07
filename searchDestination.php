<?php
// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";

// Establishing a database connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if 'searchTerm' is provided
if (isset($_POST['searchTerm'])) {
    $searchTerm = $_POST['searchTerm'];

    // Prepare the query to perform a case-insensitive search
    $stmt = $conn->prepare("SELECT id FROM destinations1 WHERE LOWER(place_name) = LOWER(?)");
    $stmt->bind_param("s", $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $row = $result->fetch_assoc()) {
        // Return ID if the place is found
        echo json_encode(["status" => "success", "id" => $row['id']]);
    } else {
        // Return an error if the place is not found
        echo json_encode(["status" => "error", "message" => "The place you searched is planned, please try another one."]);
    }
    $stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "No search term provided."]);
}

$conn->close();
?>
