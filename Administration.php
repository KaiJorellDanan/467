<!DOCTYPE html>
<html lang="en">
    <style> 
        table, th, td
        {
            text-align: center;
            table-layout: fixed;
            td 
            { 
                width: 10%;
                font-weight: normal; 
            }
        }
    </style>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Administration</title>
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

            if ($_SERVER["REQUEST_METHOD"] == "POST")
            {
                $shipping_id = $_POST['shipping_id'];
                $remove = $_POST['remove'];
                $new_weight = $_POST['new_weight'];
                $new_shipping_cost = $_POST['new_shipping_cost'];
                $add_button_click = $_POST['add_button'];
            }

            if($remove == "begone")
            {
                $sql = "DELETE FROM Shipping_cost
                        WHERE shipping_cost_id = '$shipping_id';";
                $pdo->exec($sql);
            }

            if($new_weight >= 0 && $shipping_cost >= 0 && $add_button_click == "clicked")
            {
                $sql = "INSERT INTO Shipping_cost(weight, shipping_cost) VALUES
                        ('$new_weight','$new_shipping_cost');";
                $pdo->exec($sql);
            }
        ?>  
    </head>
    <body>
        <h1>Administration</h1>
        <h2>Car Parts Administration</h2>
        <button onclick="window.location.href='https://students.cs.niu.edu/~z1952360/Orders.php';">Orders</button>
        <button onclick="window.location.href='https://students.cs.niu.edu/~z1952360/Administration.php';">Weight Brackets</button>
        <h3>Weight brackets to calculate shipping cost:<h3>

        <?php
            $sql = "SELECT * FROM Shipping_cost
                    ORDER BY weight ASC;";
            $result = $pdo->query($sql);

            echo "<table style='width:40%'>";
            echo "<tr>"; 
            echo "<th>Weight Range</th>";
            echo "<th>Shipping Cost</th>";
            echo "</tr>";

            while ($row = $result->fetch()) 
            {
                $shipping_cost_id = $row['shipping_cost_id'];
                $weight = $row['weight'];

                $query_next_weight = "SELECT weight FROM Shipping_cost WHERE weight > '$weight'
                                    ORDER BY weight ASC;";
                $result2 = $pdo->query($query_next_weight);
                $row2 = $result2->fetch();

                $weight2 = $row2['weight'];
                $shipping_cost = $row['shipping_cost'];
                echo "<tr>";
                if(!empty($weight2))
                {
                    echo "<td>From $weight to $weight2 Ibs</td>";
                }
                else
                {
                    echo "<td>over $weight Ibs</td>";
                }

                if($weight == 0)
                {
                    echo "<td>free shipping</td>";
                }
                else
                {
                    echo "<td>$$shipping_cost</td>";
                }
                echo "<td>";
                echo "<form method='post' action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "'>";
                echo "<input type='hidden' name='shipping_id' value='$shipping_cost_id'>";
                echo "<button type='submit' name='remove' value='begone'>Remove</button>";
                echo "</form>";
                echo "</td>";
                echo "</tr>";
            }
            echo "</table>";
        ?>

        <br>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            Weight: <input type="text" name="new_weight">
            Cost: <input type="text" name="new_shipping_cost">
            <button type="submit" name="add_button" value="clicked">Add new bracket</button>
        </form>
    </body>
</html>
