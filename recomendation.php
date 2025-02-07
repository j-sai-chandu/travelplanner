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

// Fetch data from the database
$search = isset($_GET['search']) ? $_GET['search'] : '';
$query = "SELECT place_name, couple_worst_months, couple_worst_months_reason 
          FROM destinations1 
          WHERE place_name LIKE '%$search%'";

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recommendation Details</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #000;
            color: #fff;
            margin: 0;
            padding-top: 70px; /* Space for the fixed header */
        }

        header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            background-color: #1e1e1e;
            color: white;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            z-index: 1000;
        }

        .back-button {
            position: relative;
            cursor: pointer;
        }

        .back-button img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .back-button:hover img {
            opacity: 0.8;
        }

        .search-bar {
            flex: 1;
            display: flex;
            justify-content: center;
        }

        .search-bar input {
            padding: 10px;
            width: 300px;
            border: none;
            border-radius: 5px;
            background-color: #001f1f;
            color: #fff;
            border: 1px solid #fff;
            font-size: 16px;
            outline: none;
            transition: background-color 0.3s, border-color 0.3s;
        }

        .search-bar input:focus {
            background-color: #002b2b;
            border-color: #00ffcc;
        }

        .title-container {
            display: flex;
            align-items: center;
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
        }

        .globe {
            color: white;
        }

        h1 {
            color: #fff;
            margin-left: 10px;
        }

        main {
            padding-bottom: 60px; /* Ensures the table doesn't overlap with the footer */
        }

        table {
            width: calc(100% - 20px); /* Adjusted for 10px left and right margins */
            margin: 20px 10px 0 10px; /* Margins around the table */
            border-collapse: collapse;
            transform: translateX(-100%);
            animation: slideInTable 0.8s ease-out forwards;
        }

        @keyframes slideInTable {
            from {
                transform: translateX(-100%);
            }
            to {
                transform: translateX(0);
            }
        }

        table, th, td {
            border: 1px solid #fff;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #004d40;
        }

        td {
            background-color: #001f1f;
            transition: background-color 0.3s;
        }

        tbody tr:hover {
            background-color: #004d40;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        footer {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background-color: #1e1e1e;
            color: white;
            text-align: center;
            padding: 10px 0;
            box-shadow: 0 -4px 8px rgba(0, 0, 0, 0.2);
            z-index: 1000;
        }

        footer p {
            font-size: 14px;
            margin: 0;
        }
        .title-container:hover::after {
            content: "Travel Planner Logo";
            position: absolute;
            top: 50px;
            right: 0;
            background-color: #000;
            color: #fff;
            padding: 5px 10px;
            font-size: 12px;
            border-radius: 5px;
            white-space: nowrap;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            z-index: 1000;
        }
        .back-button:hover::after {
            content: "Go Back";
            position: absolute;
            top: 50px;
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
    </style>
    <script>
        function goBack() {
            window.history.back(); // Navigate to the previous page
        }
    </script>
</head>
<body>
    <header>
        <!-- Back Button -->
        <div class="back-button" onclick="goBack()">
            <img src="photo/back.jpeg" alt="Back">
        </div>
        <!-- Search Bar -->
        <div class="search-bar">
            <input 
                type="text" 
                id="search" 
                placeholder="Search..." 
                onkeyup="filterTable()" 
                value="<?php echo htmlspecialchars($search); ?>"
            >
        </div>
        <!-- Title with Main Icon -->
        <div class="title-container">
            <img src="photo/main-icon.png" alt="Main Icon" class="main-icon">
            <div class="wiseglobe-text">
                <span class="wise">Travel</span><span class="globe">Planner</span>
            </div>
        </div>
    </header>

    <main>
        <h1>Recommendation Details</h1>
        <table>
            <thead>
                <tr>
                    <th>Place Name</th>
                    <th>Best Months for Visiting</th>
                    <th>Worst Months to Visit</th>
                    <th>Reason</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $place_name = $row['place_name'];
                        $worst_months = $row['couple_worst_months'];
                        $reason = $row['couple_worst_months_reason'];

                        // Calculate best months
                        $all_months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
                        $worst_months_array = array_map('trim', explode(',', $worst_months));
                        $best_months_array = array_diff($all_months, $worst_months_array);
                        $best_months = implode(', ', $best_months_array);

                        echo "<tr>
                                <td>$place_name</td>
                                <td>$best_months</td>
                                <td>$worst_months</td>
                                <td>$reason</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No results found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </main>

    <footer>
        <p>&copy; 2024 All Rights Reserved</p>
    </footer>

    <script>
        function filterTable() {
            const searchInput = document.getElementById('search').value.toLowerCase();
            const rows = document.querySelectorAll('tbody tr');

            rows.forEach(row => {
                const placeName = row.querySelector('td:first-child').innerText.toLowerCase();
                if (placeName.includes(searchInput)) {
                    row.style.display = ''; // Show the row
                } else {
                    row.style.display = 'none'; // Hide the row
                }
            });
        }
    </script>
</body>
</html>

<?php
$conn->close();
?>
