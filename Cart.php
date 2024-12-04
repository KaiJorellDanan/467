<body>
    <!-- Include JavaScript and CSS -->
    <script src="CARTJS.js" defer></script>
    <?php
    include 'CartCSS.css';
    session_start();

    // Retrieve customer ID from session
    $customer_id = $_SESSION['customer_id'] ?? null;

    // Check if customer ID is available
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
        $pdoLocal = new PDO($dsn1, $username, $password);
        $pdoLocal->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $dsn2 = "mysql:host=blitz.cs.niu.edu;dbname=csci467"; // Blitz database
        $pdoBlitz = new PDO($dsn2, $username1, $password1);
        $pdoBlitz->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


        // Fetch cart data from the local database
        $cartQuery = $pdoLocal->prepare("SELECT item_id, customerq, qweight FROM Cart WHERE customer_id = :customer_id");
        $cartQuery->bindParam(':customer_id', $customer_id, PDO::PARAM_INT);
        $cartQuery->execute();
        $cartItems = $cartQuery->fetchAll(PDO::FETCH_ASSOC);

        // If cart is empty, display message and exit
        if (!$cartItems) {
            echo "<p>Your cart is empty.</p>";
            exit;
        }

        // Fetch parts data from the Blitz database
        $itemIds = array_column($cartItems, 'item_id');
        $placeholders = implode(',', array_fill(0, count($itemIds), '?'));
        $partsQuery = $pdoBlitz->prepare("SELECT number, description, price, pictureURL FROM parts WHERE number IN ($placeholders)");
        $partsQuery->execute($itemIds);
        $partsData = $partsQuery->fetchAll(PDO::FETCH_ASSOC);

        // Merge cart data with parts data
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

    } catch (PDOException $e) {
        // Handle database connection errors
        echo "<p>Connection to database failed: " . htmlspecialchars($e->getMessage()) . "</p>";
        exit;
    }

    // Handle POST requests for removing items from the cart
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $action = $_POST['action'] ?? null;

        if ($action === 'remove_item' && isset($_POST['item_id'])) {
            try {
                $item_id = (int)$_POST['item_id'];
                $deleteQuery = $pdoLocal->prepare("DELETE FROM Cart WHERE customer_id = :customer_id AND item_id = :item_id");
                $deleteQuery->bindParam(':customer_id', $customer_id, PDO::PARAM_INT);
                $deleteQuery->bindParam(':item_id', $item_id, PDO::PARAM_INT);

                if ($deleteQuery->execute()) {
                    // After removing the item, reload the page with a status message
                    header("Location: Cart.php?status=removed");
                    exit;
                }
            } catch (PDOException $e) {
                echo "<p>Error removing item: " . htmlspecialchars($e->getMessage()) . "</p>";
            }
        }
    }

    // Step 1: Validate Credit Card via RESTful API
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['email']) && isset($_POST['address']) && isset($_POST['cc']) && isset($_POST['exp'])) {

    // Sanitize and prepare form data
    $firstName = htmlspecialchars($_POST['first_name']);
    $lastName = htmlspecialchars($_POST['last_name']);
    $email = htmlspecialchars($_POST['email']);
    $address = htmlspecialchars($_POST['address']);
    $cc = htmlspecialchars($_POST['cc']);
    $exp = htmlspecialchars($_POST['exp']);
    $totalPrice = 654.32; // Example price, you can replace with actual calculation

    // Randomize 'vendor' and 'trans'
    $vendor = 'VE001-' . str_pad(rand(0, 99), 2, '0', STR_PAD_LEFT); // Random vendor in format VE001-XX
    $trans = '907-' . str_pad(rand(100000000, 999999999), 9, '0', STR_PAD_LEFT) . '-' . str_pad(rand(100, 999), 3, '0', STR_PAD_LEFT); // Random transaction

    // Credit Card Validation API
    $creditCardData = [
        'vendor' => $vendor,
        'trans' => $trans,
        'cc' => $cc,
        'name' => "$firstName $lastName",
        'exp' => $exp,
        'amount' => $totalPrice,
    ];

    $url = 'http://blitz.cs.niu.edu/CreditCard/';
    $options = [
        'http' => [
            'header' => ['Content-type: application/json', 'Accept: application/json'],
            'method' => 'POST',
            'content' => json_encode($creditCardData),
        ],
    ];

    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);

    // Check response
    if (strpos($result, 'Error') === 0) {
        echo "<p>Faulty credit card.</p>";
        exit; // Stop further processing if card is invalid
    } else {
        $orderNumber = $pdoLocal->lastInsertId(); // Retrieve the last inserted order ID
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
    }

    // Step 2: Insert Customer Data into the Customer Table
    try {
        $pdoLocal = new PDO('mysql:host=courses;dbname=z2003741', $username, $password );
        $stmt = $pdoLocal->prepare("INSERT INTO Customer (first_name, last_name, email, address) VALUES (:first_name, :last_name, :email, :address)");
        $stmt->bindParam(':first_name', $firstName);
        $stmt->bindParam(':last_name', $lastName);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':address', $address);
        $stmt->execute();

        // Get the customer_id of the newly inserted customer
        $customer_id = $pdoLocal->lastInsertId();
        echo "<p>Customer ID $customer_id inserted successfully!</p>";

    } catch (PDOException $e) {
        echo "<p>Error inserting customer data: " . htmlspecialchars($e->getMessage()) . "</p>";
        exit; // Exit if there's an error inserting the customer data
    }

    // Step 3: Insert Order Data into the Order Table
    try {
        $orderStmt = $pdoLocal->prepare("INSERT INTO `Orders` (customer_id, price, order_date) VALUES (:customer_id, :price, NOW())");
        $orderStmt->bindParam(':customer_id', $customer_id);
        $orderStmt->bindParam(':price', $totalPrice);
        $orderStmt->execute();

        // Optionally, insert individual order items into an order_items table, if needed
        echo "<p>Order placed successfully for customer $customer_id!</p>";

        
    } catch (PDOException $e) {
        echo "<p>Error inserting order data: " . htmlspecialchars($e->getMessage()) . "</p>";
        exit; // Exit if there's an error inserting the order data
    }


} else {
    echo "<p>Please fill out all fields.</p>";
}

    ?>

    <!-- Modal -->
    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <p id="modalText"></p>
        </div>
    </div>

    <!-- Shopping Cart -->
    <div class="cart">
        <?php if ($mergedData): ?>
            <!-- Start the table for the cart -->
            <table class="cart-table">
                <tbody>
                    <?php foreach ($mergedData as $item): ?>
                        <?php
                        // Calculate total price and total weight for each item
                        $itemTotalPrice = $item['price'] * $item['customerq'];
                        $totalPrice += $itemTotalPrice;
                        $totalWeight += $item['qweight'];
                        ?>
                        <!-- Output table row for each item -->
                        <tr>
                            <td><?php echo htmlspecialchars($item['item_id']); ?></td>
                            <td><img src="<?php echo htmlspecialchars($item['pictureURL']); ?>" alt="Item Image" class="item-image"></td>
                            <td><?php echo htmlspecialchars($item['description']); ?></td>
                            <td><?php echo htmlspecialchars($item['customerq']); ?></td>
                            <td><?php echo htmlspecialchars($item['qweight']); ?> lbs</td>
                            <td>$<?php echo number_format($itemTotalPrice, 2); ?></td>
                            <td>
                                <!-- Form to remove item from cart -->
                                <form method="post" action="Cart.php">
                                    <input type="hidden" name="action" value="remove_item">
                                    <input type="hidden" name="item_id" value="<?php echo htmlspecialchars($item['item_id']); ?>">
                                    <button type="submit">Remove</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <!-- Output totals row -->
                    <tr>
                        <th colspan="4">Totals</th>
                        <th><?php echo htmlspecialchars($totalWeight); ?> lbs</th>
                        <th colspan="2">$<?php echo number_format($totalPrice, 2); ?></th>
                    </tr>
                </tfoot>
            </table>
        <?php else: ?>
            <p>Your cart is empty.</p>
        <?php endif; ?>
    </div>

    <!-- Billing Information -->
    <h2>Billing Information</h2>
    <form method="post" action="Cart.php">
        <input type="hidden" name="action" value="submit_order">
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
        <button type="submit">Submit Order</button>
    </form>
</body>
