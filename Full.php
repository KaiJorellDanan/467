<!DOCTYPE html>
<html lang="en">
<h1>Fulfillment Page</h1>
<link rel="stylesheet" href="FULLCSS.css">

<button onclick="window.location.href='https://students.cs.niu.edu/~z1952360/Full.php';">Orders</button>
<button onclick="window.location.href='https://students.cs.niu.edu/~z1952360/Receiving.php';">Receiving</button>

<?php
// Database connection credentials
$username = "z1952360";
$password = "2004May03";

$username1 = "student";
$password1 = "student";

try {
    // Establish database connection
    $dsn1 = "mysql:host=courses;dbname=z1952360"; // z1952360 database
    $pdoLocal = new PDO($dsn1, $username, $password);
    $pdoLocal->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $dsn2 = "mysql:host=blitz.cs.niu.edu;dbname=csci467"; //Blitz Database
    $pdoBlitz = new PDO($dsn2, $username1, $password1);
    $pdoBlitz->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


    // Query Order data from Orders table
    $stmt1 = $pdoLocal->prepare("SELECT order_id, price, order_weight FROM `Orders` WHERE order_status = 'UNFULFILLED'");
    $stmt1->execute();
    $orders = $stmt1->fetchAll(PDO::FETCH_ASSOC);

        // Capture unfulfilled order IDs
    $unfulfilled_order_ids = [];
    foreach ($orders as $order) {
            $unfulfilled_order_ids[] = $order['order_id'];
    }

    // Echo the unfulfilled order IDs
    echo "<h3>Unfulfilled Order IDs:</h3>";

// Check if orders are available
if (empty($orders)) {
    echo "<p>No unfulfilled orders found.</p>";
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
        // SQL query to update the order status to 'FULFILLED'
        $stmt = $pdoLocal->prepare("UPDATE Orders SET order_status = 'FULFILLED' WHERE order_id = :order_id");
        $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo "<p>Order status updated to FULFILLED successfully.</p>";
        } else {
            echo "<p>Failed to update the order status.</p>";
        }

        // Redirect back to the fulfillment page to reflect changes
        header("Location: https://students.cs.niu.edu/~z1952360/Full.php?order_id=$order_id");
        exit;  // Ensure no further code is executed after the redirect
    } catch (PDOException $e) {
        // Handle errors during the update process
        echo "<p>Error updating the order status: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
}


