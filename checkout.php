<?php
// Start the session
session_start();
include 'database.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// when you click "place order", it will run this code
if (isset($_POST['place-order'])) {
    // redirect to the chosen page when click "place order"
    $Order_Type = $mysqli->real_escape_string($_POST['Order_Type']);
    $_SESSION['selected_store_id'] = $_POST['Store_ID'];
    if ($Order_Type == 'Pickup') {
        $_SESSION['checkout_completed'] = true;
        header('Location: pickup.php');
        exit;
    } else if ($Order_Type == 'DIGIORNO') {
        header('Location: https://www.goodnes.com/digiorno/');
        exit;
    } else if ($Order_Type == 'Delivery') {
        $_SESSION['checkout_completed'] = true;
        header('Location: delivery.php');
        exit;
    } else {
        header('Location: checkout.php');
        exit;
    }
}

// Clear the cart if the "Clear Cart" button is clicked
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
            <!-- <h2 class="cart-heading">Pizza Cart</h2> -->
            <?php
            // if logged in, greet customer with name
            if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
                echo "<h2 class='php-heading'>" . $_SESSION['user']['first_name'] . ", review your cart!</h2>";
            } else { // checks if the user is logged in. If so, it returns a welcome with their name.
                echo "<h2 class='php-heading'>Review your cart!</h2>"; // or a generic welcome if not
            }
            ?>
            <div>
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
                <!-- Select box containing the order method to be selected -->
                <select id="Order_Type" name="Order_Type">
                    <option value="" selected disabled>Select Order Method</option>
                    <option value="Pickup">Pick Up</option>
                    <option value="Delivery">Delivery</option>
                    <option value="DIGIORNO">DIGIORNO</option>
                </select>
                <!-- END SELECT BOX -->
            </div>
            <div class="cart-panel">
                <ul class="cart-items">
                    <?php
                    // Assuming you have a cart stored in a session or database
                    $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

                    $totalPrice = 0; //start total at zero
                    if (count($cart) > 0) {
                        //loop through the items in the cart and display them

                        //---------QUERY FOR RETURNING CART ITEMS AS NAMES WITH PRICES---------------------------//
                        foreach ($cart as $item) {
                            $query = "SELECT Item_Cost AS Cost, Item_Name AS Name, 'item' AS Source FROM items WHERE Item_Name = '$item'
                                    UNION ALL
                                    SELECT Price AS Cost, Name, 'menu' AS Source FROM menu WHERE Pizza_ID = '$item'";
                            // returns The topping name as item, and the pizza name as Source. Also returns price as cost.\

                            $result = $mysqli->query($query);
                            // Start query to load items for the checkout
                            if ($result) {
                                while ($row = $result->fetch_assoc()) {
                                    $itemName = $row['Name'];
                                    $itemCost = $row['Cost'];
                                    $totalPrice += $itemCost;

                                    //check source and indent toppings from item table
                                    if ($row['Source'] === 'item') {
                                        echo "<li class='item-style'>$itemName - $$itemCost</li>";
                                    } else {
                                        echo "<li class='menu-style'>$itemName - $$itemCost</li>";
                                    }
                                }
                            }
                        }
                        echo "<li>----------------------</li>";
                        echo "<li>Total Price: $$totalPrice</li>";
                         //prints total price
                        echo '<li><button name="clear-cart" type="submit" class="clear-cart-button">Clear Cart</button></li>';
                    } else {
                        echo "<h2 class='php-heading'> Your cart is empty</h2>";
                    }
                    $_SESSION['totalPrice'] = $totalPrice; 
                    //saves total price to session
                    ?>
                </ul>
            </div>
            <script>
                function setRequiredFields() {
                    document.getElementById('Store_ID').required = true;
                    document.getElementById('Order_Type').required = true;
                }
            </script>
            <input class="button orderbutton" type="submit" name="place-order" value="Place Order" onclick="setRequiredFields()">
        </div>
    </form>
</body>

</html>
<?php
/*
** Title: Exploring a Pizza Ordering System with Dynamic Checkout
**
** Introduction:

** The PHP code presented here forms the backbone of a dynamic pizza ordering system
** with an emphasis on a versatile checkout process. The system leverages PHP sessions
** and incorporates a seamless interface for users to place orders, clear their shopping
** carts, and navigate through various ordering options.
**
** Session Management:

** The initiation of a PHP session at the beginning of the code ensures the persistence
** of user data across different pages. This is crucial for maintaining the state of the
** shopping cart, the selected store, and the order type throughout the user's journey.
**
** Error Reporting for Debugging:

** To facilitate the debugging process during development, error reporting is enabled,
** offering real-time insights into any potential issues. This feature aids developers in
** identifying and addressing errors promptly.
**
** Order Placement Logic:

** The core of the code lies in the logic that handles order placement. When a user clicks
** the "Place Order" button, the system captures the selected order type and store ID.
** Depending on the chosen order type, the system redirects the user to the appropriate
** page for further processing. For example, if "Pickup" is selected, the user is directed
** to the pickup.php page. A special case is implemented for "DIGIORNO," redirecting the
** user to an external website. This showcases the system's flexibility in accommodating
** various order types.
**
** Shopping Cart Management:

** The system allows users to add items to their shopping carts dynamically. The cart
** content is stored in a session variable, ensuring that the selected items persist as
** the user navigates through different pages. The "Clear Cart" button efficiently empties
** the cart, providing users with the flexibility to modify their orders.
**
** User Interface:

** The HTML structure creates an intuitive and user-friendly interface. The navigation bar
** offers links to essential sections such as Home, Menu, Cart, Profile, and Login/Logout,
** enhancing user experience and facilitating seamless navigation.
**
** Dynamic Dropdowns:

** Dropdown menus for selecting the store and order method enhance the user interface.
** The system dynamically populates the store dropdown based on data retrieved from the
** pizza_store table, excluding a specific store with ID 1. Users can choose their preferred
** store and order method, contributing to a personalized ordering experience.
**
** Detailed Cart Display:

** The shopping cart panel presents a detailed list of items selected by the user. The system
** fetches item names and prices from the database, providing a comprehensive breakdown of the
** order. Items from the 'items' table are identified as toppings, while items from the 'menu'
** table represent full pizzas. This distinction is elegantly displayed in the cart.
**
** Conclusion:

** In conclusion, this PHP code encapsulates a robust and flexible pizza ordering system.
** From session management to order placement logic and dynamic user interface elements,
** the code ensures a smooth and engaging experience for users. The thoughtful design allows
** for easy customization, making it adaptable to different scenarios within the realm of
** online pizza ordering. Developers can leverage this code as a foundation for building and
** expanding upon sophisticated e-commerce platforms.
*/
?>
