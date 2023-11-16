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
    // CURRENTLY SELECTING ANY MONTH OR YEAR REPOPULATES DAYS FOR BOTH START AND END FORCING USER TO RESELECT ANY VALUES ALREADY SELECTED THERE

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
            <select name="storeId" id="storeId" onchange="checkSelections()">
                <!-- <option value="test">Default</option> -->
                <!-- get current employee ID -->
                <option value="" selected disabled>Select Store</option>
                <?php
                    // select from store where mananager ID equals current ID. 
                    $stores = $mysqli->query("SELECT * FROM pizza_store");
                    if ($stores->num_rows > 0) {
                        while($row = $stores->fetch_assoc()) {
                            //if ($row["Pizza_Store_ID"] != 1) { continue; } //only shows store ID 1. can delete to show all
                            echo '<option value="' . $row["Pizza_Store_ID"] . '" ' . $selected . '>' . $row["Store_Address"] . ' - ' . $row["Store_City"] . '</option>';
                        }
                    }
                ?>
            </select>

            <!-- Save storeId to send to getEmployees -->
            <input type="hidden" id="storeSelID" name="storeSelID" value="1">

        </div><br>

        <div id="inventoryOptions" style="display: none;">
            <!-- Inventory Report sub-options here -->
            <label for="inventoryType">Select Inventory Report Type:</label>
            <select name="inventoryType" id="inventoryType" onchange="checkSelections()">
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
                <option value="popular">Today's Most Popular Item</option>
                <option value="datepopular">Most Popular Item From:</option>
                <option value="sales">Total Sales Today</option>
                <option value="date">Total Sales From:</option>
            </select>
        </div><br>
        <!-- //To here// -->

        <!-- Fixed Start Date Dropdown -->
        <div id="newStartDateOptions" style="display: none;">
            <label for="start_date">Start Date:</label>
            <input type="date" id="start_date" name="start_date" onchange="setDay()">

            <!-- Save stDate to post -->
            <input type="hidden" id="stDate" name="stDate">

        </div> <br>

        <!-- Fixed End Date Dropdown -->
        <div id="newEndDateOptions" style="display: none;">
            <label for="end_date">End Date:</label>
            <input type="date" id="end_date" name="end_date" onchange="setDay()">

            <!-- Saves endDate to post -->
            <input type="hidden" id="endDate" name="endDate">

        </div> <br>

        <!-- Add more drop down sub-menus here -->
        <div id="Employer" style="display: none;">

            <!-- STORE SELECTION WAS HERE -->
            

            <label for="emp_status">Select Employee Status:</label>
            <select name="emp_status" id="emp_status">
                <option value="" selected disabled>Select Employee Status</option>
                
                <option value="1">Current</option>
                <option value="0">Former</option>
                
            </select>
            <!-- </select><br> -->

            <label for="employeeDropdown">Select Employee:</label>
            <select name="employeeDropdown" id="employeeDropdown">
                <option value="" selected disabled>Select Employee</option>
                
            </select>
        </div><br>

        <!-- Working on function to restrict Generate Report button -->
        <input type="submit" class="button" value="Generate Report" id="submitButton">
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

            //If you add a new sub menu, define it here then refence it by it's id like so:
            var inventoryOptions = document.getElementById('inventoryOptions');
            var storeOptions = document.getElementById('storeOptions');
            var Employer = document.getElementById('Employer');
            var storeSelection = document.getElementById('storeSelection');

            //This if/else determines which sub menu is visible
            //To set a new one visible, set reportType === 'newMenu'
            //Then newMenuOptions.style.display = 'block'
            //Be sure to set the other sub menus to 'none' and add in your new menu to the other sub menu categories and set it to 'none'
            if (reportType.value === 'inventory') {
                inventoryOptions.style.display = 'block';
                storeOptions.style.display = 'none';
                Employer.style.display = 'none';
                storeSelection.style.display = 'block';
                newStartDateOptions.style.display = 'none';
                newEndDateOptions.style.display = 'none';
                // newMenuOptions.style.display = 'none'

            } else if (reportType.value === 'store') {
                inventoryOptions.style.display = 'none';
                storeOptions.style.display = 'block';
                storeSelection.style.display = 'block';
                Employer.style.display = 'none';
                newStartDateOptions.style.display = 'none';
                newEndDateOptions.style.display = 'none';

            } else if (reportType.value === 'performance') {
                Employer.style.display = 'block';
                inventoryOptions.style.display = 'none';
                storeOptions.style.display = 'none';
                storeSelection.style.display = 'block';
                newStartDateOptions.style.display = 'none';
                newEndDateOptions.style.display = 'none';

            } else {
                inventoryOptions.style.display = 'none';
                storeOptions.style.display = 'none';
                Employer.style.display = 'none';
                storeSelection.style.display = 'none';
                newStartDateOptions.style.display = 'none';
                newEndDateOptions.style.display = 'none';
            }

            // Update Generate Report button
            checkSelections();
        }

        // Date padding function
        function padWithZero(number){
            return number < 10 ? '0' + number : number;
        }

        // Function to set start and end dates
        function dateOptions(){
            // Debug
            console.log('dateOptions function called');

            // Store report type
            var storeType = document.getElementById('storeType');
            var dateOptions = document.getElementById('dateOptions');

            if(storeType.value === 'orderdates'){
                newStartDateOptions.style.display = 'block';
                newEndDateOptions.style.display = 'block';
            } else if(storeType.value === 'date'){
                newStartDateOptions.style.display = 'block';
                newEndDateOptions.style.display = 'block';
            } else if(storeType.value === 'datepopular'){
                newStartDateOptions.style.display = 'block';
                newEndDateOptions.style.display = 'block';
            } else{
                newStartDateOptions.style.display = 'none';
                newEndDateOptions.style.display = 'none';
            }

            // Update Generate Report button
            checkSelections();

            //Debug
            var sType = storeType.value;
            console.log('Start Date:', stDate);
            console.log('End Date:', endDate);
            console.log('Report:', sType);
        }

        // Activate Generate Reports button
        function checkSelections(){
            // Debug
            console.log('checkSelections function called');
            // Bool
            var selectionMade = false;
            // Main report type selection
            var reportType = document.getElementById('reportType');
            // Secondary report type selection
            var inventoryType = document.getElementById('inventoryType');
            var storeType = document.getElementById('storeType');
            var employeeSelect = document.getElementById('employeeDropdown');
            // Store selection
            var storeId = document.getElementById('storeId');
            // Start/End date selection
            var startDate = document.getElementById('stDate');
            var endDate = document.getElementById('endDate');

            // Can change to make initialize variable with if statement instead if needed for fringe cases
            // storeId.value !== '' changed from: (storeId.value !== '' || employeeSelect.value !== '')
            if (reportType.value !== '' && storeId.value !== '' && (inventoryType.value !== '' || storeType.value !== '' || employeeSelect.value !== '')){
                if ((storeType.value === 'orderdates' || storeType.value === 'datepopular' || storeType.value === 'date') && (startDate.value < 20220100 || endDate.value < 20220100)) {
                    selectionMade = false;
                } else {
                    selectionMade = true;
                }
            }

            document.getElementById('submitButton').disabled = !selectionMade;
            
            var storeID = 1;
            if(storeId.value !== ''){
                storeID = storeId.value;
            }
            document.getElementById('storeSelID').value = storeID;

            // Debug
            console.log('Selection Made', selectionMade);
            console.log('storeSelID set to:', storeID);

        }

        function setDay(){
            // Debug
            console.log('setDay function called');

            //Start and end date selection
            var stDate = document.getElementById('start_date').value;
            document.getElementById('stDate').value = stDate;

            var endDate = document.getElementById('end_date').value;
            document.getElementById('endDate').value = endDate;
            
            checkSelections();

            // Debug
            console.log('Start Date:', stDate);
            console.log('End Date:', endDate);
        }
    </script>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <script>
    $(document).ready(function () {

        // Handle store selection change
        function setNewStoreSelID(){
            populateEmployeeDropdown($('#storeSelID').val(), $("#emp_status").val());
            // Debug
            console.log('onChange function setting SSID ran');
            console.log('storeSelID set to', storeSelID.value);
        }
        // $('#storeSelID').on('change', function () {
        //     //alert(1);
        //     populateEmployeeDropdown($(this).val(), $("#emp_status").val());

        //     // Debug
        //     console.log('onChange function setting SSID ran');
        //     console.log('storeSelID set to', storeSelID.value);
        // });

        $('#emp_status').on('change', function () {
            //alert(1);
            populateEmployeeDropdown($("#storeSelID").val(), $(this).val());
        });

        function populateEmployeeDropdown(storeId, emp_status) {
            // Make an AJAX request to fetch employee data based on the selected store
            $.ajax({
                url: 'getEmployees.php', // Replace with your server-side script
                type: 'GET',
                data: { storeId: storeId, emp_status: emp_status },
                dataType: 'json',
                success: function (employees) {
                    // Clear existing options
                    $('#employeeDropdown').empty();

                    // Add default option
                    $('#employeeDropdown').append('<option value="" selected disabled>Select Employee</option>');

                    // Add employee options based on the selected store
                    $.each(employees, function (index, employee) {
                        $('#employeeDropdown').append('<option value="' + employee.id + '">' + employee.name + '</option>');
                    });

                    checkSelections();
                },
                error: function (xhr, status, error) {
                    console.error('Error fetching employees:', status, error);
                    console.log(xhr.responseText);
                }
            });
        }
    });
    </script>
</body>

</html>