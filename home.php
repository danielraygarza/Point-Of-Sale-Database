<?php
// home page for customers displayed after login. displays total spent and store credit

// Start the session at the beginning of the file
session_start();
include 'database.php';

// Check if user is logged in
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
    // greet customer if logged in
    echo "<h2>Welcome, " . $_SESSION['user']['first_name'] . "!</h2>";
} else {
    //if not logged in, will send to default URL
    header("Location: index.php");
}
?>

<!-- Page after user logs in  -->
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
    // get store credit info from current customer
    $userID = $_SESSION['user']['customer_id'];
    $queryCustomer = $mysqli->query("SELECT total_spent_toDate, store_credit FROM customers WHERE customer_id = $userID");
    $customerInfo = $queryCustomer->fetch_assoc();
    ?>

    <!-- Display store credit info to customer -->
    <div class="customerinfo">
        <p class="triggerDisplay">TIP: For every $100 you spend you will get $10 in store credit!</p>
        <div class="totalspent">Total Amount Spent To Date: $<?php echo $customerInfo["total_spent_toDate"]; ?></div>
        <div class="storecredit">Store Credit Available: $<?php echo $customerInfo["store_credit"]; ?> </div>
        <div class="remainingNeeded">You need to spend a total of <p class="needed">$<?php echo number_format((float)100 - fmod($customerInfo["total_spent_toDate"], 100), 2, '.', ''); ?> </p> more to get store credit!</div>
        <a href="menu.php" style="width: 125px; margin-left: auto; margin-right: auto;" class="button">Order now!</a>
    </div>

</body>

</html>