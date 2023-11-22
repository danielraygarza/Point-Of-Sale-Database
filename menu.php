<!-- this page displays all menu items -->
<?php
include 'database.php';
session_start();

// If user is logged in as employee, log them out and treat user as guest
if (isset($_GET['guest']) && $_GET['guest'] === '1') {
    // reset all session variables.
    $_SESSION = array();

    // destroy current session
    session_destroy();

    // start new session for guest
    session_start();
}

//create cart if one doesnt exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// adds item to cart array
function addToCart($item)
{
    $_SESSION['cart'][] = $item;
}

// returns number of items in cart
function getCartItemCount()
{
    return count($_SESSION['cart']);
}

// if item is not pizza, add to cart and refresh page
if (isset($_POST['add_to_cart'])) {
    $itemID = $_POST['pizza_id'];

    addToCart($itemID);
}

if (isset($_POST['add_to_cart_and_customize'])) {
    $itemID = $_POST['pizza_id'];

    addToCart($itemID);

    //variable to ensure customize pizza page cant be accessed by URL
    $_SESSION['item_selected'] = true;

    // Now redirect to the customize page with the item details
    $redirectUrl = $_POST['redirect'];
    header("Location: $redirectUrl"); //sends to customize_pizza.php
    exit;
}

// get all menu items
$sql = "SELECT * FROM menu;";
$result = $mysqli->query($sql);

?>

<!DOCTYPE html>
<html>

<head>
    <title>POS Pizza</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/menu.css">
    <link rel="icon" href="img/pizza.ico" type="image/x-icon">
</head>

<body>
    <div class="navbar">
        <a href="index.php">Home</a>
        <a href="menu.php">Order now</a>
        <?php
        if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
            echo '<a href="update_customer.php">Profile</a>';
            echo '<a href="logout.php">Logout</a>';
        } else {
            echo '<a href="customer_login.php">Login</a>';
        }
        ?>
        <a href="checkout.php" id="cart-button">Cart (<?php echo getCartItemCount(); ?>)</a>

    </div>

    <form action="menu.php" method="post">
        <h2>Menu</h2>
    </form>

    <main>
        <?php

        
        // loop through menu items and display them
        while ($row = mysqli_fetch_assoc($result)) {
        ?>
            <!-- display menu item picture -->
            <div class="card">
                <div class="image">
                    <img src=<?php echo $row["Image_Path"]; ?> alt="">
                </div>

                <!-- display menu item info -->
                <p class="pizza_name"><?php echo $row["Name"]; ?> (<?php echo $row["Size_Option"]; ?>)</p>
                <p class="description"><?php echo $row["Description"]; ?></p>
                <p class="calories"><?php echo $row["Calories"]; ?> cals</p>
                <p class="price"><b>$<?php echo $row["Price"]; ?></b></p>
                <?php
                // add item to cart based on if pizza or not
                if ($row["Is_Pizza"] == 1) {
                    echo '<form action="menu.php" method="post" style="background-color: transparent; border: none;">';
                    echo '<input type="hidden" name="pizza_id" value="' . $row["Pizza_ID"] . '">';
                    echo '<input type="hidden" name="size" value="' . $row["Size_Option"] . '">';
                    echo '<input type="hidden" name="price" value="' . $row["Price"] . '">';
                    echo '<input type="hidden" name="menu_name" value="' . $row["Name"] . '">';
                    echo '<input type="hidden" name="redirect" value="customize_pizza.php?pizza_id=' . $row["Pizza_ID"] . '&size=' . $row["Size_Option"] . '&price=' . $row["Price"] . '&menu_name=' . $row["Name"] . '">';
                    // if pizza, sends to customization page
                    echo '<button type="submit" name="add_to_cart_and_customize" class="button">Customize</button>';
                    echo '</form>';
                } else {
                    echo '<form action="menu.php" method="post" style="background-color: transparent; border: none;">';
                    echo '<input type="hidden" name="pizza_id" value="' . $row["Pizza_ID"] . '">';
                    echo '<input type="hidden" name="menu_name" value="' . $row["Name"] . '">';
                    // adds item to cart
                    echo '<div class="add-to-cart"><button type="submit" name="add_to_cart" class="button">Add to Cart</button></div>';
                    echo '</form>';
                }
                ?>
            </div>
        <?php } ?>
    </main>
</body>

</html>