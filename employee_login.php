<?php
include 'database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $Employee_ID = $mysqli->real_escape_string($_POST['Employee_ID']);
    $password = $_POST['password'];

    $result = $mysqli->query("SELECT * FROM employee WHERE Employee_ID='$Employee_ID'");

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        if (password_verify($password, $user['password'])) {
            // Successful login
            session_start();
            $_SESSION['loggedin'] = true;
            $_SESSION['user'] = $user;  //assigns all employee attributes inside an array

            //Updates clocked in to true when employee logs in
            $Employee_ID = $user['Employee_ID'];
            $mysqli->query("UPDATE employee SET clocked_in=1 WHERE Employee_ID='$Employee_ID'");


            // Redirect to a logged-in page or dashboard
            header("Location: employee_home.php");
        } else {
            // Password doesn't match
            echo "<h2>Incorrect password</h2>";
            session_start();
            $_SESSION['error'] = "Incorrect password!";
            header("Location: employee_login.php"); 
        }
    } else {
        // User doesn't exist
        echo
        session_start();
        $_SESSION['error'] = "Employee ID not found";
        header("Location: employee_login.php"); 
    }
}
?>

<!DOCTYPE html>
<!-- Employee page. Only validated users will be able to access this page -->
<html>
<head>
    <title>Employee Login</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" href="img/pizza.ico" type="image/x-icon">
</head>
<body>
<?php
    if (isset($_SESSION['error'])) {
        echo '<div id="errorMessage">' . $_SESSION['error'] . '</div>';
        unset($_SESSION['error']);  // Unset the error message after displaying it
    }
    ?>
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
        <input class = button type="submit" value="Login">
        <!-- shift start time begin at submit? shift end when log out? -->
        
    </form> 
    <script>
        window.addEventListener('DOMContentLoaded', (event) => {
            const errorMessage = document.getElementById('errorMessage');
            if (errorMessage) {
                setTimeout(() => {
                    errorMessage.style.opacity = 0;  // Fade out the error message after 2 seconds
                }, 2000);
            }
        });
    </script>

</body>
</html>
