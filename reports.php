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
    // ADD STORE SELECTOR FOR INVETORY AND STORE REPORTS
    // ADD DATE RANGE SELECTOR FOR SPECIFIC DATE REPORTS
    // COMMENT ON BROKEN PHP BELOW IS WHERE THE DROP DOWN NEEDS TO GO
    // LINES 110-118ish
    // $storeId IS THE VARIABLE FOR STORE SELECTOR
    // $stDate and $endDate ARE THE VARIABLES FOR DATE RANGE SELECTOR

    // function getEmployeeData() {
    //     include_once("./database.php");
    //     $sql = "SELECT `Employee_ID`, `E_First_Name`, `E_Last_Name` FROM `employee`";
    //     $result = mysqli_query($mysqli, $sql);
    
    //     if (!$result) {
    //         die("Error: " . mysqli_error($connection));
    //     }
    
    //     $employeeData = array();
    //     while ($row = mysqli_fetch_assoc($result)) {
    //         $employeeData[] = [
    //             'Employee_ID' => $row['Employee_ID'],
    //             'Name' => $row['E_First_Name'] . ' ' . $row['E_Last_Name'],
    //         ];
    //     }
    //     mysqli_free_result($result);
    //     return $employeeData;
    // }

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
function getStoreID(){
    include_once("./database.php");
    $sql = "SELECT `Pizza_Store_ID` FROM `pizza_store`";
    $result = mysqli_query($mysqli, $sql);

    if(!$result){
        die("Error: " . mysqli_error($connection));
    }

    $storeIdData = array();
    while($row = mysqli_fetch_assoc($result)){
        $storeIdData[] = [
            'Pizza_Store_ID' => $row['Pizza_Store_ID'],
        ];
        mysqli_free_result($result);
        return $storeIdData;
    }
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
1            </select>

        </div><br>

        <div id="inventoryOptions" style="display: none;">
            <!-- Inventory Report sub-options here -->
            <label for="inventoryType">Select Inventory Report Type:</label>
            <select name="inventoryType" id="inventoryType">
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
            <select name="storeType" id="storeType">
                <!-- Here are the different options you can display in your sub menu -->
                <!-- The value is how it will be referenced on generate_report.php and the text to the right is what appears in the drop down menu -->
                <option value="orders">Daily Orders</option>
                <option value="orderdates">Total Orders From:</option>
                <!-- <option value="pizzas">Daily Pizzas Sold</option> -->
                <option value="popular">Today's Most Popular Item</option>
                <option value="sales">Total Sales Today</option>
                <option value="date">Total Sales To Date</option>
            </select>
        </div><br>
        <!-- //To here// -->        

        <!-- Add more drop down sub-menus here -->
        <div id="Employer" style="display: none;">
            <label for="employeeDropdown">Select Employee:</label>
            <select name="employeeDropdown" id="employeeDropdown">
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
            storeId.value = ""; //resets store dropdown when you change report type

            //If you add a new sub menu, define it here then refence it by it's id like so:
            var inventoryOptions = document.getElementById('inventoryOptions');
            var storeOptions = document.getElementById('storeOptions');
            var Employer = document.getElementById('Employer');
            var storeSelection = document.getElementById('storeSelection');

            //sub-sub menu stuff deleted


            //This if/else determines which sub menu is visible
            //To set a new one visible, set reportType === 'newMenu'
            //Then newMenuOptions.style.display = 'block'
            //Be sure to set the other sub menus to 'none' and add in your new menu to the other sub menu categories and set it to 'none'
            if (reportType.value === 'inventory') {
                inventoryOptions.style.display = 'block';
                storeOptions.style.display = 'none';
                Employer.style.display = 'none';
                storeSelection.style.display = 'block';
                // newMenuOptions.style.display = 'none'
            } else if (reportType.value === 'store') {
                inventoryOptions.style.display = 'none';
                storeOptions.style.display = 'block';
                storeSelection.style.display = 'block';
                Employer.style.display = 'none';
                
            } else if (reportType.value === 'performance') {
                Employer.style.display = 'block';
                inventoryOptions.style.display = 'none';
                storeOptions.style.display = 'none';
                storeSelection.style.display = 'none';
            } else {
                inventoryOptions.style.display = 'none';
                storeOptions.style.display = 'none';
                Employer.style.display = 'none';
                storeSelection.style.display = 'none';
            }
        }
    </script>
</body>

</html>