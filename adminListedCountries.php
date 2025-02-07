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

// Array to store the places and their ids
$places = [];
$sql = "SELECT id, place_name FROM destinations1";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Storing each place along with its id in the array
        $places[] = $row;
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Listed Countries</title>
    <style>
         * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }
        body {
            display: flex;
            margin: 0;
            font-family: Arial, sans-serif;
        }
        .sidebar {
            background-color: #3f4850;
            padding: 20px;
            color: #fff;
            height: 100vh;
            width: 250px;
            display: flex;
            flex-direction: column;
            position: fixed; /* Sidebar remains fixed */
            top: 0;
            left: 0;
        }
        .sidebar h2 {
            margin-bottom: 20px;
        }
        .sidebar button {
            background: #47525d;
            border: none;
            padding: 15px;
            margin: 5px 0;
            color: white;
            cursor: pointer;
            text-align: left;
            transition: background 0.3s, transform 0.3s ease;
        }
        .sidebar button:hover {
            background: #5a6b7d;
            transform: translateX(5px);
        }
        .main-content {
            flex: 1;
            padding: 30px;
            margin-left: 250px; /* Leave space for fixed sidebar */
        }
        .main-content h1 {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .search-add-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .search-box {
            position: relative;
        }
        .search-box input[type="text"] {
            padding: 10px 40px 10px 10px;
            font-size: 1rem;
            border: 2px solid #f09b57;
            border-radius: 5px;
            outline: none;
        }
        .search-box input[type="text"]::placeholder {
            color: #777;
        }
        .search-box::before {
            content: "\1F50D"; /* Unicode for magnifying glass */
            position: absolute;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
            font-size: 1rem;
            color: #f09b57;
        }
        .add-button {
            padding: 10px 20px;
            font-size: 1rem;
            background-color: #f09b57;
            color: #ffffff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .add-button:hover {
            background-color: #e07a3f;
        }
        .destination-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .destination-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px;
            background-color: #565656;
            border-radius: 5px;
            color: #ffffff;
        }
        .destination-item button {
            padding: 5px 15px;
            font-size: 0.9rem;
            color: #ffffff;
            background-color: #ff4c4c;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .destination-item button:hover {
            background-color: #d94343;
        }
        .country-name {
            font-weight: bold;
            color: #cfcfcf;
        }
        .action-buttons {
            display: flex;
            gap: 10px;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>Travel Admin Panel</h2>
        <button onclick="window.location.href='adminDashboard.html'">Dashboard</button>
        <button onclick="window.location.href='adminRegistrations.php'">Registered Users</button>
        <button onclick="window.location.href='adminListedCountries.php'">Listed Destinations</button>
        <button onclick="window.location.href='adminProfile.php'">Admins Data</button>
        <button onclick="window.location.href='adminReview.php'">View Feedbacks</button>
        <button onclick="window.location.href='index.html'">Logout</button>
    </div>

    <div class="main-content">
        <h1>Listed Destinations</h1>
        <div class="search-add-container">
            <div class="search-box">
                <input type="text" id="search-input" placeholder="Search the destinations" onkeyup="filterDestinations()">
            </div>
            <button class="add-button" onclick="window.location.href='adminAddCountry.html'">Add a New Place</button>
        </div>

        <div class="destination-list" id="destination-list">
            <?php
            // Loop through the fetched places and display them
            foreach ($places as $place) {
                echo '<div class="destination-item">';
                echo '<span class="country-name">' . htmlspecialchars($place['place_name']) . '</span>';
                echo '<div class="action-buttons">';
                // Pass the id dynamically in the URL
                echo '<button onclick="window.location.href=\'adminEditCountry.php?id=' . urlencode($place['id']) . '\'">Edit</button>';
                echo '<button onclick="removeDestination(\'' . htmlspecialchars($place['place_name']) . '\')">Remove</button>';
                echo '</div>';
                echo '</div>';
            }
            ?>
        </div>
    </div>

    <script>
        function removeDestination(country) {
            if (confirm(`Are you sure you want to remove ${country}?`)) {
                window.location.href = `deleteDestination.php?place_name=${encodeURIComponent(country)}`;
            }
        }

        function filterDestinations() {
            const input = document.getElementById("search-input").value.toLowerCase();
            const destinationItems = document.getElementById("destination-list").getElementsByClassName("destination-item");

            for (let i = 0; i < destinationItems.length; i++) {
                const placeName = destinationItems[i].getElementsByClassName("country-name")[0].textContent.toLowerCase();
                destinationItems[i].style.display = placeName.includes(input) ? "" : "none";
            }
        }
    </script>
</body>
</html>
