<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$email = $_POST['email'];
$password = $_POST['password'];

$sql = "SELECT * FROM admin1 WHERE email='$email' AND password='$password'";
$result = $conn->query($sql);

if ($result->num_rows == 1) {
    $_SESSION['admin_logged_in'] = true;
    header("Location: adminDashboard.html");
    exit();
} else {
    echo "<script>alert('Invalid credentials');window.location.href='adminLogin.html';</script>";
}

$conn->close();
?>
