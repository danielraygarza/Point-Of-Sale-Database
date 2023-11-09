<?php
include 'database.php'; // Include the database connection details
session_start();

//limits access to page
if (isset($_SESSION['selected_store_id'])) {
    $store_id = $_SESSION['selected_store_id'];
} else {
    header('Location: checkout.php');
    exit;
}
?>

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
        <?php
        if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
            echo '<a href="update_profile.php">Profile</a>';
        } else {
            echo '<a href="customer_login.php">Login</a>';
        }
        ?>
    </div>

    <form action="" method="post">
        <h2>Thank you, we hope your enjoy your POS Pizza!</h2>
        <?php 
            //display members store credit if any
            $customerID = $_SESSION['user']['customer_id'];
            $query = "SELECT store_credit FROM customers WHERE customer_id = '$customerID'";
            $result = $mysqli->query($query); 
            if ($result && $row = $result->fetch_assoc()) {
                $_SESSION['user']['store_credit'] = $row['store_credit'];
            }
            $store_credit = $_SESSION['user']['store_credit'];
            if ($store_credit > 0) {
                echo "<h2>Store credit: $store_credit</h2>";
            }
        ?>

        <a href="menu.php" class="button">Place Another Order</a>

        <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) { ?>
            <a href="logout.php" class="button">Logout</a>
        <?php } ?>
    </form>

    <?php if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin'] == true) { ?>
        <br>
        <form>
            <h2>Sign up up to join our rewards program!</h2>
            <a href="signup.php" class="button">Sign Up Here</a>
        </form>
    <?php } ?>
</body>

</html>