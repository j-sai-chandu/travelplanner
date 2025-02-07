<?php
// Connect to the database
$conn = new mysqli('localhost', 'root', '', 'project');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch 'id1' from the URL (get the user ID)
$id1 = isset($_GET['id1']) ? $_GET['id1'] : null;

if ($id1) {
    // Fetch the user based on 'id1'
    $sql = "SELECT * FROM users1 WHERE id1 = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id1); // Bind the 'id1' as an integer
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the user is found
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
    } else {
        echo "User not found.";
        exit;
    }

    $stmt->close();
} else {
    echo "No user ID provided.";
    exit;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Page</title>
    <style>
         body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #d9e4f5, #f1e3e6);
        }

        header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 20px;
            background-color: #1e1e1e;
            color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
        }

        .back-button {
            cursor: pointer;
            position: relative;
        }

        .back-button img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
        }

        .back-button:hover::after {
            content: "Go Back";
            position: absolute;
            top: 50px;
            left: 50%;
            transform: translateX(-50%);
            background-color: #000;
            color: #fff;
            padding: 5px 10px;
            font-size: 12px;
            border-radius: 5px;
            white-space: nowrap;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            z-index: 1000;
        }

        .title-container {
            display: flex;
            align-items: center;
        }

        .main-icon {
            width: 30px;
            height: 30px;
            margin-right: 10px;
        }

        .wiseglobe-text {
            font-size: 18px;
            font-weight: bold;
            display: flex;
        }

        .wise {
            color: orangered;
        }

        .globe {
            color: white;
        }

        .logout-icon {
            position: relative;
        }

        .logout-icon img {
            width: 30px;
            height: 30px;
            cursor: pointer;
            margin-right: 50px;
        }

        .logout-icon:hover::after {
            content: "Logout";
            position: absolute;
            top: 50px;
            left: 20%;
            transform: translateX(-50%);
            background-color: #000;
            color: #fff;
            padding: 5px 10px;
            font-size: 12px;
            border-radius: 5px;
            white-space: nowrap;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            z-index: 1000;
        }

        main {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
            margin-top: 80px; /* Offset for the fixed header */
            padding-bottom: 80px; /* Space for the footer */
        }

        .form-box {
            background-color: #ffffff;
            border-radius: 15px;
            padding: 30px;
            width: 400px;
            box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        h2 {
            font-size: 28px;
            margin-bottom: 20px;
            color: #333;
        }

        .profile-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
        }

        .profile-card {
            flex: 1;
            margin: 0 10px;
            padding: 15px;
            background: #f9f9f9;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .profile-card label {
            font-weight: bold;
            font-size: 14px;
            color: #555;
            display: block;
            margin-bottom: 5px;
        }

        .profile-card p {
            margin: 0;
            padding: 10px;
            background: #ffffff;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            color: #333;
            text-align: center;
        }

        button {
            padding: 10px 20px;
            background-color: #ff4d4d;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            font-weight: bold;
            margin-top: 10px;
        }

        button:hover {
            background-color: #e04444;
        }

        footer {
            background-color: #000;
            color: #fff;
            text-align: center;
            padding: 10px;
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.2);
        }

        footer p {
            margin: 0;
            font-size: 14px;
        }

        @media (max-width: 768px) {
            .form-box {
                width: 90%;
                padding: 20px;
            }
        }
    </style>
</head>
<body>
<header>
    <div class="back-button" onclick="goBack()">
        <img src="photo/back.jpeg" alt="Back">
    </div>
    <div class="title-container">
        <img src="photo/main-icon.png" alt="Main Icon" class="main-icon">
        <div class="wiseglobe-text">
            <span class="wise">Travel</span><span class="globe">Planner</span>
        </div>
    </div>
    <div class="logout-icon">
        <a href="index.html"><img src="photo/logout.png" alt="Logout"></a>
    </div>
</header>
<main>
    <div class="form-box">
        <h2>User Profile</h2>
        <div class="profile-row">
            <div class="profile-card">
                <label>First Name</label>
                <p><?php echo htmlspecialchars($user['first_name']); ?></p>
            </div>
            <div class="profile-card">
                <label>Last Name</label>
                <p><?php echo htmlspecialchars($user['last_name']); ?></p>
            </div>
        </div>
        <div class="profile-card">
            <label>Country Code</label>
            <p><?php echo htmlspecialchars($user['country_code']); ?></p>
        </div>
        <div class="profile-card">
            <label>Phone</label>
            <p><?php echo htmlspecialchars($user['phone']); ?></p>
        </div>
        <div class="profile-card">
            <label>Email</label>
            <p><?php echo htmlspecialchars($user['email']); ?></p>
        </div>
        <div class="profile-card">
            <label>Password</label>
            <p><?php echo htmlspecialchars($user['password']); ?></p>
        </div>
        <div class="profile-card">
            <label>Subscription Status</label>
            <p><?php echo htmlspecialchars($user['have_subscription']); ?></p>
        </div>
        <button onclick="window.location.href='edit_profile.php?id1=<?php echo $user['id1']; ?>'">Edit Profile</button>
    </div>
</main>
<footer>
    <p>&copy; 2024 All Rights Reserved</p>
</footer>
<script>
    // Updated goBack function to pass the 'id1' parameter in the URL
    function goBack() {
        // Get the 'id1' value from the current page URL
        const urlParams = new URLSearchParams(window.location.search);
        const id1 = urlParams.get('id1');
        
        // Redirect to main.php with the id1 parameter
        if (id1) {
            window.location.href = `main.php?id1=${id1}`;
        } else {
            // In case id1 is not present, redirect to main.php without id1
            window.location.href = "main.php";
        }
    }
</script>
</body>
</html>
