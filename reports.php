<?php 
    // // Check if the user is not logged in
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
        <link rel="stylesheet" href="css/styles.css">
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
        
        <div>
            <label for="reportType">Select a Report:</label>
            <select name="reportType" id="reportType" onchange="showOptions()">
                <option value=""selected disabled>Select a Report</option>
                <option value="inventory">Inventory Reports</option>
                <option value="store">Store Reports</option>
                <option value="sales">Sales Report</option>
                <option value="performance">Employee Performance Report</option>
            </select>
        </div> <br>
        
        <div id="inventoryOptions" style="display: none;">
            <!-- Inventory Report sub-options here -->
                <label for="inventoryType">Select Inventory Report Type:</label>
                <select name="inventoryType" id="inventoryType">
                    <option value="all">All Stock</option>
                    <option value="low">Low Stock</option>
                    <option value="out">Out of Stock</option>
                </select>
        </div><br>

        <div id="storeOptions" style="display: none;">
            <!-- Store Report sub-options here -->
                <label for="storeType">Select Store Report Type:</label>
                <select name="storeType" id="storeType">
                    <option value="orders">Daily Orders</option>
                    <option value="pizzas">Daily Pizzas Sold</option>
                    <option value="popular">Today's Most Popular Pizza</option>
                    <option value="sales">Total Sales Today</option>
                    <option value="date">Total Sales To Date</option>
                </select>
        </div><br>

        <input type="submit" class = "button" value="Generate Report">
        
    </form> 

        <script>
            function showOptions() {
                var reportType = document.getElementById('reportType');
                var inventoryOptions = document.getElementById('inventoryOptions');
                var storeOptions = document.getElementById('storeOptions');

                if (reportType.value === 'inventory') {
                    inventoryOptions.style.display = 'block';
                    storeOptions.style.display = 'none';
                } else if (reportType.value === 'store') {
                    inventoryOptions.style.display = 'none';
                    storeOptions.style.display = 'block';
                } else {
                    inventoryOptions.style.display = 'none';
                    storeOptions.style.display = 'none';
                }
            }
        </script>
    </body>
</html>