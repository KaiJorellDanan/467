<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Catalog</title>
    <style>
   body {
    font-family: 'Arial', cursive, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #3f4a51; /* Light peachy pink */
    color: #000002;
    font-weight: bold;
}

.container {
    max-width: 1000px;
    margin: 20px auto;
    padding-right: 20px;
    padding-left: 20px;
    padding-bottom: 20px;
    padding-top: 25px;
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    background-color: #52616e; /* Soft pastel yellow */
}

h1 {
    text-align: center;
    margin-bottom: 20px;
    font-size: 2.5em;
    color: #89a2b8; /* Kitty's iconic pink */
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
}

.search-form {
    text-align: center;
    margin-bottom: 20px;
    color: #000002;
    font-weight: bold;
}

.search-form input[type="text"] {
    width: 250px;
    padding: 10px;
    border: 1px solid #000002; /* Light pink */
    border-radius: 20px;
    background-color: #fff;
    font-size: 1em;
}

.search-form button {
    padding: 10px 20px;
    border: none;
    background: #3f4a51; /* Kitty's pink */
    color: white;
    border-radius: 25px;
    cursor: pointer;
    font-size: 1.1em;
    font-weight: bold;
}

.search-form button:hover {
    background: #89a2b8; /* Darker pink */
}

.catalog {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    justify-content: center;
}

.product {
    width: 300px;
    background: #fff;
    border: 1px solid #000002; /* Light pink border */
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    background-color: #ffffff; /* Soft pink background */ 
}

.product img {
    width: 100%;
    height: 200px;
    object-fit: cover;
    border-bottom: 4px solid #000002; /* Pink border at the bottom of the image */
}

.product h3 {
    margin: 10px;
    font-size: 1.4em;
    color: #000002; /* Kitty's pink */
    text-align: center;
}

.product p {
    margin: 10px;
    font-size: 1.1em;
    font-weight: bold;
    color: #000002; /* Dark brown text for readability */
}

.product strong {
    font-weight: bold;
    font-size: 1em;
    color: #000002; /* Matching the Hello Kitty theme */
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
    border: 1px solid #000002;
    border-radius: 4px;
    background-color: #fff;
}

.product form button {
    padding: 8px 15px;
    border: none;
    background: #3f4a51; /* Pink button */
    color: white;
    border-radius: 25px;
    cursor: pointer;
    font-weight: bold;
}

.product form button:hover {
    background: #89a2b8; /* Darker pink on hover */
}

.cart-button {
    display: block;
    width: 200px;
    margin: 20px auto;
    padding: 10px;
    text-align: center;
    background: #3f4a51; /* Pink button */
    color: white;
    border: none;
    border-radius: 25px;
    cursor: pointer;
    font-weight: bold;
    text-decoration: none;
}

.cart-button:hover {
    background: #89a2b8; /* Darker pink on hover */
}
    </style>
</head>

<body>
    <?php
    // Database connections
    $username = "student";
    $password = "student";
    $dsn = "mysql:host=blitz.cs.niu.edu;port=3306;dbname=csci467";
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Second database for inventory and cart
    $username2 = "z2003741";
    $password2 = "2003Jan28";
    $dsn2 = "mysql:host=courses;dbname=z2003741";
    $pdo2 = new PDO($dsn2, $username2, $password2);
    $pdo2->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    session_start();
    $customer_id = $_SESSION['customer_id'] ?? null;

    if (!$customer_id) {
        echo "<p>Error: Customer ID is missing. Please log in.</p>";
        exit;
    }

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
        // echo "<p>Hello Customer $customer_id</p>";
        // echo "<p>Added $customerQuantity of Item ID: $itemId ($weight lbs) to your cart.</p>";
        // echo "<p>Total Weight of Items = $totalWeight</p>";

        // Insert into cart, handling duplicates
        $pdo2->exec("SET foreign_key_checks = 0");
        $stmt = $pdo2->prepare("INSERT INTO Cart (customer_id, item_id, customerq, qweight)
            VALUES (:customer_id, :item_id, :customerq, :qweight)
            ON DUPLICATE KEY UPDATE customerq = customerq + :customerq, qweight = qweight + :qweight");
        $stmt->bindParam(':customer_id', $customer_id, PDO::PARAM_INT);
        $stmt->bindParam(':item_id', $itemId, PDO::PARAM_INT);
        $stmt->bindParam(':customerq', $customerQuantity, PDO::PARAM_INT);
        $stmt->bindParam(':qweight', $totalWeight, PDO::PARAM_STR);
        $stmt->execute();
        $pdo2->exec("SET foreign_key_checks = 1");
    }

    // Search products
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
    ?>

    <div class="container">
        <h1>Product Catalog</h1>
        <!-- Cart Button -->
            <a class="cart-button" href="Cart.php">Go to Cart</a>
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

        
    </div>
</body>
</html>
