<?php
include 'database.php'; // Include the database connection details
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check if the checkout process was completed
if (empty($_SESSION['checkout_completed'])) {
    // Redirect them to the checkout page
    header('Location: checkout.php');
    exit;
}

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

//gets total price from checkout page
if (isset($_SESSION['totalPrice'])) {
    $totalPrice = $_SESSION['totalPrice'];
}
//gets cart from checkout page
if (isset($_SESSION['cart'])) {
    $cart = $_SESSION['cart'];
}



$discountAmount = 0;
if (isset($_SESSION['user']['customer_id'])) {
    $customerID = $_SESSION['user']['customer_id'];
    $checkDiscount = "SELECT store_credit FROM customers WHERE customer_id = '$customerID'";
    $result = $mysqli->query($checkDiscount);
    $row = $result->fetch_assoc();
    if ($row && $row['store_credit'] > 0) {
        $discountAmount = $row['store_credit'];
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") { // Check if the form has been submitted

    //order table
    $Current_Date = $mysqli->real_escape_string($_POST['Current_Date']);
    $Current_Time = $mysqli->real_escape_string($_POST['Current_Time']);
    $Order_Type = "Delivery";

    //delivery table
    $D_Address = $mysqli->real_escape_string($_POST['D_Address']);
    $D_Address2 = $mysqli->real_escape_string($_POST['D_Address2']);
    $D_City = $mysqli->real_escape_string($_POST['D_City']);
    $D_State = $mysqli->real_escape_string($_POST['D_State']);
    $D_Zip_Code = $mysqli->real_escape_string($_POST['D_Zip_Code']);

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
            //check if user is logged in as customer
            if (isset($_SESSION['user']['customer_id'])) {
                //get ID if customer is logged in
                $customerID = $_SESSION['user']['customer_id'];
            } else {
                //guest table
                $phone_number = $mysqli->real_escape_string(str_replace('-', '', $_POST['phone_number']));
                $first_name = $mysqli->real_escape_string($_POST['first_name']);
                $last_name = $mysqli->real_escape_string($_POST['last_name']);
                $guestSQL = "INSERT INTO guest (G_Phone_Number, G_First_Name, G_Last_Name)
                            VALUES ('$phone_number', '$first_name', '$last_name')";

                //get new ID if guest
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
                $Order_ID = $mysqli->insert_id; //assign new order ID

                //insert delivery table
                $deliverySQL = "INSERT INTO delivery (D_Order_ID, D_Date, D_Time_Processed, D_Address, D_Address2, D_City, D_State, D_Zip_Code, delivery_employeeID)
                            VALUES ('$Order_ID', '$Current_Date', '$Current_Time', '$D_Address', '$D_Address2','$D_City', '$D_State', '$D_Zip_Code', '$employee_id_assigned')";

                if ($mysqli->query($deliverySQL) === TRUE) {
                    // Update the customer's store credit after applying the discount
                    $newStoreCredit = $row['store_credit'] - $discountAmount;
                    $updateCustomerCredit = "UPDATE customers SET store_credit = $newStoreCredit WHERE customer_id = '$customerID'";
                    if (!$mysqli->query($updateCustomerCredit)) {
                        throw new Exception("Error updating customer's store credit: " . $mysqli->error);
                    }
                    //insert transaction table
                    $transactionSQL = "INSERT INTO transactions (T_Order_ID, Total_Amount_Charged, Amount_Tipped, Payment_Method, T_Date, Time_Processed)
                                VALUES ('$Order_ID', '$Total_Amount_Charged', '$Amount_Tipped', '$Payment_Method', '$Current_Date','$Current_Time')";
                    if ($mysqli->query($transactionSQL) === TRUE) {
                        // After successfully inserting into orders, deliveries, and transactions, assign the employee
                        $incrementAssignedOrdersSQL = "UPDATE employee 
                                                SET assigned_orders = assigned_orders + 1 
                                                WHERE Employee_ID = '$employee_id_assigned'";

                        if ($mysqli->query($incrementAssignedOrdersSQL) === TRUE) {
                            //adds to customers total spent to date
                            $addToCustomerTotal = "UPDATE customers
                                                    SET total_spent_toDate = total_spent_toDate + $Total_Amount_Charged 
                                                    WHERE customer_id = '$customerID'";
                            $result = $mysqli->query($addToCustomerTotal); //process update
                            
                            //LOOP THROUGH ITEMS IN CART TO ADD TO ORDER ITEMS AND UPDATE INVENTORY
                            foreach ($_SESSION['cart'] as $itemId) {
                                //check if item is in items table
                                $itemDetailsQuery = "SELECT Item_ID, Amount_per_order, Item_Cost AS Price, Item_Name AS Name FROM items WHERE Item_Name = ?";
                                $stmt = $mysqli->prepare($itemDetailsQuery);
                                $stmt->bind_param("s", $itemId);
                                $stmt->execute();
                                $itemDetailsResult = $stmt->get_result();

                                if ($itemDetailsResult->num_rows > 0) {
                                    $source = 'item'; //mark cart item as 'item'
                                } else {
                                    //else check menu table
                                    $itemDetailsQuery = "SELECT Pizza_ID AS Item_ID, Price, Name FROM menu WHERE Pizza_ID = ?";
                                    $stmt = $mysqli->prepare($itemDetailsQuery);
                                    $stmt->bind_param("i", $itemId);
                                    $stmt->execute();
                                    $itemDetailsResult = $stmt->get_result();
                                    if ($itemDetailsResult->num_rows > 0) {
                                        $source = 'menu';
                                    } else {
                                        continue;
                                    }
                                }

                                //holds attributes for cart item
                                $itemDetails = $itemDetailsResult->fetch_assoc();

                                // insert into order_items
                                $insertOrderItemSQL = "INSERT INTO order_items (Item_ID, Order_ID, Price, Item_Name, Date_Ordered) 
                                                       VALUES (?, ?, ?, ?, ?)";
                                $insertStmt = $mysqli->prepare($insertOrderItemSQL);
                                $insertStmt->bind_param("iidss", $itemDetails['Item_ID'], $Order_ID, $itemDetails['Price'], $itemDetails['Name'], $Current_Date);

                                // UPDATE INVENTORY
                                if ($insertStmt->execute()) {
                                    if ($source === 'item') {
                                        // Update inventory for items int the 'items' table
                                        $updateInventorySQL = "UPDATE inventory SET Inventory_Amount = Inventory_Amount - {$itemDetails['Amount_per_order']} 
                                                            WHERE Item_ID = {$itemDetails['Item_ID']} AND Store_ID = $store_id";
                                        $mysqli->query($updateInventorySQL);

                                    } else if ($source === 'menu') {
                                        // Check if the menu item is a pizza
                                        $pizzaDetailsQuery = "SELECT Is_Pizza, Size_Option FROM menu WHERE Pizza_ID = ?";
                                        $pizzaStmt = $mysqli->prepare($pizzaDetailsQuery);
                                        $pizzaStmt->bind_param("i", $itemDetails['Item_ID']);
                                        $pizzaStmt->execute();
                                        $pizzaDetailsResult = $pizzaStmt->get_result();

                                        if ($pizzaDetails = $pizzaDetailsResult->fetch_assoc()) {
                                            if ($pizzaDetails['Is_Pizza']) {
                                                // Determine how much dough to subtract based on pizza size
                                                $doughAmountToSubtract = 0;
                                                switch ($pizzaDetails['Size_Option']) {
                                                    case 'S':
                                                        $doughAmountToSubtract = 0.3;
                                                        break;
                                                    case 'M':
                                                        $doughAmountToSubtract = 0.5;
                                                        break;
                                                    case 'L':
                                                        $doughAmountToSubtract = 0.7;
                                                        break;
                                                    case 'XL':
                                                        $doughAmountToSubtract = 0.9;
                                                        break;
                                                }

                                                // Get the Item_ID for dough from the items table
                                                $doughIdQuery = "SELECT Item_ID FROM items WHERE Item_Name = 'dough'";
                                                $doughIdResult = $mysqli->query($doughIdQuery);
                                                if ($doughIdResult && $doughRow = $doughIdResult->fetch_assoc()) {
                                                    $doughItemId = $doughRow['Item_ID'];

                                                    // Update inventory for dough based on the pizza size
                                                    $updateDoughInventorySQL = "UPDATE inventory 
                                                                                SET Inventory_Amount = Inventory_Amount - $doughAmountToSubtract
                                                                                WHERE Item_ID = $doughItemId AND Store_ID = $store_id";
                                                    $mysqli->query($updateDoughInventorySQL);

                                                } else {
                                                    echo "Error: Dough Item_ID not found.";
                                                }
                                            } else { //if not pizza
                                                continue; //later update for side products
                                            }
                                        }
                                    }

                                } //END UPDATE INVENTORY  
                                else {
                                    // Handle error for failed insertion into order_items
                                    echo "Error inserting into order_items: " . $insertStmt->error;
                                    break;
                                }

                                if ($mysqli->error) {
                                    break;
                                }
                            } //end cart item loop

                            $_SESSION['cart'] = []; //empties cart after everything
                            $_SESSION['order_completed'] = true; //variable to restrict access to thank you page
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
            header('Location: delivery.php');
            exit;
        }
    } catch (Exception $e) {
        $e->getMessage();
        $mysqli->rollback();
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
    <form action="delivery.php" method="post">
        <h2>Delivery Information</h2>

        <!-- pulls current date and assigns to Delivery and transaction date -->
        <input type="hidden" id="Current_Date" name="Current_Date">
        <input type="hidden" id="Current_Time" name="Current_Time">
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const currentDate = new Date();
                const formattedDate = `${currentDate.getFullYear()}/${(currentDate.getMonth() + 1).toString().padStart(2, '0')}/${currentDate.getDate().toString().padStart(2, '0')}`;
                document.getElementById('Current_Date').value = formattedDate;

                const formattedTime = `${currentDate.getHours().toString().padStart(2, '0')}:${currentDate.getMinutes().toString().padStart(2, '0')}:${currentDate.getSeconds().toString().padStart(2, '0')}`;
                document.getElementById('Current_Time').value = formattedTime;
            });
        </script>


        <!-- if customer if guest, name and phone number will be prompted for guest table -->
        <?php
        if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin'] == true) { ?>
            <div>
                <label for="phone_number">Phone Number </label>
                <input type="tel" id="phone_number" name="phone_number" placeholder="Enter 10 digits" pattern="^\d{10}$|^\d{3}-\d{3}-\d{4}$" style="width: 120px;" required>

                <label for="first_name">Name </label>
                <input type="text" id="first_name" name="first_name" placeholder="First" style="width: 75px;" required>

                <label for="last_name"></label>
                <input type="text" id="last_name" name="last_name" placeholder="Last" style="width: 75px;" required>
            </div><br>
        <?php } ?>

        <div>
            <label for="D_Address">Address </label>
            <input type="text" id="D_Address" name="D_Address" <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) { ?> value="<?php echo $_SESSION['user']['address']; ?>" <?php } ?> placeholder="Enter deilvery address" required>

            <label for="D_Address2">Address 2 </label>
            <input type="text" id="D_Address2" name="D_Address2" <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) { ?> value="<?php echo $_SESSION['user']['address2']; ?>" <?php } ?> placeholder="Optional">
        </div><br>

        <div>
            <label for="D_City">City </label>
            <input type="text" id="D_City" name="D_City" <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) { ?> value="<?php echo $_SESSION['user']['city']; ?>" <?php } ?> placeholder="Enter city" style="width: 90px;" required>

            <label for="D_State">State </label>
            <select id="D_State" name="D_State" placeholder="Select state" style="width: 100px;" required>
                <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) : ?>
                    <option value="<?php echo $_SESSION['user']['state']; ?>" selected> <?php echo $_SESSION['user']['state']; ?> </option>
                <?php else : ?> <option value="" selected disabled>Select</option> <?php endif; ?>
                <option value="AL">Alabama</option><option value="AK">Alaska</option>
                <option value="AZ">Arizona</option><option value="AR">Arkansas</option>
                <option value="CA">California</option><option value="CO">Colorado</option>
                <option value="CT">Connecticut</option><option value="DE">Delaware</option>
                <option value="FL">Florida</option><option value="GA">Georgia</option>
                <option value="HI">Hawaii</option><option value="ID">Idaho</option>
                <option value="IL">Illinois</option><option value="IN">Indiana</option>
                <option value="IA">Iowa</option><option value="KS">Kansas</option>
                <option value="KY">Kentucky</option><option value="LA">Louisiana</option>
                <option value="ME">Maine</option><option value="MD">Maryland</option>
                <option value="MA">Massachusetts</option><option value="MI">Michigan</option>
                <option value="MN">Minnesota</option><option value="MS">Mississippi</option>
                <option value="MO">Missouri</option><option value="MT">Montana</option>
                <option value="NE">Nebraska</option><option value="NV">Nevada</option>
                <option value="NH">New Hampshire</option><option value="NJ">New Jersey</option>
                <option value="NM">New Mexico</option><option value="NY">New York</option>
                <option value="NC">North Carolina</option><option value="ND">North Dakota</option>
                <option value="OH">Ohio</option><option value="OK">Oklahoma</option>
                <option value="OR">Oregon</option><option value="PA">Pennsylvania</option>
                <option value="RI">Rhode Island</option><option value="SC">South Carolina</option>
                <option value="SD">South Dakota</option><option value="TN">Tennessee</option>
                <option value="TX">Texas</option><option value="UT">Utah</option>
                <option value="VT">Vermont</option><option value="VA">Virginia</option>
                <option value="WA">Washington</option><option value="WV">West Virginia</option>
                <option value="WI">Wisconsin</option><option value="WY">Wyoming</option>
            </select>
            <label for="D_Zip_Code">Zip Code </label>
            <input type="text" id="D_Zip_Code" name="D_Zip_Code" <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) { ?> value="<?php echo $_SESSION['user']['zip_code']; ?>" <?php } ?> placeholder="Enter Zip Code" pattern="\d{5}(-\d{4})?" style="width: 100px;" required>
        </div><br>

        <div>
            <label for="Amount">Amount </label>
            <input type="text" id="Amount" name="Amount" <?php if (isset($_SESSION['totalPrice'])) { ?> value=" <?php echo $totalPrice ?>" <?php } ?> placeholder="Amount" style="width: 100px;" required readonly>

            <label for="Amount_Tipped">Tip Amount </label>
            <input type="number" id="Amount_Tipped" name="Amount_Tipped" min=0 placeholder="Tip" style="width: 100px;">
        </div><br>

        <?php
        if ($discountAmount > 0) { ?>
            <div>
                <label for="Discount_Amount">Discount Applied </label>
                <input type="text" id="Discount_Amount" name="Discount_Amount" value="<?php echo $discountAmount; ?>" placeholder="Discount Amount" style="width: 100px;" required readonly>
            <?php } else { ?>
                <div>
                <?php } ?>

                <label for="Total_Amount_Charged">Total Amount </label>
                <input type="text" id="Total_Amount_Charged" name="Total_Amount_Charged" placeholder="Total Amount" style="width: 100px;" required readonly>
                </div><br>


                <script>
                    function calculateTotal() {
                        var amount = parseFloat(document.getElementById('Amount').value) || 0;
                        var tip = parseFloat(document.getElementById('Amount_Tipped').value) || 0;
                        var discount = parseFloat(<?php echo $discountAmount; ?>) || 0;
                        var total = amount + tip - discount;
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