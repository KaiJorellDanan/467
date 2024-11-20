<!DOCTYPE html>
<html lang="en">
<button onclick="window.location.href='https://students.cs.niu.edu/~z2003741/476/Cart.php';">Cart</button>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* Basic Styling */
        .catalog {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
        }
        .product {
            border: 1px solid #ccc;
            margin: 10px;
            padding: 10px;
            width: 200px;
            text-align: center;
        }
        .product img {
            width: 100%;
            height: auto;
        }
    </style>
</head>
<body>
    <h1>Customer Interface</h1>
    <p>CSCI 466 Project by Kai Danan</p>

    <?php
    // Database connection
    $username = "z1952360";
    $password = "2004May03";

    try {
        $dsn = "mysql:host=courses;dbname=z1952360";
        $pdo = new PDO($dsn, $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        session_start();
        $customer_id = $_SESSION['customer_id'] ?? null;

        // Search query
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

    } catch(PDOException $e) {
        echo "Connection to database failed: " . $e->getMessage();
    }

    // Handle adding to cart and calculate total weight
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['productId'], $_POST['quantity'])) {
        $itemId = $_POST['productId'];
        $customerQuantity = $_POST['quantity'];

        $stmt = $pdo->prepare("SELECT weight FROM parts WHERE number = :itemId");
        $stmt->bindParam(':itemId', $itemId, PDO::PARAM_INT);
        $stmt->execute();

        $itemData = $stmt->fetch(PDO::FETCH_ASSOC);
        $weight = $itemData['weight'] ?? 0;
        
        $totalWeight = $weight * $customerQuantity;
        echo "<p>Hello Customer $customer_id</p>";
        echo "<p>Added $customerQuantity of Item ID: $itemId ($weight lbs) to your cart.</p>";
        echo "<p>Total Weight of Items = $totalWeight</p>";
        
            $stmt = $pdo->prepare("INSERT INTO Cart (customer_id, item_id, customerq, qweight)
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
    ?>

    <!-- Search Form -->
    <form method="get" action="">
        <label for="search">Search by Description:</label>
        <input type="text" id="search" name="search" value="<?php echo htmlspecialchars($searchTerm); ?>">
        <button type="submit">Search</button>
    </form>

    <h1>Product Catalog</h1>
    <div class="catalog">
        <?php if ($products): ?>
            <?php foreach ($products as $product): ?>
                <?php
                    $productId = $product["number"];
                    $stmt = $pdo->prepare("SELECT quantity FROM Inventory WHERE item_id = :productId");
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

                    <!-- Order Form -->
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

    <button type="button" onclick="window.location.href='cart.php'">Go to Cart</button>
</body>
</html>
