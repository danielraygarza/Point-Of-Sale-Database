<?php 
    // // Check if the user is not logged in
    // if (!isset($_SESSION['first_name'])) {
    //     // Redirect them to the login page
    //     header('Location: login.php');
    //     exit();
    // } 
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
            
        </div>
        
    <form action="reports.php" method="post">
        <h2>Reports Page</h2>

        <label for="reportType">Select a Report:</label>
        <select name="reportType" id="reportType">
            <option value="inventory">Inventory Report</option>
            <option value="onclock">On-Clock Report</option>
            <option value="sales">Sales Report</option>
            <option value="performance">Employee Performance Report</option>
        </select>
        <input type="submit" value="Generate Report">
        
    </form> 

    </body>
</html>