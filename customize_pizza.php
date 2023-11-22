<!-- this page is displayed after a pizza is added to the cart. allows user to add toppings to pizza -->
<?php
include 'database.php';
session_start();

// ensures page was accessed by menu only
if (empty($_SESSION['item_selected'])) {
    // Redirect them to the menu page
    header('Location: menu.php');
    exit;
}

// create empty cart if not already done
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// adds item ID to cart
function addToCart($itemIDs)
{
    foreach ($itemIDs as $itemID) {
        $_SESSION['cart'][] = $itemID;
    }
}

// returns number of items in cart
function getCartItemCount()
{
    return count($_SESSION['cart']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // adds item to cart and redirects back to menu
    if (isset($_POST['add-to-cart'])) {
        $selectedToppings = isset($_POST['toppings']) ? $_POST['toppings'] : [];
        addToCart($selectedToppings);
        header('Location: menu.php');
        exit;
    }
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
        <a href="menu.php">Back to Menu</a>
        <?php
        if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
            echo '<a href="logout.php">Logout</a>';
        } else {
            echo '<a href="customer_login.php">Login</a>';
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
            <form action="" method="post" style="display: block; max-width: none;">
                <div class="toppings-list">
                    <?php
                    // shows toppings that have existing inventory
                    $toppingsQuery = "SELECT Item_Cost, Item_Name FROM items WHERE Item_Type = 'Topping' AND Item_ID IN (SELECT Item_ID FROM inventory WHERE Inventory_Amount > 20)";
                    $toppingsResult = $mysqli->query($toppingsQuery);
                    while ($toppingRow = $toppingsResult->fetch_assoc()) {
                        $toppingName = $toppingRow['Item_Name'];
                        $toppingPrice = $toppingRow['Item_Cost'];
                        // display topping by name and price
                        echo '<label class="toppings-list"><input type="checkbox" name="toppings[]" value="' . $toppingName . '">' . $toppingName . ' - $' . $toppingPrice . '</label><br>';
                    }
                    ?>
                </div>
                <input type="submit" class="button" name="add-to-cart" value="Add to Cart">
            </form>
        </div>
    </main>
    </div>
</body>

</html>