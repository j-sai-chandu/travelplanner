<?php
// Include the database configuration file
include('config.php');

// Get the user ID from the URL
$id1 = isset($_GET['id1']) ? $_GET['id1'] : 0;

// Fetch the user's details and payment status
$user_query = "SELECT first_name, last_name, have_subscription, subscription_transaction_id, subscription_amount FROM users1 WHERE id1 = ?";
$user_stmt = $conn->prepare($user_query);
$user_stmt->bind_param("i", $id1);
$user_stmt->execute();
$user_stmt->bind_result($first_name, $last_name, $have_subscription, $transaction_id, $subscription_amount);
$user_stmt->fetch();
$user_stmt->close();

// Check if the user has a subscription
if ($have_subscription == 'Yes') {
    $user_full_name = $first_name . ' ' . $last_name;
} else {
    $user_full_name = "Unknown User";
    $transaction_id = "Not Available";
    $subscription_amount = 0;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="icon" href="images/dollar.png" type="image/png" sizes="16x16">
    <title>Payment Successful - Travel Planner</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500&display=swap" rel="stylesheet">
</head>

<style>
    body {
        background: #dcf0fa;
        font-family: 'Poppins', sans-serif;
        margin: 0;
        padding: 0;
    }

    .container {
        width: 600px;
        margin: 80px auto;
        overflow: hidden;
    }

    .printer-top {
        z-index: 1;
        border: 6px solid #666666;
        height: 5px; /* Increased height for better alignment */
        border-bottom: 0;
        border-radius: 6px 6px 0 0;
        background: #333333;

    }

    .printer-bottom {
        z-index: 0;
        border: 6px solid #666666;
        height: 10px; /* Increased height for better alignment */
        border-top: 0;
        border-radius: 0 0 6px 6px;
        background: #333333;
    }

    .paper-container {
        position: relative;
        overflow: hidden;
        height: 467px;
        margin: 0;
    }

    .paper {
        background: #ffffff;
        height: 600px; /* Increase the height for a taller print */
        width: 100%;
        position: absolute;
        z-index: 2;
        margin: 0 12px;
        margin-top: -15px;
        animation: print 5000ms cubic-bezier(0.68, -0.55, 0.265, 0.9) forwards;
        margin-right:0px;
    }

    .main-contents {
        margin: 0 12px;
        padding: 24px;
    }

    .success-icon {
        text-align: center;
        font-size: 48px;
        height: 72px;
        background: #359d00;
        border-radius: 50%;
        width: 72px;
        height: 72px;
        margin: 16px auto;
        color: #fff;
    }

    .success-title {
        font-size: 22px;
        text-align: center;
        color: #666;
        font-weight: bold;
        margin-bottom: 16px;
    }

    .success-description {
        font-size: 15px;
        line-height: 21px;
        color: #999;
        text-align: center;
        margin-bottom: 24px;
    }

    .order-details {
        text-align: center;
        color: #333;
        font-weight: bold;
    }

    .order-number-label {
        font-size: 18px;
        margin-bottom: 8px;
    }

    .order-number {
        border-top: 1px solid #ccc;
        border-bottom: 1px solid #ccc;
        line-height: 48px;
        font-size: 28px; /* Increased font size for visibility */
        padding: 8px 0;
        margin-bottom: 24px;
        color: #333;
    }

    .complement {
        font-size: 18px;
        margin-bottom: 8px;
        color: #32a852;
    }

    /* Styling for the buttons */
    .button-container {
        text-align: center;
        margin-top: 30px;
    }

    .button-container .sai {
        display: inline-block;
        margin: 10px;
        margin-top: -20px;
        padding: 12px 25px;
        background-color: #359d00;
        color: white;
        text-align: center;
        text-decoration: none;
        font-size: 18px;
        border-radius: 5px;
        cursor: pointer;
        width: 200px; /* Set width for buttons */
    }

    .button-container .sai:hover {
        background-color: #2d7e00;
    }

    .button-container .sai:focus {
        outline: none;
    }

    .jagged-edge {
        position: relative;
        height: 20px;
        width: 100%;
        margin-top: -1px;
    }

    .jagged-edge:after {
        content: "";
        display: block;
        position: absolute;
        left: 0;
        right: 0;
        height: 20px;
        background: linear-gradient(45deg,
                transparent 33.333%,
                #ffffff 33.333%,
                #ffffff 66.667%,
                transparent 66.667%),
            linear-gradient(-45deg,
                transparent 33.333%,
                #ffffff 33.333%,
                #ffffff 66.667%,
                transparent 66.667%);

        background-size: 16px 40px;
        background-position: 0 -20px;
    }

    @keyframes print {
        0% {
            transform: translateY(-90%);
        }

        100% {
            transform: translateY(0);
        }
    }

    header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 20px;
        background-color: #1e1e1e;
        color: white;
        width: 100%;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        position: fixed;
        top: 0;
        left: 0;
        z-index: 1000;
    }

    .back-button {
        position: relative;
        cursor: pointer;
    }

    .back-button img {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    /* Tooltip for Back Button */
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
        margin-left: auto; /* Push the title container to the right */
        position: relative;
        cursor: pointer;
    }

    .main-icon {
        width: 30px;
        height: 30px;
        margin-right: 5px;
    }

    .wiseglobe-text {
        font-size: 18px;
        font-weight: bold;
        display: flex;
        align-items: center;
        margin-right: 40px;
    }

    .wise {
        color: orangered;
        font-size: 20px;
        font-weight: bold;
    }

    .globe {
        color: white;
        font-size: 20px;
        font-weight: bold;
    }

    footer {
        background-color: #1e1e1e;
        color: #fff;
        text-align: center;
        padding: 10px 0;
        width: 100%;
        position: fixed;
        bottom: 0;
        left: 0;
        box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.2);
    }

    footer p {
        font-size: 14px;
        margin: 0;
    }
</style>

<header>
    <!-- Back Button -->
    <div class="back-button" onclick="goBack()">
        <img src="photo/back.jpeg" alt="Back">
    </div>
    <!-- Title with Main Icon -->
    <div class="title-container">
        <img src="photo/main-icon.png" alt="Main Icon" class="main-icon">
        <div class="wiseglobe-text">
            <span class="wise">Travel</span><span class="globe">Planner</span>
        </div>
    </div>
</header>

<body>
    <div class="container">
        <div class="printer-top"></div>

        <div class="paper-container">
            <div class="printer-bottom"></div>

            <div class="paper" id="receipt">
                <div class="main-contents">
                    <div class="success-icon">&#10004;</div>
                    <div class="success-title">
                        Payment Complete
                    </div>
                    <div class="success-description">
                        Thank you for completing the payment!
                    </div>
                    <div class="order-details">
                        <div class="order-number-label">Transaction ID</div>
                        <div class="order-number"><?php echo htmlspecialchars($transaction_id); ?></div>
                        <div class="complement">Thank You!</div>
                    </div>
                </div>
                <div class="jagged-edge"></div>
            </div>
        </div>
    </div>

    <!-- Buttons for Printing and Navigation -->
    <div class="button-container">
        <!-- Print Button -->
        <button class="sai" onclick="window.print()">Print Receipt</button>

        <!-- Go to Home Button -->
        <button class="sai" onclick="window.location.href='mainS.php?id1=<?php echo $id1; ?>'">Go to Home</button>
    </div>
    <footer>
        <p>&copy; 2024 All Rights Reserved</p>
    </footer>
    <script>
        function goBack() {
            window.history.back();
        }
    </script>
</body>

</html>
