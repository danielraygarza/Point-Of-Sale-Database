<!-- checkout page displays items in cart. select store and order method before finalizing order -->
<?php
// Start the session
session_start();
include 'database.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (isset($_POST['place-order'])) {
    // check if any employees are clocked in at the selected store
    $selectedStoreId = $mysqli->real_escape_string($_POST['Store_ID']);
    $checkEmployees = $mysqli->query("SELECT COUNT(*) AS ClockedInEmployees FROM employee WHERE Store_ID = '$selectedStoreId' AND clocked_in = 1");
    $row = $checkEmployees->fetch_assoc();

    // redirect if employees are clocked in
    if ($row['ClockedInEmployees'] > 0) {
        // reads order type and redirects to delivery or pickup page
        $Order_Type = $mysqli->real_escape_string($_POST['Order_Type']);
        $_SESSION['selected_store_id'] = $_POST['Store_ID'];
        
        // create session variable checkout completed to control user access
        if ($Order_Type == 'Pickup') {
            $_SESSION['checkout_completed'] = true;
            header('Location: pickup.php');
            exit;
        } else if ($Order_Type == 'Delivery') {
            $_SESSION['checkout_completed'] = true;
            header('Location: delivery.php');
            exit;
        } else {
            header('Location: checkout.php');
            exit;
        }
    } else {
        // if no employees are clocked in, give error message
        echo "";
        $_SESSION['error'] = "Sorry, this location is closed!";
        header('Location: checkout.php');
        exit;
    }
}

// Clear cart button empties array
if (isset($_POST['clear-cart'])) {
    $_SESSION['cart'] = [];
}

// Get the number of items in the cart
function getCartItemCount()
{
    return count($_SESSION['cart']);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Checkout</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="icon" href="img/pizza.ico" type="image/x-icon">
</head>

<body>
    <div class="navbar">
        <a href="index.php">Home</a>
        <a href="menu.php">Back to Menu</a>
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

    <form action="checkout.php" method="post">
        <div class="checkout-window">
            <?php
            // if logged in, greet customer with name
            if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
                echo "<h2 class='php-heading'>" . $_SESSION['user']['first_name'] . ", review your cart!</h2>";
            } else {
                echo "<h2 class='php-heading'>Review your cart!</h2>"; // or a generic welcome if not
            }
            ?>
            <div>
                <!-- dropdown for stores -->
                <select id="Store_ID" name="Store_ID" style="margin-right: 10px">
                    <option value="" selected disabled>Select Location to Order</option>
                    <?php
                    $stores = $mysqli->query("SELECT * FROM pizza_store");
                    if ($stores->num_rows > 0) {
                        while ($row = $stores->fetch_assoc()) {
                            // does not show store ID 1
                            if ($row["Pizza_Store_ID"] == 1) {
                                continue;
                            } // A query to report the different stores we have and put them into a drop down box.
                            echo '<option value="' . $row["Pizza_Store_ID"] . '">' . $row["Store_Address"] . ' - ' . $row["Store_City"] . '</option>';
                        }
                    }
                    ?>
                </select>
                <!-- dropdown for order method -->
                <select id="Order_Type" name="Order_Type">
                    <option value="" selected disabled>Select Order Method</option>
                    <option value="Pickup">Pick Up</option>
                    <option value="Delivery">Delivery</option>
                </select>
            </div>
            <div class="cart-panel">
                <ul class="cart-items">
                    <?php
                    // establishes cart in session
                    $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

                    $totalPrice = 0; // price of order for customer
                    $totalCOG = 0; // cost of goods in order
                    if (count($cart) > 0) {
                        //loop through the items in the cart and display them
                        foreach ($cart as $item) {
                            //---------QUERY FOR RETURNING CART ITEMS AS NAMES WITH PRICES---------------------------//
                            $query = "SELECT Item_Cost AS Cost, Item_Name AS Name, Cost_Of_Good, 'item' AS Source FROM items WHERE Item_Name = '$item'
                                    UNION ALL
                                    SELECT Price AS Cost, Name, Cost_Of_Good, 'menu' AS Source FROM menu WHERE Pizza_ID = '$item'";

                            $result = $mysqli->query($query);
                            if ($result) {
                                while ($row = $result->fetch_assoc()) {
                                    $itemName = $row['Name'];
                                    $itemCost = $row['Cost'];
                                    $itemCOG = $row['Cost_Of_Good'];
                                    
                                    //accumlating total price and cost of goods for order
                                    $totalPrice += $itemCost;
                                    $totalCOG += $itemCOG;
                                    
                                    // output order items while checking source to fit styling
                                    if ($row['Source'] === 'item') {
                                        echo "<li class='item-style'>$itemName - $$itemCost</li>";
                                    } else {
                                        echo "<li class='menu-style'>$itemName - $$itemCost</li>";
                                    }
                                }
                            }
                        }
                        echo "<li>----------------------</li>";
                        // prints total price
                        echo "<li>Total Price: $$totalPrice</li>";
                        echo '<li><button name="clear-cart" type="submit" class="clear-cart-button">Clear Cart</button></li>';
                    } else {
                        // if cart count returns 0
                        echo "<h2 class='php-heading'> Your cart is empty</h2>";
                    }
                    $_SESSION['totalPrice'] = $totalPrice; //saves total price to session
                    $_SESSION['totalCOG'] = $totalCOG; //saves total COG to session
                    ?>
                </ul>
            </div>
            <script>
                //function to ensure store and method are selected when "place order is selected"
                function setRequiredFields() {
                    document.getElementById('Store_ID').required = true;
                    document.getElementById('Order_Type').required = true;
                }
            </script>
            <?php
            //displays error messages here 
            if (isset($_SESSION['error'])) {
                echo '<div id="errorMessage">' . $_SESSION['error'] . '</div>';
                unset($_SESSION['error']);  // Unset the error message after displaying it
            }
            ?>
            <input class="button orderbutton" type="submit" name="place-order" value="Place Order" onclick="setRequiredFields()">
        </div>
    </form>
</body>

</html>