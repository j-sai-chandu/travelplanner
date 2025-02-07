<?php
// Establish connection to the database
$conn = new mysqli('localhost', 'root', '', 'project');

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the email and password from the POST request
$email = $_POST['email'];
$password = $_POST['password'];

// Check if the email exists in the database
$sql = "SELECT * FROM users1 WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

// If the email exists, update the password
if ($result->num_rows > 0) {
    // Prepare the SQL query to update the password (plain text)
    $update_sql = "UPDATE users1 SET password = ? WHERE email = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("ss", $password, $email);

    // Execute the update query
    if ($update_stmt->execute()) {
        // If successful, display a success message and redirect to login page
        echo "<script>alert('Password changed successfully!'); window.location.href = 'login2.html';</script>";
    } else {
        echo "<script>alert('Error updating password. Please try again later.'); window.location.href = 'forgot_password2.html';</script>";
    }
    $update_stmt->close();
} else {
    // If no account is found with the entered email
    echo "<script>alert('No account found with this email.'); window.location.href = 'forgot_password2.html';</script>";
}

$stmt->close();

// Close the database connection
$conn->close();
?>
