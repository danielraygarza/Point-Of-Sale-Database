<?php
    // Start the session at the beginning of the file
    include 'database.php';
    session_start();

    // Check if user is logged in
    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
        //access customer attributes
        echo "<h2>Welcome, " . $_SESSION['user']['first_name'] . "!</h2>";
    } else {
        //if not logged in, will send to default URL
        header("Location: index.php");
    }
?>

<!-- Page after user logs in -->
<!DOCTYPE html>
<html>
    <head>
        <title>POS Pizza</title>
        <link rel="stylesheet" href="css/styles.css">
        <link rel="icon" href="img/pizza.ico" type="image/x-icon">
    </head>
    <body>
        <div class="navbar">
            <a href="index.php">Home</a>
            <a href="menu.php">Order now</a>
            <?php
            if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
                echo '<a href="update_customer.php">Profile</a>';
                echo '<a href="logout.php">Logout</a>';
            } 
            ?>
            
        </div>

        <?php 
            $userID = $_SESSION['user']['customer_id'];
            $totalSpent = $mysqli->query("SELECT total_spent_toDate FROM customers WHERE customer_id = $userID");
            $getTotalSpent = $totalSpent->fetch_assoc();

            $storeCredit = $mysqli->query("SELECT store_credit FROM customers WHERE customer_id = $userID");
            $getStoreCredit = $storeCredit->fetch_assoc();
        ?>

        <div class = "customerinfo">
            <p class = "triggerDisplay">TIP: For every $100 you spend you will get $10 in store credit!</p> 
            <div class = "totalspent">Total Amount Spent To Date: $<?php echo $getTotalSpent["total_spent_toDate"]; ?></div>
            <div class = "storecredit">Store Credit Available: $<?php echo $getStoreCredit["store_credit"]; ?> </div>
            <div class = "remainingNeeded">You need to spend a total of  <p class = "needed"> $<?php echo 100 - $getTotalSpent["total_spent_toDate"]; ?> </p>  more to get store credit!</div>
        </div>
        <a href="menu.php" class="button">Order now!</a>

    </body>
</html>
