<?php
// Start the session
session_start();

// Initialize the cart as an empty array if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Add an item to the cart (you can call this function when a user adds an item)
function addToCart($itemId) {
    $_SESSION['cart'][] = $itemId;
}

// Get the number of items in the cart
function getCartItemCount() {
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
    <a id="cart-container">
    <a href="checkout.php" id="cart-button">Cart (<?php echo getCartItemCount(); ?>)</a>
</div>

<a href="menu.php" class="button">Order now!</a>

<script>
    document.getElementById("cart-button").addEventListener("click", function() {
        window.location.href = "checkout.php";
    });
</script>

</body>
</html>
