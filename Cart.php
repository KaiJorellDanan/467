<body>
<script src="CARTJS.js" defer></script>

<?php
include 'CartCSS.css';

session_start();
// Retrieve the customer ID from session
$customer_id = $_SESSION['customer_id'] ?? null;

// If customer ID isn't found
if (!$customer_id) {
    echo "<p>No customer ID found. Please log in or start shopping first.</p>";
    exit;
}

// Database connection credentials
$username = "z2003741";
$password = "2003Jan28";
$username1 = "student";
$password1 = "student";

try {
    // Establish database connections
    $dsn1 = "mysql:host=courses;dbname=z2003741"; // z2003741 database
    $pdo2 = new PDO($dsn1, $username, $password);
    $pdo2->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $dsn2 = "mysql:host=blitz.cs.niu.edu;dbname=csci467"; // Blitz database
    $pdo = new PDO($dsn2, $username1, $password1);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Query Cart data from z2003741
    $stmt1 = $pdo2->prepare("SELECT item_id, customerq, qweight FROM Cart WHERE customer_id = :customer_id");
    $stmt1->bindParam(':customer_id', $customer_id, PDO::PARAM_INT);
    $stmt1->execute();
    $cartItems = $stmt1->fetchAll(PDO::FETCH_ASSOC);

    // If cart is empty
    if (!$cartItems) {
        echo "<p>Your cart is empty.</p>";
        exit;
    }

    // Query Parts data from Blitz
    $itemIds = array_column($cartItems, 'item_id'); // Extract item IDs from Cart
    $placeholders = implode(',', array_fill(0, count($itemIds), '?')); // Create placeholders for IN clause
    $stmt2 = $pdo->prepare("SELECT number, description, price, pictureURL FROM parts WHERE number IN ($placeholders)");
    $stmt2->execute($itemIds);
    $partsData = $stmt2->fetchAll(PDO::FETCH_ASSOC);

    // Combine data from both queries
    $mergedData = [];
    foreach ($cartItems as $cartItem) {
        foreach ($partsData as $part) {
            if ($cartItem['item_id'] == $part['number']) {
                $mergedData[] = array_merge($cartItem, $part);
                break;
            }
        }
    }

    // Initialize totals
    $totalPrice = 0;
    $totalWeight = 0;

    // Generate the cart table
    echo "<table class='cart-table'>
            <thead>
                <tr>
                    <th>Item ID</th>
                    <th>Picture</th>
                    <th>Description</th>
                    <th>Quantity</th>
                    <th>Weight (lbs)</th>
                    <th>Price ($)</th>
                </tr>
            </thead>
            <tbody>";

    // Loop through each merged item
    foreach ($mergedData as $item) {
        $itemTotalPrice = $item['price'] * $item['customerq'];
        $totalPrice += $itemTotalPrice;
        $totalWeight += $item['qweight'];

        // Output table row for each item
        echo "<tr>
                <td>{$item['item_id']}</td>
                <td><img src='" . htmlspecialchars($item['pictureURL']) . "' alt='Item Image'></td>
                <td>" . htmlspecialchars($item['description']) . "</td>
                <td>" . htmlspecialchars($item['customerq']) . "</td>
                <td>" . htmlspecialchars($item['qweight']) . " lbs</td>
                <td>$" . number_format($itemTotalPrice, 2) . "</td>
                <td>
                    <!-- Form to remove item from cart -->
                    <form method='post' action='Cart.php'>
                        <input type='hidden' name='item_id' value='" . htmlspecialchars($item['item_id']) . "'>
                        <button type='submit'>Remove</button>
                    </form>
                </td>
            </tr>";
    }

    // Output totals row
    echo "</tbody>
        <tfoot>
            <tr>
                <th colspan='4'>Totals</th>
                <th>{$totalWeight} lbs</th>
                <th colspan='2'>$" . number_format($totalPrice, 2) . "</th>
            </tr>
        </tfoot>
        </table>";

} catch (PDOException $e) {
    // Handle database connection errors
    echo "<p>Connection to database failed: " . htmlspecialchars($e->getMessage()) . "</p>";
    exit;
}

