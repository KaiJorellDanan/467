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
        <title>Order Details</title>
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

            if ($_SERVER["REQUEST_METHOD"] == "POST")
            {
                $details = $_POST['details'];
                $cust_id_grabbed = $_POST['customer_id'];
            }
        ?>  
    </head>
    <body>
        <h1>Administration</h1>
        <h2>Car Parts Administration</h2>
        <button onclick="window.location.href='https://students.cs.niu.edu/~z1952360/Admin_Orders.php';">Orders</button>
        <button onclick="window.location.href='https://students.cs.niu.edu/~z1952360/Administration.php';">Weight Brackets</button>
        <h3>List of Orders<h3>

        <?php
            if($details == "clicked")
            {
                $sql3 = "SELECT * FROM Cart
                         WHERE customer_id = '$cust_id_grabbed';";
                $result3 = $pdo->query($sql3);

                // loop to get all of the rows of the table from the select statement
                echo "<table style='width:80%'>";
                echo "<th>Picture</th>";
                echo "<th>Part Number</th>";
                echo "<th>Description</th>";
                echo "<th>Order Quantity</th>";
                echo "<th>Order Weight</th>";
                while ($row3 = $result3->fetch()) 
                {
                    $item_id = $row3['item_id'];
                    $customerq = $row3['customerq'];
                    $qweight = $row3['qweight'];

                    $qweight = number_format($qweight, 2);

                    $sql4 = "SELECT * FROM parts
                             WHERE number = '$item_id';";
                    $result4 = $pdo2->query($sql4);

                    // loop to get all of the rows of the table from the select statement
                    while ($row4 = $result4->fetch()) 
                    {
                        $number = $row4['number'];
                        $description = $row4['description'];
                        $picture = $row4['pictureURL'];

                        echo "<tr>";
                        echo "<td><img src='$picture' alt='Part Picture' style='width:100px;'></td>";
                        echo "<td>$number</td>";
                        echo "<td>$description</td>";
                        echo "<td>$customerq</td>";
                        echo "<td>$qweight</td>";
                        echo "</tr>";
                    }
                }
                echo "</table>";
            }
            
            $sql = "SELECT * FROM Orders;";
            $result = $pdo->query($sql);
            // print table headers
            echo "<table style='width:100%'>";
            
            // loop to get all of the rows of the table from the select statement
            while ($row = $result->fetch()) 
            {
                $customer_id = $row['customer_id'];
                $id = $row['order_id'];
                $status = $row['order_status'];
                $date = $row['order_date'];
                $price = $row['price'];
                $weight = $row['order_weight'];

                $price = number_format($price, 2);
                $weight = number_format($weight, 2);
                
                $sql2 = "SELECT first_name, last_name, email, address FROM Customer
                        WHERE customer_id = '$id';";
                $result2 = $pdo->query($sql2);
                // print table headers
                echo "<tr>"; 
                echo "<th>id: $id</th>";
                echo "</tr>";

                echo "<tr>";
                echo "<td>$status</td>";
                echo "<td>$date</td>";
                echo "<td>$$price</td>";
                echo "<td>$weight</td>";
                
                // loop to get all of the rows of the table from the select statement
                while ($row2 = $result2->fetch()) 
                {
                    $first_name = $row2['first_name'];
                    $last_name = $row2['last_name'];
                    $email = $row2['email'];
                    $address = $row2['address'];

                    echo "<td>$first_name $last_name</td>";
                    echo "<td>$email</td>";
                    echo "<td>$address</td>";
                    echo "<td>";
                    echo "<form method='post' action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "'>";
                    echo "<input type='hidden' name='customer_id' value='$customer_id'>";
                    echo "<button type='submit' name='details' value='clicked'>Details</button>";
                    echo "</form>";
                    echo "</td>";
                }
                echo "</tr>";
            }
            echo "</table>";
        ?>
    </body>
</html>
