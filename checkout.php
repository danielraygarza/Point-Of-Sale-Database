<?php
// Start the session
session_start();
include 'database.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// $_SESSION['selected_store_id'] = $store_id;

// when you click "place order", it will run this code
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // echo "<h2>Success</h2>";
    // echo '<script>setTimeout(function(){ window.location.href="checkout.php"; }, 400);</script>';
    // redirect to the chosen page when click "place order"
    $Order_Type = $mysqli->real_escape_string($_POST['Order_Type']);
    $_SESSION['selected_store_id'] = $_POST['Store_ID']; // Replace 'store_location' with the actual form field name
    if($Order_Type == 'Pickup'){
        header('Location: pickup.php');
        exit;
    } else if($Order_Type == 'Delivery'){
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

// Initialize the cart as an empty array if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Add an item to the cart (you can call this function when a user adds an item)
function addToCart($itemId)
{
    $_SESSION['cart'][] = $itemId;
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

    <form action="checkout.php" method="post">
        <div class="checkout-window">
            <!-- <h2 class="cart-heading">Pizza Cart</h2> -->
            <?php
            $today = date('m-d');
            // if logged in, greet customer with name
            if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
                echo "<h2 class='php-heading'>" . $_SESSION['user']['first_name'] . ", review your cart!</h2>";
                if (isset($_SESSION['user']['birthday'])) {
                    $birthday = strtotime($_SESSION['user']['birthday']);
                    $birthdayMonthDay = date('m-d', $birthday);
                }
                if (isset($birthdayMonthDay) && $birthdayMonthDay == $today) {
                    echo "<h2 class='php-heading'>Happy Birthday, enjoy your POS pizza!</h2>";
                } else {
                    // echo "<h2 class='php-heading'>Not your birthday, sorry loser</h2>";
                }
            } else {
                echo "<h2 class='php-heading'>Review your cart!</h2>";
            }
            ?>
            <div>
                <select id="Store_ID" name="Store_ID" style="margin-right: 10px" required>
                    <option value="" selected disabled>Select Location to Order</option>
                    <?php
                    $stores = $mysqli->query("SELECT * FROM pizza_store");
                    if ($stores->num_rows > 0) {
                        while ($row = $stores->fetch_assoc()) {
                            // does not show store ID 1
                            if ($row["Pizza_Store_ID"] == 1) { continue; }
                            echo '<option value="' . $row["Pizza_Store_ID"] . '">' . $row["Store_Address"] . ' - ' . $row["Store_City"] . '</option>';
                        }
                    }
                    ?>
                </select>
                <select id="Order_Type" name="Order_Type" required>
                    <option value="" selected disabled>Select Order Method</option>
                    <option value="Pickup">Pick Up</option>
                    <option value="Delivery">Delivery</option>
                </select>
            </div>
            <div class="cart-panel">
                <ul class="cart-items">
                    <?php
                    // Assuming you have a cart stored in a session or database
                    $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

                    $totalPrice = 0; //start total at zero
                    if (count($cart) > 0) {
                        // Loop through the items in the cart and display them
                        foreach ($cart as $item) {
                            $query = "SELECT Item_Cost FROM items WHERE Item_Name = '$item'";
                            $result = $mysqli->query($query);
                            $row = $result->fetch_assoc();
                            if($result){
                                $toppingPrice = $row['Item_Cost'];
                                $totalPrice += $toppingPrice; //accumlating total price
                            }
                            echo "<li>$item - $toppingPrice</li>";
                        }
                        echo "<li>----------------------</li>";
                        echo "<li>Total Price: $$totalPrice</li>"; //prints total price

                        echo '<li><button name="clear-cart" type="submit" class="clear-cart-button">Clear Cart</button></li>';
                    } else {
                        echo "<h2 class='php-heading'> Your cart is empty</h2>";
                    }
                    $_SESSION['totalPrice'] = $totalPrice; //saves total price to session
                    ?>
                </ul>
            </div>
            <input class="button orderbutton" type="submit" value="Place Order">
        </div>
    </form>
</body>

</html>
