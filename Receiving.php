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
            background-color: #3f4a51ff; 
        }
    
        h1, h2 
        {
            margin: 10px 0; 
            font-family: 'Abril Fatface', serif;
            text-align: center; 
        }

        .header 
        {
            font-family: 'Lato', sans-serif;
            text-align: center;
            background-color: #6d7c86ff;
            padding: 10px;
            font-weight: bold;
            border-radius: 5px;
        }

        .grid-container 
        {
            display: grid;
            grid-template-columns: repeat(3, 1fr);  
            gap: 20px;
            width: 80%;
            margin: 20px 0;
        }

        .part-box 
        {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: center;
            background-color: #52616eff;
            border: 1px solid #000002;
            padding: 15px;
            border-radius: 10px;
            text-align: center;
            font-family: 'Lato', sans-serif;
        }

        .part-box img 
        {
            width: 150px;
            height: auto;
            border: 1px solid #000002;
            margin-bottom: 10px;
        }

        .part-box .info 
        {
            margin: 5px 0;
        }

        .button-container 
        {
            margin-top: 10px;
        }

        .cell input 
        {
            padding: 5px;
        }

        button 
        {
            cursor: pointer;
            background-color: #52616eff;  
        }

        button:hover 
        {
            background-color: #6d7c86ff;  
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
            {
                $dsn = "mysql:host=courses;dbname=z1952360";
                $pdo = new PDO($dsn, $username, $password);
            } 
            catch(PDOexception $e) 
            {
                echo "Connection to database failed: " . $e->getMessage();
            }

            // connect to legacy database
            $username2 = "student";
            $password2 = "student";
            try 
            {   // if something goes wrong, an exception is thrown
                $dsn2 = "mysql:host=blitz.cs.niu.edu;port=3306;dbname=csci467";
                $pdo2 = new PDO($dsn2, $username2, $password2);
            } 
            catch(PDOexception $e) 
            {
                echo "Connection to database failed: " . $e->getMessage();
            }

            // get the form variables from post method
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

            // if the search term isn't empty then switch the search in the parts table SQL
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
        <button onclick="window.location.href='https://students.cs.niu.edu/~z1952360/Full.php';">Orders</button>
        <button onclick="window.location.href='https://students.cs.niu.edu/~z1952360/Receiving.php';">Receiving</button>
        <form method="post">
            Search by Description: <input type="text" name="search">
            <button type="submit">Search</button>
        </form>
    </body>

    <?php
        // print the parts table with the inventory quantity from my inventory table
        $result = $pdo2->query($sql);

        echo "<div class='grid-container'>";
        
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

            echo "<div class='part-box'>";
            echo "<img src='$picture' alt='Part Picture'>";
            echo "<div class='info'>Number: $number</div>";
            echo "<div class='info'>Description: $description</div>";
            echo "<div class='info'>Price: $$price</div>";
            echo "<div class='info'>Weight: $weight lbs</div>";
            echo "<div class='info'>Quantity: $quantity</div>";
            echo "<div class='button-container'>";
            echo "<form method='post' action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "'>";
            echo "Quantity: <input type='text' name='new_quantity'>";
            echo "<input type='hidden' name='part_number' value='$number'>";
            echo "<button type='submit'>Update Quantity</button>";
            echo "</form>";
            echo "</div>";
            
            echo "</div>"; 
        }

        echo "</div>";
    ?>
</html>
