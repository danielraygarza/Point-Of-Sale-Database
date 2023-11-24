<?php
// this page allows user to select the type of report to display. page submission runs generate_report.php

session_start();
include 'database.php'; // Include the database connection details
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Redirects if not manager/CEO or accessed directly via URL
if (!isset($_SESSION['user']['Title_Role']) || ($_SESSION['user']['Title_Role'] !== 'CEO' && $_SESSION['user']['Title_Role'] !== 'MAN')) {
    header("Location: employee_login.php");
    exit;
}

// function to get employees info
function getEmployeeData($mysqli)
{
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
        <?php echo '<a href="logout.php">Logout</a>'; ?>
        <a id="cart-button" style="background-color: transparent;"><?php echo 'Employee Role: ' . $_SESSION['user']['Title_Role']; ?></a>
    </div>

    <!-- run generate_report on submission -->
    <form action="generate_report.php" method="post">
        <h2>Reports</h2>

        <div>
            <label for="reportType">Report Type </label>
            <select name="reportType" id="reportType" onchange="showOptions()" required>
                <option value="" selected disabled>Select a Report</option>
                <option value="inventory">Inventory Reports</option>
                <option value="store">Store Reports</option>
                <option value="performance">Employee Performance Report</option>
            </select>
        </div> <br>

        <div id="storeSelection" style="display: none;">
            <label for="storeId">Store Location </label>
            <select name="storeId" id="storeId" onchange="checkSelections()">
                <option value="" selected disabled>Select Store</option>
                <?php
                $employee_ID = $_SESSION['user']['Employee_ID'];
                $employee_role = $_SESSION['user']['Title_Role'];

                // CEO can see all stores while manager only sees their own stores
                if ($employee_role == 'CEO') {
                    $query = "SELECT * FROM pizza_store";
                } else {
                    $query = "SELECT * FROM pizza_store WHERE Store_Manager_ID = '$employee_ID'";
                }
                $stores = $mysqli->query($query);
                if ($stores->num_rows > 0) {
                    while ($row = $stores->fetch_assoc()) {
                        if ($row["Pizza_Store_ID"] == 1) {
                            continue;
                        } // do not show store one
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
            <label for="inventoryType">Inventory Report Type </label>
            <select name="inventoryType" id="inventoryType" onchange="checkSelections()">
                <option value="" selected disabled>Select Report </option>
                <option value="all">All Stock</option>
                <option value="low">Low Stock</option>
                <option value="out">Out of Stock</option>
            </select>
        </div><br>

        <div id="storeOptions" style="display: none;">
            <!-- Store Report sub-options here -->
            <label for="storeType">Store Report Type </label>
            <select name="storeType" id="storeType" onchange="dateOptions()">
                <option value="" selected disabled>Select Report</option>
                <option value="orders">Today's Orders</option>
                <option value="orderdates">Orders by Date Range </option>
                <option value="popular">Today's Most Popular Items</option>
                <option value="datepopular">Popular Items by Date Range</option>
            </select>
        </div><br>

        <!-- get start date -->
        <div id="newStartDateOptions" style="display: none;">
            <label for="start_date">Start Date </label>
            <input type="date" id="start_date" name="start_date" onchange="setDay()">

            <!-- Save stDate to post -->
            <input type="hidden" id="stDate" name="stDate">
        </div> <br>

        <!-- get end date -->
        <div id="newEndDateOptions" style="display: none;">
            <label for="end_date">End Date </label>
            <input type="date" id="end_date" name="end_date" onchange="setDay()">

            <!-- Saves endDate to post -->
            <input type="hidden" id="endDate" name="endDate">
        </div><br>

        <!-- employee performance report filters -->
        <div id="Employer" style="display: none;">
            <label for="emp_status"> Employee Status </label>
            <select name="emp_status" id="emp_status">
                <!-- filter between current and former employees -->
                <option value="" selected disabled>Select Employee Status</option>
                <option value="1">Current</option>
                <option value="0">Former</option>
            </select>

            <!-- dropdown filtering current and former employees -->
            <label for="employeeDropdown">Employee </label>
            <select name="employeeDropdown" id="employeeDropdown" onchange="checkSelections()">
                <option value="" selected disabled>Select Employee </option>
            </select>
        </div><br>

        <!-- Completed function to restrict Generate Report button -->
        <input type="submit" class="button" value="Generate Report" id="submitButton">
    </form>

    <script>
        document.getElementById('employeeDropdown').addEventListener('change', function() {
            var selectedEmployeeId = this.value;
            if (selectedEmployeeId != 0) {
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

            // sub menus
            var inventoryOptions = document.getElementById('inventoryOptions');
            var storeOptions = document.getElementById('storeOptions');
            var Employer = document.getElementById('Employer');
            var storeSelection = document.getElementById('storeSelection');

            // only displays required input fields for selected report
            if (reportType.value === 'inventory') {
                inventoryOptions.style.display = 'block';
                storeOptions.style.display = 'none';
                Employer.style.display = 'none';
                storeSelection.style.display = 'block';
                newStartDateOptions.style.display = 'none';
                newEndDateOptions.style.display = 'none';

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
        function padWithZero(number) {
            return number < 10 ? '0' + number : number;
        }

        // Function to set start and end dates
        function dateOptions() {
            //  debug
            console.log('dateOptions function called');

            // Store report type
            var storeType = document.getElementById('storeType');
            var dateOptions = document.getElementById('dateOptions');

            if (storeType.value === 'orderdates') {
                newStartDateOptions.style.display = 'block';
                newEndDateOptions.style.display = 'block';
            } else if (storeType.value === 'date') {
                newStartDateOptions.style.display = 'block';
                newEndDateOptions.style.display = 'block';
            } else if (storeType.value === 'datepopular') {
                newStartDateOptions.style.display = 'block';
                newEndDateOptions.style.display = 'block';
            } else {
                newStartDateOptions.style.display = 'none';
                newEndDateOptions.style.display = 'none';
            }

            // Update Generate Report button
            checkSelections();

            // debug
            var sType = storeType.value;
            console.log('Start Date:', stDate);
            console.log('End Date:', endDate);
            console.log('Report:', sType);
        }

        // Activate Generate Reports button
        function checkSelections() {
            //  debug
            console.log('checkSelections function called');
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

            // ensuring all required fields are selected
            if (reportType.value !== '' && storeId.value !== '' && (inventoryType.value !== '' || storeType.value !== '' || employeeSelect.value !== '')) {
                if ((storeType.value === 'orderdates' || storeType.value === 'datepopular' || storeType.value === 'date') && (startDate.value < 20220100 || endDate.value < 20220100)) {
                    selectionMade = false;
                } else {
                    selectionMade = true;
                }
            }

            document.getElementById('submitButton').disabled = !selectionMade;
            var checkVal = document.getElementById('storeSelID').value;

            //  debug
            console.log('checkVal:', checkVal);

            // if store selection not changing, don't call setNewStoreSelID again
            if (checkVal !== storeId.value) {
                var storeID = 1;
                // if not set, sets an initial value for hidden storeId value
                if (storeId.value !== '') {
                    storeID = storeId.value;
                }
                // Sets the hidden value to the selected storeId value
                document.getElementById('storeSelID').value = storeID;

                // Updates employee drop down
                setNewStoreSelID();
            }
            //  debug
            console.log('Selection Made', selectionMade);
            console.log('storeSelID set to:', storeID);

        }

        function setDay() {
            //  debug
            console.log('setDay function called');

            // start and end date selection
            var stDate = document.getElementById('start_date').value;
            document.getElementById('stDate').value = stDate;

            var endDate = document.getElementById('end_date').value;
            document.getElementById('endDate').value = endDate;

            checkSelections();

            //  debug
            console.log('Start Date:', stDate);
            console.log('End Date:', endDate);
        }
    </script>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        function setNewStoreSelID() {
            populateEmployeeDropdown($('#storeSelID').val(), $("#emp_status").val());
            //  debug
            console.log('storeSelID set to', storeSelID.value);
        }

        function populateEmployeeDropdown(storeId, emp_status) {
            // AJAX request to get employee data based on the selected store
            $.ajax({
                url: 'getEmployees.php',
                type: 'GET',
                data: {
                    storeId: storeId,
                    emp_status: emp_status
                },
                dataType: 'json',
                success: function(employees) {
                    // Clear existing options
                    $('#employeeDropdown').empty();

                    // Add default option
                    $('#employeeDropdown').append('<option value="" selected disabled>Select Employee</option>');

                    // Add employee options based on the selected store
                    $.each(employees, function(index, employee) {
                        $('#employeeDropdown').append('<option value="' + employee.id + '">' + employee.name + '</option>');
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching employees:', status, error);
                    console.log(xhr.responseText);
                }
            });
        }

        $('#emp_status').on('change', function() {
            populateEmployeeDropdown($("#storeSelID").val(), $(this).val());
        });
    </script>
</body>

</html>