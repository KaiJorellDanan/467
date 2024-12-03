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
            height: 100vh; 
            margin: 0;
            background-image: url(https://wallpapercave.com/wp/wp10596294.jpg);
            background-position: center;
            background-repeat: no-repeat;
            background-size: 110%;
            color: #ee4b2b;
        }
        .group
        {
            padding: 100px;
            text-align: left;
            margin: 20px
        }
        h1 
        {
            font-size: 85px;
            font-family: 'Oswald', sans-serif;
            margin-bottom: 20px;
        }
        p 
        {
            font-size: 16px;
            font-family: 'Roboto', sans-serif;
            margin-bottom: 20px;
        }
        button 
        {
            padding: 10px 20px;
            font-size: 16px;
            font-family: 'Roboto', sans-serif;
            cursor: pointer;
            background-color: #ee4b2b;
        }
        button:hover
        {
            background-color: #ebf5fb;
        }
        .gifBox
        {
            position: absolute;
            top: 200px;
            right: 50px;
            text-align: center;
        }
        .topGif 
        {
            width: 500px; 
            height: auto;
        }
        .gifText 
        {
            font-size: 18px;
            font-family: 'Roboto', sans-serif;
            color: #ee4b2b;
            margin-top: 10px;
        }
        .footer
        {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background-color: #16171f;
            text-align: center;
            padding: 5px 0;
            color: #ee4b2b;
            display: flex;                
            justify-content: space-evenly;  
            gap: 25px;
        }
        .footer p
        {
            display: inline-block;
            font-size: 20px;
            font-family: 'Roboto', sans-serif;
        }
        .footer p.one
        {
            font-size: 20px;
            font-family: 'Roboto', sans-serif;
        }
        .footer p.two
        {
            font-size: 20px;
            font-family: 'Roboto', sans-serif;
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
    <div class="group">
    <h1>Welcome To RevTech Garage</h1>
    <p>Browse through over 100 different parts and vehicles!</p>

    <!-- Button to redirect to CustomerSystem.php -->
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <button type="submit" name="start" value="clicked">Explore More</button>
    </form>
    <div>

    <div class="gifBox">
        <img src="https://media.ford.com/content/dam/fordmedia/North%20America/US/2017/09/21/HoloLensLoop.gif" alt="Image" class="topGif">
        <p class="gifText">RevTech Garage any part for any vehicle!</p>
    </div>

    <div class="footer">
        <p>🚚💨Free Shipping and Handling</p> 
        <p class="one">🤝 24/7 Customer Support</p>
        <p class="two">🔙Easy Returns</p>
    </div>
</body>
</html>
