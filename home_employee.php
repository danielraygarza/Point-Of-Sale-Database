<?php 
    // session_start();
    // // Check if the user is not logged in
    // if (!isset($_SESSION['first_name'])) {
    //     // Redirect them to the login page
    //     header('Location: login.php');
    //     exit(); // stop the further execution of the script
    // } 
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
    </div>

    <?php 
    // Check if the first_name is set in the session (or retrieve it from your database if needed)
    if (isset($_SESSION['first_name'])) {
        echo "<h2>Welcome back, " . $_SESSION['first_name'] . "!</h2>";
    } else {
        echo "<h2>Welcome employees</h2>";
    }
    ?>

</body>
</html>
