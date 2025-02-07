<?php
$conn = new mysqli('localhost', 'root', '', 'project');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$email = $_POST['email'];

$sql = "SELECT password FROM users WHERE email='$email'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $password = $row['password'];
    header("Location: display_password.php?password=$password");
} else {
    echo "No account found with this email.";
}

$conn->close();
?>
