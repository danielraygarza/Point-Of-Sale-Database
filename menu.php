<?php
    session_start(); //continues current session to keep user logged in
    //dont have to be logged in to access this page so guest can still order
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
        <a href="menu.php">Order now</a>
        <!-- <a href="#">Profile</a> -->
        <?php
            // in nav bar, shows log out or log in
            if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
                echo '<a href="logout.php">Logout</a>';
            } else {
                echo '<a href="customer_login.php">Log in</a>';
            }
        ?>
    </div>

    <form action="" method="post">
            <h2>Menu</h2>

            
        </form>

</body>
</html>