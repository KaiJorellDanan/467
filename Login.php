<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
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

        // Generate a random customer_id if it doesn't already exist in the session
        session_start();

        // Generate a random customer_id if it doesn't already exist in the session
        if (!isset($_SESSION['customer_id'])) 
        {
            $_SESSION['customer_id'] = rand(10,90); // Random INT for customer ID
            $customer_id = $_SESSION['customer_id'];
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST")
        {
            $start_button = $_POST['start'];
        }

        if($start_button == "clicked")
        {
            $sql = "INSERT INTO Customer(customer_id) VALUES
                    ('$customer_id');";
            $pdo->exec($sql);

            header("Location: https://students.cs.niu.edu/~z2003741/476/CustomerSystem.php");
            exit; // Always use exit after a redirect to stop further script execution
        }
    ?>
</head>
<body>
    <h1>Welcome to Our Store</h1>
    <p>Click the button below to start shopping!</p>

    <!-- Button to redirect to CustomerSystem.php -->
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <button type="submit" name="start" value="clicked">Start Shopping</button>
    </form>
</body>
</html>
