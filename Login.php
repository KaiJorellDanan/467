<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <style>
        body 
        {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh; 
            margin: 0;
            background-image: url(https://wallpapercave.com/wp/wp2757874.gif);
            background-position: center;
            background-repeat:center;
            background-size: 100%;
            color: #ffffff;
        }
        .boarder
        {
            border: 5px solid #000;
            border-radius: 10px;
            padding: 100px;
            text-align: center;
            background color: #000;
        }
        h1 
        {
            font-size: 3em;
            margin-bottom: 20px;
        }
        p 
        {
            font-size: 1.5;
            margin-bottom: 20px;
        }
        button 
        {
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            background-color: #2471a3;
        }
        button:hover
        {
            background-color: #ebf5fb;
        }
    </style>
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

        $sql = "SELECT * FROM Customer;";
        $result = $pdo->query($sql);

        session_start();
        $_SESSION['customer_id'] = $result->rowCount()+1;
        $customer_id = $_SESSION['customer_id'];

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
    <div class="boarder">
    <h1>Welcome to Our Store 🛒</h1>
    <p>Click the button below to start shopping!</p>

    <!-- Button to redirect to CustomerSystem.php -->
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <button type="submit" name="start" value="clicked">Start Shopping</button>
    </form>
    <div>
</body>
</html>
