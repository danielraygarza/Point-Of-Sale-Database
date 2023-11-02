<?php
    include 'database.php';
    session_start();
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    $toppingsQuery = "SELECT Price, topping_name FROM pos.topping_on_pizza";
    $toppingsResult = $mysqli->query($toppingsQuery);
    $sql = "SELECT * FROM pizza;";
    $result = $mysqli->query($sql);
    function addToCart($itemId) {
        $_SESSION['cart'][] = $itemId;
    }
    function getCartItemCount() {
        return count($_SESSION['cart']);
    }
    
?>

<!DOCTYPE html>
<html>
<head>
    <title>POS Pizza</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/customize_pizza.css">
    <link rel="icon" href="img/pizza.ico" type="image/x-icon">
</head>
<body>
    <div class="navbar">
        <a href="index.php">Home</a>
        <a href="menu.php">Order now</a>
        <?php
            if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
                echo '<a href="logout.php">Logout</a>';
            }
        ?>
         <a href="checkout.php" id="cart-button">Cart (<?php echo getCartItemCount(); ?>)</a>
    </div>

    <main>
        <div class="card">
            <div class="top-card">
                <div class="right-top">
                    <div class="image">
                        <img src="img/cheese_pizza.jpeg" alt="">
                    </div>
                </div>
                <div class="left-top">
                    <p class="pizza_name">Customize Your Pizza</p>
                    <p class="description">Select any of the following toppings for your pizza:</p>
                    <p class="calories">Somewhere between 3 and 9871 Calories</p>
                </div>
            </div>
            <p class="price"><b>Calculated Price: AUTO UPDATE TOTAL PRICE</b></p>
            <div class="toppings-list">
                <form id="toppingForm">
                    <?php
                    while ($toppingRow = $toppingsResult->fetch_assoc()) {
                        $toppingName = $toppingRow['topping_name'];
                        $toppingPrice = $toppingRow['Price'];
                        echo '<label><input type="checkbox" name="toppings[]" value="' . $toppingName . '">' . $toppingName . ' - $' . $toppingPrice . '</label><br>';
                    }
                    ?>
                </form>
                <button id="addToCartButton" onclick="addSelectedToppingsToCart()">Add to Cart</button>
            </div>
        </div>
    </main>

    <script>
        function addSelectedToppingsToCart() {
            // Get the form and collect checked checkboxes
            const form = document.getElementById('toppingForm');
            const checkboxes = form.querySelectorAll('input[name="toppings[]"]:checked');
            
            // Extract the values (topping names) from the checked checkboxes
            const selectedToppings = Array.from(checkboxes).map(checkbox => checkbox.value);

            // Add the selected toppings to the cart using the addToCart function
            selectedToppings.forEach(topping => addToCart(topping));
            
            // Optionally, you can update the cart count or show a message
            const cartItemCount = getCartItemCount();
            alert(selectedToppings.length + ' topping(s) added to the cart. Total items in the cart: ' + cartItemCount);
        }
    </script>
</body>
</html>
