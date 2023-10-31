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
    function getEmployeeData() {
        include_once("./database.php"); 
        $sql = "SELECT `Employee_ID`, `E_First_Name`, `E_Last_Name` FROM `employee`";
        $result = mysqli_query($mysqli, $sql);

        if (!$result) {
            die("Error: " . mysqli_error($connection));
        }

        $employeeData = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $employeeData[] = [
                'Employee_ID' => $row['Employee_ID'],
                'Name' => $row['E_First_Name'] . ' ' . $row['E_Last_Name'],
            ];
        }
        mysqli_free_result($result);
        return $employeeData;
    }
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


        <!-- //Copy the format from here// -->
        <!-- This creates the sub-menu once you've selected the main category -->
        <!-- Here you also set your id that you will reference in the function below to make this menu visible -->
        <div id="storeOptions" style="display: none;">
            <!-- Store Report sub-options here -->
                <label for="storeType">Select Store Report Type:</label>
                <!-- Here you set your id that you'll reference on generate_report.php -->
                <!-- This will tell the page which sub report you want to run -->
                <select name="storeType" id="storeType">
                    <!-- Here are the different options you can display in your sub menu -->
                    <!-- The value is how it will be referenced on generate_report.php and the text to the right is what appears in the drop down menu -->
                    <option value="orders">Daily Orders</option>
                    <option value="pizzas">Daily Pizzas Sold</option>
                    <option value="popular">Today's Most Popular Pizza</option>
                    <option value="sales">Total Sales Today</option>
                    <option value="date">Total Sales To Date</option>
                </select>
        </div><br>
        <!-- //To here// -->

        <!-- Add more drop down sub-menus here -->

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
            //This function makes the sub-menu appear depending on what's selected
            function showOptions() {
                //This reads which main report group is currently selected
                var reportType = document.getElementById('reportType');

                //If you add a new sub menu, define it here then refence it by it's id like so:
                var inventoryOptions = document.getElementById('inventoryOptions');
                var storeOptions = document.getElementById('storeOptions');
                var Employer = document.getElementById('Employer');

                //This if/else determines which sub menu is visible
                //To set a new one visible, set reportType === 'newMenu'
                //Then newMenuOptions.style.display = 'block'
                //Be sure to set the other sub menus to 'none' and add in your new menu to the other sub menu categories and set it to 'none'
                if (reportType.value === 'inventory') {
                    inventoryOptions.style.display = 'block';
                    storeOptions.style.display = 'none';
                    // newMenuOptions.style.display = 'none'
                } else if (reportType.value === 'store') {
                    inventoryOptions.style.display = 'none';
                    storeOptions.style.display = 'block';
                } else if (reportType.value === 'performance') {
                    Employer.style.display = 'block';
                    inventoryOptions.style.display = 'none';
                    headerVar = "Employee performance report";
                }
                else {
                    inventoryOptions.style.display = 'none';
                    storeOptions.style.display = 'none';
                }
            }
        </script>
    </body>
</html>