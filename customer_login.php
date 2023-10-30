<?php
include 'database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $mysqli->real_escape_string($_POST['email']);
    $password = $_POST['password'];

    $result = $mysqli->query("SELECT * FROM customers WHERE email='$email'");

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        if (password_verify($password, $user['password'])) {
            // Successful login
            session_start();
            $_SESSION['loggedin'] = true;
            // $_SESSION['first_name'] = $user['first_name']; individually assign atrributes
            $_SESSION['user'] = $user;  //assigns all customer attributes inside an array

            // Redirect to a logged-in page or dashboard
            header("Location: home.php");
        } else {
            // Password doesn't match
            echo "";
            session_start();
            $_SESSION['error'] = "Incorrect password!";
        }
    } else {
        // User doesn't exist
        echo "";
        session_start();
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
        <a href="checkout.php" id="cart-button">Cart (<?php echo getCartItemCount(); ?>)</a>
    </div>  

<form action="customer_login.php" method="post">
    <h2>Login to your POS Pizza Account</h2>
    <div>
        <label for="email">Email address  </label>
        <input 
            type="text" 
            id="email" 
            name="email"
            placeholder="Enter email"
            required>
    </div> <br>

    <div>
        <label for="password">Password  </label>
        <input
            type="password" 
            id="password"
            name="password"
            placeholder="Enter password"
            required>
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
      <p><a href="menu.php.php">Continue as guest</a></p>
    </div>
</form> 

</body>
</html>