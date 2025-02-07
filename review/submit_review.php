<?php
// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data
$user_name = $_POST['user_name'];
$stars = $_POST['stars'];
$experience = $_POST['experience'];

// Fetch id1 from the URL (if present)
$id1 = isset($_GET['id1']) ? $_GET['id1'] : null;

if ($id1) {
    // Check user's subscription status if id1 is provided
    $sql = "SELECT have_subscription FROM users1 WHERE id1 = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id1);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Store the review
        $sql = "INSERT INTO reviews1 (user_name, stars, experience) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sis", $user_name, $stars, $experience);

        if ($stmt->execute()) {
            // Redirect based on subscription status
            if ($user['have_subscription'] === "Yes") {
                echo "<script>
                        alert('Thanks for your review!');
                        window.location.href = '../mainS.php?id1=" . $id1 . "';
                      </script>";
            } else {
                echo "<script>
                        alert('Thanks for your review!');
                        window.location.href = '../main.php?id1=" . $id1 . "';
                      </script>";
            }
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "Error: User not found.";
    }
} else {
    // If id1 is null, store the review and redirect to mainS1.php
    $sql = "INSERT INTO reviews1 (user_name, stars, experience) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sis", $user_name, $stars, $experience);

    if ($stmt->execute()) {
        echo "<script>
                alert('Thanks for your review!');
                window.location.href = '../mainS1.php';
              </script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Close the connection
$stmt->close();
$conn->close();
?>
