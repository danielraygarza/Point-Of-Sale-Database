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

//gets total Cost of goods from checkout page
if (isset($_SESSION['totalCOG'])) {
    $totalCOG = $_SESSION['totalCOG'];
}

//gets cart from checkout page
if (isset($_SESSION['cart'])) {
    $cart = $_SESSION['cart'];
}

//if logged in, assign store credit to discount
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

    //pickup table
    $first_name = $mysqli->real_escape_string($_POST['first_name']);
    $last_name = $mysqli->real_escape_string($_POST['last_name']);
    
    //order table
    $Current_Date = $mysqli->real_escape_string($_POST['Current_Date']);
    $Current_Time = $mysqli->real_escape_string($_POST['Current_Time']);
    $Cost_Of_Goods = $mysqli->real_escape_string($_POST['Cost_Of_Goods']);
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
                $guestSQL = "INSERT INTO guest (G_Phone_Number, G_First_Name, G_Last_Name)
                            VALUES ('$phone_number', '$first_name', '$last_name')";

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
            $ordersSQL = "INSERT INTO orders (Customer_ID, Date_Of_Order, Time_Of_Order, Order_Type, Total_Amount, Store_ID, Employee_ID_assigned, Cost_Of_Goods)
                        VALUES ('$customerID', '$Current_Date', '$Current_Time', '$Order_Type', '$Total_Amount_Charged', '$store_id', '$employee_id_assigned', '$totalCOG')";
            // Check if the orders table insertion was successful
            if ($mysqli->query($ordersSQL) === TRUE) {
                $Order_ID = $mysqli->insert_id; // Check this after the query

                // Inserting the data into the pickup table with the same Order_ID
                $pickupSQL = "INSERT INTO pickup (PU_Order_ID, PU_Date, PU_Time_Processed, employee, PU_firstName, PU_lastName)
                            VALUES ('$Order_ID', '$Current_Date', '$Current_Time', '$employee_id_assigned', '$first_name', '$last_name')";

                if ($mysqli->query($pickupSQL) === TRUE) {
                    // Update the customer's store credit after applying the discount
                    if (isset($_SESSION['user']['customer_id'])) {
                        $newStoreCredit = $row['store_credit'] - $discountAmount;
                        $updateCustomerCredit = "UPDATE customers SET store_credit = $newStoreCredit WHERE customer_id = '$customerID'";
                        if (!$mysqli->query($updateCustomerCredit)) {
                            throw new Exception("Error updating customer's store credit: " . $mysqli->error);
                            }
                    }
                    // Inserting the data into the transactions table with the same Order_ID
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
                                                // Determine how much dough/sauce/cheese to subtract based on pizza size
                                                $ingredientAmountToSubtract = 0;
                                                switch ($pizzaDetails['Size_Option']) {
                                                    case 'S':
                                                        $ingredientAmountToSubtract = 0.5;
                                                        break;
                                                    case 'M':
                                                        $ingredientAmountToSubtract = 1.0;
                                                        break;
                                                    case 'L':
                                                        $ingredientAmountToSubtract = 1.5;
                                                        break;
                                                    case 'X':
                                                        $ingredientAmountToSubtract = 2.0;
                                                        break;
                                                }

                                                $ingredientNames = ['dough', 'cheese', 'sauce'];
                                                $ingredientIds = [];

                                                foreach ($ingredientNames as $ingredientName) {
                                                    $ingredientIdQuery = "SELECT Item_ID FROM items WHERE Item_Name = ?";
                                                    $stmt = $mysqli->prepare($ingredientIdQuery);
                                                    $stmt->bind_param("s", $ingredientName);
                                                    $stmt->execute();
                                                    $ingredientIdResult = $stmt->get_result();

                                                    if ($ingredientIdResult && $ingredientRow = $ingredientIdResult->fetch_assoc()) {
                                                        $ingredientIds[$ingredientName] = $ingredientRow['Item_ID'];
                                                    } else {
                                                        echo "Error: $ingredientName Item_ID not found.";
                                                        // Handle the error as needed
                                                    }
                                                }
                                                // Update inventory for dough, cheese, and sauce based on the pizza size
                                                foreach ($ingredientIds as $ingredientName => $ingredientId) {
                                                    $updateIngredientInventorySQL = "UPDATE inventory 
                                                                                    SET Inventory_Amount = Inventory_Amount - $ingredientAmountToSubtract
                                                                                    WHERE Item_ID = $ingredientId AND Store_ID = $store_id";
                                                    $mysqli->query($updateIngredientInventorySQL);
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
                            
                            $_SESSION['order_completed'] = true; //variable to restrict access to thank you page
                            $_SESSION['cart'] = []; //empties cart after everything
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
    <form action="pickup.php" method="post">
        <h2>Pickup Information</h2>

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

        <!-- if customer if guest, name, phone number, and email will be prompted for guest table -->
        <?php
        if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin'] == true) { ?>
            <div>
                <label for="phone_number">Phone Number </label>
                <input type="tel" id="phone_number" name="phone_number" placeholder="Enter 10 digits" pattern="^\d{10}$|^\d{3}-\d{3}-\d{4}$" style="width: 120px;" required>
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
            <input type="text" id="Amount" name="Amount" <?php if (isset($_SESSION['totalPrice'])) { ?> value=" <?php echo $totalPrice ?>" <?php } ?> placeholder="Amount" style="width: 100px;" required readonly >

            <label for="Amount_Tipped">Tip Amount </label>
            <input type="number" id="Amount_Tipped" name="Amount_Tipped" min=0 placeholder="Tip" style="width: 100px;">

            <input type="hidden" id="Cost_Of_Goods" name="Cost_Of_Goods" <?php if (isset($_SESSION['Cost_Of_Goods'])) { ?> value= <?php $totalCOG ?> <?php } ?>>
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