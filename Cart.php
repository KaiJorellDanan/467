<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart - Billing Information</title>
    <style>
    /* General Body Styling */
    body {
        font-family: 'Lato', sans-serif;
        line-height: 2.0;
        margin: 20px;
        background-color: #3f4a51;
        color: #333;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: flex-start;
        min-height: 100vh;
        color: #000002;
        font-weight: bold;
    }

    /* Info and Error Message Styling */
    .info, .error {
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 20px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        width: 80%;
        max-width: 600px;
        text-align: center;
    }
    /*I think I am gay guys */
    .info {
        background-color: #d9f7d9;
        color: #388e3c;
    }

    .error {
        background-color: #fce4e4;
        color: #c62828;
    }

    /* Cart Table Styling */
    .cart-table {
        width: 250%;
        margin-left: -300px;
        border-collapse: collapse;
        margin-top: 20px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .cart-table th, .cart-table td {
        border: 1px solid #ddd;
        padding: 15px;
        text-align: center;
        font-size: 16px;
        vertical-align: middle;
    }

    .cart-table th {
        background-color: #f1f1f1;
        font-weight: bold;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .cart-table td {
        background-color: #fff;
    }

    h2{
        font-size: 30px;
    }
    /* Image Styling in Cart */
    .cart-table img {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 5px;
    }

    /* Button Styling */
    button {
        background: #6d7c86;
        color: white;
        border: none;
        padding: 12px 25px;
        cursor: pointer;
        border-radius: 5px;
        font-size: 16px;
        transition: background-color 0.3s ease, transform 0.2s ease;
        margin-top: 10px;
    }

    button:hover {
        background: #89a2b8;
        transform: scale(1.05);
    }

    /* Modal Styles  DONT TOUCH ANYTHING BELOW THIS LINE*/
    .modal {
        display: none;
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        animation: fadeIn 0.3s ease-in-out;
    }

    .modal-content {
        background-color: white;
        padding: 20px;
        margin: 10% auto;
        width: 80%;
        max-width: 500px;
        border-radius: 5px;
    }

    .close-btn {
        color: #aaa;
        font-size: 28px;
        font-weight: bold;
        position: absolute;
        top: 10px;
        right: 20px;
    }

    .close-btn:hover,
    .close-btn:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }

    /* Modal Button */
    .modal-btn {
        padding: 10px 20px;
        background-color: #4CAF50;
        color: white;
        border: none;
        cursor: pointer;
    }

    .modal-btn:hover {
        background-color: #45a049;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .cart-table {
            width: 100%;
        }

        button {
            width: 100%;
            padding: 15px;
        }

        form {
            width: 90%;
            padding: 20px;
        }

        .modal-content {
            width: 90%;
        }
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }
    </style>
</head>
<body>
    
    <!-- Include JavaScript and CSS -->
    <script src="CARTJS.js" defer></script>
    <?php
    include 'CartCSS.css';
    session_start();

    if (isset($_SESSION['customer_id'])) {
        $customer_id = $_SESSION['customer_id'];
        echo "<p>Customer ID retrieved from session: $customer_id</p>";
    } else {
        echo "<p>Error: Customer ID not found in session!</p>";
        exit; // Stop further execution if customer_id isn't available
    }
    
    print_r($_SESSION);

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

    foreach ($mergedData as $item) {
        // Ensure item price and quantity are valid
        $itemPrice = $item['price'];
        $itemQuantity = $item['customerq'];
        $itemWeight = $item['qweight'];

        // Calculate total price and total weight for each item
        $itemTotalPrice = $itemPrice * $itemQuantity; // price * quantity
        $itemTotalWeight = $itemWeight * $itemQuantity; // weight * quantity

        $totalPrice += $itemTotalPrice;
        $totalWeight += $itemTotalWeight; // Accumulate total weight
    }

            

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
    } 

    // Step 2: Insert Customer Data into the Customer Table
    try {
        $pdoLocal = new PDO('mysql:host=courses;dbname=z2003741', $username, $password );
        $stmt = $pdoLocal->prepare("INSERT INTO Customer (customer_id,first_name, last_name, email, address) VALUES (:customer_id,:first_name, :last_name, :email, :address)");
        $stmt->bindParam(':customer_id', $customer_id);
        $stmt->bindParam(':first_name', $firstName);
        $stmt->bindParam(':last_name', $lastName);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':address', $address);
        $stmt->execute();

       
        echo "<p>Customer ID $customer_id inserted successfully!</p>";

    } catch (PDOException $e) {
        echo "<p>Error inserting customer data: " . htmlspecialchars($e->getMessage()) . "</p>";
        exit; // Exit if there's an error inserting the customer data
    }

    // Step 3: Insert Order Data into the Order Table
    try {
        $orderStmt = $pdoLocal->prepare("INSERT INTO `Orders` (customer_id, price, order_date, order_weight) 
        VALUES (:customer_id, :price, NOW(), :order_weight)");
        $orderStmt->bindParam(':customer_id', $customer_id);
        $orderStmt->bindParam(':price', $totalPrice); // Pass the correct calculated totalPrice here
        $orderStmt->bindParam(':order_weight', $totalWeight); // Same for totalWeight
        $orderStmt->execute();

        // Optionally, insert individual order items into an order_items table, if needed
        echo "<p>Order placed successfully for customer $customer_id!</p>";
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

    <div class="cart">
    <?php if ($mergedData): ?>
        <!-- Start the table for the cart -->
        <table class="cart-table">
            <tbody>
        <?php foreach ($mergedData as $item): ?>
            <tr>
                <td><?php echo htmlspecialchars($item['item_id']); ?></td>
                <td><img src="<?php echo htmlspecialchars($item['pictureURL']); ?>" alt="Item Image" class="item-image"></td>
                <td><?php echo htmlspecialchars($item['description']); ?></td>
                <td><?php echo htmlspecialchars($item['customerq']); ?></td>
                <td><?php echo htmlspecialchars($item['qweight']); ?> lbs</td>
                <td>$<?php echo number_format($item['price'] ,2); ?></td>
                <td>
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
</html>