// If an order ID is passed in the URL, fetch and display its details
if (isset($_GET['order_id'])) {
    $order_id = (int) $_GET['order_id'];

    try {
        // DISPLAYING DETAILS -------------------
        // Step 1: Fetch the order details and associated customer_id
        $stmtOrderDetails = $pdoLocal->prepare("SELECT * FROM Orders WHERE order_id = :order_id");
        $stmtOrderDetails->bindParam(':order_id', $order_id, PDO::PARAM_INT);
        $stmtOrderDetails->execute();
        $orderDetails = $stmtOrderDetails->fetch(PDO::FETCH_ASSOC);

        if ($orderDetails) {


            // Step 2: Fetch the customer_id from the order
            $customer_id = $orderDetails['customer_id'];

            // Step 3: Fetch cart data from the local database
            $cartQuery = $pdoLocal->prepare("SELECT item_id, customerq AS quantity, qweight AS weight FROM Cart WHERE customer_id = :customer_id");
            $cartQuery->bindParam(':customer_id', $customer_id, PDO::PARAM_INT);
            $cartQuery->execute();
            $cartItems = $cartQuery->fetchAll(PDO::FETCH_ASSOC);

            if ($cartItems) {
                // Step 4: Fetch item details from the Blitz database
                $itemIds = array_column($cartItems, 'item_id');
                $placeholders = implode(',', array_fill(0, count($itemIds), '?'));
                $partsQuery = $pdoBlitz->prepare("SELECT number, description, price, pictureURL FROM parts WHERE number IN ($placeholders)");
                $partsQuery->execute($itemIds);
                $partsData = $partsQuery->fetchAll(PDO::FETCH_ASSOC);

                // Step 5: Merge cart data with parts data
                $mergedData = [];
                foreach ($cartItems as $cartItem) {
                    foreach ($partsData as $part) {
                        if ($cartItem['item_id'] == $part['number']) {
                            $mergedData[] = array_merge($cartItem, $part);
                            break;
                        }
                    }
                }


                
                // Step 6: Display the Packaging List and update the inventory table
                echo "<h3>Packaging List for Customer ID: " . htmlspecialchars($customer_id) . "</h3>";
                echo "<ul>";

                foreach ($mergedData as $item) {
                    echo "<li>";
                    echo "<strong>" . htmlspecialchars($item['description']) . "</strong><br>";
                    echo "Quantity: " . htmlspecialchars($item['quantity']) . "<br>";
                    echo "Price per Item: $" . number_format($item['price'], 2) . "<br>";
                    echo "Weight: " . htmlspecialchars($item['weight']) . " lbs<br>";

                    if (!empty($item['pictureURL'])) {
                        echo "<img src='" . htmlspecialchars($item['pictureURL']) . "' alt='Item Image' style='max-width:100px;'><br>";
                    }
                    echo "</li>";

                    // Update inventory table to subtract the quantity used in this order
                    try {
                        $updateQuery = "UPDATE Inventory SET quantity = quantity - :order_quantity WHERE item_id = :item_id";
                        $updateStmt = $pdoLocal->prepare($updateQuery);
                        $updateStmt->execute([
                            'order_quantity' => $item['quantity'],
                            'item_id' => $item['item_id']
                        ]);

                        echo "Updated inventory for Parts ID: " . htmlspecialchars($item['item_id']) . "<br>";
                    } catch (PDOException $e) {
                        echo "Error updating inventory for Parts ID: " . htmlspecialchars($item['item_id']) . " - " . $e->getMessage() . "<br>";
                    }
                }

                echo "</ul>";

                // Step 7: Fetch customer's name, address, and email for the shipping label
                try {
                    // Query to get the customer's information
                    $stmtCustomer = $pdoLocal->prepare("SELECT first_name, last_name, address, email FROM Customer WHERE customer_id = :customer_id");
                    $stmtCustomer->bindParam(':customer_id', $customer_id, PDO::PARAM_INT);
                    $stmtCustomer->execute();
                    $customerInfo = $stmtCustomer->fetch(PDO::FETCH_ASSOC);

                    if ($customerInfo) {
                        echo "<h3>Shipping Label:</h3>";
                        echo "<p><strong>Name:</strong> " . htmlspecialchars($customerInfo['first_name']) . " " . htmlspecialchars($customerInfo['last_name']) . "</p>";
                        echo "<p><strong>Address:</strong> " . htmlspecialchars($customerInfo['address']) . "</p>";
                        echo "<p><strong>Order Confirmation Sent To:</strong> " . htmlspecialchars($customerInfo['email']) . "</p>";
                        echo "<p><strong>Order will be marked as Fulfilled shortly.</strong></p>";
                    } else {
                        echo "<p>No customer information found.</p>";
                    }
                } catch (PDOException $e) {
                    echo "<p>Error fetching customer information: " . htmlspecialchars($e->getMessage()) . "</p>";
                }
            } else {
                echo "<p>No items found in the cart for this customer.</p>";
            }
        } else {
            echo "<p>Order not found.</p>";
        }
    } catch (PDOException $e) {
        echo "<p>Error fetching order details: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
}



// Function to calculate shipping cost based on order weight
function getShippingCost($order_weight, $pdo) {
    $stmt = $pdo->prepare("SELECT weight, shipping_cost FROM Shipping_cost ORDER BY weight DESC");
    $stmt->execute();
    $shippingCosts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $shipping_cost = 0.0;
    foreach ($shippingCosts as $shipping) {
        if ($order_weight >= $shipping['weight']) {
            $shipping_cost = $shipping['shipping_cost'];
            break;
        }
    }
    return $shipping_cost;
}

?>

<body>


    <?php
    // If a specific order_id is provided in the URL (via GET)
    if (isset($_GET['order_id'])) {
        $order_id = (int)$_GET['order_id'];

        $stmt1 = $pdoLocal->prepare("SELECT order_id, price, order_weight FROM `Orders` WHERE order_id = :order_id");
        $stmt1->bindParam(':order_id', $order_id, PDO::PARAM_INT);
        $stmt1->execute();
        $orders = $stmt1->fetchAll(PDO::FETCH_ASSOC);

    // If an order has been marked complete (POST request)
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'])) {
        $order_id = (int)$_POST['order_id'];

        $stmt1 = $pdoLocal->prepare("SELECT order_id, price, order_weight FROM `Orders` WHERE order_id = :order_id");
        $stmt1->bindParam(':order_id', $order_id, PDO::PARAM_INT);
        $stmt1->execute();
        $orders = $stmt1->fetchAll(PDO::FETCH_ASSOC);

    // If no order_id is provided, show all orders
    } else {
        $stmt1 = $pdoLocal->prepare("SELECT order_id, price, order_weight FROM `Orders`");
        $stmt1->execute();
        $orders = $stmt1->fetchAll(PDO::FETCH_ASSOC);
    }
    ?>

<?php
$count = 0; 
foreach ($orders as $order):
    // Check if the order is unfulfilled and its order_id is in the unfulfilled list
    if (in_array($order['order_id'], $unfulfilled_order_ids)):
        // Calculate shipping cost once per order
        $shipping_cost = getShippingCost($order['order_weight'], $pdoLocal);
?>
    <div class="order">
        <h3>Order ID: <?php echo htmlspecialchars($order['order_id']); ?></h3>
        <p><strong>Total Price:</strong> $<?php echo number_format($order['price'], 2); ?></p>
        <p><strong>Total Weight:</strong> <?php echo number_format($order['order_weight'], 2); ?> lbs</p>
        <p><strong>Total Order Amount:</strong> $<?php echo number_format($order['price'] + $shipping_cost, 2); ?></p>

        <!-- Display the shipping cost once -->
        <p><strong>Shipping Cost:</strong> $<?php echo number_format($shipping_cost, 2); ?></p>

        <?php if (!isset($_GET['order_id'])): ?>
            <!-- Form to Fulfill Order -->
            <form method="post" action="Full.php">
                <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($order['order_id']); ?>">
                <button type="submit" id="sub">Complete Order</button>
            </form>
        <?php endif; ?>

    </div>
    <?php $count++; ?>
<?php endif; ?>
<?php endforeach; ?>

<?php if (isset($_GET['order_id'])): ?>
    <!-- Show Go Back button only when viewing a specific order's details -->
    <form action="Full.php" method="GET">
        <button type="submit">Go Back to Fulfillment Page</button>
    </form>
<?php else: ?>
    <p><strong>Found</strong> <?php echo $count; ?> <strong>Orders</strong></p>
<?php endif; ?>

</body>
</html>