// Check if the Remove button was pressed
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['item_id'])) {
    $item_id = (int) $_POST['item_id'];

    try {
        // SQL query to remove the item from the cart in z2003741
        $stmt = $pdo2->prepare("DELETE FROM Cart WHERE customer_id = :customer_id AND item_id = :item_id");
        $stmt->bindParam(':customer_id', $customer_id, PDO::PARAM_INT);
        $stmt->bindParam(':item_id', $item_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo "<p>Item removed successfully.</p>";
        } else {
            echo "<p>Failed to remove the item.</p>";
        }

        // Redirect back to the cart page to reflect changes
        header("Location: Cart.php");
        exit;  // Ensure no further code is executed after the redirect
    } catch (PDOException $e) {
        // Handle errors during the deletion process
        echo "<p>Error removing the item: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
}

// Check if the Billing form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['email']) && isset($_POST['address']) && isset($_POST['cc']) && isset($_POST['exp'])) {
    // Sanitize and prepare form data
    $firstName = htmlspecialchars($_POST['first_name']);  // Get the correct input names here
    $lastName = htmlspecialchars($_POST['last_name']);
    $email = htmlspecialchars($_POST['email']);
    $address = htmlspecialchars($_POST['address']);
    $cc = htmlspecialchars($_POST['cc']);  // You may want to encrypt this for security reasons
    $exp = htmlspecialchars($_POST['exp']);  // Expiration Date (MM/YY)

    try {
        // Insert the customer data into the Customer table
        $stmt = $pdo2->prepare("INSERT INTO Customer (first_name, last_name, email, address) VALUES (:first_name, :last_name, :email, :address)");
        $stmt->bindParam(':first_name', $firstName);
        $stmt->bindParam(':last_name', $lastName);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':address', $address);

        if ($stmt->execute()) {
            echo "<p>Customer details submitted successfully.</p>";
        } else {
            echo "<p>Failed to submit customer details.</p>";
            exit;
        }

        // Get the customer_id of the newly inserted customer
        $customer_id = $pdo2->lastInsertId();

        // Calculate the total price and weight from the cart
        $totalPrice = 0;
        $totalWeight = 0;
        foreach ($mergedData as $item) {
            $totalPrice += $item['price'] * $item['customerq'];
            $totalWeight += $item['qweight'];
        }

        // Insert into the Orders table
        $stmt = $pdo2->prepare("INSERT INTO Orders (customer_id, price, order_weight) VALUES (:customer_id, :price, :order_weight)");
        $stmt->bindParam(':customer_id', $customer_id);
        $stmt->bindParam(':price', $totalPrice);
        $stmt->bindParam(':order_weight', $totalWeight);
        $stmt->execute();
       

    } catch (PDOException $e) {
        echo "<p>Error during order placement: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Place order logic
        // Assume $pdo2 is the database connection and order is successfully placed
        $orderNumber = $pdo2->lastInsertId(); // Retrieve the last inserted order ID
        $AUTHNUM = rand(100, 900); // Random authorization number
        $totalPrice = number_format($totalPrice, 2); // Format the total price
        $fullName = htmlspecialchars($firstName . ' ' . $lastName);
        $email = htmlspecialchars($email);

        // Output success message and JavaScript to populate and show the modal
        echo "<p>Order placed successfully. An email confirmation will be sent.</p>";
        echo "<script>
                window.onload = function() {
                    openModal('$orderNumber', '$totalPrice', '$AUTHNUM', '$fullName', '$email');
                }
              </script>";

    } catch (PDOException $e) {
        echo "<p>Error during order placement: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
}

?>


<!-- The Modal -->
<div id="myModal" class="modal">
  <!-- Modal content -->
  <div class="modal-content">
    <span class="close">&times;</span>
    <p id="modalText"></p>
  </div>
</div>

<h2>Billing Information</h2>
<form method="post" action="Cart.php">
    <label for="first_name">First Name:</label>
    <input type="text" name="first_name" required><br>
    <label for="last_name">Last Name:</label>
    <input type="text" name="last_name" required><br>
    <label for="email">Email:</label>
    <input type="email" name="email" required><br>
    <label for="address">Address:</label>
    <input type="text" name="address" required><br>
    <label for="cc">Credit Card Number:</label>
    <input type="text" name="cc" required><br>
    <label for="exp">Expiration Date (MM/YY):</label>
    <input type="text" name="exp" required><br>
    <button type="submit" id ="Sub">Submit Order</button>
</form>

</body>
</html>
