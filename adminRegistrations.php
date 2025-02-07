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

// Fetch the total number of users with user_type == 1
$sql_count = "SELECT COUNT(*) AS total_users FROM users1 WHERE user_type = 1";
$result_count = $conn->query($sql_count);
$total_users = 0;

if ($result_count && $result_count->num_rows > 0) {
    $row = $result_count->fetch_assoc();
    $total_users = $row['total_users'];
}

// Fetch the total number of subscribed users with user_type == 1
$sql_subscribed_count = "SELECT COUNT(*) AS total_subscribed_users FROM users1 WHERE have_subscription = 'Yes' AND user_type = 1";
$result_subscribed_count = $conn->query($sql_subscribed_count);
$total_subscribed_users = 0;

if ($result_subscribed_count && $result_subscribed_count->num_rows > 0) {
    $row = $result_subscribed_count->fetch_assoc();
    $total_subscribed_users = $row['total_subscribed_users'];
}

// Fetch user details for user_type == 1
$sql = "SELECT id1, first_name, last_name, phone, email, have_subscription, subscription_transaction_id, country_code FROM users1 WHERE user_type = 1";
$result = $conn->query($sql);

// Check if there are any users and store in array
$users = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Filtered User Details</title>
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
        <h3 style="margin-left:20px">Registered Users</h3>

        <!-- Search box for filtering users -->
        <div style="margin-left:20px; margin-bottom: 10px;">
            <input type="text" id="search-input" placeholder="Search..." onkeyup="filterUsers()"
                   style="padding: 10px; font-size: 1rem; border: 2px solid black; border-radius: 5px; outline: none;">
        </div>

        <?php if (!empty($users)): ?>
            <table id="userTable">
                <tr>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Country Code</th>
                    <th>Phone</th>
                    <th>Have_Subscription</th>
                    <th>Subscription Transaction ID</th>
                </tr>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user['first_name']); ?></td>
                        <td><?php echo htmlspecialchars($user['last_name']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td><?php echo htmlspecialchars($user['country_code'] ?: '-'); ?></td>
                        <td><?php echo htmlspecialchars($user['phone']); ?></td>
                        <td><?php echo htmlspecialchars($user['have_subscription']); ?></td>
                        <td><?php echo htmlspecialchars($user['subscription_transaction_id'] ?: '-'); ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>No users found.</p>
        <?php endif; ?>

        <!-- Button to Show Total Users -->
        <div class="button-container">
            <br>
            <button onclick="showTotalUsers()" style="margin-left:20px;font-weight:bold;background-color:#cccccc;color:black;height:40px;">Show Total Number of Users</button>
            <button onclick="showSubscribedUsers()" style="margin-left:10px;font-weight:bold;background-color:#cccccc;color:black;height:40px;">Show Total Subscribed Users</button>
        </div>

        <!-- Display the total number of users here -->
        <p id="totalUsersDisplay" style="display:none;margin-left:20px;">Total Number of Users: <?php echo $total_users; ?></p>
        <p id="totalSubscribedUsersDisplay" style="display:none;margin-left:20px;">Total Number of Subscribed Users: <?php echo $total_subscribed_users; ?></p>
    </div>

    <script>
        function showTotalUsers() {
            document.getElementById('totalUsersDisplay').style.display = 'block';
        }

        function showSubscribedUsers() {
            document.getElementById('totalSubscribedUsersDisplay').style.display = 'block';
        }

        function filterUsers() {
            const input = document.getElementById('search-input').value.toLowerCase();
            const rows = document.getElementById('userTable').getElementsByTagName('tr');

            // Loop through all table rows except the header
            for (let i = 1; i < rows.length; i++) {
                const cells = rows[i].getElementsByTagName('td');
                let match = false;

                // Check each cell in the row
                for (let j = 0; j < cells.length; j++) {
                    if (cells[j].textContent.toLowerCase().includes(input)) {
                        match = true;
                        break;
                    }
                }

                // Show or hide the row based on the match
                rows[i].style.display = match ? '' : 'none';
            }
        }
    </script>
</body>
</html>
