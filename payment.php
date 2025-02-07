<?php
// Include the database configuration file
include('config.php');

// Get booking details from the URL
$total_price = isset($_GET['total_price']) ? $_GET['total_price'] : 1;
$name = isset($_GET['name']) ? $_GET['name'] : '';

// Fetch the user ID (id1) from the URL or query
$id1 = isset($_GET['id1']) ? $_GET['id1'] : 0;

// Fetch user details from the database
$user_query = "SELECT first_name, last_name FROM users1 WHERE id1 = ?";
$user_stmt = $conn->prepare($user_query);
$user_stmt->bind_param("i", $id1);
$user_stmt->execute();
$user_stmt->bind_result($first_name, $last_name);
$user_stmt->fetch();
$user_stmt->close();

// Combine the first name and last name
$user_full_name = $first_name . ' ' . $last_name;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Travel Planner - Payment</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #000;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .logo {
            font-family: 'italianno', sans-serif;
            font-size: 36px;
            color: #ff1e00;
            font-weight: bold;
        }

        .logo span {
            font-family: 'italianno', sans-serif;
            font-size: 36px;
            color: #fff;
            font-weight: bold;
        }

        .payment-container {
            background-color: #000;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 400px;
        }

        .payment-container h1 {
            color: #fff;
            margin-bottom: 20px;
        }

        .payment-container p {
            color: #fff;
            margin: 10px 0;
        }

        .pay-now-btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: yellowgreen;
            color: #000;
            border: none;
            border-radius: 25px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-top: 20px;
            text-decoration: none;
        }

        .pay-now-btn:hover {
            background-color: #084239;
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
</head>
<body>
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
    <div class="payment-container">
        <h1>Complete Your Payment</h1>
        <p><strong>Name:</strong> <?php echo htmlspecialchars($user_full_name); ?></p>
        <p><strong>Total Amount:</strong> ₹<?php echo number_format($total_price, 2); ?></p>
        <button class="pay-now-btn" id="payNow">Pay Now</button>
    </div>
    <footer>
        <p>&copy; 2024 All Rights Reserved</p>
    </footer>
    <script>
        document.getElementById('payNow').addEventListener('click', function (e) {
            var options = {
                "key": "rzp_test_v2uhLO9KDz3sx0", // Replace with your Razorpay API key
                "amount": <?php echo $total_price * 100; ?>, // Amount in paise
                "currency": "INR",
                "name": "Travel Planner",
                "description": "Lifetime subscription payment",
                "handler": function (response) {
                    // Send the payment details to the server
                    $.ajax({
                        url: 'paymentbk.php',
                        type: 'POST',
                        data: {
                            razorpay_payment_id: response.razorpay_payment_id,
                            id1: <?php echo $id1; ?>,
                            total_price: <?php echo $total_price; ?>
                        },
                        success: function (data) {
                            alert("Payment successful!");
                            window.location.href = "payment-success.php?id1=<?php echo $id1; ?>";
                        },
                        error: function () {
                            alert("Payment failed. Please try again.");
                        }
                    });
                },
                "theme": {
                    "color": "#ff1e00"
                }
            };
            var rzp1 = new Razorpay(options);
            rzp1.open();
            e.preventDefault();
        });
        function goBack() {
                    window.history.back();
                }
    </script>
</body>
</html>
