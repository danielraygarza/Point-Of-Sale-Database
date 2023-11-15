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
** Title: In-Depth Analysis of Dynamic Pizza Ordering System with Enhanced Checkout Features
**
** Introduction:
** This PHP script embodies a comprehensive pizza ordering system, designed with a focus on
** dynamic checkout functionality. By leveraging PHP sessions and a responsive interface,
** users can seamlessly navigate through the ordering process, experiencing a highly
** personalized and interactive pizza ordering platform.
**
** Session Management:
** The initiation of a PHP session at the script's inception is paramount for maintaining
** user-specific data across different pages. This facilitates the persistence of critical
** information, including the shopping cart contents, selected store, and chosen order type
** throughout the user's entire journey. The meticulous use of sessions ensures a cohesive
** and intuitive user experience.
**
** Error Reporting for Debugging:
** To streamline the development process, the code employs robust error reporting, offering
** real-time insights into potential issues. This not only aids developers in identifying and
** addressing errors promptly but also enhances the overall reliability of the system during
** its lifecycle.
**
** Order Placement Logic:
** At the core of the system lies a sophisticated order placement logic. When users click the
** "Place Order" button, the script captures the selected order type and store ID, directing
** the user to the appropriate page for further processing. The conditional branching ensures
** a flexible system capable of handling diverse order types. Notably, a unique case is
** implemented for "DIGIORNO," showcasing the script's adaptability to redirect users to an
** external website for a specific order type.
**
** Shopping Cart Management:
** The shopping cart functionality is a hallmark of the system, allowing users to dynamically
** add items to their carts. The seamless integration with session variables guarantees that
** selected items persist as users navigate different pages. The "Clear Cart" button enhances
** user control, providing a convenient mechanism for modifying orders on the fly.
**
** User Interface:
** The HTML structure is meticulously crafted to deliver an intuitive and user-friendly
** interface. The navigation bar presents quick links to essential sections, including Home,
** Menu, Cart, Profile, and Login/Logout. This thoughtful arrangement contributes to a
** streamlined user experience, enabling users to effortlessly traverse the platform.
**
** Dynamic Dropdowns:
** The dynamic dropdown menus for selecting the store and order method are a testament to the
** script's user-centric design. Drawing data from the pizza_store table, the store dropdown
** is dynamically populated, excluding a specific store with ID 1. This dynamic approach allows
** users to personalize their pizza ordering experience by choosing their preferred store and
** order method.
**
** Detailed Cart Display:
** A standout feature is the comprehensive cart panel that provides users with a detailed list
** of selected items. The script intelligently fetches item names and prices from the database,
** presenting a breakdown of the order. Toppings, sourced from the 'items' table, are distinctly
** identified, creating an elegant distinction from full pizzas sourced from the 'menu' table.
** This meticulous detailing contributes to a visually appealing and informative cart display.
**
** Conclusion:
** In summation, this PHP script encapsulates not just a pizza ordering system but a
** sophisticated and adaptable platform for online pizza commerce. From robust session
** management to flexible order placement and a polished user interface, the script provides
** developers with a solid foundation for creating advanced e-commerce solutions. This
** in-depth analysis emphasizes the script's attention to detail and user-centric design,
** making it an ideal starting point for developers aiming to create a seamless and
** feature-rich pizza ordering experience.
*/
?>

