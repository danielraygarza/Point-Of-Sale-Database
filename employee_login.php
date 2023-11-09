<?php
include 'database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $Employee_ID = $mysqli->real_escape_string($_POST['Employee_ID']);
    $password = $_POST['password'];

    $result = $mysqli->query("SELECT * FROM employee WHERE Employee_ID='$Employee_ID'");

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        if (password_verify($password, $user['password'])) {
            session_start();
            $_SESSION['user'] = $user;  //assigns all employee attributes inside an array
            
            // Check if employee is active
            if ($user['active_employee'] == '0') {
                echo "";
                $_SESSION['error'] = "This account has been disabled. Please contact your manager.";
            } else {
                // Successful login
                 $_SESSION['loggedin'] = true;
                 
                 //Updates clocked in to true when employee logs in
                 $Employee_ID = $user['Employee_ID'];
                 $mysqli->query("UPDATE employee SET clocked_in=1 WHERE Employee_ID='$Employee_ID'");
                 
                 
                 // Redirect to a employee page
                 header("Location: employee_home.php");
            }
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
        $_SESSION['error'] = "Employee ID not found";
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Employee Login</title>
        <link rel="stylesheet" href="css/styles.css">
        <link rel="icon" href="img/pizza.ico" type="image/x-icon">
    </head>
    <body>
        <!-- Navbar -->
        <div class="navbar">
            <a href="index.php">Home</a>
            <!-- <a href="#">Order Now</a>
            <a href="#">Profile</a> -->
        </div>

        <form action="employee_login.php" method="post">
            <h2>Login to your Employee Account</h2>
            <div>
                <label for="username">Employee ID  </label>
                <input 
                    type="text" 
                    id="Employee_ID" 
                    name="Employee_ID"
                    placeholder="Enter employee ID"
                    required>
            </div>
            <br>
            <div>
                <label for="password">Password  </label>
                <input 
                    type="password" 
                    id="password"
                    name="password"
                    placeholder="Enter password"
                    required>
            </div>
            <br>
        
            <?php
                //displays error messages here 
                if (isset($_SESSION['error'])) {
                    echo '<div id="errorMessage">' . $_SESSION['error'] . '</div>';
                    unset($_SESSION['error']);  // Unset the error message after displaying it
                }
            ?>

            <input class = button type="submit" value="Login">
            
        </form> 

    </body>
</html>
