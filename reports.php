<?php
// // Check if the user is not logged in
    session_start();
    include 'database.php'; // Include the database connection details
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    // Redirects if not manager or accessed directly via URL
    // if (!isset($_SESSION['user']['Title_Role']) || ($_SESSION['user']['Title_Role'] !== 'CEO' && $_SESSION['user']['Title_Role'] !== 'MAN')) {
    //     header("Location: employee_login.php");
    //     exit; // Make sure to exit so that the rest of the script won't execute
    // }

    //TO DO://
    // ADD DATE RANGE SELECTOR FOR SPECIFIC DATE REPORTS
    // $stDate and $endDate ARE THE VARIABLES FOR DATE RANGE SELECTOR
    // FIX BUG THAT ALLOWS NOTHING TO BE SELECTED AND STILL HAVE A REPORT GENERATED

    //Daniel: altered function above to not include "database.php" inside function. 
    // it was causing continuity errors. database.php included is at top of file
    function getEmployeeData($mysqli) {
        $sql = "SELECT `Employee_ID`, `E_First_Name`, `E_Last_Name` FROM `employee`";
        $result = $mysqli->query($sql);
    
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $employeeData[] = [
                    'Employee_ID' => $row['Employee_ID'],
                    'Name' => $row['E_First_Name'] . ' ' . $row['E_Last_Name'],
                ];
            }
            $result->free();
        } 
        return $employeeData;
    }
    

// Get a list of Store Ids from Pizza_Store table as array $storeIdData
// Function may be fucked, won't generate properly, breaks page
// function getStoreID(){
//     $sql = "SELECT `Pizza_Store_ID` FROM `pizza_store`";
//     $result = mysqli_query($mysqli, $sql);

//     if(!$result){
//         die("Error: " . mysqli_error($connection));
//     }

