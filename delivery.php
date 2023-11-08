<?php
    include 'database.php'; // Include the database connection details
    session_start();
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    function getCartItemCount()
    {
        return count($_SESSION['cart']);
    }

    //carries the selected store from checkout page
    if (isset($_SESSION['selected_store_id'])) {
        $store_id = $_SESSION['selected_store_id'];
    } else {
        header('Location: checkout.php');
        exit;
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") { // Check if the form has been submitted
        //get the customer id of current user
        
        //handle if customer is a guest
        //INSERT INTO GUEST AND GET AUTO INCREMENTED ID
        
        //order table
        $customerID = $_SESSION['user']['customer_id'];
        $Current_Date = $mysqli->real_escape_string($_POST['Current_Date']);
        $Current_Time = $mysqli->real_escape_string($_POST['Current_Time']);
        $Order_Type = "Delivery";

        //delivery table
        $D_Address = $mysqli->real_escape_string($_POST['D_Address']);
        $D_Address2 = $mysqli->real_escape_string($_POST['D_Address2']);
        $D_City = $mysqli->real_escape_string($_POST['D_City']);
        $D_State = $mysqli->real_escape_string($_POST['D_State']);
        $D_Zip_Code = $mysqli->real_escape_string($_POST['D_Zip_Code']);
        // $Estimated_Delivery_Time = $mysqli->real_escape_string($_POST['Estimated_Delivery_Time']);
        // $Time_Delivered = $mysqli->real_escape_string($_POST['Time_Delivered']);
        
        //transaction table
        $Total_Amount_Charged = $mysqli->real_escape_string($_POST['Total_Amount_Charged']);
        $Amount_Tipped = $mysqli->real_escape_string($_POST['Amount_Tipped']);
        $Payment_Method = $mysqli->real_escape_string($_POST['Payment_Method']);
        $T_Date = $mysqli->real_escape_string($_POST['Current_Date']);
        $Time_Processed = $mysqli->real_escape_string($_POST['Current_Time']);
        

        // Inserting the data into the orders table first
        $ordersSQL = "INSERT INTO orders (O_Customer_ID, Date_Of_Order, Time_Of_Order, Order_Type, Total_Amount, Store_ID)
                VALUES ('$customerID', '$Current_Date', '$Current_Time', '$Order_Type', '$Total_Amount_Charged', '$store_id')";
        $mysqli->query($ordersSQL);
        $Order_ID = $mysqli->insert_id; // Retrieve the ID of the newly created order

        // Inserting the data into the delivery table with the same Order_ID
        $deliverySQL = "INSERT INTO delivery (D_Order_ID, D_Date, D_Time_Processed, D_Address, D_Address2, D_City, D_State, D_Zip_Code)
                VALUES ('$Order_ID', '$Current_Date', '$Current_Time', '$D_Address', '$D_Address2','$D_City', '$D_State', '$D_Zip_Code')";
        $mysqli->query($deliverySQL);

        // Inserting the data into the transactions table with the same Order_ID
        $transactionSQL = "INSERT INTO transactions (T_Order_ID, Total_Amount_Charged, Amount_Tipped, Payment_Method, T_Date, Time_Processed)
                VALUES ('$Order_ID', '$Total_Amount_Charged', '$Amount_Tipped', '$Payment_Method', '$Current_Date','$Current_Time')";
        $mysqli->query($transactionSQL);

        if ($mysqli->query($deliverySQL) === TRUE) {
            if ($mysqli->query($transactionSQL) === TRUE) {
                if ($mysqli->query($ordersSQL) === TRUE) {
                    $Order_ID = $mysqli->insert_id; // Retrieve the ID of the newly created order

                    // SQL to find an available employee at the selected store with the least number of assigned orders
                    $findEmployeeSQL = "SELECT Employee_ID FROM employee 
                                    WHERE Store_ID = '$store_id' AND clocked_in = 1 
                                    ORDER BY assigned_orders ASC LIMIT 1";

                    $result = $mysqli->query($findEmployeeSQL);
                    if ($result->num_rows > 0) {
                        $employee = $result->fetch_assoc();
                        $employee_id_assigned = $employee['Employee_ID'];

                        // Update the orders table with the assigned employee
                        $assignOrderEmployee = "UPDATE orders SET Employee_ID_assigned = '$employee_id_assigned' 
                                        WHERE Order_ID = '$Order_ID'";
                        
                        $assignDeilveryEmployee = "UPDATE delivery SET employee = '$employee_id_assigned' 
                                        WHERE Order_ID = '$Order_ID'";
                        $mysqli->query($assignDeilveryEmployee);
                        
                        if ($mysqli->query($assignOrderEmployee) === TRUE) {
                            // Employee assigned successfully
                            // Increment the assigned_orders for the selected employee
                            $incrementAssignedOrdersSQL = "UPDATE employee SET assigned_orders = assigned_orders + 1 
                                                    WHERE Employee_ID = '$employee_id_assigned'";
                            $mysqli->query($incrementAssignedOrdersSQL);

                            // Redirect to the thank you page
                            $mysqli->close();
                            header('Location: thankyou.php');
                            exit;
                        } else {
                            echo "Error assigning employee: " . $mysqli->error;
                        }
                    } else {
                        echo "No available employees to assign the order to.";
                    }
                } else {
                    echo "Error: " . $ordersSQL . "<br>" . $mysqli->error;
                }
            } else {
                echo "Error: " . $transactionSQL . "<br>" . $mysqli->error;
            }
        } else {
            echo "Error: " . $deliverySQL . "<br>" . $mysqli->error;
        }
    }
