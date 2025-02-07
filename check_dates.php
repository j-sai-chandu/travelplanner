<?php
// Database connection
$host = "localhost";
$username = "root"; // Replace with your database username
$password = ""; // Replace with your database password
$dbname = "project"; // Replace with your database name

$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch 'id' and 'id1' from the URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$id1 = isset($_GET['id1']) ? intval($_GET['id1']) : 0; // Fetch 'id1' if available
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';

$response = [
    'isWorstMonth' => false,
    'reason' => ''
];

if ($id && $start_date && $end_date) {
    // Query to fetch worst months and reasons for the given place ID
    // Modify the query to also consider 'id1' if needed in your logic
    $query = "SELECT couple_worst_months, couple_worst_months_reason 
              FROM destinations1 
              WHERE id = $id"; // For now, we are using just 'id'
    
    // Optionally use 'id1' in the query if needed:
    // $query = "SELECT couple_worst_months, couple_worst_months_reason 
    //           FROM destinations 
    //           WHERE id = $id AND id1 = $id1"; 

    $result = $conn->query($query);

    if ($result && $row = $result->fetch_assoc()) {
        $worst_months = explode(',', $row['couple_worst_months']);
        $reason = $row['couple_worst_months_reason'];

        // Extract month from start and end dates
        $start_month = date('F', strtotime($start_date));
        $end_month = date('F', strtotime($end_date));

        // Check if either the start or end month is in the worst months
        if (in_array($start_month, $worst_months) || in_array($end_month, $worst_months)) {
            $response['isWorstMonth'] = true;
            $response['reason'] = $reason;
        }
    }
}

header('Content-Type: application/json');
echo json_encode($response);

$conn->close();
?>