//     $storeIdData = array();
//     while($row = mysqli_fetch_assoc($result)){
//         $storeIdData[] = [
//             'Pizza_Store_ID' => $row['Pizza_Store_ID'],
//         ];
//         mysqli_free_result($result);
//         return $storeIdData;
//     }
// }

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
            <select name="reportType" id="reportType" onchange="showOptions()" required>
                <option value="" selected disabled>Select a Report</option>
                <option value="inventory">Inventory Reports</option>
                <option value="store">Store Reports</option>
                <!-- <option value="sales">Sales Report</option> -->
                <option value="performance">Employee Performance Report</option>
            </select>
        </div> <br>

        <div id="storeSelection" style="display: none;">
            <label for="storeId">Select Store:</label>
            <select name="storeId" id="storeId">
                <!-- <option value="test">Default</option> -->
                <option value="" selected disabled>Select Store</option>
                <?php
                    $stores = $mysqli->query("SELECT * FROM pizza_store");
                    if ($stores->num_rows > 0) {
                        while($row = $stores->fetch_assoc()) {
                            //if ($row["Pizza_Store_ID"] != 1) { continue; } //only shows store ID 1. can delete to show all
                            echo '<option value="' . $row["Pizza_Store_ID"] . '" ' . $selected . '>' . $row["Store_Address"] . ' - ' . $row["Store_City"] . '</option>';
                        }
                    }
                ?>
            </select>

        </div><br>

        <div id="inventoryOptions" style="display: none;">
            <!-- Inventory Report sub-options here -->
            <label for="inventoryType">Select Inventory Report Type:</label>
            <select name="inventoryType" id="inventoryType">
                <option value="" selected disabled>-</option>
                <option value="all">All Stock</option>
                <option value="low">Low Stock</option>
                <option value="out">Out of Stock</option>
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
            <select name="storeType" id="storeType" onchange="dateOptions()">
                <!-- Here are the different options you can display in your sub menu -->
                <!-- The value is how it will be referenced on generate_report.php and the text to the right is what appears in the drop down menu -->
                <option value="" selected disabled>-</option>
                <option value="orders">Daily Orders</option>
                <option value="orderdates">Total Orders From:</option>
                <!-- <option value="pizzas">Daily Pizzas Sold</option> -->
                <option value="popular">Today's Most Popular Item</option>
                <option value="sales">Total Sales Today</option>
                <option value="date">Total Sales From:</option>
            </select>
        </div><br>
        <!-- //To here// -->

        <div id="startDateOptions" style="display: none;">
            <label for="start_year">Start Year:</label>
            <select id="start_year" name="start_year" onchange="dateOptions()">
                <option value="" selected disabled>-</option>
                <option value="2022">2022</option>
                <option value="2023">2023</option>
            </select>

            <label for="start_month">Start Month:</lable>
            <select id="start_month" name="start_month" onchange="dateOptions()">
                <option value="" selected disabled>-</option>
                <option value="01">January</option>
                <option value="02">February</option>
                <option value="03">March</option>
                <option value="04">April</option>
                <option value="05">May</option>
                <option value="06">June</option>
                <option value="07">July</option>
                <option value="08">August</option>
                <option value="09">September</option>
                <option value="10">October</option>
                <option value="11">November</option>
                <option value="12">December</option>
            </select>
            
            <label for="start_day">Start Day:</label>
            <select id="start_day" name="start_day">
                <option value="" selected disabled>-</option>
                <!-- Should be populated by function dateOptions -->
            </select>
                
        </div><br>

        <div id="endDateOptions" style="display: none;">
            <label for="end_year">Start Year:</label>
            <select id="end_year" name="end_year" onchange="dateOptions()">
                <option value="" selected disabled>-</option>
                <option value="2022">2022</option>
                <option value="2023">2023</option>
            </select>

            <label for="end_month">Start Month:</lable>
            <select id="end_month" name="end_month" onchange="dateOptions()">
                <option value="" selected disabled>-</option>
                <option value="01">January</option>
                <option value="02">February</option>
                <option value="03">March</option>
                <option value="04">April</option>
                <option value="05">May</option>
                <option value="06">June</option>
                <option value="07">July</option>
                <option value="08">August</option>
                <option value="09">September</option>
                <option value="10">October</option>
                <option value="11">November</option>
                <option value="12">December</option>
            </select>
            
            <label for="end_day">Start Day:</label>
            <select id="end_day" name="end_day">
                <option value="" selected disabled>-</option>
                <!-- Should be populated by function dateOptions -->
            </select>
                
        </div><br>

        <!-- Add more drop down sub-menus here -->
        <div id="Employer" style="display: none;">
            <label for="employeeDropdown">Select Employee:</label>
            <select name="employeeDropdown" id="employeeDropdown">
                <option value="" selected disabled>-</option>
                <?php
                $employeeData = getEmployeeData($mysqli);
                foreach ($employeeData as $employee) {
                    $employeeID = $employee['Employee_ID'];
                    $employeeName = $employee['Name'];
                    echo "<option value='" . htmlspecialchars($employeeID) . "'>$employeeName</option>";
                }
                ?>
            </select>
        </div><br>

        <input type="submit" class="button" value="Generate Report">
    </form>

    <script>
        document.getElementById('employeeDropdown').addEventListener('change', function() {
            var selectedEmployeeId = this.value;
            if (selectedEmployeeId != 0) {
                // var url = "./include/function/genereateEmployReport.php?action=generateReport&id=" + selectedEmployeeId;
                window.location.href = url;
            }
        });
        //This function makes the sub-menu appear depending on what's selected
        function showOptions() {
            //This reads which main report group is currently selected
            var reportType = document.getElementById('reportType');
            
            // Resets dropdowns when you change report type
            storeId.value = ""; 
            inventoryType.value = "";
            storeType.value = "";
            employeeDropdown.value = "";
            start_year.value = "";
            start_month.value = "";
            start_day.value = "";

            //If you add a new sub menu, define it here then refence it by it's id like so:
            var inventoryOptions = document.getElementById('inventoryOptions');
            var storeOptions = document.getElementById('storeOptions');
            var Employer = document.getElementById('Employer');
            var storeSelection = document.getElementById('storeSelection');
            var startDateOptions = document.getElementById('startDateOptions');


            //This if/else determines which sub menu is visible
            //To set a new one visible, set reportType === 'newMenu'
            //Then newMenuOptions.style.display = 'block'
            //Be sure to set the other sub menus to 'none' and add in your new menu to the other sub menu categories and set it to 'none'
            if (reportType.value === 'inventory') {
                inventoryOptions.style.display = 'block';
                storeOptions.style.display = 'none';
                Employer.style.display = 'none';
                storeSelection.style.display = 'block';
                startDateOptions.style.display = 'none';
                // newMenuOptions.style.display = 'none'

            } else if (reportType.value === 'store') {
                inventoryOptions.style.display = 'none';
                storeOptions.style.display = 'block';
                storeSelection.style.display = 'block';
                Employer.style.display = 'none';
                startDateOptions.style.display = 'none';

            } else if (reportType.value === 'performance') {
                Employer.style.display = 'block';
                inventoryOptions.style.display = 'none';
                storeOptions.style.display = 'none';
                storeSelection.style.display = 'none';
                startDateOptions.style.display = 'none';

            } else {
                inventoryOptions.style.display = 'none';
                storeOptions.style.display = 'none';
                Employer.style.display = 'none';
                storeSelection.style.display = 'none';
                startDateOptions.style.display = 'none';
            }
        }

        // Function to set start and end dates
        function dateOptions(){
            // Debug
            console.log('dateOptions function called');

            // Store report type
            var storeType = document.getElementById('storeType');
            var dateOptions = document.getElementById('dateOptions');

            if(storeType.value === 'orderdates'){
                startDateOptions.style.display = 'block';
            } else if(storeType.value === 'date'){
                startDateOptions.style.display = 'block';
            } else{
                startDateOptions.style.display = 'none';
            }

            //Function to ensure dates are valid
            function daysInMonth(year, month){
                // Setting the day to zero returns the last day of the previous month
                return new Date(year, month, 0).getDate();
            }

            //Start and end date selection
            var startYear = document.getElementById('start_year').value;
            var startMonth = document.getElementById('start_month').value;
            var startDay = document.getElementById('start_day').value;
            var stDate = startYear + startMonth + startDay;

            //var endYear = document.getElementById('end_year').value;
            //var endMonth = document.getElementById('end_month').value;
            //var endDay = document.getElementById('end_day').value;
            //var endDate = endYear + endMonth + endDay;

            // Update days based on selected year and month
            var startDayDropdown = document.getElementById('start_day');
            var endDayDropdown = document.getElementById('end_day');

            // Clear existing options
            startDayDropdown.innerHTML = '';
            //endDayDropdown.innerHTML = '';

            // Populate proper num days per selected month
            for (var i = 1; i <= daysInMonth(startYear, startMonth); i++){
                var option = document.createElement('option');
                option.value = i;
                option.text = i;
                // Debug
                console.log('Adding option for start day');
                startDayDropdown.add(option);
            }

            // for (var j = 1; j <= daysInMonth(endYear, endMonth); j++){
            //     var endopt = document.createElement('option');
            //     endopt.value = j;
            //     endopt.text = j;
            //     endDayDropdown.add(endopt);
            // }
        }
    </script>
</body>

</html>