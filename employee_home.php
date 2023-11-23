<?php
/* this page is multi purposed based on employee's role
For team members and supervisors, displays orders assigned to them and allows them to mark orders completed
This will update the employee table by incrementing completed orders, decrementing assigned orders, updating time completed in orders table,
and time delivered/picked up in respective delivery/pickup table
For CEO and managers, displays options to add/update employees, and add inventory, stores, and menu items */

// Start the session at the beginning of the file
session_start();
include 'database.php';

// Redirects to login if not employee/accessed via URL
if (!isset($_SESSION['user']['Title_Role'])) {
    header("Location: employee_login.php");
    exit; // Make sure to exit so that the rest of the script won't execute
}

// Gets ID of the user currently logged in
$EMPID = $_SESSION['user']['Employee_ID'];

// Sets current filter for order to ALL
$STATUS = "ALL";
$sql = "SELECT * FROM orders WHERE Employee_ID_assigned = $EMPID";
$result = $mysqli->query($sql);

// Sets order count to order status of ALL
$orderCount = $mysqli->query("SELECT COUNT(Order_ID) FROM orders WHERE Employee_ID_assigned = $EMPID");
$getOrderCount = $orderCount->fetch_assoc();


// Check if user is logged in
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
    // welcome message for employee
    if ($_SESSION['user']['Title_Role'] == 'CEO') {
        echo "<h2>Welcome King " . $_SESSION['user']['E_First_Name'] . "!</h2>";
    } else {
        echo "<h2>Time to work, " . $_SESSION['user']['E_First_Name'] . "!</h2>";
    }
} else {
    //if not logged in, will send to default URL
    header("Location: index.php");
    exit();
}

// RUN code once POST 
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST["STATUS"]))
        $STATUS = $_POST["STATUS"];
    // Changes query of orders displayed based on which filter user clicks
    if ($STATUS == "ALL") {
        $sql = "SELECT * FROM orders WHERE Employee_ID_assigned = $EMPID";
        $orderCount = $mysqli->query("SELECT COUNT(Order_ID) FROM orders WHERE Employee_ID_assigned = $EMPID");
    } else if ($STATUS == "COMPLETED") {
        $sql = "SELECT * FROM orders WHERE Employee_ID_assigned = $EMPID AND Order_Status = 'Completed'";
        $orderCount = $mysqli->query("SELECT COUNT(Order_ID) FROM orders WHERE Employee_ID_assigned = $EMPID AND Order_Status = 'Completed'");
    } else if ($STATUS == "IN PROGRESS") {
        $sql = "SELECT * FROM orders WHERE Employee_ID_assigned = $EMPID AND Order_Status = 'In Progress'";
        $orderCount = $mysqli->query("SELECT COUNT(Order_ID) FROM orders WHERE Employee_ID_assigned = $EMPID AND Order_Status = 'In Progress'");
    }

    $getOrderCount = $orderCount->fetch_assoc();
    $result = $mysqli->query($sql);

    if (isset($_POST["ORDERID"])) {
        // Retrieve orderID from POST data
        $ORDERID = $_POST["ORDERID"];
        $ORDERTYPE = $mysqli->real_escape_string($_POST['ORDERTYPE']);
        $TIME = $mysqli->real_escape_string($_POST['TIME']);

        // Updates order status of specific ORDERIF to complete
        $updateOrderStatus = "UPDATE orders SET Order_Status = 'Completed' WHERE Order_ID = $ORDERID";
        $runUpdate = $mysqli->query($updateOrderStatus);

        // Update completion time of order once order is complete
        $timeComplete = "UPDATE orders SET Time_Completed = '$TIME' WHERE Order_ID = $ORDERID";
        $updateTimeCompleted = $mysqli->query($timeComplete);

        // Update number of completed orders for employee
        $updateNumCompletedOrder = $mysqli->query("UPDATE employee SET completed_orders = completed_orders + 1 WHERE Employee_ID = $EMPID");
        $updateAssignedOrders = $mysqli->query("UPDATE employee SET assigned_orders = assigned_orders - 1 WHERE Employee_ID = $EMPID");

        // Update in respective DELIVERY or PICKUP tables 
        if ($ORDERTYPE == "Delivery") {
            $updateDelivery = "UPDATE delivery SET Time_Delivered = '$TIME' WHERE D_Order_ID = $ORDERID";
            $runUpdateDelivery = $mysqli->query($updateDelivery);
        } else if ($ORDERTYPE == "Pickup") {
            $updatePickUp = "UPDATE pickup SET PU_Time_Picked_Up = '$TIME' WHERE PU_Order_ID = $ORDERID";
            $runUpdatePickUp = $mysqli->query($updatePickUp);
        }
    }
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>POS Pizza Employees</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="icon" href="img/pizza.ico" type="image/x-icon">
</head>

