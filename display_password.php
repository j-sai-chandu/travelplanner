<?php
$password = isset($_GET['password']) ? htmlspecialchars($_GET['password']) : 'No password provided';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Retrieved</title>
    <link rel="stylesheet" href="display_password.css">
</head>
<body>
    <div class="form-box">
        <h2>Your Password</h2>
        <p>Your password is: <strong><?php echo $password; ?></strong></p>
        <button onclick="window.location.href='login.html'">Go to Login</button>
    </div>
</body>
</html>