<?php
include 'database.php'; // Include the database connection details
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// echo "";
// $_SESSION['error'] = "Store location already exist";

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

    //pickup table
    $first_name = $mysqli->real_escape_string($_POST['first_name']);
    $last_name = $mysqli->real_escape_string($_POST['last_name']);
    
    //order table
    $Current_Date = $mysqli->real_escape_string($_POST['Current_Date']);
    $Current_Time = $mysqli->real_escape_string($_POST['Current_Time']);
    $Order_Type = "Pickup";

    //transaction table
    $Total_Amount_Charged = $mysqli->real_escape_string($_POST['Total_Amount_Charged']);
    $Amount_Tipped = $mysqli->real_escape_string($_POST['Amount_Tipped']);
    $Payment_Method = $mysqli->real_escape_string($_POST['Payment_Method']);
    $T_Date = $mysqli->real_escape_string($_POST['Current_Date']);
    $Time_Processed = $mysqli->real_escape_string($_POST['Current_Time']);

    // SQL to find an available employee at the selected store with the least number of assigned orders
    $findEmployeeSQL = "SELECT Employee_ID FROM employee  WHERE Store_ID = '$store_id' AND clocked_in = 1 
                            ORDER BY assigned_orders ASC LIMIT 1";

    // Start a transaction
    $mysqli->begin_transaction();

    try {
        $result = $mysqli->query($findEmployeeSQL);
        if ($result && $result->num_rows > 0) {
            //get the customer id of current user
            if (isset($_SESSION['user']['customer_id'])) {
                //get ID if customer is logged in
                $customerID = $_SESSION['user']['customer_id'];
            } else {
                //guest table
                $phone_number = $mysqli->real_escape_string(str_replace('-', '', $_POST['phone_number']));
                $email = $mysqli->real_escape_string($_POST['email']);
                $guestSQL = "INSERT INTO guest (G_Email, G_Phone_Number, G_First_Name, G_Last_Name)
                            VALUES ('$email', '$phone_number', '$first_name', '$last_name')";

                //get ID if guest
                if ($mysqli->query($guestSQL) === TRUE) {
                    $customerID = $mysqli->insert_id; // get new auto incremented ID
                } else {
                    header('Location: checkout.php');
                    exit;
                }
            }
            $employee = $result->fetch_assoc();
            $employee_id_assigned = $employee['Employee_ID'];
            $ordersSQL = "INSERT INTO orders (O_Customer_ID, Date_Of_Order, Time_Of_Order, Order_Type, Total_Amount, Store_ID, Employee_ID_assigned)
                        VALUES ('$customerID', '$Current_Date', '$Current_Time', '$Order_Type', '$Total_Amount_Charged', '$store_id', '$employee_id_assigned')";
            // Check if the orders table insertion was successful
            if ($mysqli->query($ordersSQL) === TRUE) {
                $Order_ID = $mysqli->insert_id; // Check this after the query

                // Inserting the data into the pickup table with the same Order_ID
                $pickupSQL = "INSERT INTO pickup (PU_Order_ID, PU_Date, PU_Time_Processed, employee, PU_firstName, PU_lastName)
                            VALUES ('$Order_ID', '$Current_Date', '$Current_Time', '$employee_id_assigned', '$first_name', '$last_name')";

                if ($mysqli->query($pickupSQL) === TRUE) {
                    // Inserting the data into the transactions table with the same Order_ID
                    $transactionSQL = "INSERT INTO transactions (T_Order_ID, Total_Amount_Charged, Amount_Tipped, Payment_Method, T_Date, Time_Processed)
                                VALUES ('$Order_ID', '$Total_Amount_Charged', '$Amount_Tipped', '$Payment_Method', '$Current_Date','$Current_Time')";
                    if ($mysqli->query($transactionSQL) === TRUE) {
                        // After successfully inserting into orders, deliveries, and transactions, assign the employee
                        $incrementAssignedOrdersSQL = "UPDATE employee 
                                                    SET assigned_orders = assigned_orders + 1 
                                                    WHERE Employee_ID = '$employee_id_assigned'";
                        if ($mysqli->query($incrementAssignedOrdersSQL) === TRUE) {
                            $mysqli->commit();
                            // Redirect to the thank you page
                            header('Location: thankyou.php');
                            exit;
                        } else {
                            throw new Exception("Error updating employee assignments: " . $mysqli->error);
                        }
                    } else {
                        throw new Exception("Error inserting into transactions: " . $mysqli->error);
                    }
                } else {
                    throw new Exception("Error inserting into delivery: " . $mysqli->error);
                }
            } else {
                throw new Exception("Error inserting into orders: " . $mysqli->error);
            }
        } else {
            echo "";
            $_SESSION['error'] = "Sorry, we're closed! We are POS!";
            header('Location: pickup.php');
            exit;
            // throw new Exception("No available employees to assign the order to.");
        }
    } catch (Exception $e) {
        $e->getMessage();
        // An exception has been thrown, rollback the transaction
        $mysqli->rollback();
        // header('Location: checkout.php');
        // Handle error, e.g., redirect to an error page or display an error message
        // ... [Error handling code]
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
            echo '<a href="update_customer.php">Profile</a>';
            echo '<a href="logout.php">Logout</a>';
        } else {
            echo '<a href="customer_login.php">Login</a>';
        }
        ?>
    </div>
    <form action="pickup.php" method="post">
        <h2>Pickup Information</h2>

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

        <!-- if customer if guest, name, phone number, and email will be prompted for guest table -->
        <?php
        if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin'] == true) { ?>
            <div>
                <label for="phone_number">Phone Number </label>
                <input type="tel" id="phone_number" name="phone_number" placeholder="Enter 10 digits" pattern="^\d{10}$|^\d{3}-\d{3}-\d{4}$" style="width: 120px;" required>
                <label for="email">Email </label>
                <!-- input requires "@" and "." -->
                <input type="email" id="email" name="email" placeholder="Enter email address" pattern=".*\..*" required>
            </div><br>
        <?php } ?>

        <div>
            <label for="first_name">Name </label>
            <input type="text" id="first_name" name="first_name" <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) { ?> value="<?php echo $_SESSION['user']['first_name']; ?>" <?php } ?> placeholder="First" style="width: 125px;" required>

            <label for="last_name"></label>
            <input type="text" id="last_name" name="last_name" <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) { ?> value="<?php echo $_SESSION['user']['last_name']; ?>" <?php } ?> placeholder="Last" style="width: 125px;" required>
        </div><br>

        <div>
            <label for="Amount">Amount </label>
            <input type="text" id="Amount" name="Amount" placeholder="Amount" style="width: 100px;" required>

            <label for="Amount_Tipped">Tip Amount </label>
            <input type="text" id="Amount_Tipped" name="Amount_Tipped" placeholder="Tip" style="width: 100px;">
        </div><br>

        <div>
            <label for="Total_Amount_Charged">Total Amount </label>
            <input type="text" id="Total_Amount_Charged" name="Total_Amount_Charged" placeholder="Total Amount" style="width: 100px;" required readonly>
        </div><br>

        <script>
            function calculateTotal() {
                var amount = parseFloat(document.getElementById('Amount').value) || 0;
                var tip = parseFloat(document.getElementById('Amount_Tipped').value) || 0;
                var total = amount + tip;
                document.getElementById('Total_Amount_Charged').value = total.toFixed(2);
            }
            document.getElementById('Amount').addEventListener('input', calculateTotal);
            document.getElementById('Amount_Tipped').addEventListener('input', calculateTotal);
        </script>

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