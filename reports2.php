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

    /////////
    //TEST//
    /////////

    // Set the default header
    $setHeader = "TEST";
    /////////
    /////////

    //$setHeader = ''; // Creates the variable to set Report Header on generate_report.php
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
        <!-- Coninue here -->
        <div id="setHeader"><?php echo $setHeader; ?></div>
        
        <div>
            <label for="reportType">Select a Report:</label>
            <select name="reportType" id="reportType" onchange="showInventoryOptions()">
                <option value=""selected disabled>Select a Report</option>
                <option value="inventory">Inventory Report</option>
                <option value="onclock">On-Clock Report</option>
                <option value="sales">Sales Report</option>
                <option value="performance">Employee Performance Report</option>
            </select>
        </div> <br>
        
        <div id="inventoryOptions" style="display: none;">
                <label for="inventoryType">Select Inventory Type:</label>
                <select name="inventoryType" id="inventoryType">
                    <option value="all">All Stock</option>
                    <option value="low">Low Stock</option>
                    <option value="out">Out of Stock</option>
                </select>
        </div>
        <div id="Employer" style="display: none;">
                <label for="employeeDropdown">Select Employee:</label>
                <select name="employeeDropdown" id="employeeDropdown">
                <?php
                    include_once("./include/function/getEmplyee.php");
                    $employeeData = getEmployeeData();

                    foreach ($employeeData as $employee) {
                        $employeeID = $employee['Employee_ID'];
                        $employeeName = $employee['Name'];
                        echo "<option value='" . htmlspecialchars($employeeID) . "'>$employeeName</option>";
                    }
                    ?>
                </select>
        </div><br>
        
        <!-- Add a hidden input field to pass the setHeader variable -->
        <input type="hidden" name="setHeader" value="<?php echo $setHeader; ?>">

        <input type="submit" class = "button" value="Generate Report">
        
    </form> 

        <script>
            document.getElementById('employeeDropdown').addEventListener('change', function() {
                var selectedEmployeeId = this.value;
                if (selectedEmployeeId != 0) {
                    var url = "./include/function/genereateEmployReport.php?action=generateReport&id=" + selectedEmployeeId;
                    window.location.href = url;
                }
            });
            function showInventoryOptions() {
                var reportType = document.getElementById('reportType');
                var inventoryOptions = document.getElementById('inventoryOptions');
                var headerVar = document.getElementById('setHeader');
                var Employer = document.getElementById('Employer');

                if (reportType.value === 'inventory') {
                    Employer.style.display = 'none';
                    inventoryOptions.style.display = 'block';
                    headerVar = "Inventory Report";
                }
                else if (reportType.value === 'performance') {
                    Employer.style.display = 'block';
                    inventoryOptions.style.display = 'none';
                    headerVar = "Employee performance report";
                }
                else {
                    inventoryOptions.style.display = 'none';
                }
            }
           
        </script>
    </body>
</html>