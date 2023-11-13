<?php
    include 'database.php';
    // Start the session at the beginning of the file
    session_start();

    // * NEED TO UPDATE WHERE IT ONLY SHOWS ORDERS ASSIGNED TO SPECIFIC EMPLOPYEE *
    $sql = "SELECT * FROM orders;";
    $result = $mysqli->query($sql);

    $orderCount = $mysqli->query("SELECT COUNT(Order_ID) FROM orders");
    $getOrderCount = $orderCount->fetch_assoc();


    // Check if user is logged in
    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
        //access employee attributes
        if($_SESSION['user']['Title_Role'] == 'CEO'){
            echo "<h2>Welcome King " . $_SESSION['user']['E_First_Name'] . "!</h2>";
        } else {
            echo "<h2>Time to work, " . $_SESSION['user']['E_First_Name'] . "!</h2>";
        }
        
    } else {
        //if not logged in, will send to default URL
        header("Location: index.php");
        exit(); //ensures code is killed
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST["ORDERID"])) {
            // Retrieve orderID from POST data
            $ORDERID = $_POST["ORDERID"];
            $ORDERTYPE = $mysqli->real_escape_string($_POST['ORDERTYPE']);
            $TIME = $mysqli->real_escape_string($_POST['TIME']);

    
            $updateOrderStatus = "UPDATE orders SET Order_Status = 'Completed' WHERE Order_ID = $ORDERID";
            $runUpdate = $mysqli->query($updateOrderStatus);

            if ($ORDERTYPE == "Delivery") {
                $updateDelivery = "UPDATE delivery SET Time_Delivered = '$TIME' WHERE D_Order_ID = $ORDERID";
                $runUpdateDelivery = $mysqli->query($updateDelivery);
            } else if ($ORDERTYPE == "Pickup") {
                $updatePickUp = "UPDATE pickup SET PU_Time_Picked_Up = '$TIME' WHERE PU_Order_ID = $ORDERID";
                $runUpdatePickUp = $mysqli->query($updatePickUp);
            }

        } else {
            echo "orderID is not set in the POST data.";
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
    <body>
        <div class="navbar">
            <a href="index.php">Home</a>
            <!-- <a href="#">Order Now</a>
            <a href="#">Profile</a> -->
            <!-- if user is logged in, logout button will display -->
            <?php
            if ($_SESSION['user']['Title_Role'] == 'MAN' || $_SESSION['user']['Title_Role'] == 'CEO' && $_SERVER['REQUEST_URI'] != '/reports.php') {
                echo '<a href="reports.php">Reports</a>';
            }
            if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
                echo '<a href="logout.php">Logout</a>';
            }
            ?>
        </div>


        <form action="" method="post">
            <?php
                    if($_SESSION['user']['Title_Role'] == 'CEO'){
                        echo "<h2>CEO Actions</h2>";
                    } else {
                        echo "<h2>Employee Home Page</h2>";
                    }
            ?>
            
            <?php // only managers will see the create employee account button
                if ($_SESSION['user']['Title_Role'] == 'MAN' || $_SESSION['user']['Title_Role'] == 'CEO') {
                    echo '<a href="employee_register.php" class="button">Create employee accounts</a>';
                }
            ?>
            <?php // only managers will see the create employee account button
                if ($_SESSION['user']['Title_Role'] == 'MAN' || $_SESSION['user']['Title_Role'] == 'CEO') {
                    echo '<a href="update_employee.php" class="button">Update employee accounts</a>';
                }
            ?>
            <?php // only managers will see the create employee account button
                if ($_SESSION['user']['Title_Role'] == 'MAN' || $_SESSION['user']['Title_Role'] == 'CEO') {
                    echo '<a href="inventory.php" class="button">Order inventory</a>';
                }
            ?> 
            <br>
            <?php // only managers will see the create employee account button
                if ($_SESSION['user']['Title_Role'] == 'CEO') {
                    echo '<a href="create_store.php" class="button">Register new store</a>';
                }
            ?>
            <?php // only managers will see the create employee account button
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
                    <div class = "od-header">Total Assigned Orders: </div>
                    <div class = "main-holder">
                        <div class = "order-display">
                    
                            <?php while($row = mysqli_fetch_assoc($result)) { 
                                $customerID = $row["O_Customer_ID"];
                                $customerName = $mysqli->query("SELECT C.first_name, C.last_name FROM customers AS C WHERE $customerID = C.customer_id");
                                $getCustomerName = $customerName->fetch_assoc();
                            ?>

                                <div class = "order-card" style = "
                                    <?php if ($row["Order_Status"] == "Canceled") {
                                        echo "background-color: #ed9999";
                                    } else if ($row["Order_Status"] == "Completed") {
                                        echo "background-color: #aff0b4";
                                    } else if ($row["Order_Status"] == "In Progress") {
                                        echo "background-color: #e9f6bd";
                                    }
                                    ?>
                                    ">
                                        <div class = "order-card-info">
                                            <div class = "order-card-left">
                                                <p class = "order-id">Order ID: <?php echo $row["Order_ID"]; ?></p>
                                                <p class = "customer">Customer Name: <?php echo $getCustomerName["first_name"], " ", $getCustomerName["last_name"]; ?></p>
                                                <p class = "date">Date Order Placed: <?php echo $row["Date_Of_Order"]; ?></p>
                                                <p class = "time">Time Order Placed: <?php echo $row["Time_Of_Order"]; ?></p>
                                                <p class = "total">Total: $<?php echo $row["Total_Amount"]; ?></p>
                                            </div>
                                            <div class = "order-card-right">
                                                <p class = "items-ordered">Items Ordered: </p>

                                                <?php 
                                                    $orderID = $row["Order_ID"];
                                                    $itemsOrdered = $mysqli->query("SELECT M.Name FROM order_items AS I, menu AS M WHERE $orderID = I.Order_ID AND I.Item_ID = M.Pizza_ID");
                                                    while ($itemRow = mysqli_fetch_assoc($itemsOrdered)) {
                                                        echo "<p class = items>" . "-" . " " . $itemRow["Name"] . "</p>";
                                                    }
                                                ?>
                                                
                                                <p class = "type">Order Type: <?php echo $row["Order_Type"]; ?></p>
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

                message = "Successfull Updated Order Status for OrderID: " + ORDERID + " at " + TIME + " " + ORDERTYPE;
                alert(message);
                $.ajax({
                    type: "POST",
                    url: "employee_home.php",
                    data: { ORDERID: ORDERID, ORDERTYPE: ORDERTYPE, TIME: TIME }
                });
            }
        </script>
        

    </body>
</html>
