<?php 
    /*
    session_start();
    include 'database.php'; // Include the database connection details
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    // Redirects if not manager or accessed directly via URL
    if (!isset($_SESSION['user']['Title_Role']) || $_SESSION['user']['Title_Role'] !== 'MAN') {
        //if not logged in, will send to default URL
        header("Location: employee_login.php");
        exit(); //ensures code is killed
    }
    // */
?>
<!-- Welcome page after user creates new account -->
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
            <a href="employee_home.php">Employee Home</a>
            <?php
                //shows logout button if logged in
                if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
                    echo '<a href="logout.php">Logout</a>';
                }
            ?>
            
            
        </div>
        
    <form action="generate_report.php" method="post">
        <h2>Reports</h2>

        <label for="reportType">Select a Report </label>
        <select name="reportType" id="reportType">
            <option value="" selected disabled>Report Type</option>
            <option value="inventory">Inventory Report</option>
            <option value="onclock">On-Clock Report</option>
            <option value="sales">Sales Report</option>
            <option value="performance">Employee Performance Report</option>
        </select>
        <input type="submit" value="Generate Report">
        
    </form> 

    </body>
</html>