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

// Function to sanitize and escape input
function sanitize_input($conn, $data) {
    return mysqli_real_escape_string($conn, trim($data));
}

// Collect and sanitize form data
$place_name = sanitize_input($conn, $_POST['place_name']);

// Couple Section
$couple_places = sanitize_input($conn, $_POST['couple_places']);
$couple_best_time = sanitize_input($conn, $_POST['couple_best_time']);
$couple_worst_months = sanitize_input($conn, implode(',', $_POST['couple_worst_months']));
$couple_worst_months_reason = sanitize_input($conn, $_POST['couple_worst_months_reason']);
$couple_min_cost = sanitize_input($conn, $_POST['couple_min_cost']);

// Family Section
$family_places = sanitize_input($conn, $_POST['family_places']);
$family_best_time = sanitize_input($conn, $_POST['family_best_time']);
$family_worst_months = sanitize_input($conn, implode(',', $_POST['family_worst_months']));
$family_worst_months_reason = sanitize_input($conn, $_POST['family_worst_months_reason']);
$family_min_cost = sanitize_input($conn, $_POST['family_min_cost']);

// Friends Section
$friends_places = sanitize_input($conn, $_POST['friends_places']);
$friends_best_time = sanitize_input($conn, $_POST['friends_best_time']);
$friends_worst_months = sanitize_input($conn, implode(',', $_POST['friends_worst_months']));
$friends_worst_months_reason = sanitize_input($conn, $_POST['friends_worst_months_reason']);
$friends_min_cost = sanitize_input($conn, $_POST['friends_min_cost']);

// Solo Section
$solo_places = sanitize_input($conn, $_POST['solo_places']);
$solo_best_time = sanitize_input($conn, $_POST['solo_best_time']);
$solo_worst_months = sanitize_input($conn, implode(',', $_POST['solo_worst_months']));
$solo_worst_months_reason = sanitize_input($conn, $_POST['solo_worst_months_reason']);
$solo_min_cost = sanitize_input($conn, $_POST['solo_min_cost']);

// Insert data into the database
$sql = "INSERT INTO destinations1 (
            place_name, 
            couple_places, couple_best_time, couple_worst_months, couple_worst_months_reason, couple_min_cost, 
            family_places, family_best_time, family_worst_months, family_worst_months_reason, family_min_cost, 
            friends_places, friends_best_time, friends_worst_months, friends_worst_months_reason, friends_min_cost, 
            solo_places, solo_best_time, solo_worst_months, solo_worst_months_reason, solo_min_cost
        ) VALUES (
            '$place_name', 
            '$couple_places', '$couple_best_time', '$couple_worst_months', '$couple_worst_months_reason', '$couple_min_cost', 
            '$family_places', '$family_best_time', '$family_worst_months', '$family_worst_months_reason', '$family_min_cost', 
            '$friends_places', '$friends_best_time', '$friends_worst_months', '$friends_worst_months_reason', '$friends_min_cost', 
            '$solo_places', '$solo_best_time', '$solo_worst_months', '$solo_worst_months_reason', '$solo_min_cost'
        )";

if ($conn->query($sql) === TRUE) {
    echo "<script>
            alert('New destination added successfully');
            window.location.href = 'adminListedCountries.php';
          </script>";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
