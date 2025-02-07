<?php
$conn = new mysqli('localhost', 'root', '', 'project');

// Check if the database connection is successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get email and password from the form submission
$email = $_POST['email'];
$password = $_POST['password'];

// Default error message for invalid credentials
$m = "Invalid credentials! Please enter the correct details.";

// Query to verify the user credentials in the users1 table
$sqlUser = "SELECT * FROM users1 WHERE email='$email' AND password='$password'";
$resultUser = $conn->query($sqlUser);

// Check if the query returned a matching user
if ($resultUser->num_rows > 0) {
    // Fetch the user details
    $user = $resultUser->fetch_assoc();
    $userId1 = $user['id1']; // Get the user's ID
    $userType = $user['user_type']; // Get the user type (1 for regular user, 2 for admin)
    $haveSubscription = $user['have_subscription']; // Get the subscription status ('Yes' or 'No')

    if ($userType == 1) {
        // Redirect based on the subscription status for regular users
        if ($haveSubscription === 'Yes') {
            header("Location: mainS.php?id1=$userId1"); // Redirect to mainS.php with the user ID
        } else {
            header("Location: main.php?id1=$userId1"); // Redirect to main.php with the user ID
        }
    } elseif ($userType == 2) {
        // Redirect to admin dashboard for admin users
        header("Location: adminDashboard.html");
    } else {
        // Handle unexpected user_type values
        echo "<script type='text/javascript'>
            alert('Unexpected user type! Please contact support.');
            window.location.href = 'login.html';
        </script>";
    }
    exit; // Stop further script execution after the redirect
} else {
    // Invalid credentials case: Show an alert and redirect to login page
    echo "<script type='text/javascript'>
        alert('$m');
        window.location.href = 'login.html';
    </script>";
    exit;
}

// Close the database connection
$conn->close();
?>
