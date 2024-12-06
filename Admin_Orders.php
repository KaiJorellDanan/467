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

        table 
        {
            border-collapse: collapse;
            text-align: center;
            font-family: 'Lato', sans-serif;
            table-layout: fixed;
            margin: 20px 0;
            width: 35%; 
            border: 1px solid #89a2b8ff; 
        }

        h1, h2
        {
            margin: 10px 0; 
            font-family: 'Abril Fatface', serif;
            text-align: center; 
        }
        
        th, td 
        {
            border: 1px solid #89a2b8ff; 
            padding: 20px; 
            text-align: center; 
            font-family: 'Lato', sans-serif;
        }

        button 
        {
            padding: 5px 10px;
            font-size: 16px;
            font-family: 'Lato', sans-serif;
            cursor: pointer;
            background-color: #52616eff;  
            color: #ffffff;
        }

        button:hover
        {
            background-color: #6d7c86ff; 
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
                if($result3->rowCount() > 0)
                {
                    echo "<table style='width:80%'>";
                    echo "<th style='background: linear-gradient(to right, red, yellow); color: black;'>Order Details</td>";
                    echo "</table>";
                    echo "<table style='width:80%'>";
                    echo "<th>Picture</th>";
                    echo "<th>Part Number</th>";
                    echo "<th>Description</th>";
                    echo "<th>Order Quantity</th>";
                    echo "<th>Order Weight</th>";
                    echo "<th>Order Price</th>";
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
                            $cost = $row4['price'];

                            $cost = $cost * $customerq;

                            echo "<tr>";
                            echo "<td><img src='$picture' alt='Part Picture' style='width:100px;'></td>";
                            echo "<td>$number</td>";
                            echo "<td>$description</td>";
                            echo "<td>$customerq</td>";
                            echo "<td>$qweight lbs</td>";
                            echo "<td>$$cost</td>";
                            echo "</tr>";
                        }
                    }
                    echo "</table>";
                }
                else
                {
                    echo "<p style='text-align:center'>No Entries Found</p>";
                }
            }
            
            $sql = "SELECT * FROM Orders;";
            $result = $pdo->query($sql);
            // print table headers
            
            // loop to get all of the rows of the table from the select statement
            while ($row = $result->fetch()) 
            {
                echo "<table style='width:100%'>";

                $customer_id = $row['customer_id'];
                $id = $row['order_id'];
                $status = $row['order_status'];
                $date = $row['order_date'];
                $price = $row['price'];
                $weight = $row['order_weight'];

                $total_price = $price;

                $price = number_format($price, 2);
                $weight = number_format($weight, 2);
                
                $sql2 = "SELECT first_name, last_name, email, address FROM Customer
                        WHERE customer_id = '$customer_id';";
                $result2 = $pdo->query($sql2);
                // print table headers
                echo "<tr>"; 
                echo "<th style='background: linear-gradient(to right, red, yellow); color: black;'>Order ID: $id</th>";
                echo "</tr>";
                echo "</table>";
                
                echo "<table style='width:100%'>";
                echo "<tr>";
                echo "<td>Order Status</td>";
                echo "<td>Order Date</td>";
                echo "<td>Order Price</td>";
                echo "<td>Order Weight</td>";
                echo "<td>Shipping Cost</td>";
                echo "<td>Total Cost</td>";
                echo "</tr>";

                echo "<tr>";
                echo "<td>$status</td>";
                echo "<td>$date</td>";
                echo "<td>$$price</td>";
                echo "<td>$weight lbs</td>";
               
                $sql5 = "SELECT shipping_cost 
                         FROM Shipping_cost
                         WHERE weight < '$weight'
                         ORDER BY weight DESC
                         LIMIT 1;";
                $result5 = $pdo->query($sql5);

                $row5 = $result5->fetch();
                $shipping_cost = $row5['shipping_cost'];

                $total_price += $shipping_cost;

                echo "<td>$$shipping_cost</td>";
                echo "<td>$$total_price</td>";
                echo "</tr>";

                echo "</table>";
                // loop to get all of the rows of the table from the select statement
                while ($row2 = $result2->fetch()) 
                {
                    echo "<table style='width:100%'>";
                    echo "<tr>";    
                    echo "<td>Customer Name</td>";
                    echo "<td>Customer Email</td>";
                    echo "<td>Customer Address</td>";
                    echo "<td>Order Details</td>";

                    echo "<tr>";
                    $first_name = $row2['first_name'];
                    $last_name = $row2['last_name'];
                    $email = $row2['email'];
                    $address = $row2['address'];
                    echo "<tr>";
                    echo "<td>$first_name $last_name</td>";
                    echo "<td>$email</td>";
                    echo "<td>$address</td>";
                    echo "<td>";
                    echo "<form method='post' action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "'>";
                    echo "<input type='hidden' name='customer_id' value='$customer_id'>";
                    echo "<button type='submit' name='details' value='clicked'>Details</button>";
                    echo "</form>";
                    echo "</td>";
                    echo "<table style='width:100%'>";
                }
            }
            echo "</table>";
        ?>
    </body>
</html>
