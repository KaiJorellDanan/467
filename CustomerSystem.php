<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* BASIC STYLING */
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
        .product-details {
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <h1>Customer Interface</h1>
    <p>CSCI 466 Project by Kai Danan</p>

    <?php
    $username = "z2003741";
    $password = "2003Jan28";

    try {
        $dsn = "mysql:host=courses;dbname=z2003741";
        $pdo = new PDO($dsn, $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Initialize search query
        $searchTerm = '';
        if (isset($_GET['search'])) {
            $searchTerm = $_GET['search'];
        }

        // SQL query with search filter
        $sql = "SELECT * FROM parts";
        if ($searchTerm) {
            $sql .= " WHERE description LIKE :searchTerm";
        }
        
        $stmt = $pdo->prepare($sql);

        // Bind the search term if it exists
        if ($searchTerm) {
            $stmt->bindValue(':searchTerm', '%' . $searchTerm . '%', PDO::PARAM_STR);
        }
        
        $stmt->execute();
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    } catch(PDOException $e) {
        echo "Connection to database failed: " . $e->getMessage();
    }
    ?>

    <!-- Search Form -->
    <form method="get" action="">
        <label for="search">Search by Description:</label>
        <input type="text" id="search" name="search" value="<?php echo htmlspecialchars($searchTerm); ?>">
        <button type="submit">Search</button>
    </form>
    <form>
    <h1>Product Catalog</h1>
    <div class="catalog">
        <?php if (count($products) > 0): ?>
            <?php foreach ($products as $product): ?>
                <div class="product">
                    <img src=<?php echo htmlspecialchars($product['pictureURL']); ?>> 
                        <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                        <p><?php echo htmlspecialchars($product['description']); ?></p>
                        <p><strong>Price:</strong> $<?php echo number_format($product['price'], 2); ?></p>
                        <p><strong>In Stock:</strong> <?php echo htmlspecialchars($Inventory['quantity']); ?></p> 
                            <input type="hidden" name="customer_id" value="<?php echo $customer_id; ?>">
                            <input type="hidden" name="item_id" value="<?php echo htmlspecialchars($product['item_id']); ?>">

                            <input type="number" name="quantity" min="1" max="<?php echo $product['quantity']; ?>" required>
                            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                             <php?
                             $number = $product[]
                            php>
                            <input type="submit" name="Order" value="Order"></form> 
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No products available.</p>
        <?php endif; ?>
    </div>


    <button type="submit">Add to Cart</button>
</form>

</body>
</html>
