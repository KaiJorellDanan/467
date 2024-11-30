<!DOCTYPE html>
<html lang="en">
    <style> 
        body
        {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: #ffffff; 
            background-color: #16171f;
        }
        table
        {
            border: 1px solid #ffffff; 
            border-collapse: collapse; 
            margin: 20px 0; 
            width: 80%; 
        }
        th, td 
        {
            border: 1px solid #ffffff; 
            padding: 10px; 
            text-align: center; 
        }
        h1, h2
        {
            margin: 10px 0; 
            text-align: center; 
        }
        button
        {
            cursor: pointer;
            background-color: #2471a3;
        }
        button:hover
        {
            background-color: #ebf5fb;
        }
    </style>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Receiving Parts</title>
        <?php
            // connect to my database
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
        
            // connect to legacy database
            $username2 = "student";
            $password2 = "student";
            try 
            { // if something goes wrong, an exception is thrown
                
                $dsn2 = "mysql:host=blitz.cs.niu.edu;port=3306;dbname=csci467";
                $pdo2 = new PDO($dsn2, $username2, $password2);
            }
            catch(PDOexception $e) 
            { // handle that exception
                echo "Connection to database failed: " . $e->getMessage();
            }
        
            // get the form varaibles from post method
            if ($_SERVER["REQUEST_METHOD"] == "POST")
            {
                $showparts = $_POST["suppliers"];
                $new_quant = $_POST["new_quantity"];
                $partnum_update = $_POST["part_number"];
                $search_term = $_POST["search"];
            }
            
            //if the new_quantity is >= 0 update the inventory with that quantity
            if($new_quant >= 0)
            {
                $sql = "UPDATE Inventory 
                        SET quantity = '$new_quant' 
                        WHERE item_id = '$partnum_update';";
                $pdo->exec($sql);
            }

            //if the search term isn't empty then switch the search in the parts table sql
            if(!empty($search_term))
            {
                $sql = "SELECT * FROM parts
                        WHERE description LIKE '%$search_term%';";
            }
            else
            {
                $sql = "SELECT * FROM parts";
            }
        ?>  
    </head>
    <body>
        <h1>Receiving Warehouse</h1>
        <h2>Car Parts Warehouse</h2>
        <button onclick="window.location.href='https://students.cs.niu.edu/~z1949818/csci467Receiving.php';">Orders</button>
        <button onclick="window.location.href='https://students.cs.niu.edu/~z1949818/csci467/Receiving.php';">Receiving</button>
        <form method="post">
            Search by Description: <input type="text" name="search">
            <button type="submit">Search</button>
        </form>
    </body>

    <?php
        // print the parts table with the inventory quantity from my inventory table
        $result = $pdo2->query($sql);

        // print table headers
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
        
        // loop to get all of the rows of the table from the select statement
        while ($row = $result->fetch()) 
        {
            $number = $row['number'];
            $description = $row['description'];
            $picture = $row['pictureURL'];
            $price = $row['price'];
            $weight = $row['weight'];

            $price = number_format($price, 2);
            $weight = number_format($weight, 2);

            // find the matching inventory quantity from the part number
            $query_quant = "SELECT quantity FROM Inventory
                            WHERE item_id = '$number';";
            $result2 = $pdo->query($query_quant);
            $row2 = $result2->fetch();

            $quantity = $row2['quantity'];

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
