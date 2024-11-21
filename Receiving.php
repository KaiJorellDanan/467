<!DOCTYPE html>
<html lang="en">
    <style> 
        table, th, td
        {
            text-align: center;
            table-layout: fixed;
            td 
            { 
                width: 33%;
                font-weight: normal; 
            }
        }
    </style>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Receiving Parts</title>
        <?php
            $username = "z1952360";
            $password = "2004May03";
            try 
            { // if something goes wrong, an exception is thrown
                
                $dsn = "mysql:host=courses;dbname=z1952360";
                $pdo = new PDO($dsn, $username, $password);
            }
            catch(PDOexception $e) 
            { // handle that exception
                echo "Connection to database failed: " . $e->getMessage();
            }
        ?>

        <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST")
            {
                $showparts = $_POST["suppliers"];
                $new_quant = $_POST["new_quantity"];
                $partnum_update = $_POST["part_number"];
                $search_term = $_POST["search"];
            }
            
            if($new_quant >= 0)
            {
                $sql = "UPDATE Inventory 
                        SET quantity = '$new_quant' 
                        WHERE item_id = '$partnum_update';";
                $pdo->exec($sql);
            }

            if(!empty($search_term))
            {
                $sql = "SELECT number, description, price, weight, pictureURL, Inventory.quantity
                        FROM parts
                        LEFT JOIN Inventory ON parts.number = Inventory.item_id
                        WHERE description LIKE '%$search_term%';";
            }
            else
            {
                $sql = "SELECT number, description, price, weight, pictureURL, Inventory.quantity
                        FROM parts
                        LEFT JOIN Inventory ON parts.number = Inventory.item_id;";
            }
        ?>  
    </head>
    <body>
        <h1>Receiving Warehouse</h1>
        <h2>Car Parts Warehouse</h2>
        <button onclick="window.location.href='https://students.cs.niu.edu/~z1952360/Orders.php';">Orders</button>
        <button onclick="window.location.href='https://students.cs.niu.edu/~z1952360/Receiving.php';">Receiving</button>
        <form method="post">
            Search by Description: <input type="text" name="search">
            <button type="submit">Search</button>
        </form>
    </body>

    <?php
        $result = $pdo->query($sql);
        echo "<table style='width:80%'>";
        echo "<tr>"; 
        echo "<th>Picture</th>";
        echo "<th>Number</th>";
        echo "<th>Description</th>";
        echo "<th>Price</th>";
        echo "<th>Weight</th>";
        echo "<th>Quantity</th>";
        echo "<th>Update Quantity</th>";
        echo "</tr>";

        while ($row = $result->fetch()) 
        {
            $number = $row['number'];
            $description = $row['description'];
            $price = $row['price'];
            $weight = $row['weight'];
            $picture = $row['pictureURL'];
            $quantity = $row['quantity'];

            echo "<tr>";
            echo "<td><img src='$picture' alt='Part Picture' style='width:100px;'></td>";
            echo "<td>$number</td>";
            echo "<td>$description</td>";
            echo "<td>$$price</td>";
            echo "<td>$weight</td>";
            echo "<td>$quantity</td>";
            echo "<td>";
            echo "<form method='post' action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "'>";
            echo "Quantity: <input type='text' name='new_quantity'>";
            echo "<input type='hidden' name='part_number' value='$number'>";
            echo "<button type='submit'>Update Quantity</button>";
            echo "</form>";
            echo "</td>";
            echo "</tr>";
        }
        echo "</table>";
    ?>
</html>
