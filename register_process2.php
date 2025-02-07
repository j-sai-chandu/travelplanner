<?php
$conn = new mysqli('localhost', 'root', '', 'project');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$country_code = $_POST['country_code'];
$phone = $_POST['phone'];
$email = $_POST['email'];
$password = $_POST['password']; // No hashing applied

$sql = "INSERT INTO users1 (first_name, last_name, country_code, phone, email, password) VALUES ('$first_name', '$last_name', '$country_code', '$phone', '$email', '$password')";

if ($conn->query($sql) === TRUE) {
    echo "<script>alert('Account Created Successfully!'); window.location.href = 'login2.html';</script>";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
