<?php 
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
    $setHeader = isset($_POST['setHeader']) ? $_POST['setHeader'] : "Default Report Header";
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
            <a href="reports.php">Back to Reports</a>
            <?php
                //shows logout button if logged in
                if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
                    echo '<a href="logout.php">Logout</a>';
                }
            ?>
        </div>

        <div class="report_header">
            <!-- Design Report header to be dynamically populated -->
            <?php echo $setHeader; ?>
        </div>

    <form action="generate_report.php" method="post">
    <?php
    // Check if the form has been submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['reportType'])) {
            $reportType = $_POST['reportType'];
            
            /////////////////////
            //INVENTORY QUERIES//
            /////////////////////
            if ($reportType === 'inventory') {
                // Database connection code:
                include 'database.php'; // Database connection file

                //Check if the connection to the database was successful
                if($mysqli === false){
                    die("Error: Could not connect to the database. " . mysqli_connect_error());
                }

                // Check the value of the inventoryType
                $inventoryType = $_POST['inventoryType'];
                $sql = '';

                // Define your SQL queries for Inventory selection
                if ($inventoryType === 'low') {
                    // Query for low stock items
                    $sql = "SELECT Inventory_ID, Inventory_Amount FROM inventory WHERE Inventory_Amount < 10";

                } elseif ($inventoryType === 'out') {
                    // Query for out of stock items
                    $sql = "SELECT Inventory_ID, Inventory_Amount FROM inventory WHERE Inventory_Amount = 0";

                } else {
                    // Query for all stock items
                    // Might be able to remove the nextline entries and keep it as a single line query
                    $sql = "SELECT I.Inventory_Amount, I.Inventory_ID, I.Item_Name, I.Cost, V.Vendor_Name,
                    CONCAT(V.V_Rep_Lname, ' ', V.V_Rep_Fname) AS Vendor_Rep,
                    V.V_Email AS Vendor_Email, V.V_Phone AS Vendor_Phone 
                    FROM INVENTORY I
                    INNER JOIN VENDOR V ON I.Vend_ID = V.Vendor_ID;";

                }

                
                // Execute the query
                $result = mysqli_query($mysqli, $sql);

                if ($result) {
                    // Check if there are rows returned
                    if (mysqli_num_rows($result) > 0) {
                        echo '<h2>Inventory Report</h2>';
                        echo '<table>';
                        echo '<tr><th>Product ID</th><th>Quantity in Stock</th></tr>';

                        // Loop through the results and display them in a table
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo '<tr>';
                            echo '<td>' . $row['Inventory_ID'] . '</td>';
                            echo '<td>' . $row['Inventory_Amount'] . '</td>';
                            echo '</tr>';
                        }

                        echo '</table>';
                    } else {
                        echo 'No inventory data available.';
                    }
                } else {
                    echo 'Error executing the SQL query: ' . mysqli_error($mysqli);
                }

                // Close the database connection
                mysqli_close($mysqli);
            }
            /////////////////////////
            //END INVENTORY QUERIES//
            /////////////////////////
            
            // Add more cases for other report types as needed
        }
    }
    ?>

    </form> 

    </body>
</html>