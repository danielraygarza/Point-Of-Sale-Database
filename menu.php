<?php
include 'database.php';
session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$sql = "SELECT * FROM menu;";
$result = $mysqli->query($sql);

// function addToCart($pizzaId, $size, $price) {
//     $pizzaItem = [
//         'pizza_id' => $pizzaId,
//         'size' => $size,
//         'price' => $price,
//     ];
//     $_SESSION['cart'][] = $pizzaItem;
// }

function addToCart($itemID) {
    $_SESSION['cart'][] = $itemID;
}

function getCartItemCount() {
    return count($_SESSION['cart']);
}

if (isset($_POST['add_to_cart'])) {
    $itemID = $_POST['pizza_id'];
    
    addToCart($itemID);
    
    header('Location: menu.php');
    // exit;
}

if (isset($_POST['add_to_cart_and_customize'])) {
    $itemID = $_POST['pizza_id'];
    
    addToCart($itemID);
    
    // Now redirect to the customize page with the item details
    $redirectUrl = $_POST['redirect'];
    header("Location: $redirectUrl");
    exit;
}

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
            }
        ?>
         <a href="checkout.php" id="cart-button">Cart (<?php echo getCartItemCount(); ?>)</a>
         
    </div>

    <form action="menu.php" method="post">
        <h2>Menu</h2>
    </form>

    <main>
        <?php
        
            while ($row = mysqli_fetch_assoc($result)) {
        ?>
        <div class="card">
            <div class="image">
                <img src=<?php echo $row["Image_Path"]; ?> alt="">
            </div>
              
               
              
            <p class="pizza_name"><?php echo $row["Name"]; ?> (<?php echo $row["Size_Option"]; ?>)</p>
            <p class="description"><?php echo $row["Description"]; ?></p>
            <p class="calories"><?php echo $row["Calories"]; ?> cals</p>
            <p class="price"><b>$<?php echo $row["Price"]; ?></b></p>
            <?php
            
            if ($row["Is_Pizza"] == 1) {
                // echo '<div class="customize"><ahref="customize_pizza.php?pizza_id=' . $row["Pizza_ID"] . '&size=' . $row["Size_Option"] . '&price=' . $row["Price"] . '">CUSTOMIZE</a></div>';
                echo '<form action="menu.php" method="post" style="background-color: transparent; border: none;>';
                echo '<input type="hidden" name="pizza_id" value="' . $row["Pizza_ID"] . '">';
                echo '<input type="hidden" name="size" value="' . $row["Size_Option"] . '">';
                echo '<input type="hidden" name="price" value="' . $row["Price"] . '">';
                echo '<input type="hidden" name="redirect" value="customize_pizza.php?pizza_id=' . $row["Pizza_ID"] . '&size=' . $row["Size_Option"] . '&price=' . $row["Price"] . '">';
                echo '<button type="submit" name="add_to_cart_and_customize" class="button">Customize</button>';
                echo '</form>';

            } else {
                echo '<form action="menu.php" method="post" style="background-color: transparent; border: none;>';
                echo '<input type="hidden" name="pizza_id" value="' . $row["Pizza_ID"] . '">';
                echo '<div class=""><button type="submit" name="add_to_cart" class = "button" ">Add to Cart</button></div>';
                echo '</form>';
            }
            ?>
        </div>
        <?php } ?>
    </main>
</body>
</html>
