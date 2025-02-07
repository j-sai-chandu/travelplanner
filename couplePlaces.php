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

// Fetch the `id` and `id1` from the URL query string
$id = isset($_GET['id']) ? $_GET['id'] : null;
$id1 = isset($_GET['id1']) ? $_GET['id1'] : null;

if ($id === null) {
    die("ID parameter is required.");
}

// Query to fetch `couple_places`, `couple_best_time`, and `couple_min_cost` based on `id`
$sql = "SELECT couple_places, couple_best_time, couple_min_cost FROM destinations1 WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id); // bind `id` as integer
$stmt->execute();
$result = $stmt->get_result();

// Initialize arrays to avoid undefined variable errors
$placesArray = [];
$bestTimeArray = [];
$minCostArray = [];
$totalCost = 0;  // Initialize total cost

// Fetch data if available
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $couplePlaces = $row['couple_places'];
    $coupleBestTime = $row['couple_best_time'];
    $coupleMinCost = $row['couple_min_cost'];

    // Convert comma-separated values into arrays
    $placesArray = !empty($couplePlaces) ? explode(',', $couplePlaces) : [];
    $bestTimeArray = !empty($coupleBestTime) ? explode(',', $coupleBestTime) : [];
    $minCostArray = !empty($coupleMinCost) ? explode(',', $coupleMinCost) : [];
    
    // Calculate total cost by summing up the costs, skipping the first place
    foreach ($minCostArray as $index => $cost) {
        if ($index === 0) {
            continue; // Skip the cost of the first place
        }
        // Remove currency symbol if it exists and add cost to total
        $cleanCost = preg_replace('/[^0-9.]/', '', $cost); // Removes any non-numeric characters
        $totalCost += (float)$cleanCost; // Add the cleaned cost to the total
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
    <title>RoadMap</title>
    <style>
         body {
            font-family: Arial, sans-serif;
            background-color: #121212;
            color: #ffffff;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
        }
        h1 {
            margin-bottom: 20px;
        }
        .places-container {
            width: 90%;
            max-width: 800px;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        .table-header {
            display: grid;
            grid-template-columns: 1fr 2fr 2fr 2fr 1fr;
            align-items: center;
            background-color: #2e7d32;
            border-radius: 8px;
            padding: 15px;
            font-weight: bold;
            color: #ffffff;
            text-align: center;
        }
        .place-card {
            display: grid;
            grid-template-columns: 1fr 2fr 2fr 2fr 1fr;
            align-items: center;
            background-color: #1e1e1e;
            border-radius: 8px;
            padding: 15px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
            transition: background-color 0.2s, transform 0.2s;
            text-align: center;
        }
        .place-card:hover {
            background-color: #2e7d32;
            transform: scale(1.02);
        }
        .highlight-cost {
            color: #ffff; /* Highlighted color for minimum cost */
            font-weight: bold;
        }
        .total-cost {
            margin-top: 20px;
            font-size: 1.2em;
            color: #ffcc00;
            font-weight: bold;
            text-align: center;
        }
        .view-button {
            background-color: #2e7d32;
            color: #ffffff;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        .view-button:hover {
            background-color: #1e5a26;
        }
        .feedback-container {
            margin-top: 20px;
            text-align: center;
        }
        .feedback-button {
            background-color: #2e7d32;
            color: #ffffff;
            border: none;
            padding: 12px 30px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
            transition: background-color 0.2s;
        }
        .feedback-button:hover {
            background-color: #1e5a26;
        }
        .back-button {
            position: absolute;
            top: 20px; /* Positioned at the top-left of the container */
            left: 20px;
            width: 50px; /* Increased size */
            height: 50px; /* Increased size */
            cursor: pointer;
            position: fixed;
        }

        .back-button img {
            width: 70%;
            height: 70%;
            border-radius: 50%;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            margin-top:0px;
        }

        /* Tooltip styling */
        .back-button:hover::after {
            content: "Go Back";
            position: absolute;
            top: 60px; /* Below the button */
            left: 50%;
            transform: translateX(-50%);
            background-color: #000;
            color: #fff;
            padding: 5px 10px;
            font-size: 12px;
            border-radius: 5px;
            white-space: nowrap;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            z-index: 1000;
        }
        .note {
            margin-top: 40px;
            font-size: 1em;
            color: #ffffff;
            text-align: center;
            padding: 15px;
            background-color: #333;
            border-radius: 8px;
            max-width: 80%;
            line-height: 1.5;
        }
        header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 20px;
            background-color: #1e1e1e;
            color: white;
            width: 100%;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            height:50px;
        }
        .title-container {
            display: flex;
            align-items: center;
            margin-left: auto; /* Push the title container to the right */
            position: relative;
            cursor: pointer;
            margin-right:30px;
        }

        .main-icon {
            width: 30px;
            height: 30px;
            margin-right: 10px;
        }

        .wiseglobe-text {
            font-size: 18px;
            font-weight: bold;
            display: flex;
            align-items: center;
        }

        .wise {
            color: orangered;
            font-size: 20px;
            font-weight: bold;
        }

        .globe {
            color: white;
            font-size: 20px;
            font-weight: bold;
        }
    </style>
</head>
<body>
<header>
    <div class="back-button" onclick="goBack()">
        <img src="photo/back.jpeg" alt="Back">
    </div>
    <div class="title-container">
        <img src="photo/main-icon.png" alt="Main Icon" class="main-icon">
        <div class="wiseglobe-text">
            <span class="wise">Travel</span><span class="globe">Planner</span>
        </div>
    </div>
</header>
<br><br><br><br><br>

<div class="places-container">
    <div class="table-header">
        <span>S.No</span>
        <span>Place Name</span>
        <span>Best View Time</span>
        <span>Minimum Travel Cost</span>
        <span>Navigation</span>
    </div>

    <?php
    if (count($placesArray) > 0) {
        for ($i = 0; $i < count($placesArray); $i++) {
            $place = trim($placesArray[$i]);
            $bestTime = isset($bestTimeArray[$i]) ? $bestTimeArray[$i] : 'No best time available';
            $minCost = isset($minCostArray[$i]) ? $minCostArray[$i] : 'No cost available';

            if ($i === 0) {
                $minCost = '-';
            }

            $serialNumber = $i + 1;

            echo "<div class='place-card'>";
            echo "<span>$serialNumber</span>";
            echo "<span>$place</span>";
            echo "<span>$bestTime</span>";
            echo "<span class='highlight-cost'>$minCost</span>";
            echo "<span><button class='view-button' onclick='window.location.href=\"maps-main/index.php?id={$id}&place=" . urlencode($place) . "\"'>Navigate</button></span>";
            echo "</div>";
        }
    }
    ?>

    <div class="total-cost">
        <p>Total Travel Expenses: <?php echo number_format($totalCost, 2); ?></p>
    </div>

    <div class="feedback-container">
        <button class="feedback-button" onclick="checkLoginAndRedirect()">Give Feedback</button>
        <button class="feedback-button" onclick="window.location.href='index.html'">Go Home</button>
    </div>
</div>

<div class="note">
    Note: This is the minimum travel cost for 1 person. It may vary for you based on your travel type, your location, and your time. This is an assumption and may vary.
</div>

<script>
function goBack() {
    window.history.back();
}

function checkLoginAndRedirect() {
    const id1 = "<?php echo $id1; ?>"; // Fetch the PHP value for id1
    if (id1=='null') {
        alert("Please login or create an account to provide feedback.");
        window.location.href = "login2.html";
    } else {
        const id = "<?php echo $id; ?>";
        window.location.href = `review/review_form.html?id=${id}&id1=${id1}`;
    }
}
</script>
</body>
</html>
