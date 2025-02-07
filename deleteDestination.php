<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['place_name'])) {
    $place_name = $conn->real_escape_string($_GET['place_name']);
    $sql = "DELETE FROM destinations1 WHERE place_name = '$place_name'";
    
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Destination removed successfully.'); window.location.href = 'adminListedCountries.php';</script>";
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}
$conn->close();
?>
