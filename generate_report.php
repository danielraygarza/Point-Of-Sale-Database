<?php
    session_start();
    include 'database.php'; // Include the database connection details
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

   // Redirects if not manager/CEO or accessed directly via URL
    // if (!isset($_SESSION['user']['Title_Role']) || ($_SESSION['user']['Title_Role'] !== 'CEO' && $_SESSION['user']['Title_Role'] !== 'MAN')) {
    //     header("Location: employee_login.php");
    //     exit; // Make sure to exit so that the rest of the script won't execute
    // }

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
                        // $setHeader = 'Low Stock Items';
                        $address = $mysqli->query("SELECT Store_Address, Store_City FROM pizza_store WHERE Pizza_Store_ID = '$storeId'");
                        if ($addressRow = $address->fetch_assoc()) {
                            $setHeader = 'Low Stock Items for ' . $addressRow['Store_Address'] . ' - ' .  $addressRow['Store_City']; //header with store address
                        }
                        // Query for low stock items
                        $sql = "SELECT I.Inventory_Amount, Items.Item_Name, Items.Item_Cost, V.Vendor_Name,
                        CONCAT(V.V_Rep_Fname, ' ', V.V_Rep_Lname) AS Vendor_Rep,
                        V.V_Email AS Vendor_Email, V.V_Phone AS Vendor_Phone, I.Store_Id
                        FROM INVENTORY I
                        INNER JOIN VENDOR V ON I.Vend_ID = V.Vendor_ID
                        INNER JOIN ITEMS ON I.Item_ID = Items.Item_ID
                        WHERE I.Inventory_Amount <= Items.Reorder_Threshold + 5 AND I.Store_ID = '$storeId';";
                    } elseif ($inventoryType === 'out') {
                        // Header for out of stock items
                        // $setHeader = 'Out of Stock Items';
                        $address = $mysqli->query("SELECT Store_Address, Store_City FROM pizza_store WHERE Pizza_Store_ID = '$storeId'");
                        if ($addressRow = $address->fetch_assoc()) {
                            $setHeader = 'Out of Stock for ' . $addressRow['Store_Address'] . ' - ' .  $addressRow['Store_City']; //header with store address
                        }
                        // Query for out of stock items
                        $sql = "SELECT I.Inventory_Amount, Items.Item_Name, Items.Item_Cost, V.Vendor_Name,
                        CONCAT(V.V_Rep_Fname, ' ', V.V_Rep_Lname) AS Vendor_Rep,
                        V.V_Email AS Vendor_Email, V.V_Phone AS Vendor_Phone 
                        FROM INVENTORY I
                        INNER JOIN VENDOR V ON I.Vend_ID = V.Vendor_ID
                        INNER JOIN ITEMS ON I.Item_ID = Items.Item_ID
                        WHERE I.Inventory_Amount <= 0 AND I.Store_ID = '$storeId';";
                    } else {
                        // Header for all items
                        // $setHeader = 'Inventory Report';
                        $address = $mysqli->query("SELECT Store_Address, Store_City FROM pizza_store WHERE Pizza_Store_ID = '$storeId'");
                        if ($addressRow = $address->fetch_assoc()) {
                            $setHeader = 'Inventory Report for ' . $addressRow['Store_Address'] . ' - ' .  $addressRow['Store_City']; //header with store address
                        }
                        // Query for all stock items
                        $sql = "SELECT I.Inventory_Amount, Items.Item_Name, Items.Item_Cost, V.Vendor_Name,
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
                            // <th class='th-spacing'>Product ID</th>
                            echo "<tr>
                                <th class='th-spacing'>Product</th>
                                <th class='th-spacing'>Quantity in Stock</th>
                                <th class='th-spacing'>Cost</th>
                                <th class='th-spacing'>Vendor</th>
                                <th class='th-spacing'>Vendor Rep</th>
                                <th class='th-spacing'>Email</th>
                                <th class='th-spacing'>Phone</th>
                                </tr>";

                            // Loop through the results and display them in a table
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo '<tr>';
                                // echo '<td>' . $row['Inventory_ID'] . '</td>';
                                echo '<td>' . $row['Item_Name'] . '</td>';
                                echo '<td>' . $row['Inventory_Amount'] . '</td>';
                                echo '<td>' . $row['Item_Cost'] . '</td>';
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
                    $ordSql = '';

                    // Define your SQL queries for Inventory selection
                    if ($storeType === 'orders') {
                        // DONE
                        // Header for daily orders
                        $setHeader = 'Daily Orders';
                        // Get the current Date
                        $currentDate = date("Y-m-d");
                        // Query for daily orders
                        $sql = "SELECT P.Pizza_Store_ID, P.Store_Address, COUNT(O.Order_ID) AS OrderCount
                        FROM PIZZA_STORE P 
                        LEFT JOIN ORDERS O
                        ON P.Pizza_Store_ID = O.Store_ID
                        WHERE P.Pizza_Store_ID = '$storeId' AND DATE(O.Date_Of_Order) = '$currentDate'
                        GROUP BY P.Pizza_Store_ID, P.Store_Address;";
                        // Get order info for daily orders
                        $ordSql = "SELECT Order_ID, Date_Of_Order, Time_Of_Order, Order_Type, Order_Status, Total_Amount, O_Customer_ID
                        FROM ORDERS
                        WHERE Store_ID = '$storeId' AND DATE(Date_Of_Order) = '$currentDate';";
                    } elseif ($storeType === 'orderdates') {
                        // DONE
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
                        // Query for orders by date
                        $sql = "SELECT P.Pizza_Store_ID, P.Store_Address, COUNT(O.Order_ID) AS OrderCount
                        FROM PIZZA_STORE P 
                        LEFT JOIN ORDERS O
                        ON P.Pizza_Store_ID = O.Store_ID
                        WHERE P.Pizza_Store_ID = '$storeId' AND DATE(O.Date_Of_Order) BETWEEN '$stDate' AND '$endDate' 
                        GROUP BY P.Pizza_Store_ID, P.Store_Address;";
                        // Get order info for daily orders
                        $ordSql = "SELECT Order_ID, Date_Of_Order, Time_Of_Order, Order_Type, Order_Status, Total_Amount, O_Customer_ID
                        FROM ORDERS
                        WHERE Store_ID = '$storeId' AND DATE(Date_Of_Order) BETWEEN '$stDate' AND '$endDate';";
                    } elseif ($storeType === 'popular') {
                        // IN PROGRESS
                        // Header for most popular item today
                        $setHeader = 'Most Popular Item';
                        // Get the current Date
                        $currentDate = date("Y-m-d");
                        // TO COMPLETE: Query for most popular item today
                        $sql = "SELECT I.Item_Name AS Most_Popular_Item, COUNT(OI.Item_ID) AS Item_Count
                        FROM ORDER_ITEMS OI
                        JOIN ORDERS O ON OI.Order_ID = O.Order_ID
                        JOIN ITEMS I ON OI.Item_ID = I.Item_ID
                        WHERE DATE(O.Date_Of_Order) = '$currentDate'AND O.Store_ID = '$storeId'
                        GROUP BY I.Item_Name
                        ORDER BY Item_Count DESC
                        LIMIT 1;";
                    } elseif ($storeType === 'datepopular') {
                        // IN PROGRESS
                        // Header for most popular item for date range
                        $setHeader = 'Most Popular Item by Date';
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
                        // TO COMPLETE: Query for most popular item today
                        $sql = "SELECT I.Item_Name AS Most_Popular_Item, COUNT(OI.Item_ID) AS Item_Count
                        FROM ORDER_ITEMS OI
                        JOIN ORDERS O ON OI.Order_ID = O.Order_ID
                        JOIN ITEMS I ON OI.Item_ID = I.Item_ID
                        WHERE O.Store_ID = '$storeId' AND DATE(O.Date_Of_Order) BETWEEN '$stDate' AND '$endDate'
                        GROUP BY I.Item_Name
                        ORDER BY Item_Count DESC
                        LIMIT 1;";
                    } elseif ($storeType === 'sales') {
                        // IN PROGRESS
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
                        // IN PROGRESS
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
                    // Check to see if $ordSql set
                    if(!empty(trim($ordSql))){
                        $ordResult = mysqli_query($mysqli, $ordSql);
                    }

                    if ($result) {
                        // Check if there are rows returned
                        if (mysqli_num_rows($result) > 0) {
                            echo '<h2>' . $setHeader . '</h2>';
                            echo '<table border="1" class="table_update">';
                            if ($storeType === 'popular') {
                                echo "<tr>
                                        <th class='th-spacing'>Item Name</th>
                                        <th class='th-spacing'>Sold Today</th>
                                    </tr>";
                            } else {
                                echo "<tr>
                                        <th class='th-spacing'>Pizza Store ID</th>
                                        <th class='th-spacing'>Pizza Store Address</th>
                                        <th class='th-spacing'>Order Count</th>
                                    </tr>";
                            }
                            // Loop through the results and display them in a table
                            while ($row = mysqli_fetch_assoc($result)) {
                                //TO DO://
                                //NEED TO FINISH DECIDING WHAT TO DISPLAY FOR REPORTS
                                //MAY HAVE TO MAKE SEPARATE DISPLAYS FOR SEPARATE STORE REPORT TYPES
                                if ($storeType === 'popular') {
                                    echo '<tr>';
                                    echo '<td>' . $row['Most_Popular_Item'] . '</td>';
                                    echo '<td>' . $row['Item_Count'] . '</td>';
                                    echo '</tr>';
                                } else {
                                    echo '<tr>';
                                    echo '<td>' . $row['Pizza_Store_ID'] . '</td>';
                                    echo '<td>' . $row['Store_Address'] . '</td>';
                                    echo '<td>' . $row['OrderCount'] . '</td>';
                                    //echo '<td>' . $row['Item_Cost'] . '</td>';
                                    //echo '<td>' . $row['Vendor_Name'] . '</td>';
                                    //echo '<td>' . $row['Vendor_Rep'] . '</td>';
                                    //echo '<td>' . $row['Vendor_Email'] . '</td>';
                                    //echo '<td>' . $row['Vendor_Phone'] . '</td>';
                                    echo '</tr>';
                                }
                            }

                            echo '</table>';
                            // Should check if we have set $ordSql and that $ordResult populated
                            if (!empty(trim($ordSql)) && $ordResult){
                                // Seems redundant, but second check
                                if (mysqli_num_rows($ordResult) > 0){
                                    echo '<h2>Order Details</h2>';
                                    echo '<table border="1" class="table_update">';
                                    echo "<tr>
                                            <th class='th-spacing'>Order ID</th>
                                            <th class='th-spacing'>Date Of Order</th>
                                            <th class='th-spacing'>Time Of Order</th>
                                            <th class='th-spacing'>Order Type</th>
                                            <th class='th-spacing'>Order Status</th>
                                            <th class='th-spacing'>Total Amount</th>
                                            <th class='th-spacing'>Customer ID</th>
                                        </tr>";

                                    // Loop through order detail results
                                    while ($ordRow = mysqli_fetch_assoc($ordResult)){
                                        echo '<tr>';
                                        echo '<td>' . $ordRow['Order_ID'] . "</td>";
                                        echo "<td>" . $ordRow['Date_Of_Order'] . "</td>";
                                        echo "<td>" . $ordRow['Time_Of_Order'] . "</td>";
                                        echo "<td>" . $ordRow['Order_Type'] . "</td>";
                                        echo "<td>" . $ordRow['Order_Status'] . "</td>";
                                        echo "<td>" . $ordRow['Total_Amount'] . "</td>";
                                        echo "<td>" . $ordRow['O_Customer_ID'] . "</td>";
                                        echo "</tr>";
                                    }

                                    echo '</table>';
                                }
                            }
                        } else {
                            echo '<h2>' . $setHeader . '</h2>';
                            echo 'No order data available for store ' . $storeId;
                            if(($storeType === 'orderdates') || ($storeType === 'date')){
                                echo 'From ' . $stDate . ' to ' . $endDate;
                            }

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
                     $setHeader = 'Employee Details';
                     $employeeId = '0';
                     if(isset($_POST['employeeDropdown'])){
                        $employeeId = $_POST['employeeDropdown'];
                     } else {
                        $employeeId = 0;
                    }
                
                    $employeeSql = "SELECT  `E_First_Name`, `E_Last_Name`, `Title_Role`, `Hire_Date`, `assigned_orders`, `completed_orders` FROM `employee`
                    WHERE `Employee_ID` = $employeeId";

                     $employeeResult = mysqli_query($mysqli, $employeeSql);

                    if ($employeeResult) {
                        // Check if there are rows returned
                        if (mysqli_num_rows($employeeResult) > 0) {
                            echo '<h2>' . $setHeader . '<h2>';
                            echo "<table border='1' class='table_update'>";

                            echo "<tr>
                                    <th class='th-spacing'>First Name</th>
                                    <th class='th-spacing'>Last Name</th>
                                    <th class='th-spacing'>Title/Role</th>
                                    <th class='th-spacing'>Hire Date</th>
                                    <th class='th-spacing'>Assigned Orders</th>
                                    <th class='th-spacing'>Completed Orders</th>
                                </tr>";
                    
                            while ($row = mysqli_fetch_assoc($employeeResult)) {
                                echo "<tr>";
                                echo "<td>" . $row['E_First_Name'] . "</td>";
                                echo "<td>" . $row['E_Last_Name'] . "</td>";
                                echo "<td>" . $row['Title_Role'] . "</td>";
                                echo "<td>" . $row['Hire_Date'] . "</td>";
                                echo "<td>" . $row['assigned_orders'] . "</td>";
                                echo "<td>" . $row['completed_orders'] . "</td>";
                                echo "</tr>";
                            }
                    
                            echo "</table>";
                        } 
                        // Display Order Details Table
                        $orderSql = "SELECT `Order_ID`, `Date_Of_Order`, `Time_Of_Order`, `Order_Type`, `Order_Status`, `Total_Amount`, `O_Customer_ID`, `Store_ID` FROM `orders` WHERE `Employee_ID_assigned` = $employeeId";
                        $orderResult = mysqli_query($mysqli, $orderSql);

                    if ($orderResult) {
                        if (mysqli_num_rows($orderResult) > 0) {
                            echo '<h2>Order Details</h2>';
                            echo "<table border='1' class='table_update'>";
                            echo "<tr>
                                    <th class='th-spacing'>Order ID</th>
                                    <th class='th-spacing'>Date Of Order</th>
                                    <th class='th-spacing'>Time Of Order</th>
                                    <th class='th-spacing'>Order Type</th>
                                    <th class='th-spacing'>Order Status</th>
                                    <th class='th-spacing'>Total Amount</th>
                                    <th class='th-spacing'>Customer ID</th>
                                    <th class='th-spacing'>Store ID</th>
                                </tr>";

                            while ($orderRow = mysqli_fetch_assoc($orderResult)) {
                                echo "<tr>";
                                echo "<td>" . $orderRow['Order_ID'] . "</td>";
                                echo "<td>" . $orderRow['Date_Of_Order'] . "</td>";
                                echo "<td>" . $orderRow['Time_Of_Order'] . "</td>";
                                echo "<td>" . $orderRow['Order_Type'] . "</td>";
                                echo "<td>" . $orderRow['Order_Status'] . "</td>";
                                echo "<td>" . $orderRow['Total_Amount'] . "</td>";
                                echo "<td>" . $orderRow['O_Customer_ID'] . "</td>";
                                echo "<td>" . $orderRow['Store_ID'] . "</td>";
                                echo "</tr>";
                            }

                            echo "</table>";
                        }
                    } else {
                        echo 'Error executing the Order SQL query: ' . mysqli_error($mysqli);
                    }
                } else {
                    echo 'Error executing the Employee SQL query: ' . mysqli_error($mysqli);
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