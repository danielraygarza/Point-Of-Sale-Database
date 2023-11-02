<?php
    include 'database.php';
    session_start();

    
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
        <!-- <a href="#">Profile</a> -->
        <?php
            if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
                echo '<a href="logout.php">Logout</a>';
            }
        ?>
    </div>

    <main>
            <div class = "card">
                <div class = "top-card">
                    <div class = "right-top">
                        <div class = "image">
                            <img src= "img/cheese_pizza.jpeg" alt="">
                        </div>
                    </div>
                    <div class = "left-top">
                        <p class = "pizza_name"><?php echo $_SESSION['pizzaname'];?></p>
                        <p class = "description">This is a placeholder description. This is a placeholder description. This is a placeholder description.</p>
                        <p class = "calories">1200 cals</p>
                    </div>
                </div>
            </div>
    </main>

    <div class = "bottom-bar">
        <p class = "total"><b>Total:</b></p>
        <p class = "price">$12.99</p>
        <div class = "add-to-cart"> ADD TO CART</div>
    </div>
    


</body>
</html>