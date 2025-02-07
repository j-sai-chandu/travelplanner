<?php
include('config.php');

// Capture data from the POST request
$id1 = isset($_POST['id1']) ? $_POST['id1'] : 0;
$payment_id = $_POST['razorpay_payment_id'];
$total_price = $_POST['total_price'];

// Insert payment details into the payment table
$sql = "INSERT INTO payment1 (userid, transaction_id, total_price) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->bind_param("sss", $id1, $payment_id, $total_price);
    
    if ($stmt->execute()) {
        // Update the user's subscription status and store the transaction ID
        $update_sql = "UPDATE users1 SET have_subscription = 'Yes', subscription_transaction_id = ?, subscription_amount = ? WHERE id1 = ?";
        $update_stmt = $conn->prepare($update_sql);
        
        if ($update_stmt) {
            $update_stmt->bind_param("sdi", $payment_id, $total_price, $id1);
            $update_stmt->execute();
            $update_stmt->close();
        }
        
        // Respond with success
        $response = ['msg' => 'Payment successfully credited', 'status' => true];
        echo json_encode($response);
    } else {
        // Respond with error if query execution fails
        $response = ['msg' => 'Error: Unable to insert payment data', 'status' => false];
        echo json_encode($response);
    }
    
    // Close the statement
    $stmt->close();
} else {
    // Respond with error if statement preparation fails
    $response = ['msg' => 'Error: ' . $conn->error, 'status' => false];
    echo json_encode($response);
}

// Close the database connection
$conn->close();
?>
