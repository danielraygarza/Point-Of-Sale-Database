<?php
// Start the session
session_start();
include 'database.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Clear the cart if the "Clear Cart" button is clicked
if (isset($_POST['clear-cart'])) {
    $_SESSION['cart'] = [];
}

// when you click "place order", it will run this code
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "<h2>Success</h2>";
    echo '<script>setTimeout(function(){ window.location.href="checkout.php"; }, 400);</script>';
    // redirect to the chosen page when click "place order"
    // header('Location: index.php');
    exit;
}

// Initialize the cart as an empty array if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}
$stores = $mysqli->query("SELECT * FROM pizza_store");

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
            <select id="Store_ID" name="Store_ID" required>
                <option value="" selected disabled>Select Store</option>
                <?php
                if ($stores->num_rows > 0) {
                    while ($row = $stores->fetch_assoc()) {
                        // does not show store ID 1
                        // if ($row["Pizza_Store_ID"] == 1) { continue; }
                        echo '<option value="' . $row["Pizza_Store_ID"] . '">' . $row["Store_Address"] . ' - ' . $row["Store_City"] . '</option>';
                    }
                }
                ?>
            </select>
            <div class="cart-panel">
                <ul class="cart-items">
                    <?php
                    // Assuming you have a cart stored in a session or database
                    $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

                    if (count($cart) > 0) {
                        // Loop through the items in the cart and display them
                        foreach ($cart as $item) {
                            $query = "SELECT Price FROM topping_on_pizza WHERE topping_name = '$item'";
                            $result = $mysqli->query($query);
                            $row = $result->fetch_assoc();
                            if($result){
                                $toppingPrice = $row['Price'];
                            }
                            echo "<li>$item - $toppingPrice</li>"; // Replace with actual item details
                        }
                        echo '<li><button name="clear-cart" type="submit" class="clear-cart-button">Clear Cart</button></li>';
                    } else {
                        echo "<h2 class='php-heading'> Your cart is empty</h2>";
                    }
                    ?>
                </ul>
                <input class="button orderbutton" type="submit" value="Place Order">
            </div>
        </div>
    </form>
    <?php
    // Calculate and display the total price
    // $totalPrice = calculateTotalPrice($cart); // Implement this function
    // echo "<p>Total: $" . number_format($totalPrice, 2) . "</p>";
    ?>
</body>

</html>
