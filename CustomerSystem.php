<!DOCTYPE html>
<html lang="en">
<button onclick="window.location.href='https://students.cs.niu.edu/~z2003741/476/Cart.php';">Cart</button>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Catalog</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
            color: #333;
        }
        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h1, h3 {
            text-align: center;
            margin-bottom: 20px;
        }
        .search-form {
            text-align: center;
            margin-bottom: 20px;
        }
        .search-form input[type="text"] {
            width: 250px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .search-form button {
            padding: 10px 20px;
            border: none;
            background: #4CAF50;
            color: white;
            border-radius: 4px;
            cursor: pointer;
        }
        .search-form button:hover {
            background: #45a049;
        }
        .catalog {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }
        .product {
            width: 300px;
            background: #f5f5f5;
            border: 1px solid #ccc;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .product img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        .product h3 {
            margin: 10px;
            font-size: 1.2em;
        }
        .product p {
            margin: 10px;
            font-size: 0.9em;
        }
        .product strong {
            font-weight: bold;
        }
        .product form {
            margin: 10px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .product form input[type="number"] {
            width: 60px;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .product form button {
            padding: 8px 15px;
            border: none;
            background: #007BFF;
            color: white;
            border-radius: 4px;
            cursor: pointer;
        }
        .product form button:hover {
            background: #0056b3;
        }
        .cart-button {
            display: block;
            width: 200px;
            margin: 20px auto;
            padding: 10px;
            text-align: center;
            background: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
        }
        .cart-button:hover {
            background: #45a049;
        }
    </style>
</head><?php
// Database connection for Blitz
$username = "student";
$password = "student";

try {
    // Connect to Blitz database for 'parts'
    $dsn = "mysql:host=blitz.cs.niu.edu;port=3306;dbname=csci467";
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Debugging output
    echo "<p>Debug: Connected to csci467 database on Blitz.</p>";

    // Connect to 'z2003741' database for inventory and cart
    $username2 = "z2003741";
    $password2 = "2003Jan28";
    $dsn2 = "mysql:host=courses;dbname=z2003741";
    $pdo2 = new PDO($dsn2, $username2, $password2);
    $pdo2->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "<p>Debug: Connected to z2003741 database.</p>";

    session_start();
    $customer_id = $_SESSION['customer_id'] ?? null;

    // Search query for 'parts' table in Blitz
    $searchTerm = $_GET['search'] ?? '';
    $sql = "SELECT * FROM parts";
    if ($searchTerm) {
        $sql .= " WHERE description LIKE :searchTerm";
    }
    $stmt = $pdo->prepare($sql);
    if ($searchTerm) {
        $stmt->bindValue(':searchTerm', '%' . $searchTerm . '%', PDO::PARAM_STR);
    }
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "<p>Debug: Products retrieved = " . count($products) . "</p>";

    // Handle adding to cart
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['productId'], $_POST['quantity'])) {
        $itemId = $_POST['productId'];
        $customerQuantity = $_POST['quantity'];

        // Get weight from Blitz database
        $stmt = $pdo->prepare("SELECT weight FROM parts WHERE number = :itemId");
        $stmt->bindParam(':itemId', $itemId, PDO::PARAM_INT);
        $stmt->execute();
        $itemData = $stmt->fetch(PDO::FETCH_ASSOC);
        $weight = $itemData['weight'] ?? 0;

        $totalWeight = $weight * $customerQuantity;
        echo "<p>Hello Customer $customer_id</p>";
        echo "<p>Added $customerQuantity of Item ID: $itemId ($weight lbs) to your cart.</p>";
        echo "<p>Total Weight of Items = $totalWeight</p>";

        // Insert into cart in 'z2003741' database
        $stmt = $pdo2->prepare("INSERT INTO Cart (customer_id, item_id, customerq, qweight)
            VALUES (:customer_id, :item_id, :customerq, :qweight)
            ON DUPLICATE KEY UPDATE
            customerq = customerq + :customerq, 
            qweight = qweight + :qweight");
        $stmt->bindParam(':customer_id', $customer_id, PDO::PARAM_INT);
        $stmt->bindParam(':item_id', $itemId, PDO::PARAM_INT);
        $stmt->bindParam(':customerq', $customerQuantity, PDO::PARAM_INT);
        $stmt->bindParam(':qweight', $totalWeight, PDO::PARAM_STR);
        $stmt->execute();
    }
} catch (PDOException $e) {
    echo "<p>Connection to database failed: " . htmlspecialchars($e->getMessage()) . "</p>";
    exit;
}
?>


    <!-- Search Form -->
    <form method="get" action="">
        <label for="search">Search by Description:</label>
        <input type="text" id="search" name="search" value="<?php echo htmlspecialchars($searchTerm); ?>">
        <button type="submit">Search</button>
    </form>

    <div class="container">
    <h1>Product Catalog</h1>

    <!-- Search Form -->
    <div class="search-form">
        <form method="get" action="">
            <label for="search">Search by Description:</label>
            <input type="text" id="search" name="search" value="<?php echo htmlspecialchars($searchTerm); ?>">
            <button type="submit">Search</button>
        </form>
    </div>

    <!-- Product Catalog -->
    <div class="catalog">
        <?php if ($products): ?>
            <?php foreach ($products as $product): ?>
                <?php
                    $productId = $product["number"];
                    $stmt = $pdo2->prepare("SELECT quantity FROM Inventory WHERE item_id = :productId");
                    $stmt->bindParam(':productId', $productId, PDO::PARAM_INT);
                    $stmt->execute();
                    $inventory = $stmt->fetch(PDO::FETCH_ASSOC);
                    $quantity = $inventory['quantity'] ?? 0;
                ?>
                <div class="product">
                    <img src="<?php echo htmlspecialchars($product['pictureURL']); ?>" alt="Product Image">
                    <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                    <p><?php echo htmlspecialchars($product['description']); ?></p>
                    <p><strong>Price:</strong> $<?php echo number_format($product['price'], 2); ?></p>
                    <p><strong>In Stock:</strong> <?php echo number_format($quantity); ?></p>
                    <form method="post" action="">
                        <input type="number" name="quantity" min="1" max="<?php echo $quantity; ?>" value="1" required>
                        <input type="hidden" name="productId" value="<?php echo $product['number']; ?>">
                        <button type="submit">Add to Cart</button>
                    </form>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No products available.</p>
        <?php endif; ?>
    </div>

    <!-- Cart Button -->
    <a class="cart-button" href="cart.php">Go to Cart</a>
</div>
</body>
</html>
