<?php
// Database connection parameters
$servername = "localhost";
$username = "root";
$password = ""; // Default password for XAMPP
$dbname = "project";

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch the total number of reviews
$sql_count = "SELECT COUNT(*) AS total_reviews FROM reviews1";
$result_count = $conn->query($sql_count);
$total_reviews = 0;

if ($result_count && $result_count->num_rows > 0) {
    $row = $result_count->fetch_assoc();
    $total_reviews = $row['total_reviews'];
}

// Fetch all review details from the database
$sql = "SELECT id, user_name, stars, experience, submitted_at FROM reviews1";
$result = $conn->query($sql);

// Check if there are any reviews and store them in an array
$reviews = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $reviews[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Reviews</title>
    <link rel="stylesheet" href="adminRegistrations.css">
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

    <div class="form-box">
        <h3 style="margin-left:20px">User Reviews</h3>

        <!-- Search box for filtering reviews -->
        <div style="margin-left:20px; margin-bottom: 10px;">
            <input type="text" id="search-input" placeholder="Search reviews...."
                   onkeyup="filterReviews()"
                   style="padding: 10px; font-size: 1rem; border: 2px solid black; border-radius: 5px; outline: none;">
        </div>

        <?php if (!empty($reviews)): ?>
            <table id="reviewTable">
                <tr>
                    <th>User Name</th>
                    <th>Rating (Stars)</th>
                    <th>Experience</th>
                    <th>Submitted At</th>
                </tr>
                <?php foreach ($reviews as $review): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($review['user_name']); ?></td>
                        <td class="stars" data-stars="<?php echo $review['stars']; ?>"><?php echo str_repeat('★', $review['stars']); ?></td>
                        <td><?php echo htmlspecialchars($review['experience']); ?></td>
                        <td><?php echo htmlspecialchars($review['submitted_at']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>No reviews found.</p>
        <?php endif; ?>

        <!-- Button to Show Total Reviews -->
        <div class="button-container">
            <br>
            <button onclick="showTotalReviews()" style="margin-left:20px;font-weight:bold;background-color:#cccccc;color:black;height:40px;">Show Total Number of Reviews</button>
        </div>

        <!-- Display the total number of reviews here -->
        <p id="totalReviewsDisplay" style="display:none;margin-left:20px;">Total Number of Reviews: <?php echo $total_reviews; ?></p> <!-- Hidden initially -->
    </div>

    <script>
        function showTotalReviews() {
            var totalReviewsElement = document.getElementById('totalReviewsDisplay');
            totalReviewsElement.style.display = 'block';
        }

        function filterReviews() {
            const input = document.getElementById('search-input').value.toLowerCase();
            const rows = document.getElementById('reviewTable').getElementsByTagName('tr');

            // Loop through all table rows except the header
            for (let i = 1; i < rows.length; i++) {
                const userName = rows[i].getElementsByTagName('td')[0].textContent.toLowerCase();
                const experience = rows[i].getElementsByTagName('td')[2].textContent.toLowerCase();
                const stars = rows[i].getElementsByClassName('stars')[0];
                const starsCount = stars ? stars.getAttribute('data-stars') : ''; // Get the stars count from data attribute

                // Check if any of the columns match the input (including stars)
                if (
                    userName.includes(input) ||
                    experience.includes(input) ||
                    starsCount.includes(input) // Filter by star count (number of stars)
                ) {
                    rows[i].style.display = '';
                } else {
                    rows[i].style.display = 'none';
                }
            }
        }
    </script>
</body>
</html>
