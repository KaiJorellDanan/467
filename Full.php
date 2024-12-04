<!DOCTYPE html>
<html lang="en">
<?php
// Database connection credentials
$username = "z2003741";
$password = "2003Jan28";

try {
    // Establish database connection
    $dsn1 = "mysql:host=courses;dbname=z2003741"; // z2003741 database
    $pdo2 = new PDO($dsn1, $username, $password);
    $pdo2->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Query Order data from Orders table
    $stmt1 = $pdo2->prepare("SELECT order_id, price, order_weight FROM `Orders`");
    $stmt1->execute();
    $orders = $stmt1->fetchAll(PDO::FETCH_ASSOC);

    // Check if orders are available
    if (empty($orders)) {
        echo "<p>No orders found.</p>";
        exit;
    }
} catch (PDOException $e) {
    echo "<p>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
    exit;
}

// Fulfill Order
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'])) {
    $order_id = (int) $_POST['order_id'];

    try {
        // Debugging: Output the order_id to confirm the value
        echo "<p>Order ID received: $order_id</p>";

        // SQL query to update the order status to 'FULFILLED'
        $stmt = $pdo2->prepare("UPDATE Orders SET order_status = 'FULFILLED' WHERE order_id = :order_id");
        $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);

        // Debugging: Check if the statement executes
        if ($stmt->execute()) {
            echo "<p>Order status updated to FULFILLED successfully.</p>";
        } else {
            echo "<p>Failed to update the order status. This might be due to SQL execution issues.</p>";
        }

        // Redirect back to the fulfillment page to reflect changes
        header("Location: Full.php");
        exit;
    } catch (PDOException $e) {
        // Handle errors during the update process
        echo "<p>Error updating the order status: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
}
?>
<body>
    <h1>Fulfillment Page</h1>
    <?php $count = 0;
    foreach ($orders as $order): ?>
        <div class="order">
            <h3>Order ID: <?php echo htmlspecialchars($order['order_id']); ?></h3>
            <p><strong>Total Price:</strong> $<?php echo number_format($order['price'], 2); ?></p>
            <p><strong>Total Weight:</strong> <?php echo number_format($order['order_weight'], 2); ?> lbs</p>
            <!-- Form to Fulfill Order -->
            <form method="post" action="Full.php">
                <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($order['order_id']); ?>">
                <button type="submit" id="sub">Complete Order</button>
            </form>
            <?php $count++; ?>
        </div>
    <?php endforeach; ?>
    <p><strong>Found</strong> <?php echo $count; ?> <strong>Orders</strong></p>
</body>
</html>
