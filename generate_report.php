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
                    if ($mysqli === false) {
                        die("Error: Could not connect to the database. " . mysqli_connect_error());
                    }

                    // Check the value of the inventoryType
                    $inventoryType = $_POST['inventoryType'];
                    $sql = '';

                    // Get the selected store
                    if(isset($_POST['storeId'])){
                        $storeId = $_POST['storeId'];
                    } else {
                        $storeId = '1';
                    }

                    // Define your SQL queries for Inventory selection
                    if ($inventoryType === 'low') {
                        // Header for low stock items
                        $setHeader = 'Low Stock Items';
                        // Query for low stock items
                        $sql = "SELECT I.Inventory_Amount, I.Inventory_ID, Items.Item_Name, I.Cost, V.Vendor_Name,
                        CONCAT(V.V_Rep_Fname, ' ', V.V_Rep_Lname) AS Vendor_Rep,
                        V.V_Email AS Vendor_Email, V.V_Phone AS Vendor_Phone, I.Store_Id
                        FROM INVENTORY I
                        INNER JOIN VENDOR V ON I.Vend_ID = V.Vendor_ID
                        INNER JOIN ITEMS ON I.Item_ID = Items.Item_ID
                        WHERE I.Inventory_Amount <= I.Reorder_Threshold + 5 AND I.Store_ID = '$storeId';";
                    } elseif ($inventoryType === 'out') {
                        // Header for out of stock items
                        $setHeader = 'Out of Stock Items';
                        // Query for out of stock items
                        $sql = "SELECT I.Inventory_Amount, I.Inventory_ID, Items.Item_Name, I.Cost, V.Vendor_Name,
                        CONCAT(V.V_Rep_Fname, ' ', V.V_Rep_Lname) AS Vendor_Rep,
                        V.V_Email AS Vendor_Email, V.V_Phone AS Vendor_Phone 
                        FROM INVENTORY I
                        INNER JOIN VENDOR V ON I.Vend_ID = V.Vendor_ID
                        INNER JOIN ITEMS ON I.Item_ID = Items.Item_ID
                        WHERE I.Inventory_Amount <= 0 AND I.Store_ID = '$storeId';";
                    } else {
                        // Header for all items
                        $setHeader = 'Inventory Report';
                        // Query for all stock items
                        $sql = "SELECT I.Inventory_Amount, I.Store_Inventory_ID, Items.Item_Name, I.Cost, V.Vendor_Name,
                        CONCAT(V.V_Rep_Fname, ' ', V.V_Rep_Lname) AS Vendor_Rep,
                        V.V_Email AS Vendor_Email, V.V_Phone AS Vendor_Phone 
                        FROM INVENTORY I
                        INNER JOIN VENDOR V ON I.Vend_ID = V.Vendor_ID
                        INNER JOIN ITEMS ON I.Item_ID = Items.Item_ID
                        WHERE I.Store_ID = '$storeId';";
                    }


                    // Execute the query
                    $result = mysqli_query($mysqli, $sql);

                    if ($result) {
                        // Check if there are rows returned
                        if (mysqli_num_rows($result) > 0) {
                            echo '<h2>' . $setHeader . '</h2>';
                            echo '<table border="1" class="table_update">';
                            echo '<tr><th>Product ID</th><th>Product</th><th>Quantity in Stock</th><th>Cost</th><th>Vendor</th><th>Vendor Rep</th><th>Email</th><th>Phone</th></tr>';

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
                            echo '<h2>' . $setHeader . '</h2>';
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
                    if ($mysqli === false) {
                        die("Error: Could not connect to the database. " . mysqli_connect_error());
                    }

                    // Get the selected store
                    if(isset($_POST['storeId'])){
                        $storeId = $_POST['storeId'];
                    } else {
                        $storeId = '1';
                    }

                    // Check the value of the inventoryType
                    $storeType = $_POST['storeType'];
                    $sql = '';

                    // Define your SQL queries for Inventory selection
                    if ($storeType === 'orders') {
                        // Header for daily orders
                        $setHeader = 'Daily Orders';
                        // Get the current Date
                        $currentDate = date("Y-m-d");
                        // Query for daily orders
                        $sql = "SELECT P.Pizza_Store_ID, P.Store_Address, COUNT(O.Order_ID) AS OrderCount
                        FROM pizza_store P 
                        LEFT JOIN orders O
                        ON P.Pizza_Store_ID = O.Store_ID
                        WHERE P.Pizza_Store_ID = '$storeId' AND DATE(O.Date_Of_Order) = '$currentDate'
                        GROUP BY P.Pizza_Store_ID, P.Store_Address;";
                    } elseif ($storeType === 'orderdates') {
                        // Header for daily orders
                        $setHeader = 'Orders by Date';
                        // Get the selected date range
                        if (isset($_POST['stDate'])) {
                            $stDate = $_POST['stDate'];
                        } else {
                            // Default test values for stDate
                            $stDate = date("Y-m-d");
                        }
                        if (isset($_POST['endDate'])) {
                            $endDate = $_POST['endDate'];
                        } else {
                            // Default test values for endDate
                            $endDate = date("Y-m-d");
                        }
                        // TO COMPLETE: Query for orders by date
                        $sql = "SELECT P.Pizza_Store_ID, P.Store_Address, COUNT(O.Order_ID) AS OrderCount
                        FROM PIZZA_STORE P 
                        LEFT JOIN ORDERS O
                        ON P.Pizza_Store_ID = O.Store_ID
                        WHERE DATE(O.Date_Of_Order) BETWEEN '$stDate' AND '$endDate'
                        GROUP BY P.Pizza_Store_ID, P.Store_Address;";
                    } elseif ($storeType === 'pizzas') {
                        // Header for pizzas sold
                        $setHeader = 'Pizzas Sold Today';
                        // TO COMPLETE: Query for pizzas sold today
                        $sql = "SELECT Pizza_Store_ID FROM pizza_store";
                    } elseif ($storeType === 'popular') {
                        // Header for most popular item today
                        $setHeader = 'Most Popular Item';
                        // TO COMPLETE: Query for most popular item today
                        $sql = "SELECT I.Item_Name 
                        FROM menu_items M
                        ";
                    } elseif ($storeType === 'sales') {
                        // Header for total sales today
                        $setHeader = 'Total Sales Today';
                        // Get the current Date
                        $currentDate = date("Y-m-d");
                        // TO COMPLETE: Query for total sales today
                        $sql = "SELECT P.Pizza_Store_ID, P.Store_Address, SUM(O.Total_Amount) AS Total_Sales
                        FROM PIZZA_STORE P 
                        LEFT JOIN ORDERS O
                        ON P.Pizza_Store_ID = O.Store_ID
                        WHERE P.Pizza_Store_ID = '$storeId' AND DATE(O.Date_Of_Order) = '$currentDate'
                        GROUP BY P.Pizza_Store_ID, P.Store_Address;";
                    } else {
                        //Header for total sales to date
                        $setHeader = 'Total Sales For Date Range';
                        // Get the selected date range
                        if (isset($_POST['stDate'])) {
                            $stDate = $_POST['stDate'];
                        } else {
                            // Default test values for stDate
                            $stDate = date("Y-m-d");
                        }
                        if (isset($_POST['endDate'])) {
                            $endDate = $_POST['endDate'];
                        } else {
                            // Default test values for endDate
                            $endDate = date("Y-m-d");
                        }
                        // TO COMPLETE: Query for date range sales
                        $sql = "SELECT P.Pizza_Store_ID, P.Store_Address, SUM(O.Total_Amount) AS Total_Sales
                        FROM PIZZA_STORE P 
                        LEFT JOIN ORDERS O
                        ON P.Pizza_Store_ID = O.Store_ID
                        WHERE DATE(O.Date_Of_Order) BETWEEN '$stDate' AND '$endDate'
                        GROUP BY P.Pizza_Store_ID, P.Store_Address;";
                    }


                    // Execute the query
                    $result = mysqli_query($mysqli, $sql);

                    if ($result) {
                        // Check if there are rows returned
                        if (mysqli_num_rows($result) > 0) {
                            echo '<h2>' . $setHeader . '</h2>';
                            echo '<table border="1" class="table_update">';
                            echo '<tr><th>Pizza Store ID</th></tr>';

                            // Loop through the results and display them in a table
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo '<tr>';
                                echo '<td>' . $row['Pizza_Store_ID'] . '</td>';
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
                            echo '<h2>' . $setHeader . '</h2>';
                            echo 'No inventory data available for store ' . $selectStore;
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


                ////////////////////////////
                ///START EMPLOYEE QUERIES///
                ////////////////////////////
                 if ($reportType === 'performance') {
                     include 'database.php'; // Database connection file

                     if ($mysqli === false) {
                         die("Error: Could not connect to the database. " . mysqli_connect_error());
                     }

                     $setHeader = 'Employee Details';
                     $employeeId = '0';
                     if(isset($_POST['employeeDropdown'])){
                        $employeeId = $_POST['employeeDropdown'];
                     } else {
                        $employeeId = 0;
                    }
                    
                    $sql = '';
                
                    $sql = "SELECT e.`Employee_ID`, e.`E_First_Name`, e.`E_Last_Name`, e.`Title_Role`, 
                    e.`Hire_Date`, e.`assigned_orders`, e.`completed_orders`, d.`Time_Delivered`, 
                    d.`Delivery_Status`
                    FROM `employee` e
                    LEFT JOIN `delivery` d ON e.`Employee_ID` = d.`employee`
                    WHERE e.`Employee_ID` = $employeeId;";

                     $result = mysqli_query($mysqli, $sql);

                    if ($result) {
                        // Check if there are rows returned
                        if (mysqli_num_rows($result) > 0) {
                            echo '<h2>' . $setHeader . '<h2>';
                            echo "<table border='1' class='table_update'>";

                            echo "<tr>
                                <th class='th-spacing'>Employee ID</th>
                                <th class='th-spacing'>First Name</th>
                                <th class='th-spacing'>Last Name</th>
                                <th class='th-spacing'>Title/Role</th>
                                <th class='th-spacing'>Hire Date</th>
                                <th class='th-spacing'>Assigned Orders</th>
                                <th class='th-spacing'>Completed Orders</th>
                                <th class='th-spacing'>Time Delivered</th>
                                <th class='th-spacing'>Delivery Status</th>
                                </tr>";
                    
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<tr>";
                                echo "<td>" . $row['Employee_ID'] . "</td>";
                                echo "<td>" . $row['E_First_Name'] . "</td>";
                                echo "<td>" . $row['E_Last_Name'] . "</td>";
                                echo "<td>" . $row['Title_Role'] . "</td>";
                                echo "<td>" . $row['Hire_Date'] . "</td>";
                                echo "<td>" . $row['assigned_orders'] . "</td>";
                                echo "<td>" . $row['completed_orders'] . "</td>";
                                echo "<td>" . $row['Time_Delivered'] . "</td>";
                                echo "<td>" . $row['Delivery_Status'] . "</td>";
                                echo "</tr>";
                            }
                    
                            echo "</table>";
                        } 
                    } else {
                        echo 'Error executing the SQL query: ' . mysqli_error($mysqli);
                    }

                    // Close the database connection
                    mysqli_close($mysqli);
                }
                /////////////////////////
                //END EMPLOYEE QUERIES///
                /////////////////////////



                // Add more cases for other report types as needed
            }
        }
        ?>

    </form>

</body>

</html>