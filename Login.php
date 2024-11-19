<?php
session_start();

// Generate a random customer_id if it doesn't already exist in the session
if (!isset($_SESSION['customer_id'])) {
    $_SESSION['customer_id'] = rand(10,90); // Random INT for customer ID
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
</head>
<body>
    <h1>Welcome to Our Store</h1>
    <p>Click the button below to start shopping!</p>

    <!-- Button to redirect to CustomerSystem.php -->
    <form action="CustomerSystem.php" method="post">
        <button type="submit">Start Shopping</button>
    </form>
</body>
</html>
