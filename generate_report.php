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
            <a href="reports.php">Back to Reports</a>
            <?php
                //shows logout button if logged in
                if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
                    echo '<a href="logout.php">Logout</a>';
                }
            ?>
        </div>

    <form action="generate_report.php" method="post">
    <?php
    // Check if the form has been submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['reportType'])) {
            $reportType = $_POST['reportType'];
            $setHeader = '';

            /////////////////////////
            ////INVENTORY QUERIES////
            /////////////////////////
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
                    // Header for low stock items
                    $setHeader = 'Low Stock Items';
                    // Query for low stock items
                    $sql = "SELECT I.Inventory_Amount, I.Inventory_ID, I.Item_Name, I.Cost, V.Vendor_Name,
                    CONCAT(V.V_Rep_Lname, ' ', V.V_Rep_Fname) AS Vendor_Rep,
                    V.V_Email AS Vendor_Email, V.V_Phone AS Vendor_Phone 
                    FROM INVENTORY I
                    INNER JOIN VENDOR V ON I.Vend_ID = V.Vendor_ID
                    WHERE I.Inventory_Amount <= I.Reorder_Threshold + 5;";

                } elseif ($inventoryType === 'out') {
                    // Header for out of stock items
                    $setHeader = 'Out of Stock Items';
                    // Query for out of stock items
                    $sql = "SELECT I.Inventory_Amount, I.Inventory_ID, I.Item_Name, I.Cost, V.Vendor_Name,
                    CONCAT(V.V_Rep_Lname, ' ', V.V_Rep_Fname) AS Vendor_Rep,
                    V.V_Email AS Vendor_Email, V.V_Phone AS Vendor_Phone 
                    FROM INVENTORY I
                    INNER JOIN VENDOR V ON I.Vend_ID = V.Vendor_ID
                    WHERE I.Inventory_Amount <= 0";

                } else {
                    // Header for all items
                    $setHeader = 'Inventory Report';
                    // Query for all stock items
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
                        echo '<h2>' . $setHeader . '</h2>';
                        echo '<table>';
                        echo '<tr><th>|Product ID|</th><th>|Product|</th><th>|Quantity in Stock|</th><th>|Cost|</th><th>|Vendor|</th><th>|Vendor Rep|</th><th>|Email|</th><th>|Phone|</th></tr>';

                        // Loop through the results and display them in a table
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo '<tr>';
                            echo '<td>' . $row['Inventory_ID'] . '</td>';
                            echo '<td>' . $row['Item_Name'] . '</td>';
                            echo '<td>' . $row['Inventory_Amount'] . '</td>';
                            echo '<td>' . $row['Cost'] . '</td>';
                            echo '<td>' . $row['Vendor_Name'] . '</td>';
                            echo '<td>' . $row['Vendor_Rep'] . '</td>';
                            echo '<td>' . $row['Vendor_Email'] . '</td>';
                            echo '<td>' . $row['Vendor_Phone'] . '</td>';
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

            /////////////////////////
            //////STORE QUERIES//////
            /////////////////////////
            if ($reportType === 'store') {
                // Database connection code:
                include 'database.php'; // Database connection file

                //Check if the connection to the database was successful
                if($mysqli === false){
                    die("Error: Could not connect to the database. " . mysqli_connect_error());
                }

                // Check the value of the inventoryType
                $storeType = $_POST['storeType'];
                $sql = '';

                // Define your SQL queries for Inventory selection
                if ($storeType === 'orders') {
                    // Header for daily orders
                    $setHeader = 'Daily Orders';
                    // TO COMPLETE: Query for daily orders
                    $sql = "SELECT Pizza_Shop_ID FROM pizza_shop";

                } elseif ($storeType === 'pizzas') {
                    // Header for pizzas sold
                    $setHeader = 'Pizzas Sold Today';
                    // TO COMPLETE: Query for pizzas sold today
                    $sql = " ";

                } elseif ($storeType === 'popular') {
                    // Header for most popular pizza today
                    $setHeader = 'Most Popular Pizza';
                    // TO COMPLETE: Query for most popular pizza today
                    $sql = " ";

                } elseif ($storeType === 'sales'){
                    // Header for total sales today
                    $setHeader = 'Total Sales Today';
                    // TO COMPLETE: Query for total sales today
                    $sql = " ";

                } else {
                    //Header for total sales to date
                    $setHeader = 'Total Sales To Date';
                    // TO COMPLETE: Query for total sales to date
                    $sql = " ";

                }

                
                // Execute the query
                $result = mysqli_query($mysqli, $sql);
                //echo '<h2>' . $setHeader . '</h2>';

                if ($result) {
                    // Check if there are rows returned
                    if (mysqli_num_rows($result) > 0) {
                        echo '<h2>' . $setHeader . '</h2>';
                        echo '<table>';
                        echo '<tr><th>|Pizza Shop ID|</th></tr>';

                        // Loop through the results and display them in a table
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo '<tr>';
                            echo '<td>' . $row['Pizza_Shop_ID'] . '</td>';
                            //echo '<td>' . $row['Item_Name'] . '</td>';
                            //echo '<td>' . $row['Inventory_Amount'] . '</td>';
                            //echo '<td>' . $row['Cost'] . '</td>';
                            //echo '<td>' . $row['Vendor_Name'] . '</td>';
                            //echo '<td>' . $row['Vendor_Rep'] . '</td>';
                            //echo '<td>' . $row['Vendor_Email'] . '</td>';
                            //echo '<td>' . $row['Vendor_Phone'] . '</td>';
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
            ////END STORE QUERIES////
            /////////////////////////

            // Add more cases for other report types as needed
        }
    }
    ?>

    </form> 

    </body>
</html>