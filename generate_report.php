<?php
// REPORTS QUERIES ARE ON THIS PAGE
// this page displays selected report from reports.php

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

date_default_timezone_set('America/Chicago');
$currentDate = date("Y-m-d");
?>

<!DOCTYPE html>
<html>

<head>
    <title>POS Pizza</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="icon" href="img/pizza.ico" type="image/x-icon">

    <!-- Scrollable area style: -->
    <style>
        /* Style for the scrollable area */
        .scrollable-area {
            min-height: auto;
            max-height: 500px;
            overflow: auto;
            border: 1px solid #ccc;
        }

        /* Style for the table */
        .table_update {
            border-collapse: collapse;
            width: 100%;
        }

        .table_update th,
        .table_update td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
    </style>
    <!-- End Scrollable Area Style -->

</head>

<body>
    <div class="navbar">
        <a href="index.php">Home</a>
        <a href="reports.php">Back to Reports</a>
        <?php echo '<a href="logout.php">Logout</a>'; ?>
        <a id="cart-button" style="background-color: transparent;"><?php echo 'Employee Role: ' . $_SESSION['user']['Title_Role']; ?></a>
    </div>
    <!-- submission button exports csv -->
    <form action="export_csv.php" method="post" style="background-color: rgba(119, 115, 115, 0.7)">
        <?php
        // code runs when reports.php submits form
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['reportType'])) {
                $reportType = $_POST['reportType'];
                $setHeader = '';
                $exportArray = array(); // array to export data to csv

                /////////////////////////
                ////INVENTORY QUERIES////
                /////////////////////////
                if ($reportType === 'inventory') {
                    // Check the value of the inventoryType
                    $inventoryType = $_POST['inventoryType'];
                    $sql = '';

                    // Get the selected store
                    if (isset($_POST['storeId'])) {
                        $storeId = $_POST['storeId'];
                    } else {
                        $storeId = '1';
                    }

                    if ($inventoryType === 'low') {
                        // Header for low stock items
                        $address = $mysqli->query("SELECT Store_Address, Store_City FROM pizza_store WHERE Pizza_Store_ID = '$storeId'");
                        if ($addressRow = $address->fetch_assoc()) {
                            $setHeader = 'Low Stock Items for ' . $addressRow['Store_Address'] . ' - ' .  $addressRow['Store_City']; //header with store address
                        }
                        // Query for low stock items
                        $sql = "SELECT I.Inventory_Amount, 
                        Items.Item_Name, 
                        Items.Cost_Of_Good, 
                        I.Last_Stock_Shipment_Date, 
                        I.Expiration_Date, 
                        V.Vendor_Name,
                        CONCAT(V.V_Rep_Fname, ' ', V.V_Rep_Lname) AS Vendor_Rep,
                        V.V_Email AS Vendor_Email, V.V_Phone AS Vendor_Phone, I.Store_Id
                        FROM INVENTORY I
                        INNER JOIN VENDOR V ON I.Vend_ID = V.Vendor_ID
                        INNER JOIN ITEMS ON I.Item_ID = Items.Item_ID
                        WHERE I.Inventory_Amount <= Items.Reorder_Threshold + 30 AND I.Store_ID = '$storeId';";
                    } elseif ($inventoryType === 'out') {
                        // Header for out of stock items
                        $address = $mysqli->query("SELECT Store_Address, Store_City FROM pizza_store WHERE Pizza_Store_ID = '$storeId'");
                        if ($addressRow = $address->fetch_assoc()) {
                            $setHeader = 'Out of Stock for ' . $addressRow['Store_Address'] . ' - ' .  $addressRow['Store_City']; //header with store address
                        }
                        // Query for out of stock items
                        $sql = "SELECT I.Inventory_Amount, 
                        Items.Item_Name, 
                        Items.Cost_Of_Good, 
                        I.Last_Stock_Shipment_Date, 
                        I.Expiration_Date, 
                        V.Vendor_Name,
                        CONCAT(V.V_Rep_Fname, ' ', V.V_Rep_Lname) AS Vendor_Rep,
                        V.V_Email AS Vendor_Email, V.V_Phone AS Vendor_Phone 
                        FROM INVENTORY I
                        INNER JOIN VENDOR V ON I.Vend_ID = V.Vendor_ID
                        INNER JOIN ITEMS ON I.Item_ID = Items.Item_ID
                        WHERE I.Inventory_Amount <= 0 AND I.Store_ID = '$storeId';";
                    } else {
                        // Header for all items
                        $address = $mysqli->query("SELECT Store_Address, Store_City FROM pizza_store WHERE Pizza_Store_ID = '$storeId'");
                        if ($addressRow = $address->fetch_assoc()) {
                            $setHeader = 'Inventory Report for ' . $addressRow['Store_Address'] . ' - ' .  $addressRow['Store_City']; //header with store address
                        }
                        // Query for all stock items
                        $sql = "SELECT I.Inventory_Amount, 
                        Items.Item_Name, 
                        Items.Cost_Of_Good, 
                        I.Last_Stock_Shipment_Date, 
                        I.Expiration_Date, 
                        V.Vendor_Name,
                        CONCAT(V.V_Rep_Fname, ' ', V.V_Rep_Lname) AS Vendor_Rep,
                        V.V_Email AS Vendor_Email, 
                        V.V_Phone AS Vendor_Phone 
                        FROM INVENTORY I
                        INNER JOIN VENDOR V ON I.Vend_ID = V.Vendor_ID
                        INNER JOIN ITEMS ON I.Item_ID = Items.Item_ID
                        WHERE I.Store_ID = '$storeId';";
                    }
                    // Execute the query
                    $result = mysqli_query($mysqli, $sql);
                    $exResult = mysqli_query($mysqli, $sql);

                    if ($result) {
                        // Check if there are rows returned
                        if (mysqli_num_rows($result) > 0) {

                            // Build exportArray
                            while ($row = mysqli_fetch_assoc($exResult)) {
                                $exportArray[] = $row;
                            }

                            // Header
                            echo '<h2>' . $setHeader . '</h2>';

                            // Start scrollable area
                            echo '<div class="scrollable-area">';

                            // Start of table
                            echo '<table border="1" class="table_update">';
                            echo "<tr>
                                <th class='th-spacing'>Product</th>
                                <th class='th-spacing'>Quantity in Stock</th>
                                <th class='th-spacing'>Last Ordered</th>
                                <th class='th-spacing'>Expiration Date</th>
                                <th class='th-spacing'>Cost</th>
                                <th class='th-spacing'>Vendor</th>
                                <th class='th-spacing'>Vendor Rep</th>
                                <th class='th-spacing'>Email</th>
                                <th class='th-spacing'>Phone</th>
                                </tr>";

                            // Loop through the results and display them in a table
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo '<tr>';
                                echo '<td>' . $row['Item_Name'] . '</td>';
                                echo '<td>' . $row['Inventory_Amount'] . '</td>';
                                echo "<td>" . $row['Last_Stock_Shipment_Date'] . "</td>";
                                echo "<td>" . $row['Expiration_Date'] . "</td>";
                                echo "<td>" . $row['Cost_Of_Good'] . "</td>";
                                echo '<td>' . $row['Vendor_Name'] . '</td>';
                                echo '<td>' . $row['Vendor_Rep'] . '</td>';
                                echo '<td>' . $row['Vendor_Email'] . '</td>';
                                echo '<td>' . $row['Vendor_Phone'] . '</td>';
                                echo '</tr>';
                            }

                            // End of table
                            echo '</table>';

                            // End of scrollable area
                            echo '</div>';

                            // Export to CSV button
                            echo '<input type="hidden" name="export_data" value="' . htmlspecialchars(json_encode($exportArray)) . '">';
                            echo '<input type="submit" class="button" name="export" value="Export to CSV">';
                        } else {
                            // if no data found
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
                    // Get the selected store
                    if (isset($_POST['storeId'])) {
                        $storeId = $_POST['storeId'];
                    } else {
                        $storeId = '1';
                    }

                    $storeType = $_POST['storeType'];
                    $sql = '';
                    $ordSql = '';

                    if ($storeType === 'orders') {
                        // Header for daily orders
                        $setHeader = 'Daily Orders';
                        // Query for daily orders
                        $sql = "SELECT Pizza_Store_ID,
                        Store_Address,
                        OrderCount,
                        Total_Sales,
                        Cost_Of_Goods,
                        (Total_Sales - Cost_Of_Goods) AS Profit_Margin
                        FROM(
                        SELECT P.Pizza_Store_ID, 
                        P.Store_Address, 
                        COUNT(O.Order_ID) AS OrderCount, 
                        SUM(O.Total_Amount) AS Total_Sales, 
                        SUM(O.Cost_Of_Goods) AS Cost_Of_Goods
                        FROM PIZZA_STORE P 
                        LEFT JOIN ORDERS O
                        ON P.Pizza_Store_ID = O.Store_ID
                        WHERE P.Pizza_Store_ID = '$storeId' AND DATE(O.Date_Of_Order) = '$currentDate'
                        GROUP BY P.Pizza_Store_ID, P.Store_Address)
                        AS Subquery;";
                        // Get order info for daily orders
                        $ordSql = "SELECT Order_ID, 
                        Date_Of_Order, 
                        Time_Of_Order, 
                        Order_Type, 
                        Order_Status, 
                        Total_Amount, 
                        Cost_Of_Goods,
                        Customer_ID,
                        Guest_ID
                        FROM ORDERS
                        WHERE Store_ID = '$storeId' AND DATE(Date_Of_Order) = '$currentDate';";
                    } elseif ($storeType === 'orderdates') {
                        // Get the selected date range
                        if (isset($_POST['stDate'])) {
                            $stDate = $_POST['stDate'];
                        } else {
                            // Default test values for stDate
                            $stDate = $currentDate;
                        }
                        if (isset($_POST['endDate'])) {
                            $endDate = $_POST['endDate'];
                        } else {
                            // Default test values for endDate
                            $endDate = $currentDate;
                        }

                        $setHeader = 'Orders from ' . $stDate . ' to ' .  $endDate; //header with date range

                        // Query for orders by date
                        $sql = "SELECT Pizza_Store_ID,
                        Store_Address,
                        OrderCount,
                        Total_Sales,
                        Cost_Of_Goods,
                        (Total_Sales - Cost_Of_Goods) AS Profit_Margin
                        FROM(
                        SELECT P.Pizza_Store_ID, 
                        P.Store_Address, 
                        COUNT(O.Order_ID) AS OrderCount, 
                        SUM(O.Total_Amount) AS Total_Sales, 
                        SUM(O.Cost_Of_Goods) AS Cost_Of_Goods
                        FROM PIZZA_STORE P 
                        LEFT JOIN ORDERS O
                        ON P.Pizza_Store_ID = O.Store_ID
                        WHERE P.Pizza_Store_ID = '$storeId' AND DATE(O.Date_Of_Order) BETWEEN '$stDate' AND '$endDate' 
                        GROUP BY P.Pizza_Store_ID, P.Store_Address)
                        AS Subquery;";
                        // Get order info for daily orders
                        $ordSql = "SELECT Order_ID, 
                        Date_Of_Order, 
                        Time_Of_Order, 
                        Order_Type, 
                        Order_Status, 
                        Total_Amount, 
                        Cost_Of_Goods,
                        Customer_ID,
                        Guest_ID
                        FROM ORDERS
                        WHERE Store_ID = '$storeId' AND DATE(Date_Of_Order) BETWEEN '$stDate' AND '$endDate';";
                    } elseif ($storeType === 'popular') {
                        // Header for popular items
                        $address = $mysqli->query("SELECT Store_Address,Store_City  FROM pizza_store WHERE Pizza_Store_ID = '$storeId'");
                        if ($addressRow = $address->fetch_assoc()) {
                            $setHeader = 'Most Popular items today from ' . $addressRow['Store_Address'] . ' - ' .  $addressRow['Store_City']; //header with store address
                        }

                        // query for popular items today
                        $sql = "SELECT 
                        I.Item_Name AS Most_Popular_Item, 
                        COUNT(OI.Item_ID) AS Item_Count,
                        I.Item_Cost, I.Cost_Of_Good,
                        (COUNT(OI.Item_ID) * I.Item_Cost) AS Total_Sales,
                        (COUNT(OI.Item_ID) * I.Cost_Of_Good) AS Cost_Of_Goods,
                        ((COUNT(OI.Item_ID) * I.Item_Cost) - (COUNT(OI.Item_ID) * I.Cost_Of_Good)) AS Profit_Margin
                        FROM ORDER_ITEMS OI
                        JOIN ORDERS O ON OI.Order_ID = O.Order_ID
                        JOIN ITEMS I ON OI.Item_ID = I.Item_ID
                        WHERE O.Store_ID = '$storeId' AND DATE(O.Date_Of_Order) = '$currentDate'
                        GROUP BY I.Item_Name, I.Item_Cost, I.Cost_Of_Good
                        ORDER BY Item_Count DESC
                        LIMIT 3;";
                    } elseif ($storeType === 'datepopular') {
                        // Get the selected date range
                        if (isset($_POST['stDate'])) {
                            $stDate = $_POST['stDate'];
                        } else {
                            // Default test values for stDate
                            $stDate = $currentDate;
                        }
                        if (isset($_POST['endDate'])) {
                            $endDate = $_POST['endDate'];
                        } else {
                            // Default test values for endDate
                            $endDate = $currentDate;
                        }

                        // Header for most popular item for date range
                        $setHeader = 'Most Popular items from ' . $stDate . ' to ' .  $endDate;
                        
                        // query for most popular item in date range
                        $sql = "SELECT 
                        I.Item_Name AS Most_Popular_Item, 
                        COUNT(OI.Item_ID) AS Item_Count,
                        I.Item_Cost, I.Cost_Of_Good,
                        (COUNT(OI.Item_ID) * I.Item_Cost) AS Total_Sales,
                        (COUNT(OI.Item_ID) * I.Cost_Of_Good) AS Cost_Of_Goods,
                        ((COUNT(OI.Item_ID) * I.Item_Cost) - (COUNT(OI.Item_ID) * I.Cost_Of_Good)) AS Profit_Margin
                        FROM ORDER_ITEMS OI
                        JOIN ORDERS O ON OI.Order_ID = O.Order_ID
                        JOIN ITEMS I ON OI.Item_ID = I.Item_ID
                        WHERE O.Store_ID = '$storeId' AND DATE(O.Date_Of_Order) BETWEEN '$stDate' AND '$endDate'
                        GROUP BY I.Item_Name, I.Item_Cost, I.Cost_Of_Good
                        ORDER BY Item_Count DESC
                        LIMIT 3;";
                    } 

                    // Execute the query
                    $result = mysqli_query($mysqli, $sql);
                    $exResult = mysqli_query($mysqli, $sql);

                    // Check to see if $ordSql set
                    if (!empty(trim($ordSql))) {
                        $ordResult = mysqli_query($mysqli, $ordSql);
                        $ordExResult = mysqli_query($mysqli, $ordSql);
                    }

                    if ($result) {
                        // Check if there are rows returned
                        if (mysqli_num_rows($result) > 0) {

                            // Build exportArray
                            if ($storeType === 'popular' || $storeType === 'datepopular') {
                                while ($row = mysqli_fetch_assoc($exResult)) {
                                    $exportArray[] = $row;
                                }
                            } else {
                                while ($row = mysqli_fetch_assoc($ordExResult)) {
                                    $exportArray[] = $row;
                                }
                            }

                            echo '<h2>' . $setHeader . '</h2>';

                            // Start of scrollable area
                            echo '<div class="scrollable-area">';

                            //Start of table
                            echo '<table border="1" class="table_update">';
                            // Returns table columns for popular item by day and date range
                            if ($storeType === 'popular' || $storeType === 'datepopular') {
                                echo "<tr>
                                        <th class='th-spacing'>Item Name</th>
                                        <th class='th-spacing'>Amount Sold</th>
                                        <th class='th-spacing'>Total Sales</th>
                                        <th class='th-spacing'>Cost of Goods</th>
                                        <th class='th-spacing'>Profit Margin</th>
                                    </tr>";

                            // Returns table columns for orders by day and orders by date range
                            } else {
                                echo "<tr>
                                        <th class='th-spacing'>Pizza Store ID</th>
                                        <th class='th-spacing'>Pizza Store Address</th>
                                        <th class='th-spacing'>Order Count</th>
                                        <th class='th-spacing'>Total Sales</th>
                                        <th class='th-spacing'>Cost of Goods</th>
                                        <th class='th-spacing'>Profit Margin</th>
                                    </tr>";
                            }
                            // Loop through the results and display them in a table
                            while ($row = mysqli_fetch_assoc($result)) {
                                // Populates columns for popular item by day and date range
                                if ($storeType === 'popular' || $storeType === 'datepopular') {
                                    echo '<tr>';
                                    echo '<td>' . $row['Most_Popular_Item'] . '</td>';
                                    echo '<td>' . $row['Item_Count'] . '</td>';
                                    echo '<td>' . $row['Total_Sales'] . '</td>';
                                    echo '<td>' . $row['Cost_Of_Goods'] . '</td>';
                                    echo '<td>' . $row['Profit_Margin'] . '</td>';
                                    echo '</tr>';

                                // Populates columns for orders by day and orders by date range
                                } else {
                                    echo '<tr>';
                                    echo '<td>' . $row['Pizza_Store_ID'] . '</td>';
                                    echo '<td>' . $row['Store_Address'] . '</td>';
                                    echo '<td>' . $row['OrderCount'] . '</td>';
                                    echo '<td>' . $row['Total_Sales'] . '</td>';
                                    echo '<td>' . $row['Cost_Of_Goods'] . '</td>';
                                    echo '<td>' . $row['Profit_Margin'] . '</td>';
                                    echo '</tr>';
                                }
                            }

                            // End of table
                            echo '</table>';

                            // End of scrollable area
                            echo '</div>';

                            // Should check if we have set $ordSql and that $ordResult populated
                            if (!empty(trim($ordSql)) && $ordResult) {
                                if (mysqli_num_rows($ordResult) > 0) {
                                    echo '<h2>Order Details</h2>';

                                    // Start scrollable area
                                    echo '<div class="scrollable-area">';

                                    // Start of table for order details on store report
                                    echo '<table border="1" class="table_update">';
                                    echo "<tr>
                                            <th class='th-spacing'>Order ID</th>
                                            <th class='th-spacing'>Date Of Order</th>
                                            <th class='th-spacing'>Time Of Order</th>
                                            <th class='th-spacing'>Order Type</th>
                                            <th class='th-spacing'>Order Status</th>
                                            <th class='th-spacing'>Total Amount</th>
                                            <th class='th-spacing'>Cost</th>
                                            <th class='th-spacing'>Customer ID</th>
                                        </tr>";

                                    // Loop through order detail results
                                    while ($ordRow = mysqli_fetch_assoc($ordResult)) {
                                        // populates order details
                                        echo '<tr>';
                                        echo '<td>' . $ordRow['Order_ID'] . "</td>";
                                        echo "<td>" . $ordRow['Date_Of_Order'] . "</td>";
                                        echo "<td>" . $ordRow['Time_Of_Order'] . "</td>";
                                        echo "<td>" . $ordRow['Order_Type'] . "</td>";
                                        echo "<td>" . $ordRow['Order_Status'] . "</td>";
                                        echo "<td>" . $ordRow['Total_Amount'] . "</td>";
                                        echo "<td>" . $ordRow['Cost_Of_Goods'] . "</td>";
                                        if (!empty($ordRow['Customer_ID'])) {
                                            echo "<td>" . $ordRow['Customer_ID'] . "</td>";
                                        } else {
                                            echo "<td>" . $ordRow['Guest_ID'] . "</td>";
                                        }
                                        echo "</tr>";
                                    }

                                    // End of table
                                    echo '</table>';

                                    // End of scrollable area
                                    echo '</div>';

                                    // Export to CSV button
                                    echo '<input type="hidden" name="export_data" value="' . htmlspecialchars(json_encode($exportArray)) . '">';
                                    echo '<input type="submit" class="button" name="export" value="Export to CSV">';
                                }
                            }
                        } else {
                            // no date found
                            echo '<h2>' . $setHeader . '</h2>';
                            echo 'No order data available for store ' . $storeId;
                            if (($storeType === 'orderdates') || ($storeType === 'date')) {
                                echo ' From ' . $stDate . ' to ' . $endDate;
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
                    // get employee info from reports
                    if (isset($_POST['employeeDropdown'])) {
                        $employeeId = $_POST['employeeDropdown'];
                    } else {
                        $employeeId = 0;
                    }

                    // get store location from reports
                    if (isset($_POST['storeId'])) {
                        $storeId = $_POST['storeId'];
                    } else {
                        $storeId = 1;
                    }

                    // get employee status from reports filter
                    $active_employee = $_POST['emp_status'];

                    $employeeSql = "SELECT  `E_First_Name`, `E_Last_Name`, `Title_Role`, `Hire_Date`, `assigned_orders`, `completed_orders` 
                                    FROM `employee`
                                    WHERE  `Store_ID` = '" . $storeId . "' AND `active_employee` = '" . $active_employee . "' AND  `Employee_ID` = $employeeId";


                    $employeeResult = mysqli_query($mysqli, $employeeSql);

                    $is_result = 0;

                    if ($employeeResult) {
                        if (mysqli_num_rows($employeeResult) > 0) {
                            $is_result = 1;
                            echo '<h2>' . $setHeader . '</h2>';

                            // Start scrollable area
                            echo '<div class="scrollable-area">';

                            echo "<table border='1' class='table_update'>";

                            // create columnds for employee details
                            echo "<tr>
                                    <th class='th-spacing'>First Name</th>
                                    <th class='th-spacing'>Last Name</th>
                                    <th class='th-spacing'>Title/Role</th>
                                    <th class='th-spacing'>Hire Date</th>
                                    <th class='th-spacing'>Assigned Orders</th>
                                    <th class='th-spacing'>Completed Orders</th>
                                </tr>";

                            while ($row = mysqli_fetch_assoc($employeeResult)) {
                                // populate data for employee details
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

                            // End of scrollable area
                            echo '</div>';
                        }

                        // Display Order Details Table
                        $orderSql = "SELECT Order_ID, Date_Of_Order, Time_Of_Order, Order_Type, Order_Status, Total_Amount, Customer_ID, Guest_ID, pizza_store.Store_Address
                                    FROM orders LEFT JOIN employee ON employee.Employee_ID = orders.Employee_ID_assigned LEFT JOIN pizza_store ON pizza_store.Pizza_Store_ID = orders.Store_ID
                                    WHERE Employee_ID_assigned = $employeeId AND orders.Store_ID = $storeId AND active_employee = $active_employee";

                        $orderResult = mysqli_query($mysqli, $orderSql);
                        $exResult = mysqli_query($mysqli, $orderSql);

                        if ($orderResult) {
                            if (mysqli_num_rows($orderResult) > 0) {

                                // Build exportArray
                                while ($row = mysqli_fetch_assoc($exResult)) {
                                    $exportArray[] = $row;
                                }

                                echo '<h2>Order Details</h2>';

                                // Start scrollable area
                                echo '<div class="scrollable-area">';

                                // create columns for assigned orders
                                echo "<table border='1' class='table_update' id='export-data'>";
                                echo "<tr>
                                        <th class='th-spacing'>Order ID</th>
                                        <th class='th-spacing'>Date Of Order</th>
                                        <th class='th-spacing'>Time Of Order</th>
                                        <th class='th-spacing'>Order Type</th>
                                        <th class='th-spacing'>Order Status</th>
                                        <th class='th-spacing'>Total Amount</th>
                                        <th class='th-spacing'>Customer ID</th>
                                        <th class='th-spacing'>Store Location</th>
                                    </tr>";

                                while ($orderRow = mysqli_fetch_assoc($orderResult)) {
                                    // populate order information
                                    echo "<tr>";
                                    echo "<td>" . $orderRow['Order_ID'] . "</td>";
                                    echo "<td>" . $orderRow['Date_Of_Order'] . "</td>";
                                    echo "<td>" . $orderRow['Time_Of_Order'] . "</td>";
                                    echo "<td>" . $orderRow['Order_Type'] . "</td>";
                                    echo "<td>" . $orderRow['Order_Status'] . "</td>";
                                    echo "<td>" . $orderRow['Total_Amount'] . "</td>";
                                    if (!empty($orderRow['Customer_ID'])) {
                                        echo "<td>" . $orderRow['Customer_ID'] . "</td>";
                                    } else {
                                        echo "<td>" . $orderRow['Guest_ID'] . "</td>";
                                    }
                                    echo "<td>" . $orderRow['Store_Address'] . "</td>";
                                    echo "</tr>";
                                }

                                echo "</table>";

                                // End of scrollable area
                                echo '</div>';

                                // Export to CSV button
                                echo '<input type="hidden" name="export_data" value="' . htmlspecialchars(json_encode($exportArray)) . '">';
                                echo '<input type="submit" class="button" name="export" value="Export to CSV">';
                            }
                        } else {
                            echo 'Error executing the Order SQL query: ' . mysqli_error($mysqli);
                        }
                    } else {
                        echo 'Error executing the Employee SQL query: ' . mysqli_error($mysqli);
                    }

                    // no data found
                    if ($is_result == 0) {
                        echo '<h2>No Records Found!</h2>';
                    }
                    // Close the database connection
                    mysqli_close($mysqli);
                }
                /////////////////////////
                //END EMPLOYEE QUERIES///
                /////////////////////////
            }
        }
        ?>

    </form>

</body>

</html>