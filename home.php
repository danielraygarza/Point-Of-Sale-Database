<?php
    // Start the session at the beginning of the file
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
        <link rel="stylesheet" href="styles.css">
        <link rel="icon" href="img/pizza.ico" type="image/x-icon">
    </head>
    <body>
        <div class="navbar">
            <a href="index.php">Home</a>
            <a href="menu.php">Order now</a>
            <!-- <a href="#">Profile</a> -->
            <?php
            if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
                echo '<a href="logout.php">Logout</a>';
            }
            ?>
        </div>

        <a href="menu.php" class="button">Order now!</a>

    </body>
</html>
