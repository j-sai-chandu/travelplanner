<?php
// Database connection
$host = 'localhost'; // Update with your host
$dbname = 'project'; // Update with your database name
$username = 'root'; // Update with your database username
$password = ''; // Update with your database password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo 'false';
        exit;
    }

    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users1 WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $count = $stmt->fetchColumn();

    echo $count > 0 ? 'true' : 'false';
}
?>
