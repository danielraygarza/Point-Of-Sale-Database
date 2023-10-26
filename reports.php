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
    $setHeader = "Default Report Header";

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['reportType'])) {
            $reportType = $_POST['reportType'];
            // Determine the subgroup
            $subgroup = '';

            if ($reportType === 'inventory') {
                $subgroup = $_POST['inventoryType'];

                if ($subgroup === 'low') {
                    $setHeader = "Low Stock Items Report";
                } elseif ($subgroup === 'out') {
                    $setHeader = "Out of Stock Items Report";
                } else {
                    $setHeader = "All Stock Items Report";
                }
            } elseif ($reportType === 'sales') {
                $setHeader = "Sales Report";
            }
        }
    }
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
        
        <!-- Add a hidden input field to pass the setHeader variable -->
        <input type="hidden" name="setHeader" value="<?php echo $setHeader; ?>">
        
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
        </div><br>

        <input type="submit" class = "button" value="Generate Report">
        
    </form> 

        <script>
            function showInventoryOptions() {
                var reportType = document.getElementById('reportType');
                var inventoryOptions = document.getElementById('inventoryOptions');

                if (reportType.value === 'inventory') {
                    inventoryOptions.style.display = 'block';
                } else {
                    inventoryOptions.style.display = 'none';
                }
            }
        </script>
    </body>
</html>