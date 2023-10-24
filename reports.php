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

        <label for="reportType">Select a Report:</label>
        <select name="reportType" id="reportType" onchange="showInventoryOptions()">
            <option value=""selected disabled>Select a Report</option>
            <option value="inventory">Inventory Report</option>
            <option value="onclock">On-Clock Report</option>
            <option value="sales">Sales Report</option>
            <option value="performance">Employee Performance Report</option>
        </select>

        <div id="inventoryOptions" style="display: none;">
                <label for="inventoryType">Select Inventory Type:</label>
                <select name="inventoryType" id="inventoryType">
                    <option value="all">All Stock</option>
                    <option value="low">Low Stock</option>
                    <option value="out">Out of Stock</option>
                </select>
            </div>

        <input type="submit" value="Generate Report">
        
    </form> 

        <script>
            function showReportHeader(reportType){
                var reportHeader = document.getElementById('reportHeader'); // variable for header
                if (reportType.value === 'inventory') {
                    //inventoryOptions.style.display = 'block';

                    //Determine selected inventoryType
                    var inventoryType = document.getElemntById('inventoryType').value;

                    //Define report header text to display
                    var reportHeaderText = '';
                    if(inventoryType === 'all'){
                        reportHeaderText = 'All Stock Items Report';
                    } else if (inventoryType === 'low'){
                        reportHeaderText = 'Low Stock Items Report';
                    } else if (inventoryType === 'out'){
                        reportHeaderText = 'Out of Stock Items Report';
                    }

                    //Update the report header text
                    reportHeader.textContent = reportHeaderText;

                } else if (reportType === 'sales') {
                    reportHeaderText = 'Sales Report';

                    //Update the report header text
                    reportHeader.textContent = reportHeaderText;
                } else{
                    //Test
                    reportHeaderText = 'Blank';
                    reportHeader.textContent = reportHeaderText;
                }
            }
            function showInventoryOptions() {
                var reportType = document.getElementById('reportType');
                var inventoryOptions = document.getElementById('inventoryOptions');
                var reportHeader = document.getElementById('reportHeader'); // variable for header

                if (reportType.value === 'inventory') {
                    inventoryOptions.style.display = 'block';
                    showReportHeader(reportType); //set header

                    /*
                    //Made a separate function for setting the header
                    //Determine selected inventoryType
                    var inventoryType = document.getElemntById('inventoryType').value;

                    //Define report header text to display
                    var reportHeaderText = '';
                    if(inventoryType === 'all'){
                        reportHeaderText = 'All Stock Items Report';
                    } else if (inventoryType === 'low'){
                        reportHeaderText = 'Low Stock Items Report';
                    } else if (inventoryType === 'out'){
                        reportHeaderText = 'Out of Stock Items Report';
                    }

                    //Update the report header text
                    reportHeader.textContent = reportHeaderText;
                    */
                } else {
                    inventoryOptions.style.display = 'none';
                    showReportHeader(reportType); //set header for default
                }
            }
        </script>
    </body>
</html>