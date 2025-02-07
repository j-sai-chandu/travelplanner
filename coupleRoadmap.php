<?php
// Database connection
$servername = "localhost";
$username = "root"; // your database username
$password = ""; // your database password
$dbname = "project"; // replace with your actual database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch the `places` and `id` parameters from the URL
$places = isset($_GET['places']) ? $_GET['places'] : null;
$id = isset($_GET['id']) ? $_GET['id'] : null;  // Fetch the ID from the URL

// Check if both parameters are set
if ($places === null && $id === null) {
    die("Error: Both 'places' and 'id' parameters are missing.");
} elseif ($places === null) {
    die("Error: 'places' parameter is missing.");
} elseif ($id === null) {
    die("Error: 'id' parameter is missing.");
}

// Convert the `places` string into an array
$selectedPlaces = explode(",", $places);

// Query to fetch the `couple_places` and `couple_best_time` based on `id`
$sql = "SELECT couple_places, couple_best_time FROM destinations WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id); // Bind `id` as integer
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $couplePlaces = $row['couple_places'];
    $coupleBestTime = $row['couple_best_time'];

    // Convert `couple_best_time` (comma-separated) into an array
    $bestTimeArray = explode(',', $coupleBestTime);

    // Match selected places with their respective best times
    $matchedPlaces = [];
    $placesArray = explode(',', $couplePlaces);
    foreach ($selectedPlaces as $selectedPlace) {
        $selectedPlace = trim($selectedPlace);
        $key = array_search($selectedPlace, $placesArray);
        if ($key !== false) {
            $matchedPlaces[] = [
                'place' => $selectedPlace,
                'best_time' => isset($bestTimeArray[$key]) ? $bestTimeArray[$key] : 'No best time available'
            ];
        }
    }
} else {
    echo "No data found for this ID.";
    exit;
}

// Close the database connection
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Couple Roadmap</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #121212;
            color: #ffffff;
            padding: 20px;
        }
        h1 {
            text-align: center;
            margin-bottom: 30px;
        }
        .roadmap-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }
        .roadmap-card {
            background-color: #1e1e1e;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
        }
        .roadmap-card h2 {
            margin: 0 0 10px;
        }
        .roadmap-card p {
            font-size: 1.1em;
            margin: 0;
        }
    </style>
</head>
<body>

    <h1>Your Travel Roadmap</h1>

    <div class="roadmap-container">
        <?php
        // Display selected places with their best time
        if (!empty($matchedPlaces)) {
            foreach ($matchedPlaces as $placeInfo) {
                echo "<div class='roadmap-card'>";
                echo "<h2>" . htmlspecialchars($placeInfo['place']) . "</h2>";
                echo "<p><strong>Best Time to Visit:</strong> " . htmlspecialchars($placeInfo['best_time']) . "</p>";
                echo "</div>";
            }
        } else {
            echo "<p>No matched places found.</p>";
        }
        ?>
    </div>

</body>
</html>