?>
<!DOCTYPE html>

<head>
    <title>POS Pizza Delivery Page</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="icon" href="img/pizza.ico" type="image/x-icon">
</head>

<body>
    <div class="navbar">
        <a href="index.php">Home</a>
        <a href="menu.php">Order now</a>
        <?php echo '<a href="checkout.php" id="cart-button">Cart (' . getCartItemCount() . ')</a>'; ?>
        <?php
        if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
            echo '<a href="update_profile.php">Profile</a>';
            echo '<a href="logout.php">Logout</a>';
        } else {
            echo '<a href="customer_login.php">Login</a>';
        }
        ?>
    </div>
    <form action="delivery.php" method="post">
        <h2>Delivery Information</h2>

        <!-- pulls current date and assigns to Delivery and transaction date -->
        <input type="hidden" id="Current_Date" name="Current_Date">
        <input type="hidden" id="Current_Time" name="Current_Time">
        <input type="hidden" id="Estimated_Order_Completion" name="Estimated_Order_Completion">
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const currentDate = new Date();
                const formattedDate = `${currentDate.getFullYear()}/${(currentDate.getMonth() + 1).toString().padStart(2, '0')}/${currentDate.getDate().toString().padStart(2, '0')}`;
                document.getElementById('Current_Date').value = formattedDate;

                const formattedTime = `${currentDate.getHours().toString().padStart(2, '0')}:${currentDate.getMinutes().toString().padStart(2, '0')}:${currentDate.getSeconds().toString().padStart(2, '0')}`;
                document.getElementById('Current_Time').value = formattedTime;

                // Estimated time ready (current time + 30 minutes)
                currentDate.setMinutes(currentDate.getMinutes() + 30);
                const estimatedTimeReady = `${currentDate.getHours().toString().padStart(2, '0')}:${currentDate.getMinutes().toString().padStart(2, '0')}:${currentDate.getSeconds().toString().padStart(2, '0')}`;
                document.getElementById('Estimated_Order_Completion').value = estimatedTimeReady;
            });
        </script>

        <div>
            <label for="D_Address">Address </label>
            <input type="text" id="D_Address" name="D_Address" <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) { ?> value="<?php echo $_SESSION['user']['address']; ?>"  <?php } ?> placeholder="Enter deilvery address" required>

            <label for="D_Address2">Address 2 </label>
            <input type="text" id="D_Address2" name="D_Address2" <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) { ?> value="<?php echo $_SESSION['user']['address2']; ?>"  <?php } ?> placeholder="Optional">
        </div><br>

        <div>
            <label for="D_City">City </label>
            <input type="text" id="D_City" name="D_City" <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) { ?> value="<?php echo $_SESSION['user']['city']; ?>"  <?php } ?>  placeholder="Enter city" style="width: 90px;" required>

            <label for="D_State">State </label>
            <select id="D_State" name="D_State" placeholder="Select state" style="width: 100px;" required>
                <option <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) { ?> value="<?php echo $_SESSION['user']['state']; ?>"  <?php } ?>> <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) { ?><?php echo $_SESSION['user']['state']; ?> <?php } ?></option>
                <option value="AL">Alabama</option> <option value="AK">Alaska</option>
                <option value="AZ">Arizona</option> <option value="AR">Arkansas</option>
                <option value="CA">California</option> <option value="CO">Colorado</option>
                <option value="CT">Connecticut</option> <option value="DE">Delaware</option>
                <option value="FL">Florida</option> <option value="GA">Georgia</option>
                <option value="HI">Hawaii</option> <option value="ID">Idaho</option>
                <option value="IL">Illinois</option> <option value="IN">Indiana</option>
                <option value="IA">Iowa</option> <option value="KS">Kansas</option>
                <option value="KY">Kentucky</option> <option value="LA">Louisiana</option>
                <option value="ME">Maine</option> <option value="MD">Maryland</option>
                <option value="MA">Massachusetts</option> <option value="MI">Michigan</option>
                <option value="MN">Minnesota</option> <option value="MS">Mississippi</option>
                <option value="MO">Missouri</option> <option value="MT">Montana</option>
                <option value="NE">Nebraska</option> <option value="NV">Nevada</option>
                <option value="NH">New Hampshire</option> <option value="NJ">New Jersey</option>
                <option value="NM">New Mexico</option> <option value="NY">New York</option>
                <option value="NC">North Carolina</option> <option value="ND">North Dakota</option>
                <option value="OH">Ohio</option> <option value="OK">Oklahoma</option>
                <option value="OR">Oregon</option> <option value="PA">Pennsylvania</option>
                <option value="RI">Rhode Island</option> <option value="SC">South Carolina</option>
                <option value="SD">South Dakota</option> <option value="TN">Tennessee</option>
                <option value="TX">Texas</option> <option value="UT">Utah</option>
                <option value="VT">Vermont</option> <option value="VA">Virginia</option>
                <option value="WA">Washington</option> <option value="WV">West Virginia</option>
                <option value="WI">Wisconsin</option> <option value="WY">Wyoming</option>
            </select>
            <label for="D_Zip_Code">Zip Code </label>
            <input type="text" id="D_Zip_Code" name="D_Zip_Code" <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) { ?> value="<?php echo $_SESSION['user']['zip_code']; ?>"  <?php } ?> placeholder="Enter Zip Code" pattern="\d{5}(-\d{4})?" style="width: 100px;" required>
        </div><br>

        <div>
            <label for="">Amount </label>
            <input type="text" placeholder="Amount" style="width: 100px;" required> <!--readonly -->

            <label for="Amount_Tipped">Tip Amount </label>
            <input type="text" id="Amount_Tipped" name="Amount_Tipped" placeholder="Tip" style="width: 100px;">
        </div><br>

        <div>
            <label for="Total_Amount_Charged">Total Amount </label>
            <input type="text" id="Total_Amount_Charged" name="Total_Amount_Charged" placeholder="Total Amount" style="width: 100px;" required> <!--readonly -->
        </div><br>

        <div>
            <select id="Payment_Method" name="Payment_Method" required>
                <option value="" selected disabled>Select Payment Method</option>
                <option value="Cash">Cash</option>
                <option value="Credit Card">Credit Card</option>
                <option value="Bitcoin">Bitcoin</option>
                <option value="V-Bucks">V-Bucks</option>
            </select>
        </div><br>


        <?php
        //displays error messages here 
        if (isset($_SESSION['error'])) {
            echo '<div id="errorMessage">' . $_SESSION['error'] . '</div>';
            unset($_SESSION['error']);  // Unset the error message after displaying it
        }
        ?>

        <div>
            <input class=button type="submit" value="Finalize Order">
    </form>
</body>

</html>