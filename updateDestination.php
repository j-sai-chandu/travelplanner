<?php
// Database connection
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

// Collect form data and sanitize inputs
$place_name = $conn->real_escape_string($_POST['place_name']);
$id = $conn->real_escape_string($_POST['id']); // Assuming the ID is passed from a form

// Couple Section
$couple_places = $conn->real_escape_string($_POST['couple_places']);
$couple_best_time = $conn->real_escape_string($_POST['couple_best_time']);
$couple_worst_months = implode(',', array_map([$conn, 'real_escape_string'], $_POST['couple_worst_months']));
$couple_worst_months_reason = $conn->real_escape_string($_POST['couple_worst_months_reason']);
$couple_min_cost = $conn->real_escape_string($_POST['couple_min_cost']);

// Family Section
$family_places = $conn->real_escape_string($_POST['family_places']);
$family_best_time = $conn->real_escape_string($_POST['family_best_time']);
$family_worst_months = implode(',', array_map([$conn, 'real_escape_string'], $_POST['family_worst_months']));
$family_worst_months_reason = $conn->real_escape_string($_POST['family_worst_months_reason']);
$family_min_cost = $conn->real_escape_string($_POST['family_min_cost']);

// Friends Section
$friends_places = $conn->real_escape_string($_POST['friends_places']);
$friends_best_time = $conn->real_escape_string($_POST['friends_best_time']);
$friends_worst_months = implode(',', array_map([$conn, 'real_escape_string'], $_POST['friends_worst_months']));
$friends_worst_months_reason = $conn->real_escape_string($_POST['friends_worst_months_reason']);
$friends_min_cost = $conn->real_escape_string($_POST['friends_min_cost']);

// Solo Section
$solo_places = $conn->real_escape_string($_POST['solo_places']);
$solo_best_time = $conn->real_escape_string($_POST['solo_best_time']);
$solo_worst_months = implode(',', array_map([$conn, 'real_escape_string'], $_POST['solo_worst_months']));
$solo_worst_months_reason = $conn->real_escape_string($_POST['solo_worst_months_reason']);
$solo_min_cost = $conn->real_escape_string($_POST['solo_min_cost']);

// Update query
$query = "UPDATE destinations1 SET 
    place_name = '$place_name',
    couple_places = '$couple_places',
    couple_best_time = '$couple_best_time',
    couple_worst_months = '$couple_worst_months',
    couple_worst_months_reason = '$couple_worst_months_reason',
    couple_min_cost = '$couple_min_cost',
    family_places = '$family_places',
    family_best_time = '$family_best_time',
    family_worst_months = '$family_worst_months',
    family_worst_months_reason = '$family_worst_months_reason',
    family_min_cost = '$family_min_cost',
    friends_places = '$friends_places',
    friends_best_time = '$friends_best_time',
    friends_worst_months = '$friends_worst_months',
    friends_worst_months_reason = '$friends_worst_months_reason',
    friends_min_cost = '$friends_min_cost',
    solo_places = '$solo_places',
    solo_best_time = '$solo_best_time',
    solo_worst_months = '$solo_worst_months',
    solo_worst_months_reason = '$solo_worst_months_reason',
    solo_min_cost = '$solo_min_cost'
    WHERE id = $id";

if ($conn->query($query) === TRUE) {
    echo "<script>
            alert('Destination Updated successfully');
            window.location.href = 'adminListedCountries.php';
          </script>";
} else {
    echo "Error updating destination: " . $conn->error;
}

$conn->close();
?>
