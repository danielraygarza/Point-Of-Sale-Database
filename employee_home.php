<?php
// Start the session at the beginning of the file
session_start();

// Check if user is logged in
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
    //access employee attributes
    echo "<h2>Time to work, " . $_SESSION['user']['E_First_Name'] . "!</h2>";
} else {
    //if not logged in, will send to default URL
    header("Location: index.php");
    exit(); //ensures code is killed
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>POS Pizza Employees</title>
        <link rel="stylesheet" href="styles.css">
        <link rel="icon" href="img/pizza.ico" type="image/x-icon">
    </head>
    <body>
        <div class="navbar">
            <a href="index.php">Home</a>
            <!-- <a href="#">Order Now</a>
            <a href="#">Profile</a> -->
            <!-- if user is logged in, logout button will display -->
            <?php
            if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
                echo '<a href="logout.php">Logout</a>';
            }
            ?>
        </div>
        <form action="" method="post">
            <h2>Employee Home Page</h2>

            
        </form>

    </body>
</html>
