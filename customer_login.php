<?php
// login page for customers

include 'database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // reads given email and passwod
    $email = $mysqli->real_escape_string($_POST['email']);
    $password = $_POST['password'];

    // finds customer with inputted email
    $result = $mysqli->query("SELECT * FROM customers WHERE email='$email'");

    // if email found, check if password matches
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // if users password and inputted password match, log customer in
        if (password_verify($password, $user['password'])) {
            session_start(); //begins session
            $_SESSION['loggedin'] = true; // mark customer as logged in
            $_SESSION['user'] = $user;  // assigns all customer attributes inside an array

            // Redirect to home page
            header("Location: home.php");
        } else {
            // Password doesn't match
            $_SESSION['error'] = "Incorrect password!";
        }
    } else {
        // User doesn't exist
        $_SESSION['error'] = "Email not found";
    }
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
        <a href="employee_login.php">Employee Home</a>
    </div>

    <form action="customer_login.php" method="post">
        <h2>Login to your POS Pizza Account</h2>
        <div>
            <label for="email">Email address </label>
            <input type="text" id="email" name="email" placeholder="Enter email" required>
        </div> <br>

        <div>
            <label for="password">Password </label>
            <input type="password" id="password" name="password" placeholder="Enter password" required>
        </div> <br>

        <?php
        //displays error messages here 
        if (isset($_SESSION['error'])) {
            echo '<div id="errorMessage">' . $_SESSION['error'] . '</div>';
            unset($_SESSION['error']);  // Unset the error message after displaying it
        }
        ?>

        <input class="button" type="submit" value="Login">

        <a href="signup.php" class="button">Sign up</a>

        <div>
            <p><a href="menu.php?guest=1">Continue as guest</a></p>
        </div>
    </form>

</body>

</html>