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

// Check if the email is from the admin domain (anyuser@travelplanner.com)
if (strpos($email, '@travelplanner.com') !== false) {
    // Query to verify the admin credentials
    $sqlAdmin = "SELECT * FROM admin1 WHERE email='$email' AND password='$password'";
    $resultAdmin = $conn->query($sqlAdmin);

    // Check if the query returned a matching admin
    if ($resultAdmin->num_rows > 0) {
        // Admin is authenticated, redirect to admin dashboard
        header("Location: adminDashboard.html");
        exit;
    } else {
        // Invalid admin credentials
        echo "<script type='text/javascript'>
            alert('Invalid admin credentials! Please enter the correct admin details.');
            window.location.href = 'login.html';
        </script>";
        exit;
    }
} else {
    // Query to verify the user credentials in the users1 table
    $sqlUser = "SELECT * FROM users1 WHERE email='$email' AND password='$password'";
    $resultUser = $conn->query($sqlUser);

    // Check if the query returned a matching user
    if ($resultUser->num_rows > 0) {
        // Fetch the user details
        $user = $resultUser->fetch_assoc();
        $userId1 = $user['id1']; // Get the user's ID
        $haveSubscription = $user['have_subscription']; // Get the subscription status ('Yes' or 'No')

        // Redirect based on the subscription status
        if ($haveSubscription === 'Yes') {
            header("Location: review/review_form.html?id1=$userId1"); // Redirect to mainS.php with the user ID
        } else {
            header("Location: review/review_form.html?id1=$userId1"); // Redirect to main.php with the user ID
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
}

// Close the database connection
$conn->close();
?>