<body id="order-display">
    <div class="navbar">
        <a href="index.php">Home</a>
        <?php
        if ($_SESSION['user']['Title_Role'] == 'MAN' || $_SESSION['user']['Title_Role'] == 'CEO' && $_SERVER['REQUEST_URI'] != '/reports.php') {
            echo '<a href="reports.php">Reports</a>';
        }
        echo '<a href="logout.php">Logout</a>';
        ?>
        <a id="cart-button" style="background-color: transparent;"><?php echo 'Employee Role: ' . $_SESSION['user']['Title_Role']; ?></a>
    </div>


    <form action="" method="post">
        <?php
        if ($_SESSION['user']['Title_Role'] == 'CEO') {
            echo "<h2>CEO Actions</h2>";
        } else {
            echo "<h2>Employee Home Page</h2>";
        }
        ?>

        <?php // // only managers and CEO can create employees
        if ($_SESSION['user']['Title_Role'] == 'MAN' || $_SESSION['user']['Title_Role'] == 'CEO') {
            echo '<a href="employee_register.php" class="button">Create employee accounts</a>';
        }
        ?>
        <?php // // only managers and CEO can update employee accounts
        if ($_SESSION['user']['Title_Role'] == 'MAN' || $_SESSION['user']['Title_Role'] == 'CEO') {
            echo '<a href="update_employee.php" class="button">Update employee accounts</a>';
        }
        ?>
        <?php // only managers and CEO can order inventory
        if ($_SESSION['user']['Title_Role'] == 'MAN' || $_SESSION['user']['Title_Role'] == 'CEO') {
            echo '<a href="inventory.php" class="button">Order inventory</a>';
        }
        ?>
        <br>
        <?php // only CEO can add new store locations
        if ($_SESSION['user']['Title_Role'] == 'CEO') {
            echo '<a href="create_store.php" class="button">Register new store</a>';
        }
        ?>
        <?php // only CEO can add new menu items
        if ($_SESSION['user']['Title_Role'] == 'CEO') {
            echo '<a href="create_menuItem.php" class="button">Add menu item</a>';
        }
        ?>
    </form>

    <?php
    // only shows orders for team members and supervisor roles
    if (!isset($_SESSION['user']['Title_Role']) || ($_SESSION['user']['Title_Role'] !== 'CEO' && $_SESSION['user']['Title_Role'] !== 'MAN')) {
    ?>
        <main>
            <!-- Displays buttons to fiter through status of orders -->
            <div class="od-header">
                <div class="filterALL" style="cursor: pointer;" onclick='filter("ALL")'>
                    <p class="filterLabel">ALL</p>
                </div>
                <div class="filterALL" style="cursor: pointer;" onclick='filter("COMPLETED")'>
                    <p class="filterLabel">Completed</p>
                </div>
                <div class="filterALL" style="cursor: pointer;" onclick='filter("IN PROGRESS")'>
                    <p class="filterLabel">In Progress</p>
                </div>
                <p class="count"> Total Assigned Orders: <?php echo $getOrderCount['COUNT(Order_ID)'] ?> </p>
            </div>

            <script>
                function filter(STATUS) {
                    $.ajax({
                        type: "POST",
                        url: "employee_home.php",
                        data: {
                            STATUS: STATUS
                        },
                        success: function(response) {
                            // Update the resultContainer with the new result
                            $("#order-display").html(response);
                        }
                    });
                }
            </script>

            <!-- displays each order assigned to employee logged in -->
            <div class="main-holder">
                <div class="order-display">

                    <?php while ($row = mysqli_fetch_assoc($result)) {
                        $customerID = $row["Customer_ID"];
                        $customerName = $mysqli->query("SELECT C.first_name, C.last_name FROM customers AS C WHERE $customerID = C.customer_id");
                        $getCustomerName = $customerName->fetch_assoc();
                    ?>

                        <div class="order-card" style="
                                    <?php if ($row["Order_Status"] == "Canceled") {
                                        echo "background-color: #ed9999";
                                    } else if ($row["Order_Status"] == "Completed") {
                                        echo "background-color: #aff0b4";
                                    } else if ($row["Order_Status"] == "In Progress") {
                                        echo "background-color: #e9f6bd";
                                    }
                                    ?>
                                    ">
                            <div class="order-card-info">
                                <div class="order-card-left">
                                    <p class="order-id">Order ID: <?php echo $row["Order_ID"]; ?></p>
                                    <p class="customer">Customer Name: <?php echo $getCustomerName["first_name"], " ", $getCustomerName["last_name"]; ?></p>
                                    <p class="date">Date Order Placed: <?php echo $row["Date_Of_Order"]; ?></p>
                                    <p class="time">Time Order Placed: <?php echo $row["Time_Of_Order"]; ?></p>
                                    <p class="total">Total: $<?php echo $row["Total_Amount"]; ?></p>
                                </div>
                                <div class="order-card-right">
                                    <p class="items-ordered">Items Ordered: </p>

                                    <?php
                                    $orderID = $row["Order_ID"];
                                    $itemsOrdered = $mysqli->query("SELECT M.Name FROM order_items AS I, menu AS M WHERE $orderID = I.Order_ID AND I.Item_ID = M.Pizza_ID");
                                    while ($itemRow = mysqli_fetch_assoc($itemsOrdered)) {
                                        echo "<p class = items>" . "-" . " " . $itemRow["Name"] . "</p>";
                                    }
                                    ?>

                                    <p class="type">Order Type: <?php echo $row["Order_Type"]; ?></p>
                                    <?php
                                    if ($row["Order_Status"] == "Completed") {
                                        echo "<p class = time_completed>" . "Time Completed: " . $row["Time_Completed"] . "</p>";
                                    }
                                    ?>
                                </div>
                                <?php
                                $orderID = $row["Order_ID"];
                                $orderType = $row["Order_Type"];
                                if ($row["Order_Status"] == "Completed") {
                                    echo "<p class = status>" . $row["Order_Status"] . "</p>";
                                } else if ($row["Order_Status"] == "In Progress") {
                                    echo "<div class = complete_button onclick = completeOrder(" . $orderID . ',' . '"' . $orderType . '"' . ")>" . "<input type=hidden id=Current_Time name=Current_Time>" . "<p class = status>" . $row["Order_Status"] . "</p>" . "</div>";
                                }
                                ?>

                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </main>
    <?php } ?>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        function completeOrder(ORDERID, ORDERTYPE) {
            const currentDate = new Date();
            const formattedDate = `${currentDate.getFullYear()}/${(currentDate.getMonth() + 1).toString().padStart(2, '0')}/${currentDate.getDate().toString().padStart(2, '0')}`;

            const TIME = `${currentDate.getHours().toString().padStart(2, '0')}:${currentDate.getMinutes().toString().padStart(2, '0')}:${currentDate.getSeconds().toString().padStart(2, '0')}`;
            document.getElementById('Current_Time').value = TIME;

            message = "Successfull Updated Order Status for OrderID: " + ORDERID + " at " + TIME;
            alert(message);
            $.ajax({
                type: "POST",
                url: "employee_home.php",
                data: {
                    ORDERID: ORDERID,
                    ORDERTYPE: ORDERTYPE,
                    TIME: TIME
                }
            });
        }
    </script>


</body>

</html>