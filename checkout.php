<?php
// Start the session
session_start();
include 'database.php';
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
        <?php
        if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
            echo '<a href="update_profile.php">Profile</a>';
            echo '<a href="logout.php">Logout</a>';
        }
        ?>
        <a href="checkout.php" id="cart-button">Cart (<?php echo getCartItemCount(); ?>)</a>
    </div>


    <form action="" method="post">
        <div class="checkout-window">
            <h2 class="cart-heading">Pizza Cart</h2>
            <div class="cart-panel">
                <ul class="cart-items">
                    <?php
                    // Assuming you have a cart stored in a session or database
                    $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

                    if (count($cart) > 0) {
                        // Loop through the items in the cart and display them
                        foreach ($cart as $item) {
                            echo "<li>$item - $10.00</li>"; // Replace with actual item details
                        }
                    } else {
                        $toppingName = 'Pepperoni'; // Replace with the desired topping name
                        $query = "SELECT Price FROM topping_on_pizza WHERE topping_name = '$toppingName'";
                        $result = $mysqli->query($query);

                        if ($result) {
                            $row = $result->fetch_assoc();

                            if ($row) {
                                $toppingPrice = $row['Price'];
                                echo "$toppingName:                ", $row['Price'];
                            } else {
                                echo "Topping not found.";
                            }
                        } else {
                            echo "Error executing the query: " . $mysqli->error;
                        }
                    }
                    ?>
                </ul>
            </div>
            <a href="index.php" type="submit" class="orderbutton">Place Order</a>
        </div>
    </form>
        <?php
        // Calculate and display the total price
        // $totalPrice = calculateTotalPrice($cart); // Implement this function
        // echo "<p>Total: $" . number_format($totalPrice, 2) . "</p>";
        ?>
</body>
</html>