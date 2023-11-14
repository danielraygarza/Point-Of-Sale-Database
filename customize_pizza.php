<?php
include 'database.php';
session_start();

//ensures page was accessed by menu only
if (empty($_SESSION['item_selected'])) {
    // Redirect them to the menu page
    header('Location: menu.php');
    exit;
}

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

function addToCart($itemIDs) {
    foreach ($itemIDs as $itemID) {
        $_SESSION['cart'][] = $itemID;
    }
}

function getCartItemCount() {
    return count($_SESSION['cart']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add-to-cart'])) {
        $selectedToppings = isset($_POST['toppings']) ? $_POST['toppings'] : [];
        addToCart($selectedToppings);
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
                <!-- <a href="menu.php" class="button">Order More</a> -->
                <div class="left-top">
                    <p class="pizza_name">Customize Your Pizza</p>
                    <p class="description">Select any of the following toppings for your pizza:</p>
                    <p class="calories">Somewhere between 3 and 9871 Calories</p>
                </div>
            </div>
            <!-- <p class="price"><b>Calculated Price: AUTO UPDATE TOTAL PRICE</b></p> -->
            <form action="" method="post">
                <div class="toppings-list">
                    <?php
                    $toppingsQuery = "SELECT Item_Cost, Item_Name FROM items WHERE Item_Name <> 'dough'";
                    $toppingsResult = $mysqli->query($toppingsQuery);
                    while ($toppingRow = $toppingsResult->fetch_assoc()) {
                        $toppingName = $toppingRow['Item_Name'];
                        $toppingPrice = $toppingRow['Item_Cost'];
                        echo '<label class="toppings-list"><input type="checkbox" name="toppings[]" value="' . $toppingName . '">' . $toppingName . ' - $' . $toppingPrice . '</label><br>';
                    }
                    ?>
                </div>
                <input type="submit" class="button" name="add-to-cart" value="Add to Cart">
                <a href="menu.php" class="button">Order More</a>
            </form>
        </div>
    </main>

    <!-- <div class="bottom-bar">
        <p class="total"><b>Total:</b></p>
        <p class="price">$12.99</p>
        <div class="add-to-cart">ADD TO CART</div> -->
    </div>
</body>
</html>
